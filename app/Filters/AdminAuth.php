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
            return redirect()->to('/auth/login')->with('error', 'Veuillez vous connecter');
        }

       
        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
       
    }
}
