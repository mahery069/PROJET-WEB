<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="/assets/css/nutriplan.css">
</head>
    <body>
    <?= view('partials/admin_header') ?>

    <div class="wrap">
        <div class="container">
            <h1>Bienvenue <?= esc($admin_nom ?? 'Admin') ?></h1>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value"><?= $stats['total_users'] ?></div>
                <div class="stat-label">Utilisateurs</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?= number_format($stats['total_wallet'], 2) ?> EUR</div>
                <div class="stat-label">Portefeuilles Totaux</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?= $stats['total_regimes'] ?></div>
                <div class="stat-label">Regimes</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?= $stats['total_activites'] ?></div>
                <div class="stat-label">Activites</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?= $stats['used_codes'] ?>/<?= $stats['total_codes'] ?></div>
                <div class="stat-label">Codes Utilises</div>
            </div>
        </div>

        <div class="charts-grid">
            <div class="chart-card">
                <h3>Distribution des Codes Wallet</h3>
                <canvas id="codesChart"></canvas>
            </div>
            <div class="chart-card">
                <h3>Top 5 Portefeuilles</h3>
                <canvas id="topUsersChart"></canvas>
            </div>
            <div class="chart-card">
                <h3>Objectifs Utilisateurs</h3>
                <canvas id="objectiveChart"></canvas>
            </div>
        </div>

    </div>

    <script>
        const codesCtx = document.getElementById('codesChart').getContext('2d');
        new Chart(codesCtx, {
            type: 'doughnut',
            data: {
                labels: ['Utilises', 'Disponibles'],
                datasets: [{
                    data: [<?= $codesChart['used'] ?>, <?= $codesChart['unused'] ?>],
                    backgroundColor: ['#667eea', '#ddd'],
                    borderColor: white,
                    borderWidth: 2
                }]
            },
            options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
        });

        const topCtx = document.getElementById('topUsersChart').getContext('2d');
        new Chart(topCtx, {
            type: 'bar',
            data: {
                labels: <?= json_encode($topUsersChart['labels']) ?>,
                datasets: [{
                    label: 'Solde (€)',
                    data: <?= json_encode($topUsersChart['data']) ?>,
                    backgroundColor: '#667eea',
                    borderRadius: 5
                }]
            },
            options: { responsive: true, scales: { y: { beginAtZero: true } } }
        });

        const objCtx = document.getElementById('objectiveChart').getContext('2d');
        new Chart(objCtx, {
            type: 'pie',
            data: {
                labels: <?= json_encode($objectiveChart['labels'] ?? []) ?>,
                datasets: [{
                    data: <?= json_encode($objectiveChart['data'] ?? []) ?>,
                    backgroundColor: ['#667eea', '#764ba2', '#f093fb'],
                    borderWidth: 2
                }]
            },
            options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
        });
    </script>
</body>
</html>
