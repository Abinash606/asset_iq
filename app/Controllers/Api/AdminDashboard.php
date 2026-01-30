<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;

class AdminDashboard extends BaseController
{
    public function index()
    {
        $user = $this->request->user ?? null;

        if (!$user) {
            return redirect()->to(base_url('/'));
        }
        return view('admin/dashboard', ['user' => $user]);
    }
}