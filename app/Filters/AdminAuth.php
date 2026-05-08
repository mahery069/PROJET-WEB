<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminAuth implements FilterInterface
{
   
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        $adminId = $session->get('admin_id');

        if (!$adminId) {
            return redirect()->to('/admin/login')->with('error', 'Veuillez vous connecter');
        }

        if ($session->get('admin_role') !== 'admin') {
            return redirect()->to('/admin/login')->with('error', 'Accès refusé');
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No post-processing needed
    }
}
