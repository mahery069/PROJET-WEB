<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="/assets/css/nutriplan.css">
</head>
<body>
    <div class="login-container">
        <h2>Connexion</h2>

        <form method="POST" action="/auth/login">
            <div class="form-group">
                <label for="email">Email</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    placeholder="Votre email" 
                    required
                >
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <div class="password-field">
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="Votre mot de passe" 
                        required
                    >
                    <button type="button" class="password-toggle" onclick="togglePassword('password')">👁️</button>
                </div>
            </div>

            <div class="remember-me">
                <input type="checkbox" id="remember" name="remember" value="1">
                <label for="remember">Se souvenir de moi</label>
            </div>

            <button type="submit" class="btn-login">Se connecter</button>
        </form>

        <div class="login-footer">
            <p>Pas encore de compte? <a href="/auth/register">S'inscrire</a></p>
            <p><a href="/auth/forgot-password">Mot de passe oublie?</a></p>
        </div>
    </div>

    <script>
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
</body>
</html>
