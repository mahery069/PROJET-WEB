<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Etape 2</title>
</head>
<body>

<form>
    <fieldset>
        <legend>Etape 2 : Informations physiques</legend>

        <label for="taille">Taille (cm) :</label>
        <input type="number" id="taille" name="taille" min="100" max="250" required>

        <label for="poids">Poids (kg) :</label>
        <input type="number" id="poids" name="poids" min="30" max="200" step="0.1" required>

        <br><br>

        <button type="submit">
            Creer mon compte
        </button>
    </fieldset>
</form>

</body>
</html>