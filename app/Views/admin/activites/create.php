<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Creer Activite</title>
    <link rel="stylesheet" href="/assets/css/nutriplan.css">
</head>
<body>
    <?= view('partials/admin_header') ?>

    <div class="container">
        <h1>Ajouter Activite</h1>

        <form method="POST" action="/admin/activites">
            <div class="form-group">
                <label>Nom *</label>
                <input type="text" name="nom" required>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description"></textarea>
            </div>

            <div class="form-group">
                <label>Intensite *</label>
                <select name="intensite" required>
                    <option value="faible">Faible</option>
                    <option value="moyenne" selected>Moyenne</option>
                    <option value="elevee">Elevee</option>
                </select>
            </div>

            <div class="form-group">
                <label>Duree (minutes) *</label>
                <input type="number" name="duree_minutes" value="30" required>
            </div>

            <div class="form-group">
                <label><input type="checkbox" name="actif" value="1" checked> Actif</label>
            </div>

            <button type="submit">Creer</button>
            <a href="/admin/activites">Annuler</a>
        </form>
    </div>
</body>
</html>
