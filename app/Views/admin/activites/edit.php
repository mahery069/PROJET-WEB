<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Activite</title>
    <link rel="stylesheet" href="/assets/css/nutriplan.css">
</head>
<body>
    <?= view('partials/admin_header') ?>

    <div class="container">
        <h1>Modifier Activite</h1>

        <form method="POST" action="/admin/activites/update/<?= $activite['id'] ?>">
            <div class="form-group">
                <label>Nom</label>
                <input type="text" name="nom" value="<?= esc($activite['nom']) ?>" required>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description"><?= esc($activite['description'] ?? '') ?></textarea>
            </div>

            <div class="form-group">
                <label>Intensite</label>
                <div class=\"gender-buttons\">
                    <button type=\"button\" class=\"gender-btn\" data-gender=\"faible\" <?= $activite['intensite'] == 'faible' ? 'data-preselected=\"1\"' : '' ?>>
                        Faible
                    </button>
                    <button type=\"button\" class=\"gender-btn\" data-gender=\"moyenne\" <?= $activite['intensite'] == 'moyenne' ? 'data-preselected=\"1\"' : '' ?>>
                        Moyenne
                    </button>
                    <button type=\"button\" class=\"gender-btn\" data-gender=\"elevee\" <?= $activite['intensite'] == 'elevee' ? 'data-preselected=\"1\"' : '' ?>>
                        Elevee
                    </button>
                </div>
                <input type=\"hidden\" id=\"intensite\" name=\"intensite\" value=\"<?= esc($activite['intensite']) ?>\" required>
            </div>

            <div class="form-group">
                <label>Duree (minutes)</label>
                <input type="number" name="duree_minutes" value="<?= $activite['duree_minutes'] ?>" required>
            </div>

            <div class="form-group">
                <label><input type="checkbox" name="actif" value="1" <?= $activite['actif'] ? 'checked' : '' ?>> Actif</label>
            </div>

            <button type="submit">Mettre a jour</button>
            <a href="/admin/activites">Annuler</a>
        </form>

        <script>
            const intensiteBtns = document.querySelectorAll('.gender-buttons .gender-btn');
            const intensiteInput = document.getElementById('intensite');

            intensiteBtns.forEach(btn => {
                if (btn.dataset.preselected === '1') {
                    btn.classList.add('active');
                }
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    intensiteBtns.forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');
                    intensiteInput.value = btn.dataset.gender;
                });
            });
        </script>
    </div>
</body>
</html>
