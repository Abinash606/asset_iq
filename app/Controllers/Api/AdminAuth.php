<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\AdminModel;

class AdminAuth extends BaseController
{
    public function index()
    {
        helper('jwt');
        $token = $this->request->getCookie('access_token');

        if ($token) {
            try {
                validateJWT($token);
                return redirect()->to(base_url('api/admin/dashboard'));
            } catch (\Exception $e) {
                // Invalid token → show login
            }
        }

        return view('auth/login');
    }

    public function login()
    {
        helper('jwt');

        $validation = \Config\Services::validation();
        $validation->setRules([
            'email'    => 'required|valid_email',
            'password' => 'required|min_length[6]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Validation failed',
                'errors'  => $validation->getErrors()
            ])->setStatusCode(400);
        }

        $adminModel = new AdminModel();
        $admin = $adminModel->where('email', $this->request->getVar('email'))->first();

        if (!$admin || !password_verify($this->request->getVar('password'), $admin['password'])) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Invalid email or password'
            ])->setStatusCode(401);
        }

        $accessToken = generateAccessToken([
            'uid'   => $admin['id'],
            'email' => $admin['email']
        ]);

        $refreshToken = generateRefreshToken([
            'uid' => $admin['id']
        ]);

        return $this->response->setJSON([
            'status'        => true,
            'message'       => 'Login successful',
            'access_token'  => $accessToken,
            'refresh_token' => $refreshToken,
            'user' => [
                'id'    => $admin['id'],
                'name'  => $admin['name'],
                'email' => $admin['email']
            ]
        ]);
    }

    public function refresh()
    {
        helper('jwt');

        $refreshToken = $this->request->getCookie('refresh_token');

        if (!$refreshToken) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Refresh token missing'
            ])->setStatusCode(401);
        }

        try {
            $decoded = validateJWT($refreshToken);

            if ($decoded->type !== 'refresh') {
                throw new \Exception('Invalid token type');
            }

            $accessToken = generateAccessToken([
                'uid'   => $decoded->data->uid,
                'email' => $decoded->data->email ?? null
            ]);

            return $this->response->setJSON([
                'status'       => true,
                'access_token' => $accessToken
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Invalid refresh token'
            ])->setStatusCode(401);
        }
    }

    public function logout()
    {
        return $this->response->setJSON([
            'status'  => true,
            'message' => 'Logged out successfully'
        ]);
    }
}
