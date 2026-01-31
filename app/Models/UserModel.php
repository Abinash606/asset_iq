<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';

    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;

    protected $allowedFields = [
        'company_id',     
        'role_id',       
        'full_name',      
        'email',          
        'password_hash', 
        'phone',          
        'status',       
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules = [
        'email'         => 'required|valid_email',
        'password_hash' => 'required|min_length[6]',
        'full_name'     => 'required|min_length[2]',
        'role_id'       => 'required|integer',
    ];

    protected $validationMessages = [
        'email' => [
            'required'    => 'Email is required',
            'valid_email' => 'Email must be valid'
        ],
        'password_hash' => [
            'required'   => 'Password is required',
            'min_length' => 'Password must be at least 6 characters'
        ],
        'full_name' => [
            'required'   => 'Full name is required',
            'min_length' => 'Full name must be at least 2 characters'
        ],
        'role_id' => [
            'required' => 'Role is required',
            'integer'  => 'Role must be a valid number'
        ]
    ];

    protected $skipValidation = false;

    /**
     * Get user by email
     */
    public function getByEmail(string $email)
    {
        return $this->where('email', $email)
            ->where('deleted_at', null)
            ->first();
    }

    /**
     * Verify password
     */
    public function verifyPassword(string $email, string $password): bool
    {
        $user = $this->getByEmail($email);
        if (!$user) {
            return false;
        }

        return password_verify($password, $user['password_hash']);
    }
}