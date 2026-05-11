<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gerer Activites</title>
    <link rel="stylesheet" href="/assets/css/nutriplan.css">
</head>
<body>
    <?= view('partials/admin_header') ?>

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
                        <a href="/admin/activites/edit/<?= $a['id'] ?>" class="btn-sm">Modifier</a>
                        <a href="/admin/activites/delete/<?= $a['id'] ?>" class="btn-sm btn-sm-danger" onclick="return confirm('Confirmer?')">Supprimer</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
