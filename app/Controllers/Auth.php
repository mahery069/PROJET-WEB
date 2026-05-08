<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Auth extends BaseController
{
    /**
     * Display the login form
     */
    public function login()
    {
        return view('auth/login');
    }

    /**
     * Handle login form submission
     */
    public function handleLogin()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $db = db_connect();
        $user = $db->table('users')->where('email', $email)->get()->getRowArray();

        if (!$user || !password_verify($password ?? '', $user['mot_de_passe'])) {
            return redirect()->back()->with('error', 'Identifiants invalides');
        }

        session()->set([
            'user_id' => $user['id'],
            'user_email' => $user['email'],
        ]);

        return redirect()->to('/profil');
    }

    /**
     * Display the registration form - Step 1
     */
    public function registerStep1()
    {
        return view('auth/formulaire');
    }

    /**
     * Handle registration step 1 - Store data in session
     */
    public function handleRegisterStep1()
    {
        session()->set([
            'register_step1' => [
                'nom' => $this->request->getPost('nom'),
                'prenom' => $this->request->getPost('prenom'),
                'email' => $this->request->getPost('email'),
                'password' => $this->request->getPost('password'),
                'genre' => $this->request->getPost('genre'),
                'date_naissance' => $this->request->getPost('date_naissance'),
            ]
        ]);

        return redirect()->to('/formulaire-step2');
    }

    /**
     * Display the registration form - Step 2
     */
    public function registerStep2()
    {
        return view('auth/formuaire2');
    }

    /**
     * Handle registration step 2 - Final registration
     */
    public function handleRegisterStep2()
    {
        if (!session()->has('register_step1')) {
            return redirect()->to('/auth/register');
        }

        $step1 = session('register_step1');
        $taille = $this->request->getPost('taille');
        $poids = $this->request->getPost('poids');
        $objectif = $this->request->getPost('objectif');
        $email = $step1['email'] ?? '';

        $nomComplet = trim(($step1['nom'] ?? '') . ' ' . ($step1['prenom'] ?? ''));

        if ($email === '') {
            return redirect()->back()->with('error', 'Email obligatoire.');
        }

        $db = db_connect();
        $existing = $db->table('users')->where('email', $email)->get()->getRowArray();
        if ($existing) {
            return redirect()->back()->with('error', 'Cet email existe deja.');
        }
        $db->transStart();

        $db->table('users')->insert([
            'nom' => $nomComplet === '' ? ($step1['nom'] ?? '') : $nomComplet,
            'email' => $email,
            'mot_de_passe' => password_hash($step1['password'] ?? '', PASSWORD_BCRYPT),
            'genre' => $step1['genre'] ?? 'autre',
            'date_naissance' => $step1['date_naissance'] ?? date('Y-m-d'),
            'role' => 'user',
            'is_gold' => 0,
        ]);

        $userId = $db->insertID();

        $db->table('user_health')->insert([
            'id_user' => $userId,
            'taille_cm' => $taille,
            'poids_kg' => $poids,
            'objectif' => $objectif ?: 'imc_ideal',
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Erreur lors de l\'inscription.');
        }

        session()->remove('register_step1');

        return redirect()->to('/auth/login')
            ->with('success', 'Inscription reussie! Connectez-vous');
    }

    /**
     * Logout the user
     */
    public function logout()
    {
        $userId = session('user_id');
        if ($userId) {
            try {
                $db = db_connect();
                $db->table('user_logs')->insert([
                    'id_user' => $userId,
                    'action' => 'logout',
                    'ip_address' => $this->request->getIPAddress(),
                    'user_agent' => $this->request->getUserAgent()->getAgentString(),
                ]);
            } catch (\Throwable $e) {
                // Ignore log errors
            }
        }

        session()->destroy();
        return redirect()->to('/auth/login')
            ->with('success', 'Vous avez été déconnecté');
    }

    /**
     * Display forgot password form
     */
    public function forgotPassword()
    {
        return view('auth/forgot-password');
    }

    /**
     * Display the profile form
     */
    public function profile()
    {
        $userId = session('user_id');
        if (!$userId) {
            return redirect()->to('/auth/login');
        }

        $db = db_connect();
        $user = $db->table('users')->where('id', $userId)->get()->getRowArray();
        $health = $db->table('user_health')->where('id_user', $userId)->get()->getRowArray();

        return view('auth/profile', [
            'user' => $user,
            'health' => $health,
        ]);
    }

    /**
     * Handle profile update (placeholder)
     */
    public function updateProfile()
    {
        $userId = session('user_id');
        if (!$userId) {
            return redirect()->to('/auth/login');
        }

        $nom = $this->request->getPost('nom');
        $prenom = $this->request->getPost('prenom');
        $nomComplet = trim(($nom ?? '') . ' ' . ($prenom ?? ''));
        $email = $this->request->getPost('email');
        $genre = $this->request->getPost('genre');
        $dateNaissance = $this->request->getPost('date_naissance');
        $taille = $this->request->getPost('taille');
        $poids = $this->request->getPost('poids');
        $objectif = $this->request->getPost('objectif');

        $db = db_connect();
        $db->transStart();

        $db->table('users')->where('id', $userId)->update([
            'nom' => $nomComplet === '' ? ($nom ?? '') : $nomComplet,
            'email' => $email,
            'genre' => $genre,
            'date_naissance' => $dateNaissance,
        ]);

        $health = $db->table('user_health')->where('id_user', $userId)->get()->getRowArray();
        if ($health) {
            $db->table('user_health')->where('id_user', $userId)->update([
                'taille_cm' => $taille,
                'poids_kg' => $poids,
                'objectif' => $objectif,
            ]);
        } else {
            $db->table('user_health')->insert([
                'id_user' => $userId,
                'taille_cm' => $taille,
                'poids_kg' => $poids,
                'objectif' => $objectif ?: 'imc_ideal',
            ]);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Erreur lors de la mise a jour.');
        }

        return redirect()->back()->with('success', 'Profil mis a jour.');
    }

    /**
     * Display objectifs and suggestions page
     */
    public function objectifs()
    {
        return view('auth/objectifs');
    }

    /**
     * Return suggestions from DB (regimes + activites)
     */
    public function objectifsData()
    {
        $objectif = $this->request->getGet('objectif');

        $db = db_connect();
        $regimesBuilder = $db->table('regimes')->where('actif', 1);

        if ($objectif === 'augmenter') {
            $regimesBuilder->where('variation_poids >', 0);
        } elseif ($objectif === 'reduire') {
            $regimesBuilder->where('variation_poids <', 0);
        } elseif ($objectif === 'imc_ideal') {
            $regimesBuilder->where('variation_poids >=', -0.2)->where('variation_poids <=', 0.2);
        }

        $regimes = $regimesBuilder->orderBy('variation_poids', 'DESC')->limit(3)->get()->getResultArray();

        $activites = $db->table('activites')
            ->where('actif', 1)
            ->orderBy('intensite', 'ASC')
            ->limit(3)
            ->get()
            ->getResultArray();

        $suggestions = [];
        $count = max(count($regimes), count($activites));
        for ($i = 0; $i < $count; $i++) {
            $regime = $regimes[$i] ?? null;
            $activite = $activites[$i] ?? null;

            if (!$regime && !$activite) {
                continue;
            }

            $suggestions[] = [
                'regime' => $regime ? $regime['nom'] : 'Regime a definir',
                'impact' => $regime ? (string) $regime['variation_poids'] . ' kg' : '-',
                'activite' => $activite ? $activite['nom'] : 'Activite a definir',
            ];
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => $suggestions,
        ]);
use App\Models\User;
use CodeIgniter\HTTP\ResponseInterface;

class Auth extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

   
    public function login()
    {
        $session = session();
        if ($session->get('admin_id')) {
            return redirect()->to('/admin/dashboard');
        }
        return view('auth/admin_login');
    }

    
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

   
    public function logout()
    {
        session()->remove(['admin_id', 'admin_email', 'admin_nom', 'admin_role']);
        return redirect()->to('/auth/login')->with('success', 'Vous avez ete deconnecte');
    }
}
