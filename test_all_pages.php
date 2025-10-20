<?php
/**
 * Script de test automatique pour toutes les pages du jeu
 * Teste chaque page et collecte les erreurs
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configuration
$_SERVER['HTTP_HOST'] = 'localhost';
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';

$errors = [];
$warnings = [];
$success = [];

// Pages publiques à tester
$public_pages = [
    'login.php',
    'signup.php',
    'index.php'
];

// Pages authentifiées à tester (nécessitent une session)
$auth_pages = [
    'explore.php',
    'hospital.php',
    'jail.php',
    'gym.php',
    'forums.php',
    'contactlist.php',
    'inventory.php',
    'preferences.php',
    'yourgang.php',
    'newspaper.php',
    'staff.php',
    'search.php',
    'viewuser.php',
    'sendmoney.php',
    'bank.php',
    'city.php',
    'events.php',
    'joblist.php'
];

echo "=== TEST DES PAGES PUBLIQUES ===\n\n";

foreach ($public_pages as $page) {
    if (!file_exists($page)) {
        $errors[] = "$page - FILE NOT FOUND";
        echo "❌ $page - FILE NOT FOUND\n";
        continue;
    }

    ob_start();
    try {
        include $page;
        $output = ob_get_clean();

        // Vérifier si la page contient des erreurs visibles
        if (stripos($output, 'fatal error') !== false) {
            $errors[] = "$page - Contains FATAL ERROR in output";
            echo "❌ $page - FATAL ERROR\n";
        } elseif (stripos($output, 'warning') !== false) {
            $warnings[] = "$page - Contains WARNING in output";
            echo "⚠️  $page - WARNING\n";
        } else {
            $success[] = "$page - OK (" . strlen($output) . " bytes)";
            echo "✅ $page - OK (" . strlen($output) . " bytes)\n";
        }
    } catch (Throwable $e) {
        ob_end_clean();
        $error_msg = "$page - Exception: {$e->getMessage()} in {$e->getFile()}:{$e->getLine()}";
        $errors[] = $error_msg;
        echo "❌ $page - EXCEPTION: {$e->getMessage()}\n";
        echo "   File: {$e->getFile()}:{$e->getLine()}\n";
    }
}

echo "\n=== TEST DES PAGES AUTHENTIFIÉES (simulation de session) ===\n\n";

// Simuler une session utilisateur
session_start();
$_SESSION['loggedin'] = 1;
$_SESSION['userid'] = 1; // ID de l'admin

foreach ($auth_pages as $page) {
    if (!file_exists($page)) {
        $warnings[] = "$page - FILE NOT FOUND (may be in subdirectory)";
        echo "⚠️  $page - FILE NOT FOUND\n";
        continue;
    }

    ob_start();
    try {
        // Reset some globals that might interfere
        $jobquery = false;
        $housequery = false;
        $macropage = false;
        $atkpage = false;
        $menuhide = false;

        include $page;
        $output = ob_get_clean();

        if (stripos($output, 'fatal error') !== false) {
            $errors[] = "$page - Contains FATAL ERROR in output";
            echo "❌ $page - FATAL ERROR\n";
        } elseif (stripos($output, 'warning') !== false) {
            $warnings[] = "$page - Contains WARNING in output";
            echo "⚠️  $page - WARNING\n";
        } else {
            $success[] = "$page - OK (" . strlen($output) . " bytes)";
            echo "✅ $page - OK (" . strlen($output) . " bytes)\n";
        }
    } catch (Throwable $e) {
        ob_end_clean();
        $error_msg = "$page - Exception: {$e->getMessage()} in {$e->getFile()}:{$e->getLine()}";
        $errors[] = $error_msg;
        echo "❌ $page - EXCEPTION: {$e->getMessage()}\n";
        echo "   File: {$e->getFile()}:{$e->getLine()}\n";
    }
}

// Résumé
echo "\n=== RÉSUMÉ DES TESTS ===\n\n";
echo "✅ Succès: " . count($success) . "\n";
echo "⚠️  Avertissements: " . count($warnings) . "\n";
echo "❌ Erreurs: " . count($errors) . "\n\n";

if (count($errors) > 0) {
    echo "=== ERREURS DÉTAILLÉES ===\n";
    foreach ($errors as $error) {
        echo "- $error\n";
    }
    echo "\n";
}

if (count($warnings) > 0) {
    echo "=== AVERTISSEMENTS DÉTAILLÉS ===\n";
    foreach ($warnings as $warning) {
        echo "- $warning\n";
    }
}

// Sauvegarder dans un fichier JSON pour analyse ultérieure
file_put_contents('test_results.json', json_encode([
    'success' => $success,
    'warnings' => $warnings,
    'errors' => $errors,
    'timestamp' => date('Y-m-d H:i:s')
], JSON_PRETTY_PRINT));

echo "\nRésultats sauvegardés dans test_results.json\n";
