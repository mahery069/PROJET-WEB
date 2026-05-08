<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Créer Activité</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI'; background: #f5f5f5; }
        .navbar { background: #333; color: white; padding: 15px 0; }
        .navbar div { max-width: 800px; margin: 0 auto; display: flex; justify-content: space-between; padding: 0 20px; }
        .navbar a { color: white; text-decoration: none; }
        .container { max-width: 800px; margin: 20px auto; padding: 20px; background: white; border-radius: 10px; }
        h1 { color: #333; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: 600; }
        input, textarea, select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; }
        button { padding: 10px 20px; background: #667eea; color: white; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background: #764ba2; }
        a { color: #667eea; margin-left: 10px; }
    </style>
</head>
<body>
    <div class="navbar">
        <div>
            <h1 style="margin: 0; color: white;">NutriPlan</h1>
            <a href="/admin/activites">Retour</a>
        </div>
    </div>

    <div class="container">
        <h1>Ajouter Activité</h1>

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
                <label>Intensité *</label>
                <select name="intensite" required>
                    <option value="faible">Faible</option>
                    <option value="moyenne" selected>Moyenne</option>
                    <option value="elevee">Élevée</option>
                </select>
            </div>

            <div class="form-group">
                <label>Durée (minutes) *</label>
                <input type="number" name="duree_minutes" value="30" required>
            </div>

            <div class="form-group">
                <label><input type="checkbox" name="actif" value="1" checked> Actif</label>
            </div>

            <button type="submit">Créer</button>
            <a href="/admin/activites">Annuler</a>
        </form>
    </div>
</body>
</html>
