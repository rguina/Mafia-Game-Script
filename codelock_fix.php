<?php
/**
 * CODELOCK FIX - Polyfill pour fichiers encodés
 *
 * Ce fichier définit une constante fictive 'codelock' pour éviter
 * l'erreur "Undefined constant codelock" dans les fichiers encodés
 */

// Définir la constante codelock si elle n'existe pas
if (!defined('codelock')) {
    define('codelock', 1);
}

// Initialiser le tableau $codelock_decrypter s'il n'existe pas
if (!isset($codelock_decrypter)) {
    $codelock_decrypter = array();
}
?>
