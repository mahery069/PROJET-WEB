<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Etape 2</title>
</head>
<body>

<form id="registerStep2" method="POST" action="/auth/register-step2" novalidate>
    <fieldset>
        <legend>Etape 2 : Informations physiques</legend>

        <label for="taille">Taille (cm) :</label>
        <input type="number" id="taille" name="taille" min="100" max="250" required>
        <div id="tailleError"></div>

        <label for="poids">Poids (kg) :</label>
        <input type="number" id="poids" name="poids" min="30" max="200" step="0.1" required>
        <div id="poidsError"></div>

        <label for="objectif">Objectif :</label>
        <select id="objectif" name="objectif" required>
            <option value="">Selectionner</option>
            <option value="augmenter">Augmenter son poids</option>
            <option value="reduire">Reduire son poids</option>
            <option value="imc_ideal">Atteindre l'IMC ideal</option>
        </select>

        <br><br>

        <button type="submit">
            Creer mon compte
        </button>
    </fieldset>
</form>

<script src="/assets/js/register-validation.js" defer></script>

</body>
</html>