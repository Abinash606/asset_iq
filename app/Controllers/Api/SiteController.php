<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\SiteModel;
use CodeIgniter\HTTP\ResponseInterface;

class SiteController extends BaseController
{
    protected $siteModel;

    public function __construct()
    {
        $this->siteModel = new SiteModel();
    }

    /**
     * Display the sites view page
     */
    public function index()
    {
        return view('admin/sites');
    }

    /**
     * Get all sites for DataTable (with optional customer filter)
     * GET /api/admin/sites/list
     * Optional query param: ?customer_id=123
     */
    public function list()
    {
        try {
            $customerId = $this->request->getGet('customer_id');

            $builder = $this->siteModel->builder();
            $builder->select('sites.*, customers.name as customer_name')
                ->join('customers', 'customers.id = sites.customer_id', 'left')
                ->where('sites.deleted_at', null);

            if ($customerId) {
                $builder->where('sites.customer_id', $customerId);
            }

            $sites = $builder->get()->getResultArray();

            return $this->response->setJSON([
                'success' => true,
                'data' => $sites
            ]);
        } catch (\Exception $e) {
            return $this->response
                ->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR)
                ->setJSON([
                    'success' => false,
                    'message' => 'Failed to fetch sites: ' . $e->getMessage()
                ]);
        }
    }

    /**
     * Get a single site by ID
     * GET /api/admin/sites/{id}
     */
    public function get($id)
    {
        try {
            $builder = $this->siteModel->builder();
            $site = $builder->select('sites.*, customers.name as customer_name')
                ->join('customers', 'customers.id = sites.customer_id', 'left')
                ->where('sites.id', $id)
                ->where('sites.deleted_at', null)
                ->get()
                ->getRowArray();

            if (!$site) {
                return $this->response
                    ->setStatusCode(ResponseInterface::HTTP_NOT_FOUND)
                    ->setJSON([
                        'success' => false,
                        'message' => 'Site not found'
                    ]);
            }

            return $this->response->setJSON([
                'success' => true,
                'data' => $site
            ]);
        } catch (\Exception $e) {
            return $this->response
                ->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR)
                ->setJSON([
                    'success' => false,
                    'message' => 'Failed to fetch site: ' . $e->getMessage()
                ]);
        }
    }

    /**
     * Create a new site
     * POST /api/admin/sites
     */
    public function store()
    {
        try {
            $data = $this->request->getJSON(true);

            // Try to get company_id from multiple sources
            $companyId = null;

            // Method 1: Try from JWT token
            $token = service('request')->getServer('HTTP_AUTHORIZATION');
            if ($token) {
                $companyId = $this->getCompanyIdFromToken($token);
            }

            // Method 2: Try from session (if you're using sessions)
            if (!$companyId && session()->has('company_id')) {
                $companyId = session()->get('company_id');
            }

            // Method 3: Try from request data (if passed explicitly)
            if (!$companyId && isset($data['company_id'])) {
                $companyId = $data['company_id'];
            }

            // Method 4: Get from the customer's company_id (recommended approach)
            if (!$companyId && !empty($data['customer_id'])) {
                $customerModel = new \App\Models\CustomerModel();
                $customer = $customerModel->find($data['customer_id']);
                if ($customer && isset($customer['company_id'])) {
                    $companyId = $customer['company_id'];
                }
            }

            if (!$companyId) {
                // Log the error for debugging
                log_message('error', 'Company ID not found. Token: ' . ($token ?? 'none') . ', Session: ' . (session()->has('company_id') ? 'yes' : 'no'));

                return $this->response
                    ->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST)
                    ->setJSON([
                        'success' => false,
                        'message' => 'Company ID could not be determined. Please contact support.'
                    ]);
            }

            // Add company_id to data
            $data['company_id'] = $companyId;

            // Validate required fields
            if (empty($data['customer_id'])) {
                return $this->response
                    ->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST)
                    ->setJSON([
                        'success' => false,
                        'message' => 'Customer ID is required'
                    ]);
            }

            if (empty($data['name'])) {
                return $this->response
                    ->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST)
                    ->setJSON([
                        'success' => false,
                        'message' => 'Site name is required'
                    ]);
            }

            // Insert site
            $siteId = $this->siteModel->insert($data);

            if (!$siteId) {
                $errors = $this->siteModel->errors();
                return $this->response
                    ->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST)
                    ->setJSON([
                        'success' => false,
                        'message' => 'Validation failed',
                        'errors' => $errors
                    ]);
            }

            $site = $this->siteModel->find($siteId);

            return $this->response
                ->setStatusCode(ResponseInterface::HTTP_CREATED)
                ->setJSON([
                    'success' => true,
                    'message' => 'Site created successfully',
                    'data' => $site
                ]);
        } catch (\Exception $e) {
            log_message('error', 'Site creation failed: ' . $e->getMessage());
            return $this->response
                ->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR)
                ->setJSON([
                    'success' => false,
                    'message' => 'Failed to create site: ' . $e->getMessage()
                ]);
        }
    }

    /**
     * Update an existing site
     * PUT /api/admin/sites/{id}
     */
    public function update($id)
    {
        try {
            $site = $this->siteModel->find($id);

            if (!$site) {
                return $this->response
                    ->setStatusCode(ResponseInterface::HTTP_NOT_FOUND)
                    ->setJSON([
                        'success' => false,
                        'message' => 'Site not found'
                    ]);
            }

            $data = $this->request->getJSON(true);

            // Remove company_id and customer_id from update data (shouldn't be changed)
            unset($data['company_id']);
            unset($data['customer_id']);

            // Update site
            $updated = $this->siteModel->update($id, $data);

            if (!$updated) {
                $errors = $this->siteModel->errors();
                return $this->response
                    ->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST)
                    ->setJSON([
                        'success' => false,
                        'message' => 'Validation failed',
                        'errors' => $errors
                    ]);
            }

            $site = $this->siteModel->find($id);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Site updated successfully',
                'data' => $site
            ]);
        } catch (\Exception $e) {
            return $this->response
                ->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR)
                ->setJSON([
                    'success' => false,
                    'message' => 'Failed to update site: ' . $e->getMessage()
                ]);
        }
    }

    /**
     * Delete a site (soft delete)
     * DELETE /api/admin/sites/{id}
     */
    public function delete($id)
    {
        try {
            $site = $this->siteModel->find($id);

            if (!$site) {
                return $this->response
                    ->setStatusCode(ResponseInterface::HTTP_NOT_FOUND)
                    ->setJSON([
                        'success' => false,
                        'message' => 'Site not found'
                    ]);
            }

            // Soft delete
            $deleted = $this->siteModel->delete($id);

            if (!$deleted) {
                return $this->response
                    ->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR)
                    ->setJSON([
                        'success' => false,
                        'message' => 'Failed to delete site'
                    ]);
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Site deleted successfully'
            ]);
        } catch (\Exception $e) {
            return $this->response
                ->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR)
                ->setJSON([
                    'success' => false,
                    'message' => 'Failed to delete site: ' . $e->getMessage()
                ]);
        }
    }

    /**
     * Get sites by customer ID
     * GET /api/admin/sites/customer/{customer_id}
     */
    public function getByCustomer($customerId)
    {
        try {
            $sites = $this->siteModel
                ->where('customer_id', $customerId)
                ->findAll();

            return $this->response->setJSON([
                'success' => true,
                'data' => $sites
            ]);
        } catch (\Exception $e) {
            return $this->response
                ->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR)
                ->setJSON([
                    'success' => false,
                    'message' => 'Failed to fetch sites: ' . $e->getMessage()
                ]);
        }
    }

    /**
     * Helper method to extract company_id from JWT token
     */
    private function getCompanyIdFromToken($authHeader)
    {
        try {
            if (!$authHeader) {
                return null;
            }

            // Remove "Bearer " prefix
            $token = str_replace('Bearer ', '', $authHeader);

            if (empty($token)) {
                return null;
            }

            // Method 1: Try using JWT helper if available
            if (function_exists('validateJWT')) {
                helper('jwt');
                $decoded = validateJWT($token);
                if (isset($decoded->company_id)) {
                    return $decoded->company_id;
                }
            }

            // Method 2: Try direct JWT decode (if you're using Firebase JWT)
            if (class_exists('\Firebase\JWT\JWT')) {
                try {
                    $key = getenv('JWT_SECRET_KEY') ?: 'your-secret-key';
                    $decoded = \Firebase\JWT\JWT::decode($token, new \Firebase\JWT\Key($key, 'HS256'));

                    if (isset($decoded->company_id)) {
                        return $decoded->company_id;
                    }
                    if (isset($decoded->data->company_id)) {
                        return $decoded->data->company_id;
                    }
                } catch (\Exception $e) {
                    log_message('error', 'JWT decode failed: ' . $e->getMessage());
                }
            }

            // Method 3: Manual JWT decode (base64 decode the payload)
            try {
                $parts = explode('.', $token);
                if (count($parts) === 3) {
                    $payload = json_decode(base64_decode(str_replace(['-', '_'], ['+', '/'], $parts[1])), true);
                    if (isset($payload['company_id'])) {
                        return $payload['company_id'];
                    }
                    if (isset($payload['data']['company_id'])) {
                        return $payload['data']['company_id'];
                    }
                }
            } catch (\Exception $e) {
                log_message('error', 'Manual JWT decode failed: ' . $e->getMessage());
            }

            return null;
        } catch (\Exception $e) {
            log_message('error', 'Failed to extract company_id from token: ' . $e->getMessage());
            return null;
        }
    }
}