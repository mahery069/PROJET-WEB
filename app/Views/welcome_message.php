<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NutriPlan - Accueil</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="/assets/css/nutriplan.css">
    <link rel="stylesheet" href="/assets/css/nutriplan-home.css">
</head>
<body>
<?= view('partials/header') ?>

<div class="page-wrapper">

    <div class="header">
        <div class="logo-pill">
            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10 10-4.5 10-10S17.5 2 12 2zm0 3c1.7 0 3 1.3 3 3s-1.3 3-3 3-3-1.3-3-3 1.3-3 3-3zm0 14.2c-2.5 0-4.7-1.3-6-3.2.03-2 4-3.1 6-3.1 2 0 5.97 1.1 6 3.1-1.3 1.9-3.5 3.2-6 3.2z" />
            </svg>
        
        </div>
        <h1>Bienvenue sur<br><span>NutriPlan</span></h1>
        <p class="subtitle">Selectionnez votre espace pour acceder a l'application de gestion des regimes alimentaires personnalises.</p>
    </div>

    <div class="cards-grid">

        <a class="card card-user" href="/auth/login">
            <div class="card-icon">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="12" cy="8" r="4" fill="#2d6a4f" opacity="0.9" />
                    <path d="M4 20c0-4 3.6-7 8-7s8 3 8 7" stroke="#2d6a4f" stroke-width="2" stroke-linecap="round" fill="none" />
                </svg>
            </div>
            <div class="card-label">Espace</div>
            <div class="card-title">Utilisateur</div>
            <p class="card-desc">Gerez votre profil, decouvrez vos regimes personnalises et atteignez vos objectifs sante.</p>
            <div class="card-features">
                <div class="feature-item"><span class="feature-dot"></span>Calcul IMC & objectifs</div>
                <div class="feature-item"><span class="feature-dot"></span>Suggestions de regimes</div>
                <div class="feature-item"><span class="feature-dot"></span>Porte-monnaie et Option Gold</div>
                <div class="feature-item"><span class="feature-dot"></span>Export PDF</div>
            </div>
            <span class="cta-btn">Acceder <span class="cta-arrow">→</span></span>
        </a>

        <a class="card card-admin" href="/admin/login">
            <div class="card-icon">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="3" y="3" width="8" height="8" rx="2" fill="#e65100" opacity="0.9" />
                    <rect x="13" y="3" width="8" height="8" rx="2" fill="#e65100" opacity="0.55" />
                    <rect x="3" y="13" width="8" height="8" rx="2" fill="#e65100" opacity="0.55" />
                    <rect x="13" y="13" width="8" height="8" rx="2" fill="#e65100" opacity="0.9" />
                </svg>
            </div>
            <div class="card-label">Espace</div>
            <div class="card-title">Administrateur</div>
            <p class="card-desc">Gerez les regimes, activites, codes porte-monnaie et consultez les statistiques avec des graphiques.</p>
            <div class="card-features">
                <div class="feature-item"><span class="feature-dot"></span>Dashboard & graphiques</div>
                <div class="feature-item"><span class="feature-dot"></span>CRUD regimes & activites</div>
                <div class="feature-item"><span class="feature-dot"></span>Validation codes porte-monnaie</div>
                <div class="feature-item"><span class="feature-dot"></span>Gestion des parametres</div>
            </div>
            <span class="cta-btn">Acceder <span class="cta-arrow">→</span></span>
        </a>

    </div>

    

</div>

    <div class="container">
        <h1>Selecteur de regime alimentaire</h1>
        
        <section id="wallet-widget" style="margin-top:20px;padding:12px;border:1px solid #ddd;border-radius:6px;max-width:420px;">
            <h2>Wallet</h2>
            <div style="margin-bottom:8px;">Solde: <strong id="wallet-balance">0.00 EUR</strong></div>
            <div style="display:flex;gap:8px;margin-bottom:8px;">
                <input id="wallet-user-id" type="number" placeholder="user id" value="1" style="width:80px;padding:6px;" />
                <input id="wallet-code" type="text" placeholder="Entrez code" style="flex:1;padding:6px;" />
                <button id="wallet-redeem-btn" style="padding:6px 10px;">Recharger</button>
            </div>
            <div id="wallet-message" style="color:#333;font-size:0.95rem;"></div>
        </section>
    </div>
    <script src="/assets/js/script.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function(){
            var uidEl = document.getElementById('wallet-user-id');
            if (uidEl) fetchWalletBalance(uidEl.value);
        });
    </script>
</body>
</html>
