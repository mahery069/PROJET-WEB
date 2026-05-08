<?php

namespace App\Controllers;

use App\Models\Regime;
use App\Models\Activite;
use App\Models\CodesWalletModel;

class Admin extends BaseController
{
    protected $regimeModel;
    protected $activiteModel;
    protected $codesModel;

    public function __construct()
    {
        $this->regimeModel = new Regime();
        $this->activiteModel = new Activite();
        $this->codesModel = new CodesWalletModel();
    }
    
    public function dashboard()
    {
        $session = session();

        $db = \Config\Database::connect();

        // Basic counts and sums
        $totalUsers = (int) $db->table('users')->countAllResults();
        $row = $db->query('SELECT COALESCE(SUM(solde),0) AS total FROM user_wallet')->getRow();
        $totalWallet = $row ? $row->total : 0;
        $totalRegimes = (int) $db->table('regimes')->countAllResults();
        $totalActivites = (int) $db->table('activites')->countAllResults();
        $totalCodes = (int) $db->table('codes_wallet')->countAllResults();
        $usedCodes = (int) $db->table('codes_wallet')->where('est_utilise', 1)->countAllResults();
        $unusedCodes = $totalCodes - $usedCodes;

        // Top 5 users by wallet balance
        $topUsersQuery = $db->table('users u')
            ->select('u.id, u.nom, COALESCE(w.solde,0) AS solde')
            ->join('user_wallet w', 'w.id_user = u.id', 'left')
            ->orderBy('solde', 'DESC')
            ->limit(5)
            ->get();

        $topUsers = $topUsersQuery->getResult();
        $topLabels = [];
        $topData = [];
        foreach ($topUsers as $u) {
            $topLabels[] = $u->nom ?: ('#' . $u->id);
            $topData[] = (float) $u->solde;
        }

        // Objective distribution
        $objectiveData = $db->table('user_health')
            ->select('objectif, COUNT(*) as count')
            ->groupBy('objectif')
            ->get()
            ->getResultArray();
        
        $objLabels = [];
        $objCounts = [];
        foreach ($objectiveData as $obj) {
            $objLabels[] = ucfirst($obj['objectif']);
            $objCounts[] = $obj['count'];
        }

        $data = [
            'admin_nom' => $session->get('admin_nom'),
            'stats' => [
                'total_users' => $totalUsers,
                'total_wallet' => $totalWallet,
                'total_regimes' => $totalRegimes,
                'total_activites' => $totalActivites,
                'total_codes' => $totalCodes,
                'used_codes' => $usedCodes,
                'unused_codes' => $unusedCodes,
            ],
            'codesChart' => [ 'used' => $usedCodes, 'unused' => $unusedCodes ],
            'topUsersChart' => [ 'labels' => $topLabels, 'data' => $topData ],
            'objectiveChart' => [ 'labels' => $objLabels, 'data' => $objCounts ],
        ];

        return view('admin/dashboard', $data);
    }

    /**
     * Regimes Management
     */
    public function regimesIndex()
    {
        $regimes = $this->regimeModel->findAll();
        return view('admin/regimes/index', ['regimes' => $regimes]);
    }

    public function regimesCreate()
    {
        return view('admin/regimes/create');
    }

    public function regimesStore()
    {
        $data = [
            'nom' => $this->request->getPost('nom'),
            'description' => $this->request->getPost('description'),
            'pourcentage_viande' => $this->request->getPost('pourcentage_viande'),
            'pourcentage_poisson' => $this->request->getPost('pourcentage_poisson'),
            'pourcentage_volaille' => $this->request->getPost('pourcentage_volaille'),
            'variation_poids' => $this->request->getPost('variation_poids'),
            'duree_jours' => $this->request->getPost('duree_jours'),
            'prix' => $this->request->getPost('prix'),
            'actif' => $this->request->getPost('actif') ? 1 : 0,
        ];

        if ($this->regimeModel->insert($data)) {
            return redirect()->to('/admin/regimes')->with('success', 'Régime créé avec succès');
        }

        return redirect()->back()->with('error', 'Erreur lors de la création');
    }

    public function regimesEdit($id)
    {
        $regime = $this->regimeModel->find($id);
        if (!$regime) {
            return redirect()->to('/admin/regimes')->with('error', 'Régime non trouvé');
        }
        return view('admin/regimes/edit', ['regime' => $regime]);
    }

    public function regimesUpdate($id)
    {
        $regime = $this->regimeModel->find($id);
        if (!$regime) {
            return redirect()->to('/admin/regimes')->with('error', 'Régime non trouvé');
        }

        $data = [
            'nom' => $this->request->getPost('nom'),
            'description' => $this->request->getPost('description'),
            'pourcentage_viande' => $this->request->getPost('pourcentage_viande'),
            'pourcentage_poisson' => $this->request->getPost('pourcentage_poisson'),
            'pourcentage_volaille' => $this->request->getPost('pourcentage_volaille'),
            'variation_poids' => $this->request->getPost('variation_poids'),
            'duree_jours' => $this->request->getPost('duree_jours'),
            'prix' => $this->request->getPost('prix'),
            'actif' => $this->request->getPost('actif') ? 1 : 0,
        ];

        if ($this->regimeModel->update($id, $data)) {
            return redirect()->to('/admin/regimes')->with('success', 'Régime mis à jour');
        }

        return redirect()->back()->with('error', 'Erreur lors de la mise à jour');
    }

    public function regimesDelete($id)
    {
        if ($this->regimeModel->delete($id)) {
            return redirect()->to('/admin/regimes')->with('success', 'Régime supprimé');
        }
        return redirect()->back()->with('error', 'Erreur lors de la suppression');
    }

    /**
     * Activites Management
     */
    public function activitesIndex()
    {
        $activites = $this->activiteModel->findAll();
        return view('admin/activites/index', ['activites' => $activites]);
    }

    public function activitesCreate()
    {
        return view('admin/activites/create');
    }

    public function activitesStore()
    {
        $data = [
            'nom' => $this->request->getPost('nom'),
            'description' => $this->request->getPost('description'),
            'intensite' => $this->request->getPost('intensite'),
            'duree_minutes' => $this->request->getPost('duree_minutes'),
            'actif' => $this->request->getPost('actif') ? 1 : 0,
        ];

        if ($this->activiteModel->insert($data)) {
            return redirect()->to('/admin/activites')->with('success', 'Activité créée avec succès');
        }

        return redirect()->back()->with('error', 'Erreur lors de la création');
    }

    public function activitesEdit($id)
    {
        $activite = $this->activiteModel->find($id);
        if (!$activite) {
            return redirect()->to('/admin/activites')->with('error', 'Activité non trouvée');
        }
        return view('admin/activites/edit', ['activite' => $activite]);
    }

    public function activitesUpdate($id)
    {
        $activite = $this->activiteModel->find($id);
        if (!$activite) {
            return redirect()->to('/admin/activites')->with('error', 'Activité non trouvée');
        }

        $data = [
            'nom' => $this->request->getPost('nom'),
            'description' => $this->request->getPost('description'),
            'intensite' => $this->request->getPost('intensite'),
            'duree_minutes' => $this->request->getPost('duree_minutes'),
            'actif' => $this->request->getPost('actif') ? 1 : 0,
        ];

        if ($this->activiteModel->update($id, $data)) {
            return redirect()->to('/admin/activites')->with('success', 'Activité mise à jour');
        }

        return redirect()->back()->with('error', 'Erreur lors de la mise à jour');
    }

    public function activitesDelete($id)
    {
        if ($this->activiteModel->delete($id)) {
            return redirect()->to('/admin/activites')->with('success', 'Activité supprimée');
        }
        return redirect()->back()->with('error', 'Erreur lors de la suppression');
    }

    /**
     * Codes Wallet Management
     */
    public function codesIndex()
    {
        $codes = $this->codesModel->orderBy('id', 'DESC')->findAll();
        return view('admin/codes/index', ['codes' => $codes]);
    }

    public function codesCreate()
    {
        return view('admin/codes/create');
    }

    public function codesStore()
    {
        $code = strtoupper(substr(md5(microtime()), 0, 10));

        $data = [
            'code' => $code,
            'montant' => $this->request->getPost('montant'),
            'est_utilise' => 0,
        ];

        if ($this->codesModel->insert($data)) {
            return redirect()->to('/admin/codes')->with('success', 'Code créé: ' . $code);
        }

        return redirect()->back()->with('error', 'Erreur lors de la création');
    }

    public function codesValidate($id)
    {
        $codeRow = $this->codesModel->find($id);
        if (!$codeRow) {
            return redirect()->to('/admin/codes')->with('error', 'Code non trouvé');
        }

        if ($codeRow['est_utilise']) {
            return redirect()->to('/admin/codes')->with('error', 'Code déjà utilisé');
        }

        $this->codesModel->update($id, [
            'est_utilise' => 1,
            'date_utilisation' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/admin/codes')->with('success', 'Code validé');
    }
}
