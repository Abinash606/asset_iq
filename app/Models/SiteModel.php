<?php

namespace App\Models;

use CodeIgniter\Model;

class SiteModel extends Model
{
    protected $table            = 'sites';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $useSoftDeletes   = true;
    protected $useTimestamps    = true;

    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $allowedFields = [
        'company_id',
        'customer_id',
        'name',
        'site_identifier',
        'address',
        'city',
        'state',
        'zip',
        'contact_name',
        'email',
        'phone',
    ];

    protected $validationRules = [
        'company_id'  => 'required|is_natural_no_zero',
        'customer_id' => 'required|is_natural_no_zero',
        'name'        => 'required|min_length[2]',
        'email'       => 'permit_empty|valid_email',
        'phone'       => 'permit_empty|max_length[50]',
    ];
}
