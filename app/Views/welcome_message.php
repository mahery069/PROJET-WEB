<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selecteur de regime alimentaire</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Selecteur de regime alimentaire</h1>
        
        <section id="wallet-widget" style="margin-top:20px;padding:12px;border:1px solid #ddd;border-radius:6px;max-width:420px;">
            <h2>Wallet</h2>
            <div style="margin-bottom:8px;">Solde: <strong id="wallet-balance">0.00 €</strong></div>
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
        // On charge initial balance for demo (reads user id input)
        document.addEventListener('DOMContentLoaded', function(){
            var uidEl = document.getElementById('wallet-user-id');
            if (uidEl) fetchWalletBalance(uidEl.value);
        });
    </script>
</body>
</html>
