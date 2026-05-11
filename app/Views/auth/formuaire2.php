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
               

            <div class="input-row">
                <div class="form-group">
                    <label for="taille">Taille (cm)</label>
                    <input type="number" id="taille" name="taille" min="100" max="250" placeholder="175" required>
                </div>
                <div class="form-group">
                    <label for="poids">Poids (kg)</label>
                    <input type="number" id="poids" name="poids" min="30" max="200" step="0.1" placeholder="70" required>
                </div>
            </div>

            <div class="imc-container">
                <div class="imc-label">Votre IMC calculé</div>
                <div class="imc-bar">
                    <div class="imc-progress" id="imcProgress"></div>
                </div>
                <div class="imc-values">
                    <span>18.5</span>
                    <span id="imcValue">--</span>
                    <span>40</span>
                </div>
                <div class="imc-category" id="imcCategory"></div>
            </div>

            <div class="form-group">
                <label for="objectif">Objectif</label>
                <div class="gender-buttons">
                    <button type="button" class="gender-btn" data-gender="augmenter">
                        📈 Augmenter
                    </button>
                    <button type="button" class="gender-btn" data-gender="reduire">
                        📉 Reduire
                    </button>
                    <button type="button" class="gender-btn" data-gender="imc_ideal">
                        ⚖️ IMC ideal
                    </button>
                </div>
                <input type="hidden" id="objectif" name="objectif" required>
            </div>

            <div class="actions">
                <button class="btn btn-login" type="submit">Creer mon compte</button>
            </div>
        </form>
    </div>
</div>

<script>
    const genderBtns = document.querySelectorAll('.gender-btn');
    const genreInput = document.getElementById('genre');
    const tailleInput = document.getElementById('taille');
    const poidsInput = document.getElementById('poids');
    const imcValue = document.getElementById('imcValue');
    const imcProgress = document.getElementById('imcProgress');
    const imcCategory = document.getElementById('imcCategory');
    const objectifInput = document.getElementById('objectif');
    const objectifBtns = document.querySelectorAll('.gender-buttons:last-of-type .gender-btn');

    // Handle objectif button selection
    objectifBtns.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            objectifBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            objectifInput.value = btn.dataset.gender;
        });
    });

    function calculateIMC() {
        const taille = parseFloat(tailleInput.value);
        const poids = parseFloat(poidsInput.value);

        if (!taille || !poids || taille < 100 || poids < 30) {
            imcValue.textContent = '--';
            imcProgress.style.width = '0%';
            imcCategory.style.display = 'none';
            return;
        }

        const imc = (poids / ((taille / 100) ** 2)).toFixed(1);
        imcValue.textContent = imc;

        const progressPercent = Math.min((imc / 40) * 100, 100);
        imcProgress.style.width = progressPercent + '%';

        imcCategory.style.display = 'block';
        imcCategory.className = 'imc-category';

        if (imc < 18.5) {
            imcCategory.classList.add('underweight');
            imcCategory.textContent = 'Poids insuffisant';
        } else if (imc < 25) {
            imcCategory.classList.add('normal');
            imcCategory.textContent = 'Poids normal';
        } else if (imc < 30) {
            imcCategory.classList.add('overweight');
            imcCategory.textContent = 'Surpoids';
        } else {
            imcCategory.classList.add('obese');
            imcCategory.textContent = 'Obésité';
        }
    }

    tailleInput.addEventListener('input', calculateIMC);
    poidsInput.addEventListener('input', calculateIMC);

    document.getElementById('registerStep2').addEventListener('submit', (e) => {
        if (!objectifInput.value) {
            e.preventDefault();
            alert('Veuillez choisir votre objectif');
        }
    });
</script>

</body>
</html>