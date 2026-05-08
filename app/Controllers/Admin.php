<?php

namespace App\Controllers;

class Admin extends BaseController
{
    
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
        ];

        return view('admin/dashboard', $data);
    }
}
