<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gold - Adhesion Premium</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/nutriplan.css">
</head>
<body>
    <div class="container">
        <div class="gold-container">
            <div class="gold-header">
                <h1>GOLD</h1>
                <p class="subtitle">Adhesion Premium</p>
            </div>

            <div id="gold-status-loading">
                <p style="text-align: center;">Verification de votre statut...</p>
            </div>

            <div id="gold-content" style="display: none;">
                <div id="already-gold-section" style="display: none;">
                    <div class="already-gold">
                        <h2>Vous etes Gold!</h2>
                        <p>Vous beneficiez deja de 15% de remise sur tous les regimes.</p>
                    </div>
                </div>

                <div id="not-gold-section" style="display: none;">
                    <div class="gold-benefits">
                        <h3>Avantages Gold</h3>
                        <div class="benefit-item">15% de remise sur tous les regimes</div>
                        <div class="benefit-item">Acces prioritaire aux nouveaux regimes</div>
                        <div class="benefit-item">Support prioritaire en cas de question</div>
                        <div class="benefit-item">Duree normale sans limite de temps</div>
                    </div>

                    <div class="wallet-info">
                        <div class="wallet-balance">
                            Solde wallet: <strong id="wallet-balance-display">0.00 EUR</strong>
                        </div>
                        <button onclick="refreshBalance()" style="padding: 6px 12px; background: #f0f0f0; border: 1px solid #ccc; border-radius: 4px; cursor: pointer;">Refresh</button>
                    </div>

                    <div class="gold-price">
                        <div>Prix unique</div>
                        <div class="price">50.00 EUR</div>
                        <div class="price-details">Acces illimite a 15% de remise</div>
                    </div>

                    <button id="btn-purchase-gold" class="btn-purchase" onclick="purchaseGold()">
                        Acheter Gold
                    </button>

                    <div id="gold-message" class="message"></div>
                </div>
            </div>

            <div class="back-link">
                <a href="/">Retour a l'accueil</a>
            </div>
        </div>
    </div>

    <script src="/assets/js/script.js"></script>
    <script>
        function getUserId() {
            const urlParams = new URLSearchParams(window.location.search);
            const userId = urlParams.get('user_id') || localStorage.getItem('user_id') || 1;
            return userId;
        }

        document.addEventListener('DOMContentLoaded', function() {
            const userId = getUserId();
            checkGoldStatus(userId);
            fetchWalletBalance(userId);
        });

        function checkGoldStatus(userId) {
            fetch(`/wallet/gold/status?user_id=${userId}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('gold-status-loading').style.display = 'none';
                document.getElementById('gold-content').style.display = 'block';

                if (data.success) {
                    if (data.data.is_gold) {
                        document.getElementById('already-gold-section').style.display = 'block';
                        document.getElementById('not-gold-section').style.display = 'none';
                    } else {
                        document.getElementById('already-gold-section').style.display = 'none';
                        document.getElementById('not-gold-section').style.display = 'block';
                    }
                } else {
                    showMessage('error', 'Erreur: ' + data.message);
                }
            })
            .catch(error => {
                document.getElementById('gold-status-loading').style.display = 'none';
                document.getElementById('gold-content').style.display = 'block';
                showMessage('error', 'Erreur de connexion: ' + error);
            });
        }

        function fetchWalletBalance(userId) {
            fetch(`/wallet/balance?user_id=${userId}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('wallet-balance-display').textContent = 
                        parseFloat(data.data.solde).toFixed(2) + ' EUR';
                }
            })
            .catch(error => console.error('Error fetching balance:', error));
        }

        function refreshBalance() {
            const userId = getUserId();
            fetchWalletBalance(userId);
        }

        function purchaseGold() {
            const userId = getUserId();
            const btn = document.getElementById('btn-purchase-gold');
            
            btn.disabled = true;
            btn.classList.add('loading');
            btn.textContent = 'Traitement en cours...';

            fetch('/wallet/gold/purchase', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    user_id: userId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showMessage('success', data.message);
                    btn.textContent = 'Acheter Gold';
                    setTimeout(() => {
                        checkGoldStatus(userId);
                        fetchWalletBalance(userId);
                    }, 2000);
                } else {
                    showMessage('error', 'Erreur: ' + data.message);
                    btn.disabled = false;
                    btn.classList.remove('loading');
                    btn.textContent = 'Acheter Gold';
                }
            })
            .catch(error => {
                showMessage('error', 'Erreur de connexion: ' + error);
                btn.disabled = false;
                btn.classList.remove('loading');
                btn.textContent = 'Acheter Gold';
            });
        }

        function showMessage(type, message) {
            const msgEl = document.getElementById('gold-message');
            msgEl.className = 'message ' + type;
            msgEl.textContent = message;
        }
    </script>
</body>
</html>
