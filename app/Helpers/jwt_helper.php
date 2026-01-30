<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

/**
 * Generate Access Token
 */
function generateAccessToken(array $data, int $expiry = 900)
{
    $key = getenv('JWT_SECRET_KEY');

    if (!$key || strlen($key) < 32) {
        throw new Exception('JWT secret key is missing or too weak');
    }

    $payload = [
        'iss'  => 'asset_iq',
        'aud'  => 'admin',
        'iat'  => time(),
        'exp'  => time() + $expiry,
        'type' => 'access',
        'data' => $data
    ];

    return JWT::encode($payload, $key, 'HS256');
}

/**
 * Generate Refresh Token
 */
function generateRefreshToken(array $data, int $expiry = 2592000)
{
    $key = getenv('JWT_SECRET_KEY');

    $payload = [
        'iss'  => 'asset_iq',
        'aud'  => 'admin',
        'iat'  => time(),
        'exp'  => time() + $expiry,
        'type' => 'refresh',
        'data' => $data
    ];

    return JWT::encode($payload, $key, 'HS256');
}

/**
 * Validate JWT
 */
function validateJWT(string $token)
{
    $key = getenv('JWT_SECRET_KEY');
    return JWT::decode($token, new Key($key, 'HS256'));
}
