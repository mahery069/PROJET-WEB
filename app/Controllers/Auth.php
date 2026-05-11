<?php

namespace App\Controllers;

use Dompdf\Dompdf;
use Dompdf\Options;

class Auth extends BaseController
{
    public function login()
    {
        if (session()->get('user_id')) {
            return redirect()->to('/dashboard');
        }

        return view('auth/login');
    }

    public function handleLogin()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        if (empty($email) || empty($password)) {
            return redirect()->back()->with('error', 'Email et mot de passe requis');
        }

        $db = db_connect();
        $user = $db->table('users')->where('email', $email)->get()->getRowArray();

        if (!$user) {
            return redirect()->back()->with('error', 'Utilisateur non trouvé');
        }

        if ($user['role'] === 'admin') {
            return redirect()->back()->with('error', 'Accès réservé aux utilisateurs. Utilisez la page admin.');
        }

        if (!password_verify($password, $user['mot_de_passe'])) {
            return redirect()->back()->with('error', 'Mot de passe incorrect');
        }

        session()->set([
            'user_id' => $user['id'],
            'user_email' => $user['email'],
            'user_name' => $user['nom'] ?? $user['email'],
        ]);

        return redirect()->to('/dashboard');
    }

    public function registerStep1()
    {
        return view('auth/formulaire');
    }

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

    public function registerStep2()
    {
        return view('auth/formuaire2');
    }

    public function handleRegisterStep2()
    {
        if (!session()->has('register_step1')) {
            return redirect()->to('/auth/register');
        }

        $step1 = session('register_step1');
        $taille = $this->request->getPost('taille');
        $poids = $this->request->getPost('poids');
        $objectif = $this->request->getPost('objectif');
        $genre = $this->request->getPost('genre') ?: ($step1['genre'] ?? 'autre');
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
            'genre' => $genre,
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

    public function forgotPassword()
    {
        return view('auth/forgot-password');
    }

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

    public function updateProfile()
    {
        $userId = session('user_id');
        if (!$userId) {
            return redirect()->to('/auth/login');
        }
        $db = db_connect();
        $user = $db->table('users')->where('id', $userId)->get()->getRowArray() ?? [];
        $health = $db->table('user_health')->where('id_user', $userId)->get()->getRowArray() ?? [];

        $nom = trim((string) $this->request->getPost('nom'));
        $prenom = trim((string) $this->request->getPost('prenom'));
        $email = trim((string) $this->request->getPost('email'));
        $genre = trim((string) $this->request->getPost('genre'));
        $dateNaissance = trim((string) $this->request->getPost('date_naissance'));
        $taille = trim((string) $this->request->getPost('taille'));
        $poids = trim((string) $this->request->getPost('poids'));
        $objectif = trim((string) $this->request->getPost('objectif'));

        $nomComplet = trim($nom . ' ' . $prenom);
        if ($nomComplet === '') {
            $nomComplet = $user['nom'] ?? '';
        }

        if ($email === '') {
            $email = $user['email'] ?? '';
        }
        if ($genre === '') {
            $genre = $user['genre'] ?? '';
        }
        if ($dateNaissance === '') {
            $dateNaissance = $user['date_naissance'] ?? '';
        }
        if ($taille === '') {
            $taille = $health['taille_cm'] ?? '';
        }
        if ($poids === '') {
            $poids = $health['poids_kg'] ?? '';
        }
        if ($objectif === '') {
            $objectif = $health['objectif'] ?? '';
        }
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

    public function objectifs()
    {
        return view('auth/objectifs');
    }

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

        if (empty($regimes)) {
            $regimes = $db->table('regimes')
                ->where('actif', 1)
                ->orderBy('prix', 'ASC')
                ->limit(3)
                ->get()
                ->getResultArray();
        }

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
    }

    public function exportPDF()
    {
        $session = session();
        $userId = $session->get('user_id');

        if (!$userId) {
            return redirect()->to('/auth/login')->with('error', 'Veuillez vous connecter');
        }

        $db = db_connect();
        $user = $db->table('users')->where('id', $userId)->get()->getRowArray();
        $health = $db->table('user_health')->where('id_user', $userId)->get()->getRowArray();
        $subscriptions = $db->table('user_regimes')
            ->select('user_regimes.*, regimes.nom, regimes.description, regimes.prix')
            ->join('regimes', 'user_regimes.id_regime = regimes.id')
            ->where('user_regimes.id_user', $userId)
            ->where('user_regimes.est_actif', 1)
            ->get()
            ->getResultArray();

        // Build HTML content
        $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Profil Utilisateur - NutriPlan</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; color: #000; }
        h1 { margin: 0 0 6px 0; font-size: 22px; font-weight: bold; }
        p { margin: 4px 0; }
        .section { margin-top: 18px; }
        .section-title { font-weight: bold; margin-bottom: 8px; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { padding: 8px; text-align: left; border: 1px solid #000; }
        th { font-weight: bold; }
        .imc { font-weight: bold; }
        .footer { margin-top: 24px; font-size: 12px; }
    </style>
</head>
<body>
    <h1>Profil Utilisateur - NutriPlan</h1>
    <p>Rapport généré le ' . date('d/m/Y H:i') . '</p>

    <div class="section">
        <div class="section-title">Informations Personnelles</div>
        <p><strong>Nom:</strong> ' . htmlspecialchars($user['nom'] ?? 'N/A') . '</p>
        <p><strong>Email:</strong> ' . htmlspecialchars($user['email']) . '</p>
        <p><strong>Genre:</strong> ' . htmlspecialchars($user['genre'] ?? 'N/A') . '</p>
        <p><strong>Statut Gold:</strong> ' . ($user['is_gold'] ? 'Membre Gold (15% remise)' : 'Standard') . '</p>
    </div>';

        if ($health) {
            $imc = $health['poids_kg'] / (($health['taille_cm'] / 100) * ($health['taille_cm'] / 100));
            $html .= '
    <div class="section">
        <div class="section-title">Données de Santé</div>
        <p><strong>Taille:</strong> ' . htmlspecialchars($health['taille_cm']) . ' cm</p>
        <p><strong>Poids:</strong> ' . htmlspecialchars($health['poids_kg']) . ' kg</p>
        <p><strong>IMC (Indice de Masse Corporelle):</strong> <span class="imc">' . number_format($imc, 2) . '</span></p>
        <p><strong>Objectif:</strong> ' . htmlspecialchars($health['objectif']) . '</p>
    </div>';
        }

        if (!empty($subscriptions)) {
            $html .= '
    <div class="section">
        <div class="section-title">Régimes Actifs</div>
        <table>
            <thead>
                <tr>
                    <th>Régime</th>
                    <th>Description</th>
                    <th>Prix Payé</th>
                    <th>Date Fin</th>
                </tr>
            </thead>
            <tbody>';

            foreach ($subscriptions as $sub) {
                $html .= '
                <tr>
                    <td>' . htmlspecialchars($sub['nom']) . '</td>
                    <td>' . htmlspecialchars(substr($sub['description'] ?? '', 0, 50)) . '...</td>
                    <td>€' . number_format($sub['prix_paye'], 2) . '</td>
                    <td>' . htmlspecialchars($sub['date_fin']) . '</td>
                </tr>';
            }

            $html .= '
            </tbody>
        </table>
    </div>';
        }

        $html .= '
    <div class="footer">
        <p>© ' . date('Y') . ' NutriPlan - Tous droits réservés</p>
    </div>
</body>
</html>';

        $options = new Options();
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $filename = 'profil_' . $user['id'] . '_' . date('Ymd_His') . '.pdf';
        $pdfOutput = $dompdf->output();

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody($pdfOutput);
    }
}
