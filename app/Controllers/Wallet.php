<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;

class Wallet extends BaseController
{
    /**
     * Display Gold page
     */
    public function goldPage()
    {
        return view('wallet/gold');
    }

    public function balance()
    {
        $session = session();
        $userId = $session->get('user_id') ?? $this->request->getVar('user_id');

        if (empty($userId)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Utilisateur non spécifié.',
                'data' => null,
                'errors' => ['user_id' => 'missing']
            ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }

        $db = \Config\Database::connect();
        $builder = $db->table('user_wallet');
        $row = $builder->where('id_user', $userId)->get()->getRowArray();

        if (! $row) {
            // If no wallet row, assume zero balance
            $solde = '0.00';
        } else {
            $solde = $row['solde'];
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Solde récupéré',
            'data' => ['solde' => $solde],
            'errors' => null
        ]);
    }

    public function redeem()
    {
        $session = session();
        $userId = $session->get('user_id') ?? $this->request->getVar('user_id');
        $code = $this->request->getJSON(true)['code'] ?? $this->request->getPost('code');

        if (empty($userId) || empty($code)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Parametres manquants.',
                'data' => null,
                'errors' => ['user_id' => empty($userId) ? 'missing' : null, 'code' => empty($code) ? 'missing' : null]
            ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }

        $db = \Config\Database::connect();
        $db->transStart();

        // Find code
        $codeRow = $db->table('codes_wallet')->where('code', $code)->get()->getRowArray();
        if (! $codeRow) {
            $db->transComplete();
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Code invalide.',
                'data' => null,
                'errors' => ['code' => 'not_found']
            ])->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
        }

        if ((int)$codeRow['est_utilise'] === 1) {
            $db->transComplete();
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Code déjà utilisé.',
                'data' => null,
                'errors' => ['code' => 'already_used']
            ])->setStatusCode(ResponseInterface::HTTP_CONFLICT);
        }

        $montant = $codeRow['montant'];

        // Ensure user_wallet exists
        $walletTable = $db->table('user_wallet');
        $wallet = $walletTable->where('id_user', $userId)->get()->getRowArray();
        if ($wallet) {
            $newSolde = bcadd($wallet['solde'], $montant, 2);
            $walletTable->where('id', $wallet['id'])->update(['solde' => $newSolde, 'updated_at' => date('Y-m-d H:i:s')]);
        } else {
            $newSolde = number_format($montant, 2, '.', '');
            $walletTable->insert(['id_user' => $userId, 'solde' => $newSolde, 'created_at' => date('Y-m-d H:i:s')]);
        }

        // Mark code used
        $db->table('codes_wallet')->where('id', $codeRow['id'])->update([
            'est_utilise' => 1,
            'id_utilisateur' => $userId,
            'date_utilisation' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erreur lors de la transaction.',
                'data' => null,
                'errors' => ['db' => 'transaction_failed']
            ])->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Recharge effectuée',
            'data' => ['solde' => $newSolde, 'montant' => $montant, 'code' => $code],
            'errors' => null
        ]);
    }

    /**
     * Purchase a regime using wallet balance
     */
    public function purchase()
    {
        $session = session();
        $userId = $session->get('user_id') ?? $this->request->getVar('user_id');
        $regimeId = $this->request->getJSON(true)['regime_id'] ?? $this->request->getPost('regime_id');

        if (empty($userId) || empty($regimeId)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Paramètres manquants.',
                'data' => null,
                'errors' => [
                    'user_id' => empty($userId) ? 'missing' : null,
                    'regime_id' => empty($regimeId) ? 'missing' : null
                ]
            ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }

        $db = \Config\Database::connect();
        $db->transStart();

        // Get regime details
        $regime = $db->table('regimes')->where('id', $regimeId)->where('actif', 1)->get()->getRowArray();
        if (!$regime) {
            $db->transComplete();
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Régime non trouvé ou inactif.',
                'data' => null,
                'errors' => ['regime_id' => 'not_found']
            ])->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
        }

        // Check if user already has this regime active
        $existingSubscription = $db->table('user_regimes')
            ->where('id_user', $userId)
            ->where('id_regime', $regimeId)
            ->where('est_actif', 1)
            ->get()
            ->getRowArray();

        if ($existingSubscription) {
            $db->transComplete();
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Vous avez déjà un abonnement actif à ce régime.',
                'data' => null,
                'errors' => ['regime_id' => 'already_subscribed']
            ])->setStatusCode(ResponseInterface::HTTP_CONFLICT);
        }

        // Get user wallet
        $wallet = $db->table('user_wallet')->where('id_user', $userId)->get()->getRowArray();
        if (!$wallet) {
            // Create wallet if it doesn't exist
            $db->table('user_wallet')->insert([
                'id_user' => $userId,
                'solde' => 0,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            $wallet = $db->table('user_wallet')->where('id_user', $userId)->get()->getRowArray();
        }

        $prix = $regime['prix'];
        $solde = $wallet['solde'];

        // Check if user has enough balance
        if (bccomp($solde, $prix, 2) < 0) {
            $db->transComplete();
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Solde insuffisant. Vous devez recharger votre portefeuille.',
                'data' => ['solde_actuel' => $solde, 'prix' => $prix, 'deficit' => bcsub($prix, $solde, 2)],
                'errors' => ['wallet' => 'insufficient_balance']
            ])->setStatusCode(ResponseInterface::HTTP_PAYMENT_REQUIRED);
        }

        // Deduct from wallet
        $newSolde = bcsub($solde, $prix, 2);
        $db->table('user_wallet')
            ->where('id', $wallet['id'])
            ->update([
                'solde' => $newSolde,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

        // Calculate subscription dates
        $dateDebut = date('Y-m-d');
        $dateFin = date('Y-m-d', strtotime("+{$regime['duree_jours']} days"));

        // Create subscription
        $db->table('user_regimes')->insert([
            'id_user' => $userId,
            'id_regime' => $regimeId,
            'date_debut' => $dateDebut,
            'date_fin' => $dateFin,
            'prix_paye' => $prix,
            'est_actif' => 1,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erreur lors de la transaction.',
                'data' => null,
                'errors' => ['db' => 'transaction_failed']
            ])->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Régime acheté avec succès!',
            'data' => [
                'regime_id' => $regimeId,
                'regime_nom' => $regime['nom'],
                'prix_paye' => $prix,
                'solde_restant' => $newSolde,
                'date_debut' => $dateDebut,
                'date_fin' => $dateFin,
                'duree_jours' => $regime['duree_jours']
            ],
            'errors' => null
        ])->setStatusCode(ResponseInterface::HTTP_CREATED);
    }

    /**
     * Get user's active subscriptions
     */
    public function subscriptions()
    {
        $session = session();
        $userId = $session->get('user_id') ?? $this->request->getVar('user_id');

        if (empty($userId)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Utilisateur non spécifié.',
                'data' => null,
                'errors' => ['user_id' => 'missing']
            ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }

        $db = \Config\Database::connect();
        $subscriptions = $db->table('user_regimes')
            ->select('user_regimes.*, regimes.nom, regimes.description, regimes.duree_jours, regimes.prix')
            ->join('regimes', 'user_regimes.id_regime = regimes.id')
            ->where('user_regimes.id_user', $userId)
            ->where('user_regimes.est_actif', 1)
            ->get()
            ->getResultArray();

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Abonnements récupérés',
            'data' => $subscriptions,
            'errors' => null
        ]);
    }

    /**
     * Purchase Gold status using wallet balance
     * Gold costs 50.00 and gives 15% discount on regimes
     */
    public function purchaseGold()
    {
        $session = session();
        $userId = $session->get('user_id') ?? $this->request->getVar('user_id');

        if (empty($userId)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Utilisateur non spécifié.',
                'data' => null,
                'errors' => ['user_id' => 'missing']
            ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }

        $db = \Config\Database::connect();
        $db->transStart();

        // Get user
        $user = $db->table('users')->where('id', $userId)->get()->getRowArray();
        if (!$user) {
            $db->transComplete();
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Utilisateur non trouvé.',
                'data' => null,
                'errors' => ['user_id' => 'not_found']
            ])->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
        }

        if ((int)$user['is_gold'] === 1) {
            $db->transComplete();
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Vous avez déjà l\'adhésion Gold.',
                'data' => null,
                'errors' => ['gold' => 'already_gold']
            ])->setStatusCode(ResponseInterface::HTTP_CONFLICT);
        }

        $goldPrice = 50.00;

        // Get user wallet
        $wallet = $db->table('user_wallet')->where('id_user', $userId)->get()->getRowArray();
        if (!$wallet) {
            // Create wallet if it doesn't exist
            $db->table('user_wallet')->insert([
                'id_user' => $userId,
                'solde' => 0,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            $wallet = $db->table('user_wallet')->where('id_user', $userId)->get()->getRowArray();
        }

        $solde = $wallet['solde'];

        // Check if user has enough balance
        if (bccomp($solde, $goldPrice, 2) < 0) {
            $db->transComplete();
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Solde insuffisant pour acheter Gold.',
                'data' => ['solde_actuel' => $solde, 'prix_gold' => $goldPrice, 'deficit' => bcsub($goldPrice, $solde, 2)],
                'errors' => ['wallet' => 'insufficient_balance']
            ])->setStatusCode(ResponseInterface::HTTP_PAYMENT_REQUIRED);
        }

        // Deduct from wallet
        $newSolde = bcsub($solde, $goldPrice, 2);
        $db->table('user_wallet')
            ->where('id', $wallet['id'])
            ->update([
                'solde' => $newSolde,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

        // Update user to Gold
        $db->table('users')
            ->where('id', $userId)
            ->update([
                'is_gold' => 1,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erreur lors de la transaction.',
                'data' => null,
                'errors' => ['db' => 'transaction_failed']
            ])->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Vous êtes maintenant Gold! 👑 Bénéficiez de 15% de remise sur tous les régimes.',
            'data' => [
                'is_gold' => true,
                'prix_paye' => $goldPrice,
                'solde_restant' => $newSolde,
                'remise_pourcentage' => 15
            ],
            'errors' => null
        ])->setStatusCode(ResponseInterface::HTTP_CREATED);
    }

    /**
     * Get user's Gold status
     */
    public function goldStatus()
    {
        $session = session();
        $userId = $session->get('user_id') ?? $this->request->getVar('user_id');

        if (empty($userId)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Utilisateur non spécifié.',
                'data' => null,
                'errors' => ['user_id' => 'missing']
            ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }

        $db = \Config\Database::connect();
        $user = $db->table('users')->where('id', $userId)->get()->getRowArray();

        if (!$user) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Utilisateur non trouvé.',
                'data' => null,
                'errors' => ['user_id' => 'not_found']
            ])->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
        }

        $isGold = (int)$user['is_gold'] === 1;

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Statut Gold récupéré',
            'data' => [
                'is_gold' => $isGold,
                'remise_pourcentage' => $isGold ? 15 : 0,
                'prix_gold' => 50.00
            ],
            'errors' => null
        ]);
    }
}
