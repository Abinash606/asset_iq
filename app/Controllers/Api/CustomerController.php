<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\CustomerModel;
use CodeIgniter\HTTP\ResponseInterface;

class CustomerController extends BaseController
{
    protected $customerModel;

    public function __construct()
    {
        $this->customerModel = new CustomerModel();
        helper(['form', 'url']);
    }

    public function index()
    {
        return view('admin/customers');
    }

    /**
     * Get list of customers for DataTable
     */
    public function list()
    {
        $customers = $this->customerModel->findAll();

        return $this->response->setJSON([
            'success' => true, 
            'data' => array_map(function ($c) {
                return [
                    'id' => $c['id'],
                    'name' => $c['name'],
                    'address' => $c['billing_address'],
                    'city' => $c['billing_city'],
                    'state' => $c['billing_state'],
                    'zip' => $c['billing_zip'],
                    'contact' => $c['contact_name'],
                    'email' => $c['email'],
                    'phone' => $c['phone'],
                    'website' => $c['website'],
                ];
            }, $customers)
        ]);
    }


    /**
     * Get single customer
     */
    public function get($id)
    {
        try {
            $customer = $this->customerModel->find($id);

            if (!$customer) {
                return $this->response->setJSON([
                    'success' => false,  
                    'message' => 'Customer not found'
                ])->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
            }

            return $this->response->setJSON([
                'success' => true,  
                'data' => $customer
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false, 
                'message' => $e->getMessage()
            ])->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store new customer
     */
    public function store()
    {
        $payload = $this->request->getJSON(true) ?? $this->request->getPost();

        $data = [
            'company_id' => session('company_id') ?? 1,
            'name' => $payload['name'] ?? null,
            'billing_address' => $payload['address'] ?? null,
            'billing_city' => $payload['city'] ?? null,
            'billing_state' => $payload['state'] ?? null,
            'billing_zip' => $payload['zip'] ?? null,
            'contact_name' => $payload['contact_name'] ?? null,
            'email' => $payload['email'] ?? null,
            'phone' => $payload['phone'] ?? null,
            'fax' => $payload['fax'] ?? null,
            'website' => $payload['website'] ?? null,
            'created_by' => session('user_id') ?? 1,
        ];

        if (!$this->customerModel->insert($data)) {
            return $this->response->setJSON([
                'success' => false,  
                'message' => 'Failed to create customer',
                'errors' => $this->customerModel->errors()
            ])->setStatusCode(400);
        }

        return $this->response->setJSON([
            'success' => true, 
            'message' => 'Customer created successfully'
        ]);
    }


    /**
     * Update customer
     */
    public function update($id)
    {
        $payload = $this->request->getJSON(true) ?? $this->request->getRawInput();

        if (!$this->customerModel->find($id)) {
            return $this->response->setJSON([
                'success' => false,  
                'message' => 'Customer not found'
            ])->setStatusCode(404);
        }

        if (!$this->customerModel->update($id, $payload)) {
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Failed to update customer',
                'errors' => $this->customerModel->errors()
            ])->setStatusCode(400);
        }

        return $this->response->setJSON([
            'success' => true,  
            'message' => 'Customer updated successfully'
        ]);
    }


    /**
     * Delete customer (soft delete)
     */
    public function delete($id)
    {
        try {
            $customer = $this->customerModel->find($id);
            if (!$customer) {
                return $this->response->setJSON([
                    'success' => false,  
                    'message' => 'Customer not found'
                ])->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
            }

            if ($this->customerModel->delete($id)) {
                return $this->response->setJSON([
                    'success' => true,  
                    'message' => 'Customer deleted successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false, 
                    'message' => 'Failed to delete customer'
                ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,  
                'message' => $e->getMessage()
            ])->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}