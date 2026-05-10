<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Generer Code</title>
    <link rel="stylesheet" href="/assets/css/nutriplan.css">
</head>
<body>
    <div class="navbar">
        <div>
            <h1 style="margin: 0; color: white;">NutriPlan</h1>
            <a href="/admin/codes">Retour</a>
        </div>
    </div>

    <div class="container">
        <h1>Generer Code Wallet</h1>

        <div class="info">
            Le code sera genere automatiquement. Entrez simplement le montant du code.
        </div>

        <form method="POST" action="/admin/codes">
            <div class="form-group">
                <label>Montant (€) *</label>
                <input type="number" name="montant" min="0" step="0.01" required placeholder="Ex: 10.00">
            </div>

            <button type="submit">Generer Code</button>
            <a href="/admin/codes">Annuler</a>
        </form>
    </div>
</body>
</html>
