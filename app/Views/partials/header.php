<nav class="front-nav">
    <a class="logo" href="/">NutriBalance</a>
    <div class="nav-links">
        <a href="/">Accueil</a>
        <a href="/regimes">Regimes</a>
        <a href="/porte-monnaie">Porte-monnaie</a>
        <a href="/mon-profil">Profil</a>
        <a href="/objectifs">Objectifs</a>

        <?php if (session()->get('user_id')): ?>
            <span class="badge badge-green" style="margin-left:10px">Connecté</span>
            <a href="/auth/logout" class="btn-sm" style="margin-left:8px">Déconnexion</a>
        <?php else: ?>
            <a href="/formulaire" class="btn-outline">S'inscrire</a>
            <a href="/auth/login" class="btn-primary btn-sm" style="margin-left:8px">Se connecter</a>
        <?php endif; ?>
    </div>
</nav>
