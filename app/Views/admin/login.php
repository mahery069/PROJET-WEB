<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Connexion</title>
    <link rel="stylesheet" href="/assets/css/app-header.css">
    <link rel="stylesheet" href="/assets/css/nutriplan.css">
</head>
<body>
    <div class="login-container">
        <h2>Admin NutriPlan</h2>
        
        <?php if (session()->getFlashdata('error')): ?>
            <div class="error"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <form method="POST" action="/admin/login">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="admin@nutriplan.com" required>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" placeholder="••••••••" required>
            </div>
            <button type="submit">Se connecter</button>
        </form>

        <p class="hint">
            Admin demonstration: admin@nutriplan.com / admin123
        </p>
    </div>
</body>
</html>
