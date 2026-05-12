<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($parametre) ? 'Modifier' : 'Ajouter' ?> Parametre</title>
    <link rel="stylesheet" href="/assets/css/nutriplan.css">
</head>
<body>
    <?= view('partials/admin_header') ?>

    <div class="container">
        <h1><?= isset($parametre) ? 'Modifier' : 'Ajouter' ?> Parametre</h1>

        <form method="POST" action="<?= isset($parametre) ? '/admin/parametres/update/'.$parametre['id'] : '/admin/parametres' ?>">
            <div class="form-group">
                <label>Cle</label>
                <input type="text" name="cle" value="<?= esc($parametre['cle'] ?? '') ?>" required />
            </div>
            <div class="form-group">
                <label>Valeur</label>
                <input type="text" name="valeur" value="<?= esc($parametre['valeur'] ?? '') ?>" required />
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description"><?= esc($parametre['description'] ?? '') ?></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn">Enregistrer</button>
                <a href="/admin/parametres" class="btn">Annuler</a>
            </div>
        </form>
    </div>
</body>
</html>
