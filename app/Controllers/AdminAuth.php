<?php

namespace App\Controllers;

use App\Models\User;
use CodeIgniter\HTTP\ResponseInterface;

class AdminAuth extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    /**
     * Display admin login form
     */
    public function login()
    {
        $session = session();
        if ($session->get('admin_id')) {
            return redirect()->to('/admin/dashboard');
        }
        return view('admin/login');
    }

    /**
     * Handle admin login
     */
    public function handleLogin()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        if (empty($email) || empty($password)) {
            return redirect()->back()->with('error', 'Email et mot de passe requis');
        }

        $user = $this->userModel->findAdminByEmail($email);

        if (!$user || !User::verifyPassword($password, $user['mot_de_passe'])) {
            return redirect()->back()->with('error', 'Email ou mot de passe incorrect');
        }

        session()->set([
            'admin_id' => $user['id'],
            'admin_email' => $user['email'],
            'admin_nom' => $user['nom'],
            'admin_role' => $user['role'],
        ]);

        return redirect()->to('/admin/dashboard')->with('success', 'Bienvenue ' . $user['nom']);
    }

    /**
     * Logout admin
     */
    public function logout()
    {
        session()->remove(['admin_id', 'admin_email', 'admin_nom', 'admin_role']);
        return redirect()->to('/')->with('success', 'Vous avez été déconnecté');
    }
}
