<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gerer Codes Wallet</title>
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
        <h1>Gerer Codes Wallet</h1>
        <a href="/admin/codes/create" class="btn">+ Generer Code</a>
        
        <?php if (session()->getFlashdata('success')): ?>
            <div style="background: #d4edda; color: #155724; padding: 12px; border-radius: 5px; margin: 20px 0;">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <table>
            <thead>
                <tr><th>Code</th><th>Montant (EUR)</th><th>Utilise</th><th>Utilisateur</th><th>Date Utilisation</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php foreach ($codes as $code): ?>
                <tr class="<?= $code['est_utilise'] ? 'used' : 'unused' ?>">
                    <td><strong><?= esc($code['code']) ?></strong></td>
                    <td><?= number_format($code['montant'], 2) ?></td>
                    <td><?= $code['est_utilise'] ? 'Oui' : 'Non' ?></td>
                    <td><?= $code['id_utilisateur'] ?? '-' ?></td>
                    <td><?= $code['date_utilisation'] ?? '-' ?></td>
                    <td>
                        <?php if (!$code['est_utilise']): ?>
                            <a href="/admin/codes/validate/<?= $code['id'] ?>" class="btn btn-success" style="padding: 5px 10px; font-size: 12px;">Valider</a>
                            <a href="/admin/codes/delete/<?= $code['id'] ?>" class="btn" style="padding: 5px 10px; font-size: 12px; background: #dc3545;" onclick="return confirm('Supprimer ce code?');">Supprimer</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
