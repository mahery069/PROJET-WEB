<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Générer Code</title>
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
        input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; }
        button { padding: 10px 20px; background: #667eea; color: white; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background: #764ba2; }
        a { color: #667eea; margin-left: 10px; }
        .info { background: #e7f3ff; color: #004085; padding: 12px; border-radius: 5px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="navbar">
        <div>
            <h1 style="margin: 0; color: white;">NutriPlan</h1>
            <a href="/admin/codes">Retour</a>
        </div>
    </div>

    <div class="container">
        <h1>Générer Code Wallet</h1>

        <div class="info">
            Le code sera généré automatiquement. Entrez simplement le montant du code.
        </div>

        <form method="POST" action="/admin/codes">
            <div class="form-group">
                <label>Montant (€) *</label>
                <input type="number" name="montant" min="0" step="0.01" required placeholder="Ex: 10.00">
            </div>

            <button type="submit">Générer Code</button>
            <a href="/admin/codes">Annuler</a>
        </form>
    </div>
</body>
</html>
