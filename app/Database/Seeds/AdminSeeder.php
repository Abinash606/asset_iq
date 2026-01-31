<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'company_id'    => 1, 
            'role_id'       => 1, 
            'full_name'     => 'Super Admin',
            'email'         => 'admin@assetiq.com',
            'password_hash' => password_hash('Admin@123', PASSWORD_DEFAULT),
            'status'        => 'active',
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];

        $this->db->table('users')->insert($data);
    }
}