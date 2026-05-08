<?php

namespace App\Controllers;

class Admin extends BaseController
{
    
    public function dashboard()
    {
        $session = session();
        $data = [
            'admin_nom' => $session->get('admin_nom'),
        ];
        return view('admin/dashboard', $data);
    }
}
