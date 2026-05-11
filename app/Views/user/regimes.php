<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NutriPlan - Regimes</title>
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
            <h1>Regimes suggeres</h1>
            <p>Choisissez un regime adapte a votre objectif. Si vous etes Gold, la remise de 15% est appliquee automatiquement.</p>
            <div class="toolbar">
                <a class="btn" href="/dashboard">Retour dashboard</a>
                <a class="btn secondary" href="/porte-monnaie">Ouvrir le wallet</a>
                <a class="btn gold" href="/wallet/gold">Option Gold</a>
            </div>
        </div>

        <div class="panel">
            <div class="stats">
                <div class="stat">
                    <div class="label">Objectif</div>
                    <div class="value"><?= esc($objectiveLabel ?? 'IMC ideal') ?></div>
                </div>
                <div class="stat">
                    <div class="label">Solde wallet</div>
                    <div class="value" data-wallet-balance><?= number_format($balance, 2, ',', ' ') ?> EUR</div>
                </div>
                <div class="stat">
                    <div class="label">Statut</div>
                    <div class="value"><?= $isGold ? 'Gold' : 'Standard' ?></div>
                </div>
            </div>

            <div class="grid">
                <?php if (! empty($suggestedRegimes)): ?>
                    <?php foreach ($suggestedRegimes as $regime): ?>
                        <div class="card">
                            <div class="pill"><?= esc($regime['duree_jours']) ?> jours</div>
                            <h3><?= esc($regime['nom']) ?></h3>
                            <div class="desc"><?= esc($regime['description'] ?? 'Regime disponible') ?></div>
                            <div class="price-row">
                                <div>
                                    <div class="current"><?= number_format((float) $regime['prix_affiche'], 2, ',', ' ') ?> EUR</div>
                                    <?php if (! empty($isGold) && (float) $regime['prix_original'] > (float) $regime['prix_affiche']): ?>
                                        <div class="original"><?= number_format((float) $regime['prix_original'], 2, ',', ' ') ?> EUR</div>
                                    <?php endif; ?>
                                </div>
                                <div class="pill"><?= esc($regime['variation_poids']) ?> kg</div>
                            </div>
                            <button type="button" onclick="purchaseRegime(<?= $userId ?>, <?= (int) $regime['id'] ?>).then(function(json){
                                var msg = document.getElementById('regime-message-<?= (int) $regime['id'] ?>');
                                if (!msg) return;
                                msg.className = 'message ' + (json && json.success ? 'success' : 'error');
                                msg.textContent = json && json.message ? json.message : 'Reponse inconnue';
                                if (json && json.success) { fetchWalletBalance(<?= $userId ?>); }
                            }).catch(function(){ var msg = document.getElementById('regime-message-<?= (int) $regime['id'] ?>'); if (msg) { msg.className = 'message error'; msg.textContent = 'Erreur reseau'; } });">
                                Acheter ce regime
                            </button>
                            <div id="regime-message-<?= (int) $regime['id'] ?>" class="message"></div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty">Aucun regime suggere pour le moment.</div>
                <?php endif; ?>
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
