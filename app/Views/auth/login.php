<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
  
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
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    placeholder="Votre mot de passe" 
                    required
                >
            </div>

            <div class="remember-me">
                <input type="checkbox" id="remember" name="remember" value="1">
                <label for="remember">Se souvenir de moi</label>
            </div>

            <button type="submit" class="btn-login">Se connecter</button>
        </form>

        <div class="login-footer">
            <p>Pas encore de compte? <a href="/auth/register">S'inscrire</a></p>
            <p><a href="/auth/forgot-password">Mot de passe oublié?</a></p>
        </div>
    </div>
</body>
</html>
