<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RedirectResponse;

class Dashboard extends BaseController
{
    private function requireUser(): ?RedirectResponse
    {
        if (! session()->get('user_id')) {
            return redirect()->to('/auth/login');
        }

        return null;
    }

    private function fetchUserContext(): array
    {
        $userId = (int) session()->get('user_id');
        $db = db_connect();

        $user = $db->table('users')->where('id', $userId)->get()->getRowArray() ?? [];
        $health = $db->table('user_health')->where('id_user', $userId)->get()->getRowArray() ?? [];
        $wallet = $db->table('user_wallet')->where('id_user', $userId)->get()->getRowArray() ?? [];
        $subscriptions = $db->table('user_regimes')
            ->select('user_regimes.*, regimes.nom, regimes.description, regimes.prix, regimes.duree_jours')
            ->join('regimes', 'user_regimes.id_regime = regimes.id')
            ->where('user_regimes.id_user', $userId)
            ->where('user_regimes.est_actif', 1)
            ->orderBy('user_regimes.created_at', 'DESC')
            ->get()
            ->getResultArray();

        $taille = (float) ($health['taille_cm'] ?? 0);
        $poids = (float) ($health['poids_kg'] ?? 0);
        $imc = $taille > 0 ? round($poids / pow($taille / 100, 2), 1) : null;

        return [
            'userId' => $userId,
            'user' => $user,
            'health' => $health,
            'wallet' => $wallet,
            'walletBalance' => isset($wallet['solde']) ? (float) $wallet['solde'] : 0.0,
            'subscriptions' => $subscriptions,
            'activeRegime' => $subscriptions[0] ?? null,
            'isGold' => (int) ($user['is_gold'] ?? 0) === 1,
            'imc' => $imc,
        ];
    }

    private function fetchSuggestedRegimes(string $objectif, bool $isGold): array
    {
        $db = db_connect();
        $builder = $db->table('regimes')->where('actif', 1);

        if ($objectif === 'augmenter') {
            $builder->where('variation_poids >', 0);
        } elseif ($objectif === 'reduire') {
            $builder->where('variation_poids <', 0);
        } else {
            $builder->where('variation_poids >=', -0.2)->where('variation_poids <=', 0.2);
        }

        $regimes = $builder->orderBy('prix', 'ASC')->limit(6)->get()->getResultArray();

        if (empty($regimes)) {
            $regimes = $db->table('regimes')
                ->where('actif', 1)
                ->orderBy('prix', 'ASC')
                ->limit(6)
                ->get()
                ->getResultArray();
        }

        return array_map(static function (array $regime) use ($isGold): array {
            $prixOriginal = (float) $regime['prix'];
            $prixAffiche = $isGold ? round($prixOriginal * 0.85, 2) : $prixOriginal;

            $regime['prix_original'] = $prixOriginal;
            $regime['prix_affiche'] = $prixAffiche;
            $regime['remise'] = $isGold ? 15 : 0;

            return $regime;
        }, $regimes);
    }

    private function objectiveLabel(?string $objectif): string
    {
        return match ($objectif) {
            'augmenter' => 'Augmenter son poids',
            'reduire' => 'Réduire son poids',
            default => 'Atteindre l\'IMC idéal',
        };
    }

    /**
     * Display the user dashboard
     */
    public function index()
    {
        if ($redirect = $this->requireUser()) {
            return $redirect;
        }

        $context = $this->fetchUserContext();
        $objectif = $context['health']['objectif'] ?? 'imc_ideal';

        return view('user/dashboard', [
            ...$context,
            'userName' => $context['user']['nom'] ?? session()->get('user_name') ?? 'Utilisateur',
            'objectiveLabel' => $this->objectiveLabel($objectif),
            'suggestedRegimes' => $this->fetchSuggestedRegimes($objectif, $context['isGold']),
        ]);
    }

    /**
     * Display regimes page for user
     */
    public function regimes()
    {
        if ($redirect = $this->requireUser()) {
            return $redirect;
        }

        $context = $this->fetchUserContext();
        $objectif = $context['health']['objectif'] ?? 'imc_ideal';

        return view('user/regimes', [
            ...$context,
            'userName' => $context['user']['nom'] ?? session()->get('user_name') ?? 'Utilisateur',
            'objectiveLabel' => $this->objectiveLabel($objectif),
            'suggestedRegimes' => $this->fetchSuggestedRegimes($objectif, $context['isGold']),
        ]);
    }

    /**
     * Display wallet page for user
     */
    public function wallet()
    {
        if ($redirect = $this->requireUser()) {
            return $redirect;
        }

        $context = $this->fetchUserContext();

        return view('user/wallet', [
            ...$context,
            'userName' => $context['user']['nom'] ?? session()->get('user_name') ?? 'Utilisateur',
            'objectiveLabel' => $this->objectiveLabel($context['health']['objectif'] ?? 'imc_ideal'),
        ]);
    }

    /**
     * Display profile page for user
     */
    public function profile()
    {
        if ($redirect = $this->requireUser()) {
            return $redirect;
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
