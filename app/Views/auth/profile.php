<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - Modification</title>

</head>
<body>
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
                    <input type="text" id="nom" name="nom" placeholder="Votre nom">
                </div>
                <div class="form-group">
                    <label for="prenom">Prenom</label>
                    <input type="text" id="prenom" name="prenom" placeholder="Votre prenom">
                </div>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="exemple@mail.com">
            </div>

            <div class="form-group">
                <label for="genre">Genre</label>
                <select id="genre" name="genre">
                    <option value="">Selectionner</option>
                    <option value="homme">Homme</option>
                    <option value="femme">Femme</option>
                    <option value="autre">Autre</option>
                </select>
            </div>

            <div class="row">
                <div class="form-group">
                    <label for="taille">Taille (cm)</label>
                    <input type="number" id="taille" name="taille" placeholder="170">
                </div>
                <div class="form-group">
                    <label for="poids">Poids (kg)</label>
                    <input type="number" id="poids" name="poids" placeholder="70">
                </div>
            </div>

            <div class="button-group">
                <button type="submit">Enregistrer</button>
                <button type="button" class="secondary" onclick="window.location.href='/'">Retour</button>
            </div>
        </form>
    </div>
</body>
</html>
