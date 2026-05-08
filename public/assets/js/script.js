// Script principal du projet S4
// Selecteur de regime alimentaire

document.addEventListener('DOMContentLoaded', function() {
    console.log('Application chargee');
    // Ajouter votre code JavaScript ici
});

// Wallet helper functions
function fetchWalletBalance(userId) {
    if (!userId) return;
    fetch('/wallet/balance?user_id=' + encodeURIComponent(userId))
        .then(function(res){ return res.json(); })
        .then(function(json){
            if (json && json.success && json.data) {
                var el = document.getElementById('wallet-balance');
                if (el) el.textContent = parseFloat(json.data.solde).toFixed(2) + ' €';
            }
        }).catch(function(e){ console.error(e); });
}

function redeemCode(userId, code) {
    if (!userId || !code) return Promise.reject('missing');
    return fetch('/wallet/redeem', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        body: JSON.stringify({ user_id: userId, code: code })
    }).then(function(res){ return res.json(); });
}

// UI wiring for wallet widget
document.addEventListener('DOMContentLoaded', function() {
    var btn = document.getElementById('wallet-redeem-btn');
    var codeInput = document.getElementById('wallet-code');
    var uidInput = document.getElementById('wallet-user-id');
    var msg = document.getElementById('wallet-message');

    if (btn && codeInput && uidInput) {
        btn.addEventListener('click', function(){
            var userId = uidInput.value;
            var code = codeInput.value.trim();
            if (!code) {
                if (msg) msg.textContent = 'Entrez un code.';
                return;
            }
            if (msg) msg.textContent = 'En cours...';
            redeemCode(userId, code).then(function(json){
                if (json && json.success) {
                    if (msg) msg.textContent = 'Recharge OK: +' + (json.data && json.data.montant ? json.data.montant + ' €' : '');
                    fetchWalletBalance(userId);
                    codeInput.value = '';
                } else {
                    if (msg) msg.textContent = (json && json.message) ? json.message : 'Erreur';
                }
            }).catch(function(e){
                if (msg) msg.textContent = 'Erreur réseau';
                console.error(e);
            });
        });
    }
});
