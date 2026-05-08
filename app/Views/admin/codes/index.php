<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gérer Codes Wallet</title>
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
        .btn-success { background: #28a745; }
        table { width: 100%; border-collapse: collapse; background: white; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f9f9f9; font-weight: 600; }
        .unused { background: #fff3cd; }
        .used { background: #d4edda; }
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
        <h1>Gérer Codes Wallet</h1>
        <a href="/admin/codes/create" class="btn">+ Générer Code</a>
        
        <?php if (session()->getFlashdata('success')): ?>
            <div style="background: #d4edda; color: #155724; padding: 12px; border-radius: 5px; margin: 20px 0;">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <table>
            <thead>
                <tr><th>Code</th><th>Montant (€)</th><th>Utilisé</th><th>Utilisateur</th><th>Date Utilisation</th><th>Actions</th></tr>
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
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
