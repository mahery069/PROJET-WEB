<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - Modification</title>
    <link rel="stylesheet" href="/assets/css/nutriplan.css">

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
    <div class="wrap">
        <div class="login-container">
            <h2>Modifier mon profil</h2>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="message">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

            <form method="POST" action="/profil">
                <div class="grid">
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

                <div class="grid">
                    <div class="form-group">
                        <label for="genre">Genre</label>
                        <div class="gender-buttons">
                            <button type="button" class="gender-btn" data-gender="homme" <?= $genreValue === 'homme' ? 'data-preselected="1"' : '' ?>>
                                ♂ Homme
                            </button>
                            <button type="button" class="gender-btn" data-gender="femme" <?= $genreValue === 'femme' ? 'data-preselected="1"' : '' ?>>
                                ♀ Femme
                            </button>
                            <button type="button" class="gender-btn" data-gender="autre" <?= $genreValue === 'autre' ? 'data-preselected="1"' : '' ?>>
                                ⚧ Autre
                            </button>
                        </div>
                        <input type="hidden" id="genre" name="genre" value="<?= esc($genreValue) ?>">
                    </div>

                    <div class="form-group">
                        <label for="date_naissance">Date de naissance</label>
                        <input type="date" id="date_naissance" name="date_naissance" value="<?= esc($user['date_naissance'] ?? '') ?>">
                    </div>
                </div>

                <div class="grid">
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
                    <div class="gender-buttons">
                        <button type="button" class="gender-btn" data-gender="augmenter" <?= $objectifValue === 'augmenter' ? 'data-preselected="1"' : '' ?>>
                            📈 Augmenter
                        </button>
                        <button type="button" class="gender-btn" data-gender="reduire" <?= $objectifValue === 'reduire' ? 'data-preselected="1"' : '' ?>>
                            📉 Reduire
                        </button>
                        <button type="button" class="gender-btn" data-gender="imc_ideal" <?= $objectifValue === 'imc_ideal' ? 'data-preselected="1"' : '' ?>>
                            ⚖️ IMC ideal
                        </button>
                    </div>
                    <input type="hidden" id="objectif" name="objectif" value="<?= esc($objectifValue) ?>">
                </div>

                <div class="actions">
                    <button class="btn" type="submit">Enregistrer</button>
                    <button type="button" class="btn secondary" onclick="window.location.href='/export-pdf'" title="Exporter en PDF">Exporter PDF</button>
                    <button type="button" class="btn secondary" onclick="window.location.href='/dashboard'">Retour</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Handle genre button selection
        const genreBtns = document.querySelectorAll('.gender-buttons:nth-of-type(1) .gender-btn');
        const genreInput = document.getElementById('genre');

        genreBtns.forEach(btn => {
            if (btn.dataset.preselected === '1') {
                btn.classList.add('active');
            }
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                genreBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                genreInput.value = btn.dataset.gender;
            });
        });

        // Handle objectif button selection
        const objectifBtns = document.querySelectorAll('.gender-buttons:nth-of-type(2) .gender-btn');
        const objectifInput = document.getElementById('objectif');

        objectifBtns.forEach(btn => {
            if (btn.dataset.preselected === '1') {
                btn.classList.add('active');
            }
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                objectifBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                objectifInput.value = btn.dataset.gender;
            });
        });
    </script>
</body>
</html>
