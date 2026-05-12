<header class="app-header admin-header">
    <div class="app-header__brand">
        <a href="/admin/dashboard">NutriPlan Admin</a>
    </div>
    <nav class="app-header__nav">
        <a href="/admin/dashboard">Dashboard</a>
        <a href="/admin/regimes">Regimes</a>
        <a href="/admin/activites">Activites</a>
        <a href="/admin/parametres">Paramètres</a>
        <a href="/admin/codes">Codes</a>
        <a href="javascript:void(0);" onclick="confirmLogout('/admin/logout')">Déconnexion</a>
    </nav>

    <script>
        function confirmLogout(logoutUrl) {
            if (confirm('Souhaitez-vous vraiment vous déconnecter ?')) {
                window.location.href = logoutUrl;
            }
        }
    </script>
</header>
