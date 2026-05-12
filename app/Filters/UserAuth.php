<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class UserAuth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        $userId = $session->get('user_id');

        if (!$userId) {
            return redirect()->to('/auth/login')->with('error', 'Veuillez vous connecter');
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No post-processing needed
    }
}
