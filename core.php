<?php
/**
 * CORE.PHP - Version décodée/simplifiée pour PHP 8.x
 *
 * Ce fichier remplace le core.php encodé qui ne fonctionne pas avec PHP 8.x
 * Il contient les fonctions essentielles pour faire fonctionner le jeu
 */

if (!defined('MONO_ON')) { define('MONO_ON', 1); }

// Chargement de la configuration
require_once('config.php');

// Chargement du driver de base de données
$driver = isset($_CONFIG['driver']) ? $_CONFIG['driver'] : 'mysqli';
if ($driver == 'mysqli' && file_exists('class/class_db_mysqli.php')) {
    require_once('class/class_db_mysqli.php');
} elseif ($driver == 'mysql' && file_exists('class/class_db_mysql.php')) {
    require_once('class/class_db_mysql.php');
} else {
    die('Database driver not found or not configured properly');
}

// Initialisation de la base de données
$db = new database();
$db->configure(
    $_CONFIG['hostname'],
    $_CONFIG['username'],
    $_CONFIG['password'],
    $_CONFIG['database'],
    isset($_CONFIG['persistent']) ? $_CONFIG['persistent'] : 0
);
$c = $db->connect();

if (!$c) {
    die('Could not connect to database');
}

// Chargement des fonctions globales
if (file_exists('global_func.php')) {
    require_once('global_func.php');
}

// Définir des constantes utiles
if (!defined('MONO_ON')) { define('MONO_ON', 1); }
?>
