<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; margin: 0; }
        .navbar { background: #333; color: white; padding: 15px 20px; display: flex; justify-content: space-between; align-items: center; }
        .navbar h1 { margin: 0; font-size: 24px; }
        .navbar a { color: white; text-decoration: none; margin-left: 20px; }
        .navbar a:hover { text-decoration: underline; }
        .container { max-width: 1200px; margin: 20px auto; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h2 { color: #333; }
        .success { background: #d4edda; color: #155724; padding: 12px; border-radius: 4px; margin-bottom: 20px; }
        .dashboard-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-top: 20px; }
        .dashboard-card { background: #f9f9f9; padding: 20px; border-radius: 8px; border-left: 4px solid #007bff; }
        .dashboard-card h3 { margin-top: 0; color: #007bff; }
        .dashboard-card a { display: inline-block; margin-top: 10px; padding: 8px 12px; background: #007bff; color: white; text-decoration: none; border-radius: 4px; }
        .dashboard-card a:hover { background: #0056b3; }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>Admin Dashboard</h1>
        <div>
            <span><?= $admin_nom ?></span>
            <a href="/auth/logout">Logout</a>
        </div>
    </div>
    
    <div class="container">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>
        
        <h2>Bienvenue <?= $admin_nom ?> !</h2>
        <p>Statistiques rapides et graphiques.</p>

        <div class="dashboard-grid">
            <div class="dashboard-card">
                <h3>Utilisateurs</h3>
                <p><strong><?= $stats['total_users'] ?? 0 ?></strong> utilisateurs</p>
            </div>

            <div class="dashboard-card">
                <h3>Solde total Wallet</h3>
                <p><strong><?= number_format($stats['total_wallet'] ?? 0, 2) ?> €</strong></p>
            </div>

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
