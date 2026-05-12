<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Creer Regime</title>
    <link rel="stylesheet" href="/assets/css/nutriplan.css">
</head>
<body>
    <?= view('partials/admin_header') ?>

    <div class="container">
        <h1>Ajouter un Regime</h1>

        <form method="POST" action="/admin/regimes">
            <div class="form-group">
                <label for="nom">Nom du regime *</label>
                <input type="text" id="nom" name="nom" required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description"></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="duree_jours">Duree (jours) *</label>
                    <input type="number" id="duree_jours" name="duree_jours" required min="1">
                </div>
                <div class="form-group">
                    <label for="prix">Prix (Ar) *</label>
                    <input type="number" id="prix" name="prix" required min="0" step="0.01">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="variation_poids">Variation Poids (kg) *</label>
                    <input type="number" id="variation_poids" name="variation_poids" required step="0.1">
                </div>
                <div class="form-group">
                    <label for="pourcentage_viande">Viande (%)</label>
                    <input type="number" id="pourcentage_viande" name="pourcentage_viande" value="0" min="0" max="100" step="0.1">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="pourcentage_poisson">Poisson (%)</label>
                    <input type="number" id="pourcentage_poisson" name="pourcentage_poisson" value="0" min="0" max="100" step="0.1">
                </div>
                <div class="form-group">
                    <label for="pourcentage_volaille">Volaille (%)</label>
                    <input type="number" id="pourcentage_volaille" name="pourcentage_volaille" value="0" min="0" max="100" step="0.1">
                </div>
            </div>

            <div class="form-group">
                <label for="actif">Actif</label>
                <input type="checkbox" id="actif" name="actif" value="1" checked class="w-auto">
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary">Creer Regime</button>
                <a href="/admin/regimes" class="btn-outline">Retour</a>
            </div>
        </form>
    </div>
</body>
</html>
