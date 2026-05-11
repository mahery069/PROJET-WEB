<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="/assets/css/nutriplan.css">
</head>
<body>
    <?= view('partials/admin_header') ?>

    <div class="container">
        <h1>Dashboard - Statistiques</h1>

        <div class="grid-4 mt-16">
            <div class="stat-card">
                <div class="muted">Utilisateurs</div>
                <div class="mini-stat"><?= $stats['total_users'] ?></div>
            </div>
            <div class="stat-card">
                <div class="muted">Solde total (wallet)</div>
                <div class="mini-stat"><?= number_format((float)$stats['total_wallet'],2,',',' ') ?> EUR</div>
            </div>
            <div class="stat-card">
                <div class="muted">Régimes</div>
                <div class="mini-stat"><?= $stats['total_regimes'] ?></div>
            </div>
            <div class="stat-card">
                <div class="muted">Activités</div>
                <div class="mini-stat"><?= $stats['total_activites'] ?></div>
            </div>
            <div class="stat-card">
                <div class="muted">Codes (total / utilisés)</div>
                <div class="mini-stat"><?= $stats['total_codes'] ?> / <?= $stats['used_codes'] ?></div>
            </div>
        </div>

        <div class="grid-3 mt-16">
            <div class="card">
                <h4>Codes: utilisés vs non-utilisés</h4>
                <canvas id="codesChart"></canvas>
            </div>

            <div class="card">
                <h4>Top utilisateurs (solde)</h4>
                <canvas id="topUsersChart"></canvas>
            </div>

            <div class="card">
                <h4>Objectifs utilisateurs</h4>
                <canvas id="objectiveChart"></canvas>
            </div>
        </div>

        <section class="mt-24">
            <h3>Tableau croisé: abonnements par régime</h3>
            <?php if (!empty($subscriptions_pivot)): ?>
                <table class="pivot">
                    <thead>
                        <tr>
                            <th>Régime</th>
                            <th>Actifs</th>
                            <th>Inactifs</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($subscriptions_pivot as $row): ?>
                            <tr>
                                <td><?= esc($row['regime']) ?></td>
                                <td><?= $row['active_count'] ?></td>
                                <td><?= $row['inactive_count'] ?></td>
                                <td><?= $row['total_count'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="muted">Aucune donnée d'abonnement disponible.</div>
            <?php endif; ?>
        </section>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const codesData = <?= json_encode($codesChart ?? ['used'=>0,'unused'=>0]) ?>;
        const topUsers = <?= json_encode($topUsersChart ?? ['labels'=>[], 'data'=>[]]) ?>;
        const objectives = <?= json_encode($objectiveChart ?? ['labels'=>[], 'data'=>[]]) ?>;

        // Codes doughnut
        new Chart(document.getElementById('codesChart'), {
            type: 'doughnut',
            data: {
                labels: ['Utilisés','Non utilisés'],
                datasets: [{ data: [codesData.used, codesData.unused], backgroundColor: ['#4caf50','#f44336'] }]
            },
            options: { responsive: true }
        });

        // Top users bar
        new Chart(document.getElementById('topUsersChart'), {
            type: 'bar',
            data: { labels: topUsers.labels, datasets: [{ label: 'Solde', data: topUsers.data, backgroundColor: '#2196f3' }] },
            options: { responsive: true, scales: { y: { beginAtZero: true } } }
        });

        // Objectives pie
        new Chart(document.getElementById('objectiveChart'), {
            type: 'pie',
            data: { labels: objectives.labels, datasets: [{ data: objectives.data, backgroundColor: ['#ff9800','#8bc34a','#03a9f4'] }] },
            options: { responsive: true }
        });
    </script>
</body>
</html>
