<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<form method="POST" action="/auth/register-step1">
    <fieldset>
    <legend>Informations personnelles</legend>
<label for="nom">Nom:</label>
<input type="text" id="nom" name="nom">
<label for="prenom">Prénom:</label>
<input type="text" id="prenom" name="prenom">

<label for="genre">Genre:</label>
<select id="genre" name="genre">
    <option value="">Selectionner</option>
    <option value="homme">Homme</option>
    <option value="femme">Femme</option>
    <option value="autre">Autre</option>
</select>


<!--<label for="date">DATE:</label>
<input type="date" name="date" id="date">
 Date de naissance :
<input type="text" placeholder="JJ">
<input type="text" placeholder="MM">
<input type="text" placeholder="AAAA"><br><br> -->


</fieldset>

<fieldset>
    <legend>CONTACTS</legend>
<label for="email">Email:</label>
<input type="email" id="email" name="email">
<label for="telephone">Téléphone :</label>
<input type="number" name="telephone" id="telephone">

</fieldset>

<fieldset>
    <legend>securite</legend>
<label for="password">Mot de passe</label>
<input type="password" id="password" name="password">

</fieldset>
<button type="submit">Suivant</button>
</form>

</body>
</html>
