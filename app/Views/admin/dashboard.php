<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f5f5; }
        .navbar { background: #333; color: white; padding: 15px 0; }
        .navbar div { max-width: 1200px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center; padding: 0 20px; }
        .navbar h1 { margin: 0; font-size: 24px; }
        .navbar a { color: white; text-decoration: none; margin: 0 15px; }
        .navbar a:hover { color: #667eea; }
        .logout { background: #dc3545; padding: 8px 12px; border-radius: 5px; }
        .container { max-width: 1200px; margin: 20px auto; padding: 20px; }
        h1 { color: #333; margin: 20px 0; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin: 30px 0; }
        .stat-card { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center; }
        .stat-value { font-size: 32px; font-weight: bold; color: #667eea; }
        .stat-label { color: #999; margin-top: 10px; }
        .charts-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 20px; }
        .chart-card { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .chart-card h3 { margin-bottom: 15px; color: #333; }
        canvas { max-height: 300px; }
        .actions { margin: 30px 0; padding: 20px; background: white; border-radius: 10px; }
        .btn { display: inline-block; padding: 10px 20px; background: #667eea; color: white; text-decoration: none; border-radius: 5px; margin-right: 10px; margin-bottom: 10px; }
        .btn:hover { background: #764ba2; }
    </style>
</head>
<body>
    <div class="navbar">
        <div>
            <h1>NutriPlan Admin</h1>
            <div>
                <a href="/admin/dashboard">Dashboard</a>
                <a href="/admin/regimes">Régimes</a>
                <a href="/admin/activites">Activités</a>
                <a href="/admin/codes">Codes Wallet</a>
                <a href="/admin/logout" class="logout">Déconnexion</a>
            </div>
        </div>
    </div>

    <div class="container">
        <h1>Bienvenue <?= esc($admin_nom ?? 'Admin') ?></h1>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value"><?= $stats['total_users'] ?></div>
                <div class="stat-label">Utilisateurs</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?= number_format($stats['total_wallet'], 2) ?>€</div>
                <div class="stat-label">Portefeuilles Totaux</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?= $stats['total_regimes'] ?></div>
                <div class="stat-label">Régimes</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?= $stats['total_activites'] ?></div>
                <div class="stat-label">Activités</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?= $stats['used_codes'] ?>/<?= $stats['total_codes'] ?></div>
                <div class="stat-label">Codes Utilisés</div>
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

        <div class="actions">
            <h2>Actions rapides</h2>
            <a href="/admin/regimes/create" class="btn">+ Ajouter Régime</a>
            <a href="/admin/activites/create" class="btn">+ Ajouter Activité</a>
            <a href="/admin/codes/create" class="btn">+ Générer Code</a>
            <a href="/admin/regimes" class="btn">Gérer Régimes</a>
            <a href="/admin/activites" class="btn">Gérer Activités</a>
            <a href="/admin/codes" class="btn">Gérer Codes</a>
        </div>
    </div>

    <script>
        // Codes Chart - Doughnut
        const codesCtx = document.getElementById('codesChart').getContext('2d');
        new Chart(codesCtx, {
            type: 'doughnut',
            data: {
                labels: ['Utilisés', 'Disponibles'],
                datasets: [{
                    data: [<?= $codesChart['used'] ?>, <?= $codesChart['unused'] ?>],
                    backgroundColor: ['#667eea', '#ddd'],
                    borderColor: white,
                    borderWidth: 2
                }]
            },
            options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
        });

        // Top Users Chart - Bar
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

        // Objective Chart - Pie
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

            <div class="dashboard-card">
                <h3>Regimes</h3>
                <p><strong><?= $stats['total_regimes'] ?? 0 ?></strong> regimes</p>
            </div>

            <div class="dashboard-card">
                <h3>Activites</h3>
                <p><strong><?= $stats['total_activites'] ?? 0 ?></strong> activites</p>
            </div>

            <div class="dashboard-card">
                <h3>Codes Wallet</h3>
                <p><strong><?= $stats['total_codes'] ?? 0 ?></strong> codes (<strong><?= $stats['used_codes'] ?? 0 ?></strong> utilises)</p>
            </div>
        </div>

        <div style="margin-top:30px; display:grid; grid-template-columns: 1fr 1fr; gap:20px;">
            <div class="dashboard-card" style="padding:16px;">
                <h3>Codes utilisés vs non utilisés</h3>
                <canvas id="codesChart" width="400" height="250"></canvas>
            </div>

            <div class="dashboard-card" style="padding:16px;">
                <h3>Top 5 soldes utilisateurs</h3>
                <canvas id="topUsersChart" width="400" height="250"></canvas>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Data passed from PHP
        const codesData = {
            labels: ['Utilisés', 'Non utilisés'],
            datasets: [{
                data: [<?= (int)($codesChart['used'] ?? 0) ?>, <?= (int)($codesChart['unused'] ?? 0) ?>],
                backgroundColor: ['#28a745', '#ffc107']
            }]
        };

        const topUsersLabels = <?= json_encode($topUsersChart['labels'] ?? []) ?>;
        const topUsersData = <?= json_encode($topUsersChart['data'] ?? []) ?>;

        // Codes doughnut
        new Chart(document.getElementById('codesChart').getContext('2d'), {
            type: 'doughnut',
            data: codesData,
            options: { responsive: true, maintainAspectRatio: false }
        });

        // Top users bar
        new Chart(document.getElementById('topUsersChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: topUsersLabels,
                datasets: [{ label: 'Solde (€)', data: topUsersData, backgroundColor: '#007bff' }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });
    </script>
</body>
</html>
