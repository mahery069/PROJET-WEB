<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Objectifs et Suggestions</title>
    <style>
        :root {
            --bg: #f4f6fb;
            --card: #ffffff;
            --primary: #3f6ae0;
            --accent: #f0b429;
            --text: #1f2937;
            --muted: #6b7280;
            --line: #e5e7eb;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background: radial-gradient(1200px 600px at 10% -10%, #e6ecff, #f4f6fb);
            color: var(--text);
            min-height: 100vh;
            padding: 24px;
        }

        .wrap {
            max-width: 980px;
            margin: 0 auto;
        }

        .header {
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: 14px;
            padding: 22px 24px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.06);
            margin-bottom: 18px;
        }

        .header h1 {
            font-size: 24px;
            margin-bottom: 6px;
        }

        .header p {
            color: var(--muted);
            font-size: 14px;
        }

        .panel {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .card {
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: 14px;
            padding: 18px;
        }

        .card h2 {
            font-size: 18px;
            margin-bottom: 12px;
        }

        label {
            display: block;
            font-weight: 600;
            margin: 12px 0 6px;
        }

        select, input {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid var(--line);
            border-radius: 8px;
            font-size: 14px;
        }

        .actions {
            margin-top: 14px;
        }

        button {
            width: 100%;
            padding: 12px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
        }

        .hint {
            font-size: 12px;
            color: var(--muted);
            margin-top: 8px;
        }

        .suggestions {
            display: grid;
            gap: 10px;
            margin-top: 10px;
        }

        .suggestion {
            border: 1px solid var(--line);
            border-radius: 10px;
            padding: 12px;
            display: grid;
            gap: 6px;
        }

        .tag {
            display: inline-block;
            background: #eef2ff;
            color: #374151;
            font-size: 12px;
            padding: 4px 8px;
            border-radius: 999px;
        }

        .activity {
            border-left: 3px solid var(--accent);
            padding-left: 8px;
            color: #7a4b00;
            font-size: 13px;
        }

        .empty {
            color: var(--muted);
            font-size: 14px;
            padding: 8px 0;
        }

        @media (max-width: 860px) {
            .panel {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
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
                    <option value="gain">Augmenter son poids</option>
                    <option value="perte">Reduire son poids</option>
                    <option value="ideal">Atteindre l'IMC ideal</option>
                </select>

                <label for="duree">Duree (semaines)</label>
                <input id="duree" type="number" min="2" max="24" placeholder="Ex: 8">

                <div class="actions">
                    <button type="button" onclick="updateSuggestions()">Voir les suggestions</button>
                </div>
                <div class="hint">Les suggestions sont des exemples front pour la demo (regimes + activites).</div>
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
        const data = {
            gain: [
                {
                    regime: "Regime hypercalorique",
                    impact: "+0.5 kg/semaine",
                    activite: "Musculation 3x/semaine"
                },
                {
                    regime: "Regime proteine + glucides",
                    impact: "+0.3 kg/semaine",
                    activite: "Marche active 2x/semaine"
                }
            ],
            perte: [
                {
                    regime: "Regime hypocalorique",
                    impact: "-0.6 kg/semaine",
                    activite: "Cardio 4x/semaine"
                },
                {
                    regime: "Regime mediterraneen",
                    impact: "-0.4 kg/semaine",
                    activite: "Natation 2x/semaine"
                }
            ],
            ideal: [
                {
                    regime: "Regime equilibre",
                    impact: "Stabilisation",
                    activite: "Jogging 3x/semaine"
                },
                {
                    regime: "Regime fractionne",
                    impact: "Stabilisation",
                    activite: "Yoga 2x/semaine"
                }
            ]
        };

        function updateSuggestions() {
            const target = document.getElementById("suggestions");
            const objectif = document.getElementById("objectif").value;
            const duree = document.getElementById("duree").value;

            if (!objectif || !data[objectif]) {
                target.innerHTML = '<div class="empty">Choisissez un objectif pour voir des suggestions.</div>';
                return;
            }

            const items = data[objectif];
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
        }
    </script>
</body>
</html>
