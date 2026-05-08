<?php

/**
 * Router pour le serveur de développement PHP
 * Redirige tous les fichiers non-existants vers index.php
 */

if (php_sapi_name() === 'cli-server') {
    // Serveur de développement PHP
    if (preg_match('/\.(?:css|js|gif|jpg|jpeg|png|ico|svg|webp)$/', $_SERVER['REQUEST_URI'])) {
        // Fichiers statiques
        return false;
    }
    
    // Redirige tout vers index.php
    if ($_SERVER['REQUEST_URI'] !== '/' && !is_file(__DIR__ . $_SERVER['REQUEST_URI'])) {
        require __DIR__ . '/index.php';
        return true;
    }
}

return false;
