<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

class JwtFilter implements FilterInterface
{
    private $key;

    public function __construct()
    {
        $this->key = getenv('JWT_SECRET_KEY') ?: 'your-secret-key-here-change-this-in-production';
    }

    public function before(RequestInterface $request, $arguments = null)
    {
        // Get token from cookie first, then fallback to Authorization header
        $token = $request->getCookie('access_token');

        if (!$token) {
            // Try to get from Authorization header
            $authHeader = $request->getHeaderLine('Authorization');

            if ($authHeader) {
                // Extract token from "Bearer TOKEN" format
                $arr = explode(' ', $authHeader);
                $token = isset($arr[1]) ? $arr[1] : null;
            }
        }

        if (!$token) {
            return service('response')
                ->setJSON([
                    'status' => false,
                    'message' => 'Token required'
                ])
                ->setStatusCode(401);
        }

        try {
            // Decode and verify token
            $decoded = JWT::decode($token, new Key($this->key, 'HS256'));

            // Check if token is access token
            if (!isset($decoded->type) || $decoded->type !== 'access') {
                throw new Exception('Invalid token type');
            }

            // Check if token is expired
            if (isset($decoded->exp) && $decoded->exp < time()) {
                throw new Exception('Token expired');
            }

            // Add user data to request for use in controllers
            $request->user = (array) $decoded;
        } catch (Exception $e) {
            return service('response')
                ->setJSON([
                    'status' => false,
                    'message' => 'Invalid or expired token',
                    'error' => $e->getMessage()
                ])
                ->setStatusCode(401);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
