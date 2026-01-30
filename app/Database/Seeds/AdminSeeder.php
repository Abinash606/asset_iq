<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'name'       => 'Super Admin',
            'email'      => 'admin@assetiq.com',
            'password'   => password_hash('Admin@123', PASSWORD_DEFAULT),
            'role'       => 'super_admin',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $this->db->table('admins')->insert($data);
    }
}
