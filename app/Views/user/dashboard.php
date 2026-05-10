<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NutriPlan - Dashboard Utilisateur</title>
    <link rel="stylesheet" href="/assets/css/nutriplan.css">
</head>
<body>
    <header class="dashboard-header">
        <div class="header-content">
            <div class="header-title">Bonjour, <?= esc($userName ?? 'Utilisateur') ?></div>
            <div class="header-subtitle">Voici votre tableau de bord NutriPlan</div>
            <?php if (! empty($isGold)): ?>
                <div class="badge">Option Gold active - 15% sur les regimes</div>
            <?php endif; ?>
        </div>
    </header>

    <div class="action-bar">
        <div class="action-content">
            <a class="btn btn-gold" href="/wallet/gold">Option Gold</a>
            <a class="btn btn-export" href="/export-pdf">Export PDF</a>
            <a class="btn btn-export" href="/auth/logout">Deconnexion</a>
        </div>
    </div>

    <div class="nav-tabs">
        <div class="nav-item"><a class="active" href="/dashboard">Vue generale</a></div>
        <div class="nav-item"><a href="/regimes">Regimes</a></div>
        <div class="nav-item"><a href="/porte-monnaie">Porte-monnaie</a></div>
        <div class="nav-item"><a href="/mon-profil">Profil</a></div>
    </div>

    <div class="dashboard-container two-column-layout">
        <aside class="sidebar">
            <div class="login-card">
                <h3>NutriBalance</h3>
                <p class="muted">Solde disponible</p>
                <div class="balance"><?= number_format((float)($walletBalance ?? 0), 2, ',', ' ') ?> EUR</div>
                <div style="margin-top:12px">
                    <a class="btn" href="/porte-monnaie">Ouvrir le porte-monnaie</a>
                </div>
            </div>

            <div class="wallet-card">
                <div class="muted">Abonnements</div>
                <div class="mini-stat">Active: <?= count($subscriptions ?? []) ?></div>
                <div style="margin-top:10px">
                    <a class="btn btn-gold" href="/wallet/gold">Voir Gold</a>
                </div>
            </div>

            <div class="admin-card">
                <div class="muted">Raccourcis</div>
                <div class="mini-stat">Regimes, Activites, Codes</div>
            </div>
        </aside>

        <main class="main-content">
        <div class="stats-top">
            <div class="stat-item">
                <div class="stat-label">IMC actuel</div>
                <div class="stat-value"><?= $imc !== null ? esc(number_format($imc, 1, ',', '')) : 'N/A' ?></div>
                <div class="stat-subtitle"><?= esc($objectiveLabel ?? 'Objectif personnel') ?></div>
            </div>

            <div class="stat-item accent-weight">
                <div class="stat-label">Poids actuel</div>
                <div class="stat-value"><?= esc($health['poids_kg'] ?? '0') ?> <span style="font-size: 20px;">kg</span></div>
                <div class="stat-subtitle">Taille : <?= esc($health['taille_cm'] ?? '0') ?> cm</div>
            </div>

            <div class="stat-item accent-goal">
                <div class="stat-label">Objectif</div>
                <div class="stat-value"><?= esc($objectiveLabel ?? 'IMC ideal') ?></div>
                <div class="stat-subtitle">Portefeuille: <?= number_format((float)($walletBalance ?? 0), 2, ',', ' ') ?> EUR</div>
            </div>
        </div>

        <div class="overview-grid">
            <section class="card-shell">
                <h3>Regime actif</h3>
                <?php if (! empty($activeRegime)): ?>
                    <div class="mini-item">
                        <strong><?= esc($activeRegime['nom']) ?></strong>
                        <div><?= esc($activeRegime['description'] ?? 'Regime en cours') ?></div>
                        <div class="price">Paye: <?= number_format((float) $activeRegime['prix_paye'], 2, ',', ' ') ?> EUR</div>
                        <div>Du <?= esc($activeRegime['date_debut']) ?> au <?= esc($activeRegime['date_fin']) ?></div>
                    </div>
                <?php else: ?>
                    <div class="empty-state">Aucun regime actif pour le moment. Consultez la page Regimes.</div>
                <?php endif; ?>

                <div class="dashboard-actions">
                    <a class="link-button" href="/regimes">Voir les regimes</a>
                    <a class="link-button secondary" href="/porte-monnaie">Gerer le wallet</a>
                </div>
            </section>

            <section class="card-shell">
                <h3>Suggestions selon votre objectif</h3>
                <div class="mini-list">
                    <?php if (! empty($suggestedRegimes)): ?>
                        <?php foreach ($suggestedRegimes as $regime): ?>
                            <div class="mini-item">
                                <strong><?= esc($regime['nom']) ?></strong>
                                <div><?= esc($regime['description'] ?? '') ?></div>
                                <div class="price">
                                    <?= number_format((float) $regime['prix_affiche'], 2, ',', ' ') ?> EUR
                                    <?php if (! empty($isGold)): ?>
                                        <small>(-15%)</small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="empty-state">Aucune suggestion disponible.</div>
                    <?php endif; ?>
                </div>
            </section>
        </div>
        </main>
    </div>
</body>
</html>
