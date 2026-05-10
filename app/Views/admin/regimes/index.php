<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerer Regimes</title>
    <link rel="stylesheet" href="/assets/css/nutriplan.css">
</head>
<body>
    <div class="navbar">
        <div>
            <h1 style="margin: 0;">NutriPlan Admin</h1>
            <div>
                <a href="/admin/dashboard">Dashboard</a>
                <a href="/admin/regimes">Regimes</a>
                <a href="/admin/activites">Activites</a>
                <a href="/admin/logout">Deconnexion</a>
            </div>
        </div>
    </div>

    <div class="container">
        <h1>Gerer Regimes</h1>
        <?php if (session()->getFlashdata('success')): ?>
            <div class="success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <div class="actions">
            <a href="/admin/regimes/create" class="btn">+ Ajouter Regime</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Duree (jours)</th>
                    <th>Prix (EUR)</th>
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
