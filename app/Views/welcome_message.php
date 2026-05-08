<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Sélecteur de Régime Alimentaire</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            padding: 50px;
            max-width: 600px;
            text-align: center;
        }
        
        h1 {
            color: #333;
            font-size: 32px;
            margin-bottom: 20px;
        }
        
        p {
            color: #666;
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 30px;
        }
        
        .buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        a {
            display: inline-block;
            padding: 12px 25px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        a:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        .secondary {
            background: #f0f0f0;
            color: #333;
        }
        
        .secondary:hover {
            background: #e0e0e0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🍽️ Sélecteur de Régime Alimentaire</h1>
        <p>Bienvenue! Cette application vous aide à choisir le régime alimentaire qui correspond le mieux à vos besoins et objectifs santé.</p>
        
        <div class="buttons">
            <a href="/formulaire">📋 Formulaire</a>
            <a href="/auth/login" class="secondary">🔐 Se Connecter</a>
        </div>
    </div>
</body>
</html>
