<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;

class Wallet extends BaseController
{
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
}
