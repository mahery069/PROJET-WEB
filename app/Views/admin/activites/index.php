<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gerer Activites</title>
    <link rel="stylesheet" href="/assets/css/nutriplan.css">
</head>
<body>
    <div class="navbar">
        <div>
            <h1 style="margin: 0; color: white;">NutriPlan</h1>
            <div>
                <a href="/admin/regimes">Regimes</a>
                <a href="/admin/activites">Activites</a>
                <a href="/admin/codes">Codes</a>
                <a href="/admin/logout">Deconnexion</a>
            </div>
        </div>
    </div>

    <div class="container">
        <h1>Gerer Activites</h1>
        <a href="/admin/activites/create" class="btn">+ Ajouter</a>
        <table>
            <thead>
                <tr><th>Nom</th><th>Intensite</th><th>Duree (min)</th><th>Actif</th><th>Actions</th></tr>
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
