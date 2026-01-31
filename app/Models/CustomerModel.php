<?php

namespace App\Models;

use CodeIgniter\Model;

class CustomerModel extends Model
{
    protected $table            = 'customers';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;

    protected $allowedFields = [
        'company_id',
        'name',
        'billing_address',
        'billing_city',
        'billing_state',
        'billing_zip',
        'contact_name',
        'email',
        'phone',
        'fax',
        'website',
        'logo_path',
        'created_by',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules = [
        'name'      => 'required|min_length[2]',
        'email'     => 'valid_email',
        'phone'     => 'max_length[50]',
    ];

    protected $skipValidation = false;
}
