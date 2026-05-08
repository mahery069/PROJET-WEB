// Star rating
function setStars(n) {
  document.querySelectorAll('#stars span').forEach((s, i) => {
    s.classList.toggle('on', i < n);
  });
}

// Range label sync (called inline via oninput, but also wired here for flexibility)
document.addEventListener('DOMContentLoaded', () => {
  const range = document.getElementById('priority-range');
  const label = document.getElementById('range-label');
  if (range && label) {
    range.addEventListener('input', () => { label.textContent = range.value; });
  }
});

// Wallet: fetch and display balance
function fetchWalletBalance(userId) {
  if (!userId) return;
  fetch('/wallet/balance?user_id=' + encodeURIComponent(userId))
    .then(r => r.json())
    .then(json => {
      if (json && json.success && json.data) {
        const el = document.getElementById('wallet-balance');
        if (el) el.textContent = parseFloat(json.data.solde).toFixed(2) + ' €';
      }
    }).catch(console.error);
}

// Wallet: redeem code via AJAX
function redeemCode(userId, code) {
  if (!userId || !code) return Promise.reject('missing');
  return fetch('/wallet/redeem', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
    body: JSON.stringify({ user_id: userId, code: code })
  }).then(r => r.json()).then(json => {
    if (json.success) {
      // update displayed balance if present
      const el = document.getElementById('wallet-balance');
      if (el && json.data && json.data.solde) el.textContent = parseFloat(json.data.solde).toFixed(2) + ' €';
    }
    return json;
  });
}
