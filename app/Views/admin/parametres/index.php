<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paramètres</title>
    <link rel="stylesheet" href="/assets/css/nutriplan.css">
</head>
<body>
    <?= view('partials/admin_header') ?>

    <div class="container">
        <h1>Paramètres</h1>
        <?php if (session()->getFlashdata('success')): ?>
            <div class="success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <div class="actions">
            <a href="/admin/parametres/create" class="btn">+ Ajouter Paramètre</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Clé</th>
                    <th>Valeur</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($params as $p): ?>
                <tr>
                    <td><?= esc($p['cle']) ?></td>
                    <td><?= esc($p['valeur']) ?></td>
                    <td><?= esc($p['description']) ?></td>
                    <td>
                        <a href="/admin/parametres/edit/<?= $p['id'] ?>" class="btn">Modifier</a>
                        <a href="/admin/parametres/delete/<?= $p['id'] ?>" class="btn btn-danger" onclick="return confirm('Confirmer suppression ?')">Supprimer</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
