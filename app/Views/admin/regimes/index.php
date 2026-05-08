<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer Régimes</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f5f5; }
        .navbar { background: #333; color: white; padding: 15px 0; }
        .navbar div { max-width: 1200px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center; padding: 0 20px; }
        .navbar a { color: white; text-decoration: none; margin: 0 15px; }
        .navbar a:hover { color: #667eea; }
        .container { max-width: 1200px; margin: 20px auto; padding: 20px; }
        h1 { color: #333; margin: 20px 0; }
        .actions { margin: 20px 0; }
        .btn { display: inline-block; padding: 10px 20px; background: #667eea; color: white; text-decoration: none; border-radius: 5px; margin-right: 10px; border: none; cursor: pointer; }
        .btn:hover { background: #764ba2; }
        .btn-danger { background: #dc3545; }
        table { width: 100%; border-collapse: collapse; background: white; margin: 20px 0; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f9f9f9; font-weight: 600; }
        tr:hover { background: #f9f9f9; }
        .success { background: #d4edda; color: #155724; padding: 12px; border-radius: 5px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="navbar">
        <div>
            <h1 style="margin: 0;">NutriPlan Admin</h1>
            <div>
                <a href="/admin/dashboard">Dashboard</a>
                <a href="/admin/regimes">Régimes</a>
                <a href="/admin/activites">Activités</a>
                <a href="/admin/codes">Codes</a>
                <a href="/admin/logout">Déconnexion</a>
            </div>
        </div>
    </div>

    <div class="container">
        <h1>Gérer Régimes</h1>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <div class="actions">
            <a href="/admin/regimes/create" class="btn">+ Ajouter Régime</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Durée (jours)</th>
                    <th>Prix (€)</th>
                    <th>Variation Poids (kg)</th>
                    <th>Viande %</th>
                    <th>Actif</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($regimes as $regime): ?>
                <tr>
                    <td><?= esc($regime['nom']) ?></td>
                    <td><?= $regime['duree_jours'] ?></td>
                    <td><?= number_format($regime['prix'], 2) ?></td>
                    <td><?= $regime['variation_poids'] ?></td>
                    <td><?= $regime['pourcentage_viande'] ?>%</td>
                    <td><?= $regime['actif'] ? 'Oui' : 'Non' ?></td>
                    <td>
                        <a href="/admin/regimes/edit/<?= $regime['id'] ?>" class="btn" style="padding: 5px 10px; font-size: 12px;">Modifier</a>
                        <a href="/admin/regimes/delete/<?= $regime['id'] ?>" class="btn btn-danger" style="padding: 5px 10px; font-size: 12px;" onclick="return confirm('Confirmer?')">Supprimer</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
