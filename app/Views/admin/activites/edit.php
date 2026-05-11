<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Activite</title>
    <link rel="stylesheet" href="/assets/css/nutriplan.css">
</head>
<body>
    <?= view('partials/admin_header') ?>

    <div class="container">
        <h1>Modifier Activite</h1>

        <form method="POST" action="/admin/activites/update/<?= $activite['id'] ?>">
            <div class="form-group">
                <label>Nom</label>
                <input type="text" name="nom" value="<?= esc($activite['nom']) ?>" required>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description"><?= esc($activite['description'] ?? '') ?></textarea>
            </div>

            <div class="form-group">
                <label>Intensite</label>
                <select name="intensite" required>
                    <option value="faible" <?= $activite['intensite'] == 'faible' ? 'selected' : '' ?>>Faible</option>
                    <option value="moyenne" <?= $activite['intensite'] == 'moyenne' ? 'selected' : '' ?>>Moyenne</option>
                    <option value="elevee" <?= $activite['intensite'] == 'elevee' ? 'selected' : '' ?>>Elevee</option>
                </select>
            </div>

            <div class="form-group">
                <label>Duree (minutes)</label>
                <input type="number" name="duree_minutes" value="<?= $activite['duree_minutes'] ?>" required>
            </div>

            <div class="form-group">
                <label><input type="checkbox" name="actif" value="1" <?= $activite['actif'] ? 'checked' : '' ?>> Actif</label>
            </div>

            <button type="submit">Mettre a jour</button>
            <a href="/admin/activites">Annuler</a>
        </form>
    </div>
</body>
</html>
