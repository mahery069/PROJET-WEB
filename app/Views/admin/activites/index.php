<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gérer Activités</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI'; background: #f5f5f5; }
        .navbar { background: #333; color: white; padding: 15px 0; }
        .navbar div { max-width: 1200px; margin: 0 auto; display: flex; justify-content: space-between; padding: 0 20px; }
        .navbar a { color: white; text-decoration: none; margin: 0 15px; }
        .container { max-width: 1200px; margin: 20px auto; padding: 20px; }
        h1 { color: #333; margin: 20px 0; }
        .btn { display: inline-block; padding: 10px 20px; background: #667eea; color: white; text-decoration: none; border-radius: 5px; margin-right: 10px; border: none; cursor: pointer; }
        .btn:hover { background: #764ba2; }
        .btn-danger { background: #dc3545; }
        table { width: 100%; border-collapse: collapse; background: white; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f9f9f9; font-weight: 600; }
    </style>
</head>
<body>
    <div class="navbar">
        <div>
            <h1 style="margin: 0; color: white;">NutriPlan</h1>
            <div>
                <a href="/admin/regimes">Régimes</a>
                <a href="/admin/activites">Activités</a>
                <a href="/admin/codes">Codes</a>
                <a href="/admin/logout">Déconnexion</a>
            </div>
        </div>
    </div>

    <div class="container">
        <h1>Gérer Activités</h1>
        <a href="/admin/activites/create" class="btn">+ Ajouter</a>
        <table>
            <thead>
                <tr><th>Nom</th><th>Intensité</th><th>Durée (min)</th><th>Actif</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php foreach ($activites as $a): ?>
                <tr>
                    <td><?= esc($a['nom']) ?></td>
                    <td><?= ucfirst($a['intensite']) ?></td>
                    <td><?= $a['duree_minutes'] ?></td>
                    <td><?= $a['actif'] ? 'Oui' : 'Non' ?></td>
                    <td>
                        <a href="/admin/activites/edit/<?= $a['id'] ?>" class="btn" style="padding: 5px 10px; font-size: 12px;">Modifier</a>
                        <a href="/admin/activites/delete/<?= $a['id'] ?>" class="btn btn-danger" style="padding: 5px 10px; font-size: 12px;" onclick="return confirm('Confirmer?')">Supprimer</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
