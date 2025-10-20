<?php
/**
 * CORE.PHP - Version décodée/simplifiée pour PHP 8.x
 *
 * Ce fichier remplace le core.php encodé qui ne fonctionne pas avec PHP 8.x
 * Il contient les fonctions essentielles pour faire fonctionner le jeu
 */

// Désactiver l'affichage des warnings pour un code legacy
error_reporting(E_ERROR | E_PARSE);

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

// PHP 8.x: Créer des fonctions de compatibilité pour les anciennes fonctions mysql_*
if (!function_exists('mysql_real_escape_string')) {
    function mysql_real_escape_string($string) {
        global $db;
        if (isset($db) && isset($db->connection_id)) {
            return mysqli_real_escape_string($db->connection_id, $string);
        }
        return addslashes($string);
    }
}

if (!function_exists('mysql_escape_string')) {
    function mysql_escape_string($string) {
        return mysql_real_escape_string($string);
    }
}

// Charger les settings depuis la base de données
$set = array();
if (isset($db) && $db->connection_id) {
    $settings_query = $db->query("SELECT * FROM settings");
    if ($settings_query) {
        while ($row = $db->fetch_row($settings_query)) {
            $set[$row['conf_name']] = $row['conf_value'];
        }
    }
}

// Définir des constantes utiles
if (!defined('MONO_ON')) { define('MONO_ON', 1); }
?>
