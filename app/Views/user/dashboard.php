<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NutriPlan - Dashboard Utilisateur</title>
    <link rel="stylesheet" href="/assets/css/nutriplan.css">
</head>
<body>
    <?= view('partials/header') ?>

    <div class="wrap dashboard-page">
        <section class="dashboard-hero panel">
            <div class="dashboard-hero__copy">
                <p class="eyebrow">Dashboard NutriPlan</p>
                <h1>Bonjour, <?= esc($userName ?? 'Utilisateur') ?></h1>
                <p class="header-subtitle">Voici votre tableau de bord NutriPlan. Accédez rapidement à vos objectifs, vos régimes et votre porte-monnaie.</p>
                <?php if (! empty($isGold)): ?>
                    <div class="badge">Option Gold active - 15% sur les regimes</div>
                <?php endif; ?>
            </div>

            <div class="dashboard-hero__actions">
                <a class="btn btn-gold" href="/wallet/gold">Option Gold</a>
                <a class="btn secondary" href="/objectifs">Objectifs</a>
                <a class="btn secondary" href="/export-pdf">Export PDF</a>
            </div>
        </section>

        <div class="dashboard-layout two-column-layout">
            <aside class="sidebar">
                <div class="login-card">
                    <h3>NutriBalance</h3>
                    <p class="muted">Solde disponible</p>
                    <div class="balance"><?= number_format((float)($walletBalance ?? 0), 0, ',', ' ') ?> Ar</div>
                    <div class="mt-12">
                        <a class="btn" href="/porte-monnaie">Ouvrir le porte-monnaie</a>
                    </div>
                </div>

                <div class="wallet-card">
                    <div class="muted">Abonnements</div>
                    <div class="mini-stat">Active: <?= count($subscriptions ?? []) ?></div>
                    <div class="mt-10">
                        <a class="btn btn-gold" href="/wallet/gold">Voir Gold</a>
                    </div>
                </div>

                <div class="shortcuts-card">
                    <div class="muted">Raccourcis rapides</div>
                    <nav class="shortcuts-nav">
                        <a href="/regimes" class="shortcut-link">
                            <span class="shortcut-icon">RG</span>
                            <span>Mes Régimes</span>
                        </a>
                        <a href="/objectifs" class="shortcut-link">
                            <span class="shortcut-icon">OB</span>
                            <span>Mes Objectifs</span>
                        </a>
                        <a href="/porte-monnaie" class="shortcut-link">
                            <span class="shortcut-icon">WM</span>
                            <span>Porte-monnaie</span>
                        </a>
                        <a href="/profil" class="shortcut-link">
                            <span class="shortcut-icon">PR</span>
                            <span>Mon Profil</span>
                        </a>
                    </nav>
                </div>
            </aside>

            <main class="main-content">
                <!-- Stats Top -->
                <div class="stats-top">
                    <div class="stat-item">
                        <div class="stat-label">IMC actuel</div>
                        <div class="stat-value"><?= $imc !== null ? esc(number_format($imc, 1, ',', '')) : 'N/A' ?></div>
                        <div class="stat-subtitle">Poids normal</div>
                    </div>

                    <div class="stat-item accent-weight">
                        <div class="stat-label">Poids actuel</div>
                        <div class="stat-value"><?= esc($health['poids_kg'] ?? '0') ?> <span class="fs-20">kg</span></div>
                        <div class="stat-subtitle">Taille : <?= esc($health['taille_cm'] ?? '0') ?> cm</div>
                    </div>

                    <div class="stat-item accent-goal">
                        <div class="stat-label">Objectif</div>
                        <div class="stat-value"><?= esc($objectiveLabel ?? 'IMC ideal') ?></div>
                        <div class="stat-subtitle">Cible : 65 kg</div>
                    </div>
                </div>

                <!-- IMC Detail Section -->
                <div class="imc-detail-card card-shell">
                    <h3>Mon IMC en détail</h3>
                    <div class="imc-detail-container">
                        <div class="imc-circle">
                            <svg viewBox="0 0 200 200" class="imc-svg">
                                <circle cx="100" cy="100" r="90" fill="none" stroke="#e4ece5" stroke-width="8"/>
                                <circle cx="100" cy="100" r="90" fill="none" stroke="#e65100" stroke-width="8" 
                                    stroke-dasharray="<?= $imc ? (($imc - 18.5) / (40 - 18.5)) * (2 * 3.14159 * 90) : 0 ?>" 
                                    stroke-dashoffset="0" transform="rotate(-90 100 100)"
                                    stroke-linecap="round"/>
                            </svg>
                            <div class="imc-value"><?= $imc !== null ? esc(number_format($imc, 1, ',', '')) : '0' ?></div>
                        </div>

                        <div class="imc-info">
                            <div class="imc-info-title">Votre situation actuelle</div>
                            <div class="imc-range">
                                <div class="imc-range-bar">
                                    <span style="left: 0%">18.5</span>
                                    <span style="left: 25%">25</span>
                                    <span style="left: 60%">30</span>
                                </div>
                                <div class="imc-range-indicator" style="left: <?= $imc ? min(100, (($imc - 18.5) / (40 - 18.5)) * 100) : 0 ?>%"></div>
                            </div>
                            <div class="imc-status">IMC ideal : 18.5 – 24.9  Vous êtes dans la norme, proche du seuil superieur.</div>
                        </div>
                    </div>
                    <div class="imc-range-legend">
                        <span class="legend-item" style="color: #3b8bd4;">Poids insuffisant</span>
                        <span class="legend-item" style="color: var(--green);">Poids normal</span>
                        <span class="legend-item" style="color: #e65100;">Surpoids</span>
                        <span class="legend-item" style="color: var(--red);">Obésité</span>
                    </div>
                </div>

                <!-- Regime Actif -->
                <div class="overview-grid">
                    <section class="card-shell">
                        <h3>Regime actif</h3>
                        <?php if (! empty($activeRegime)): ?>
                            <div class="mini-item">
                                <strong><?= esc($activeRegime['nom']) ?></strong>
                                <div><?= esc($activeRegime['description'] ?? 'Regime en cours') ?></div>
                                    <div class="price">Paye: <?= number_format((float) $activeRegime['prix_paye'], 0, ',', ' ') ?> Ar</div>
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
                                                <?= number_format((float) $regime['prix_affiche'], 0, ',', ' ') ?> Ar
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
    </div>
</body>
</html>
