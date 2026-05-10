<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Dashboard extends BaseController
{
    /**
     * Display the user dashboard
     */
    public function index()
    {
        // Check if user is logged in
        if (!session()->get('user_id')) {
            return redirect()->to('/auth/login');
        }

        $db = db_connect();
        $user = $db->table('users')
                    ->where('id', session()->get('user_id'))
                    ->get()
                    ->getRowArray();

        if (!$user) {
            session()->destroy();
            return redirect()->to('/auth/login');
        }

        $data = [
            'user' => $user,
            'userName' => $user['nom'] ?? 'Utilisateur',
        ];

        return view('user/dashboard', $data);
    }

    /**
     * Display regimes page for user
     */
    public function regimes()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('/auth/login');
        }

        $data = [
            'userName' => session()->get('user_email'),
        ];

        return view('user/regimes', $data);
    }

    /**
     * Display wallet page for user
     */
    public function wallet()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('/auth/login');
        }

        $data = [
            'userName' => session()->get('user_email'),
        ];

        return view('user/wallet', $data);
    }

    /**
     * Display profile page for user
     */
    public function profile()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('/auth/login');
        }

        $db = db_connect();
        $user = $db->table('users')
                    ->where('id', session()->get('user_id'))
                    ->get()
                    ->getRowArray();

        $data = [
            'user' => $user,
        ];

        return view('user/profile', $data);
    }
}
