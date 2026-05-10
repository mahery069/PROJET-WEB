<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NutriPlan - Porte-monnaie</title>
    <link rel="stylesheet" href="/assets/css/nutriplan.css">
</head>
<body>
    <?= view('partials/header') ?>

    <?php
    $userId = (int) ($userId ?? session()->get('user_id') ?? 0);
    $balance = (float) ($walletBalance ?? 0);
    $isGold = ! empty($isGold);
    ?>

    <div class="wrap">
        <div class="hero">
            <h1>Porte-monnaie</h1>
            <p>Rechargez avec un code, consultez votre solde et accedez a l'adhesion Gold.</p>
            <div class="toolbar">
                <a class="btn" href="/dashboard">Retour dashboard</a>
                <a class="btn secondary" href="/regimes">Voir les regimes</a>
                <a class="btn gold" href="/wallet/gold">Option Gold</a>
            </div>
        </div>

        <div class="panel">
            <div class="stats">
                <div class="stat">
                    <div class="label">Solde actuel</div>
                    <div class="value" data-wallet-balance><?= number_format($balance, 2, ',', ' ') ?> EUR</div>
                </div>
                <div class="stat">
                    <div class="label">Statut</div>
                    <div class="value"><?= $isGold ? 'Gold' : 'Standard' ?></div>
                </div>
                <div class="stat">
                    <div class="label">Objectif</div>
                    <div class="value"><?= esc($objectiveLabel ?? 'IMC ideal') ?></div>
                </div>
            </div>

            <div class="grid">
                <div class="card">
                    <h3>Recharger avec un code</h3>
                    <div class="field">
                        <label for="wallet-code">Code de recharge</label>
                        <input id="wallet-code" type="text" placeholder="Ex: CODE-TEST-001">
                    </div>
                    <div class="field">
                        <label for="wallet-user-id">Votre identifiant</label>
                        <input id="wallet-user-id" type="number" value="<?= $userId ?>" readonly>
                    </div>
                    <button id="wallet-redeem-btn" class="primary-btn" type="button">Recharger maintenant</button>
                    <div id="wallet-message" class="message"></div>
                </div>

                <div class="card">
                    <h3>Abonnements actifs</h3>
                    <?php if (! empty($subscriptions)): ?>
                        <?php foreach ($subscriptions as $subscription): ?>
                            <div class="subscription">
                                <strong><?= esc($subscription['nom']) ?></strong>
                                <div class="meta">Du <?= esc($subscription['date_debut']) ?> au <?= esc($subscription['date_fin']) ?></div>
                                <div class="meta">Paye: <?= number_format((float) $subscription['prix_paye'], 2, ',', ' ') ?> EUR</div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="subscription">
                            <strong>Aucun regime actif</strong>
                            <div class="meta">Decouvrez les suggestions et achetez un regime depuis la page Regimes.</div>
                        </div>
                    <?php endif; ?>

                    <div class="gold-box">
                        <h4>Option Gold</h4>
                        <p>Activez Gold pour obtenir 15% de remise automatique sur tous les regimes.</p>
                        <?php if ($isGold): ?>
                            <strong>Vous etes deja Gold.</strong>
                        <?php else: ?>
                            <a class="btn gold" href="/wallet/gold">Acheter Gold</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="/assets/js/script.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            fetchWalletBalance(<?= $userId ?>);
        });
    </script>
</body>
</html>
