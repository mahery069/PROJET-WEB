<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Objectifs et Suggestions</title>
    <link rel="stylesheet" href="/assets/css/nutriplan.css">
</head>
<body>
    <?= view('partials/header') ?>
    <div class="wrap">
        <div class="header">
            <h1>Objectifs et suggestions</h1>
            <p>Conformement au sujet: choisir un objectif (augmenter, reduire, ou atteindre l'IMC ideal) pour recevoir des suggestions de regimes et d'activites.</p>
        </div>

        <div class="panel">
            <div class="card">
                <h2>Choisir votre objectif</h2>
                <label for="objectif">Objectif</label>
                <select id="objectif" name="objectif">
                    <option value="">Selectionner un objectif</option>
                    <option value="augmenter">Augmenter son poids</option>
                    <option value="reduire">Reduire son poids</option>
                    <option value="imc_ideal">Atteindre l'IMC ideal</option>
                </select>

                <label for="duree">Duree (semaines)</label>
                <input id="duree" type="number" min="2" max="24" placeholder="Ex: 8">

                <div class="actions">
                    <button type="button" onclick="updateSuggestions()">Voir les suggestions</button>
                </div>
                <div class="hint">Suggestions chargees depuis la base (regimes + activites).</div>
            </div>

            <div class="card">
                <h2>Suggestions</h2>
                <div id="suggestions" class="suggestions">
                    <div class="empty">Choisissez un objectif pour voir des suggestions.</div>
                </div>
            </div>
        </div>
    </div>

    <script>
        async function updateSuggestions() {
            const target = document.getElementById("suggestions");
            const objectif = document.getElementById("objectif").value;
            const duree = document.getElementById("duree").value;

            if (!objectif) {
                target.innerHTML = '<div class="empty">Choisissez un objectif pour voir des suggestions.</div>';
                return;
            }

            target.innerHTML = '<div class="empty">Chargement...</div>';

            try {
                const url = `/objectifs/data?objectif=${encodeURIComponent(objectif)}`;
                const response = await fetch(url);
                const payload = await response.json();
                const items = payload.data || [];

                if (!items.length) {
                    target.innerHTML = '<div class="empty">Aucune suggestion trouvee.</div>';
                    return;
                }

                const duration = duree ? `${duree} semaines` : "duree non precisee";

                target.innerHTML = items.map((item) => {
                    return `
                        <div class="suggestion">
                            <div><strong>${item.regime}</strong> <span class="tag">${item.impact}</span></div>
                            <div class="activity">Activite: ${item.activite}</div>
                            <div class="hint">Duree: ${duration}</div>
                        </div>
                    `;
                }).join('');
            } catch (e) {
                target.innerHTML = '<div class="empty">Erreur de chargement.</div>';
            }
        }
    </script>
</body>
</html>
