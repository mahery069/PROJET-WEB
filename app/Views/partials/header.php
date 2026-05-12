<header class="app-header">
    <div class="app-header__brand">
        <a href="/">NutriPlan</a>
    </div>
    <nav class="app-header__nav">
        <a href="/dashboard">Accueil</a>
        <?php if (session()->get('user_id')): ?>
            <a href="/dashboard">Dashboard</a>
            <a href="/regimes">Regimes</a>
            <a href="/porte-monnaie">Porte-monnaie</a>
            <a href="/mon-profil">Profil</a>
            <a href="/objectifs">Objectifs</a>
            <a href="javascript:void(0);" onclick="confirmLogout('/auth/logout')">Logout</a>
        <?php else: ?>
            <a href="/formulaire">Inscription</a>
            <a href="/auth/login">Login</a>
        <?php endif; ?>
    </nav>

    <script>
        function confirmLogout(logoutUrl) {
            if (confirm('Souhaitez-vous vraiment vous déconnecter ?')) {
                window.location.href = logoutUrl;
            }
        }
    </script>
</header>
