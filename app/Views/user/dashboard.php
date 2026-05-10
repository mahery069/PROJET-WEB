<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NutriPlan - Dashboard Utilisateur</title>
    <link rel="stylesheet" href="/assets/css/user-dashboard.css">
</head>
<body>
    <!-- Header -->
    <header class="dashboard-header">
        <div class="header-content">
            <div class="header-title">Bonjour, <?= esc($userName ?? 'Utilisateur') ?> 🍎</div>
            <div class="header-subtitle">Voici votre tableau de bord NutriPlan</div>
        </div>
    </header>

    <!-- Action Bar -->
    <div class="action-bar">
        <div class="action-content">
            <a class="btn btn-gold" href="/wallet/gold">⭐ Option Gold</a>
            <a class="btn btn-export" href="/export-pdf">📥 Export PDF</a>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="nav-tabs">
        <div class="nav-item"><a class="active" href="/dashboard">👁️ Vue générale</a></div>
        <div class="nav-item"><a href="/regimes">🍽️ Régimes</a></div>
        <div class="nav-item"><a href="/porte-monnaie">💳 Porte-monnaie</a></div>
        <div class="nav-item"><a href="/mon-profil">👤 Profil</a></div>
    </div>

    <!-- Main Container -->
    <div class="dashboard-container">
        <!-- Top Stats -->
        <div class="stats-top">
            <!-- IMC Card -->
            <div class="stat-item">
                <div class="stat-label">📊 IMC actuel</div>
                <div class="stat-value"><?= esc($user['imc'] ?? '23.4') ?></div>
                <div class="stat-subtitle">Poids normal</div>
            </div>

            <!-- Weight Card -->
            <div class="stat-item accent-weight">
                <div class="stat-label">⚖️ Poids actuel</div>
                <div class="stat-value"><?= esc($user['poids'] ?? '72') ?> <span style="font-size: 20px;">kg</span></div>
                <div class="stat-subtitle">Taille : <?= esc($user['taille'] ?? '175') ?> cm</div>
            </div>

            <!-- Goal Card -->
            <div class="stat-item accent-goal">
                <div class="stat-label">🎯 Objectif</div>
                <div class="stat-value"><?= esc($user['poids_objectif'] ?? '65') ?> kg</div>
                <div class="stat-subtitle">Réduire le poids</div>
            </div>
        </div>

        <!-- Progress Section -->
        <div class="progress-section">
            <div class="progress-header">
                <h3>Progression vers l'objectif</h3>
                <div class="progress-percentage">30% accompli</div>
            </div>

            <div class="progress-info">
                <div class="info-item">
                    <div class="info-label">Départ</div>
                    <div class="info-value"><?= esc($user['poids'] ?? '72') ?> kg</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Régime actif</div>
                    <div class="info-value">Méditerranéen léger</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Temps restant</div>
                    <div class="info-value">18 jours</div>
                </div>
            </div>

            <div class="progress-bar">
                <div class="progress-fill" style="width: 30%;">30%</div>
            </div>

            <div class="progress-label">
                <span><?= esc($user['poids'] ?? '72') ?> kg (départ)</span>
                <span><?= esc($user['poids_objectif'] ?? '65') ?> kg (cible)</span>
            </div>
        </div>

        <!-- Activities Section -->
        <section class="activities-section">
            <h3>🏋️ Activité sportive recommandée</h3>
            <div class="activities-grid">
                <!-- Course à pied -->
                <div class="activity-card activity-1">
                    <div class="activity-icon">🏃</div>
                    <div class="activity-name">Course à pied</div>
                    <div class="activity-detail frequency">3× / semaine</div>
                    <div class="activity-detail">~30 min</div>
                </div>

                <!-- Natation -->
                <div class="activity-card activity-2">
                    <div class="activity-icon">🏊</div>
                    <div class="activity-name">Natation</div>
                    <div class="activity-detail frequency">2× / semaine</div>
                    <div class="activity-detail">~45 min</div>
                </div>

                <!-- Vélo -->
                <div class="activity-card activity-3">
                    <div class="activity-icon">🚴</div>
                    <div class="activity-name">Vélo</div>
                    <div class="activity-detail frequency">2× / semaine</div>
                    <div class="activity-detail">~40 min</div>
                </div>
            </div>
        </section>
    </div>
</body>
</html>
