<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gold - Adhésion Premium</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        .gold-container {
            max-width: 600px;
            margin: 40px auto;
            padding: 30px;
            background: linear-gradient(135deg, #ffd700 0%, #ffed4e 100%);
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        }

        .gold-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .gold-header h1 {
            font-size: 2.5em;
            color: #333;
            margin: 0 0 10px 0;
            text-shadow: 2px 2px 4px rgba(255, 255, 255, 0.5);
        }

        .gold-header .subtitle {
            font-size: 1.1em;
            color: #666;
        }

        .gold-benefits {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .gold-benefits h3 {
            color: #ffd700;
            margin-top: 0;
        }

        .benefit-item {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
            font-size: 0.95em;
        }

        .benefit-item::before {
            content: "✓";
            color: #ffd700;
            font-weight: bold;
            font-size: 1.3em;
            margin-right: 12px;
        }

        .gold-price {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }

        .gold-price .price {
            font-size: 2.5em;
            font-weight: bold;
            color: #ffd700;
            margin: 10px 0;
        }

        .gold-price .price-details {
            color: #666;
            font-size: 0.9em;
        }

        .wallet-info {
            background: white;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .wallet-balance {
            font-size: 1.1em;
        }

        .wallet-balance strong {
            color: #ffd700;
            font-size: 1.3em;
        }

        .btn-purchase {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1em;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
        }

        .btn-purchase:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 107, 53, 0.4);
        }

        .btn-purchase:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .btn-purchase.loading {
            opacity: 0.7;
        }

        .message {
            margin-top: 20px;
            padding: 12px;
            border-radius: 6px;
            display: none;
        }

        .message.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            display: block;
        }

        .message.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            display: block;
        }

        .message.info {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
            display: block;
        }

        .already-gold {
            text-align: center;
            padding: 30px;
            background: #d4edda;
            border-radius: 8px;
            color: #155724;
        }

        .already-gold h2 {
            margin-top: 0;
        }

        .back-link {
            text-align: center;
            margin-top: 20px;
        }

        .back-link a {
            color: #666;
            text-decoration: none;
            font-size: 0.95em;
        }

        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="gold-container">
            <div class="gold-header">
                <h1>👑 GOLD</h1>
                <p class="subtitle">Adhésion Premium</p>
            </div>

            <div id="gold-status-loading">
                <p style="text-align: center;">Vérification de votre statut...</p>
            </div>

            <div id="gold-content" style="display: none;">
                <!-- Si déjà Gold -->
                <div id="already-gold-section" style="display: none;">
                    <div class="already-gold">
                        <h2>✓ Vous êtes Gold!</h2>
                        <p>Vous bénéficiez déjà de 15% de remise sur tous les régimes.</p>
                    </div>
                </div>

                <!-- Si pas encore Gold -->
                <div id="not-gold-section" style="display: none;">
                    <div class="gold-benefits">
                        <h3>Avantages Gold</h3>
                        <div class="benefit-item">15% de remise sur tous les régimes</div>
                        <div class="benefit-item">Accès prioritaire aux nouveaux régimes</div>
                        <div class="benefit-item">Support prioritaire en cas de question</div>
                        <div class="benefit-item">Durée normale sans limite de temps</div>
                    </div>

                    <div class="wallet-info">
                        <div class="wallet-balance">
                            Solde wallet: <strong id="wallet-balance-display">0.00 €</strong>
                        </div>
                        <button onclick="refreshBalance()" style="padding: 6px 12px; background: #f0f0f0; border: 1px solid #ccc; border-radius: 4px; cursor: pointer;">↻</button>
                    </div>

                    <div class="gold-price">
                        <div>Prix unique</div>
                        <div class="price">50.00 €</div>
                        <div class="price-details">Accès illimité à 15% de remise</div>
                    </div>

                    <button id="btn-purchase-gold" class="btn-purchase" onclick="purchaseGold()">
                        Acheter Gold
                    </button>

                    <div id="gold-message" class="message"></div>
                </div>
            </div>

            <div class="back-link">
                <a href="/">← Retour à l'accueil</a>
            </div>
        </div>
    </div>

    <script src="/assets/js/script.js"></script>
    <script>
        // Get user ID from session or URL
        function getUserId() {
            const urlParams = new URLSearchParams(window.location.search);
            const userId = urlParams.get('user_id') || localStorage.getItem('user_id') || 1;
            return userId;
        }

        // Check Gold status on page load
        document.addEventListener('DOMContentLoaded', function() {
            const userId = getUserId();
            checkGoldStatus(userId);
            fetchWalletBalance(userId);
        });

        // Check if user already has Gold
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
                        // User is already Gold
                        document.getElementById('already-gold-section').style.display = 'block';
                        document.getElementById('not-gold-section').style.display = 'none';
                    } else {
                        // User is not Gold
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

        // Get wallet balance
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
                        parseFloat(data.data.solde).toFixed(2) + ' €';
                }
            })
            .catch(error => console.error('Error fetching balance:', error));
        }

        // Refresh balance
        function refreshBalance() {
            const userId = getUserId();
            fetchWalletBalance(userId);
        }

        // Purchase Gold
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
                    showMessage('success', '🎉 ' + data.message);
                    btn.textContent = 'Acheter Gold';
                    
                    // Refresh in 2 seconds
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

        // Show message
        function showMessage(type, message) {
            const msgEl = document.getElementById('gold-message');
            msgEl.className = 'message ' + type;
            msgEl.textContent = message;
        }
    </script>
</body>
</html>
