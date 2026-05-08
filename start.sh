#!/bin/bash

# Lancer le serveur PHP avec un rewrite router pour CodeIgniter
cd "$(dirname "$0")/public"

echo "================================"
echo "🚀 Démarrage du serveur..."
echo "================================"
echo ""
echo "📍 Adresse: http://localhost:8080"
echo "📍 Login: http://localhost:8080/auth/login"
echo "📍 Formulaire: http://localhost:8080/formulaire"
echo ""
echo "Pour arrêter: Appuyez sur Ctrl+C"
echo "================================"
echo ""

php -S localhost:8080 -t . ../system/Commands/Utilities/Router.php
