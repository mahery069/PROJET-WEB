<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Etape 2</title>
    <link rel="stylesheet" href="/assets/css/nutriplan.css">
    <style>
        .gender-buttons {
            display: flex;
            gap: 12px;
            margin-bottom: 16px;
        }
        .gender-btn {
            flex: 1;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 6px;
            background: #fff;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .gender-btn:hover {
            border-color: #4CAF50;
        }
        .gender-btn.active {
            background: #4CAF50;
            color: #fff;
            border-color: #4CAF50;
        }
        .input-row {
            display: flex;
            gap: 16px;
            margin-bottom: 16px;
        }
        .input-row .form-group {
            flex: 1;
            margin-bottom: 0;
        }
        .imc-container {
            margin: 20px 0;
            padding: 16px;
            background: #f5f5f5;
            border-radius: 6px;
        }
        .imc-label {
            font-size: 12px;
            color: #666;
            margin-bottom: 8px;
        }
        .imc-bar {
            width: 100%;
            height: 8px;
            background: #ddd;
            border-radius: 4px;
            overflow: hidden;
            margin-bottom: 8px;
        }
        .imc-progress {
            height: 100%;
            background: linear-gradient(90deg, #4CAF50, #8BC34A);
            width: 0%;
            transition: width 0.3s ease;
        }
        .imc-values {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            color: #999;
        }
        .imc-category {
            margin-top: 8px;
            padding: 8px;
            border-radius: 4px;
            font-size: 13px;
            font-weight: 500;
            text-align: center;
            display: none;
        }
        .imc-category.underweight {
            background: #E3F2FD;
            color: #1976D2;
        }
        .imc-category.normal {
            background: #E8F5E9;
            color: #388E3C;
        }
        .imc-category.overweight {
            background: #FFF3E0;
            color: #F57C00;
        }
        .imc-category.obese {
            background: #FFEBEE;
            color: #D32F2F;
        }
    </style>
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

<script>
    const genderBtns = document.querySelectorAll('.gender-btn');
    const genreInput = document.getElementById('genre');
    const tailleInput = document.getElementById('taille');
    const poidsInput = document.getElementById('poids');
    const imcValue = document.getElementById('imcValue');
    const imcProgress = document.getElementById('imcProgress');
    const imcCategory = document.getElementById('imcCategory');

    genderBtns.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            genderBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            genreInput.value = btn.dataset.gender;
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
        if (!genreInput.value) {
            e.preventDefault();
            alert('Veuillez choisir votre genre');
        }
    });
</script>

</body>
</html>