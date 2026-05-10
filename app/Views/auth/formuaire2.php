<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Etape 2</title>
    <link rel="stylesheet" href="/assets/css/nutriplan.css">
</head>
<body>

<?= view('partials/header') ?>
<div class="wrap">
    <div class="login-container">
        <h1>Etape 2 — Informations physiques</h1>
        <form id="registerStep2" method="POST" action="/auth/register-step2" novalidate>
            <div class="form-group">
                <label for="taille">Taille (cm)</label>
                <input type="number" id="taille" name="taille" min="100" max="250" required>
                <div id="tailleError"></div>
            </div>

            <div class="form-group">
                <label for="poids">Poids (kg)</label>
                <input type="number" id="poids" name="poids" min="30" max="200" step="0.1" required>
                <div id="poidsError"></div>
            </div>

            <div class="form-group">
                <label for="objectif">Objectif</label>
                <select id="objectif" name="objectif" required>
                    <option value="">Selectionner</option>
                    <option value="augmenter">Augmenter son poids</option>
                    <option value="reduire">Reduire son poids</option>
                    <option value="imc_ideal">Atteindre l'IMC ideal</option>
                </select>
            </div>

            <div class="actions">
                <button class="btn btn-login" type="submit">Creer mon compte</button>
            </div>
        </form>
    </div>
</div>

<script src="/assets/js/register-validation.js" defer></script>

</body>
</html>