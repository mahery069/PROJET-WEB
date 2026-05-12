<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gerer Codes Wallet</title>
    <link rel="stylesheet" href="/assets/css/nutriplan.css">
</head>
<body>
    <?= view('partials/admin_header') ?>

    <div class="container">
        <h1>Gerer Codes Wallet</h1>
        <a href="/admin/codes/create" class="btn">+ Generer Code</a>
        
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success mt-20">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <table>
            <thead>
                <tr><th>Code</th><th>Montant (Ar)</th><th>Utilise</th><th>Utilisateur</th><th>Date Utilisation</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php foreach ($codes as $code): ?>
                <tr class="<?= $code['est_utilise'] ? 'used' : 'unused' ?>">
                    <td><strong><?= esc($code['code']) ?></strong></td>
                    <td><?= number_format($code['montant'], 0, ',', ' ') ?> Ar</td>
                    <td><?= $code['est_utilise'] ? 'Oui' : 'Non' ?></td>
                    <td><?= $code['id_utilisateur'] ?? '-' ?></td>
                    <td><?= $code['date_utilisation'] ?? '-' ?></td>
                    <td>
                        <?php if (!$code['est_utilise']): ?>
                            <a href="/admin/codes/validate/<?= $code['id'] ?>" class="btn-sm btn-success">Valider</a>
                            <a href="/admin/codes/delete/<?= $code['id'] ?>" class="btn-sm btn-danger" onclick="return confirm('Supprimer ce code?');">Supprimer</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
