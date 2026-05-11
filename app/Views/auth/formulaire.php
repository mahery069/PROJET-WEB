<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="/assets/css/nutriplan.css">
</head>
<body>

<?= view('partials/header') ?>

<div class="wrap">
    <div class="login-container">
        <h1>Inscription</h1>

        <form id="registerStep1" method="POST" action="/auth/register-step1" novalidate>

            <div class="form-group">
                <label for="nom">Nom</label>
                <input type="text" id="nom" name="nom">
                <div id="nomError"></div>
            </div>

            <div class="form-group">
                <label for="prenom">Prenom</label>
                <input type="text" id="prenom" name="prenom">
                <div id="prenomError"></div>
            </div>

            <div class="form-group">
                <label>Genre</label>

                <div class="gender-buttons">
                    <button type="button" class="gender-btn" data-gender="homme">
                        ♂ Homme
                    </button>

                    <button type="button" class="gender-btn" data-gender="femme">
                        ♀ Femme
                    </button>
                </div>

                <input type="hidden" id="genre" name="genre" required>

                <div id="genreError"></div>
            </div>

            <div class="form-group">
                <label for="date_naissance">Date de naissance</label>
                <input type="date" id="date_naissance" name="date_naissance">
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email">
                <div id="emailError"></div>
            </div>

            <div class="form-group">
                <label for="telephone">Telephone</label>
                <input type="number" name="telephone" id="telephone">
                <div id="telephoneError"></div>
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <div class="password-field">
                    <input type="password" id="password" name="password">
                    <button type="button" class="password-toggle" onclick="togglePassword('password')">👁️</button>
                </div>
                <div id="passwordError"></div>
            </div>

            <div class="actions">
                <button class="btn btn-login" type="submit">
                    Suivant
                </button>
            </div>

        </form>
    </div>
</div>

<script>
    const genderButtons = document.querySelectorAll('.gender-btn');
    const genderInput = document.getElementById('genre');

    genderButtons.forEach(button => {
        button.addEventListener('click', () => {

            genderButtons.forEach(btn => {
                btn.classList.remove('active');
            });

            button.classList.add('active');

            genderInput.value = button.dataset.gender;
        });
    });

    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        const btn = event.target;
        if (input.type === 'password') {
            input.type = 'text';
            btn.textContent = '🙈';
        } else {
            input.type = 'password';
            btn.textContent = '👁️';
        }
    }
</script>

<script src="/assets/js/register-validation.js" defer></script>

</body>
</html>
