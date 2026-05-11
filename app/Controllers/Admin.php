<?php

namespace App\Controllers;

use App\Models\Regime;
use App\Models\Activite;
use App\Models\CodesWalletModel;
use App\Models\ParametreModel;

class Admin extends BaseController
{
    protected $regimeModel;
    protected $activiteModel;
    protected $codesModel;
    protected $paramModel;

    public function __construct()
    {
        $this->regimeModel = new Regime();
        $this->activiteModel = new Activite();
        $this->codesModel = new CodesWalletModel();
        $this->paramModel = new ParametreModel();
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

        // Pivot: subscriptions per regime (active / inactive)
        $pivotQuery = $db->query(
            "SELECT r.nom AS regime, 
                SUM(CASE WHEN ur.est_actif = 1 THEN 1 ELSE 0 END) AS active_count, 
                SUM(CASE WHEN ur.est_actif = 0 THEN 1 ELSE 0 END) AS inactive_count, 
                COUNT(*) AS total_count 
            FROM user_regimes ur 
            JOIN regimes r ON r.id = ur.id_regime 
            GROUP BY r.id, r.nom ORDER BY total_count DESC"
        );

        $subscriptionsPivot = $pivotQuery->getResultArray();

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
            'subscriptions_pivot' => $subscriptionsPivot,
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

    public function codesDelete($id)
    {
        $codeRow = $this->codesModel->find($id);
        if (!$codeRow) {
            return redirect()->to('/admin/codes')->with('error', 'Code non trouvé');
        }

        if ($codeRow['est_utilise']) {
            return redirect()->to('/admin/codes')->with('error', 'Impossible de supprimer un code déjà utilisé');
        }

        if ($this->codesModel->delete($id)) {
            return redirect()->to('/admin/codes')->with('success', 'Code supprimé');
        }

        return redirect()->to('/admin/codes')->with('error', 'Erreur lors de la suppression');
    }

    /**
     * Parametres Management
     */
    public function parametresIndex()
    {
        $params = $this->paramModel->orderBy('cle','ASC')->findAll();
        return view('admin/parametres/index', ['params' => $params]);
    }

    public function parametresCreate()
    {
        return view('admin/parametres/form');
    }

    public function parametresStore()
    {
        $data = [
            'cle' => $this->request->getPost('cle'),
            'valeur' => $this->request->getPost('valeur'),
            'description' => $this->request->getPost('description'),
        ];

        if ($this->paramModel->insert($data)) {
            return redirect()->to('/admin/parametres')->with('success', 'Paramètre ajouté');
        }

        return redirect()->back()->with('error', 'Erreur lors de la création');
    }

    public function parametresEdit($id)
    {
        $param = $this->paramModel->find($id);
        if (!$param) {
            return redirect()->to('/admin/parametres')->with('error', 'Paramètre non trouvé');
        }
        return view('admin/parametres/form', ['parametre' => $param]);
    }

    public function parametresUpdate($id)
    {
        $param = $this->paramModel->find($id);
        if (!$param) {
            return redirect()->to('/admin/parametres')->with('error', 'Paramètre non trouvé');
        }

        $data = [
            'cle' => $this->request->getPost('cle'),
            'valeur' => $this->request->getPost('valeur'),
            'description' => $this->request->getPost('description'),
        ];

        if ($this->paramModel->update($id, $data)) {
            return redirect()->to('/admin/parametres')->with('success', 'Paramètre mis à jour');
        }

        return redirect()->back()->with('error', 'Erreur lors de la mise à jour');
    }

    public function parametresDelete($id)
    {
        $param = $this->paramModel->find($id);
        if (!$param) {
            return redirect()->to('/admin/parametres')->with('error', 'Paramètre non trouvé');
        }

        if ($this->paramModel->delete($id)) {
            return redirect()->to('/admin/parametres')->with('success', 'Paramètre supprimé');
        }

        return redirect()->back()->with('error', 'Erreur lors de la suppression');
    }
}
