<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Régime</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f5f5; }
        .navbar { background: #333; color: white; padding: 15px 0; }
        .navbar div { max-width: 1200px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center; padding: 0 20px; }
        .navbar a { color: white; text-decoration: none; margin: 0 15px; }
        .container { max-width: 800px; margin: 20px auto; padding: 20px; background: white; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #333; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: 600; color: #555; }
        input, textarea, select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px; }
        input:focus, textarea:focus, select:focus { outline: none; border-color: #667eea; box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1); }
        textarea { resize: vertical; min-height: 100px; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        button { padding: 10px 20px; background: #667eea; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; }
        button:hover { background: #764ba2; }
        a { color: #667eea; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="navbar">
        <div>
            <h1 style="margin: 0; color: white;">NutriPlan Admin</h1>
            <a href="/admin/dashboard">Dashboard</a>
        </div>
    </div>

    <div class="container">
        <h1>Modifier Régime</h1>

        <form method="POST" action="/admin/regimes/update/<?= $regime['id'] ?>">
            <div class="form-group">
                <label for="nom">Nom du régime</label>
                <input type="text" id="nom" name="nom" value="<?= esc($regime['nom']) ?>" required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description"><?= esc($regime['description'] ?? '') ?></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="duree_jours">Durée (jours)</label>
                    <input type="number" id="duree_jours" name="duree_jours" value="<?= $regime['duree_jours'] ?>" required min="1">
                </div>
                <div class="form-group">
                    <label for="prix">Prix (€)</label>
                    <input type="number" id="prix" name="prix" value="<?= $regime['prix'] ?>" required min="0" step="0.01">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="variation_poids">Variation Poids (kg)</label>
                    <input type="number" id="variation_poids" name="variation_poids" value="<?= $regime['variation_poids'] ?>" required step="0.1">
                </div>
                <div class="form-group">
                    <label for="pourcentage_viande">Viande (%)</label>
                    <input type="number" id="pourcentage_viande" name="pourcentage_viande" value="<?= $regime['pourcentage_viande'] ?>" min="0" max="100" step="0.1">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="pourcentage_poisson">Poisson (%)</label>
                    <input type="number" id="pourcentage_poisson" name="pourcentage_poisson" value="<?= $regime['pourcentage_poisson'] ?>" min="0" max="100" step="0.1">
                </div>
                <div class="form-group">
                    <label for="pourcentage_volaille">Volaille (%)</label>
                    <input type="number" id="pourcentage_volaille" name="pourcentage_volaille" value="<?= $regime['pourcentage_volaille'] ?>" min="0" max="100" step="0.1">
                </div>
            </div>

            <div class="form-group">
                <label for="actif">Actif</label>
                <input type="checkbox" id="actif" name="actif" value="1" <?= $regime['actif'] ? 'checked' : '' ?> style="width: auto;">
            </div>

            <button type="submit">Mettre à jour</button>
            <a href="/admin/regimes">Retour</a>
        </form>
    </div>
</body>
</html>
