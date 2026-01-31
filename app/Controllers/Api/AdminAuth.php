<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\UserModel;

class AdminAuth extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        helper('jwt');
    }

    public function index()
    {
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
        $data = $this->request->getJSON(true); // Read JSON body

        $email    = $data['email'] ?? null;
        $password = $data['password'] ?? null;

        if (!$email || !$password) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Email and password are required'
            ])->setStatusCode(400);
        }

        $admin = $this->userModel->getByEmail($email);

        if (!$admin || !$this->userModel->verifyPassword($email, $password)) {
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
                'id'        => $admin['id'],
                'full_name' => $admin['full_name'],
                'email'     => $admin['email'],
                'role_id'   => $admin['role_id']
            ]
        ]);
    }

    public function refresh()
    {
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