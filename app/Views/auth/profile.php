<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - Modification</title>
    <link rel="stylesheet" href="/assets/css/app-header.css">

</head>
<body>
    <?= view('partials/header') ?>
    <?php
    $user = $user ?? [];
    $health = $health ?? [];
    $nomValue = $user['nom'] ?? '';
    $prenomValue = '';
    if (trim($nomValue) !== '') {
        $parts = preg_split('/\s+/', trim($nomValue), 2);
        $nomValue = $parts[0] ?? $nomValue;
        $prenomValue = $parts[1] ?? '';
    }
    $genreValue = $user['genre'] ?? '';
    $objectifValue = $health['objectif'] ?? '';
    ?>
    <div class="container">
        <h2>Modifier mon profil</h2>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="message">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="/profil">
            <div class="row">
                <div class="form-group">
                    <label for="nom">Nom</label>
                    <input type="text" id="nom" name="nom" placeholder="Votre nom" value="<?= esc($nomValue) ?>">
                </div>
                <div class="form-group">
                    <label for="prenom">Prenom</label>
                    <input type="text" id="prenom" name="prenom" placeholder="Votre prenom" value="<?= esc($prenomValue) ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="exemple@mail.com" value="<?= esc($user['email'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="genre">Genre</label>
                <select id="genre" name="genre">
                    <option value="" <?= $genreValue === '' ? 'selected' : '' ?>>Selectionner</option>
                    <option value="homme" <?= $genreValue === 'homme' ? 'selected' : '' ?>>Homme</option>
                    <option value="femme" <?= $genreValue === 'femme' ? 'selected' : '' ?>>Femme</option>
                    <option value="autre" <?= $genreValue === 'autre' ? 'selected' : '' ?>>Autre</option>
                </select>
            </div>

            <div class="form-group">
                <label for="date_naissance">Date de naissance</label>
                <input type="date" id="date_naissance" name="date_naissance" value="<?= esc($user['date_naissance'] ?? '') ?>">
            </div>

            <div class="row">
                <div class="form-group">
                    <label for="taille">Taille (cm)</label>
                    <input type="number" id="taille" name="taille" placeholder="170" value="<?= esc($health['taille_cm'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="poids">Poids (kg)</label>
                    <input type="number" id="poids" name="poids" placeholder="70" value="<?= esc($health['poids_kg'] ?? '') ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="objectif">Objectif</label>
                <select id="objectif" name="objectif">
                    <option value="" <?= $objectifValue === '' ? 'selected' : '' ?>>Selectionner</option>
                    <option value="augmenter" <?= $objectifValue === 'augmenter' ? 'selected' : '' ?>>Augmenter son poids</option>
                    <option value="reduire" <?= $objectifValue === 'reduire' ? 'selected' : '' ?>>Reduire son poids</option>
                    <option value="imc_ideal" <?= $objectifValue === 'imc_ideal' ? 'selected' : '' ?>>Atteindre l'IMC ideal</option>
                </select>
            </div>

            <div class="button-group">
                <button type="submit">Enregistrer</button>
                <button type="button" class="secondary" onclick="window.location.href='/'">Retour</button>
            </div>
        </form>
    </div>
</body>
</html>
