<?php
/**
 * Test complet après correctifs
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$_SESSION['loggedin'] = 1;
$_SESSION['userid'] = 1;
$_SERVER['HTTP_HOST'] = 'localhost';
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';

$test_pages = [
    'explore.php',
    'hospital.php',
    'jail.php',
    'gym.php',
    'city.php',
    'bank.php',
    'inventory.php',
    'preferences.php',
    'search.php',
    'viewuser.php'
];

echo "=== TEST DES PAGES APRÈS CORRECTIFS ===\n\n";

$success = 0;
$errors = 0;
$warnings = 0;

foreach ($test_pages as $page) {
    if (!file_exists($page)) {
        echo "⚠️  $page - NOT FOUND\n";
        continue;
    }

    // Reset globals
    $jobquery = false;
    $housequery = false;
    $macropage = false;
    $atkpage = false;
    $menuhide = false;

    ob_start();
    try {
        include $page;
        $output = ob_get_clean();

        if (stripos($output, 'fatal error') !== false) {
            echo "❌ $page - FATAL ERROR\n";
            $errors++;
        } elseif (stripos($output, 'warning:') !== false || stripos($output, 'notice:') !== false) {
            echo "⚠️  $page - WARNING (" . strlen($output) . " bytes)\n";
            $warnings++;
        } else {
            echo "✅ $page - OK (" . strlen($output) . " bytes)\n";
            $success++;
        }
    } catch (Throwable $e) {
        ob_end_clean();
        echo "❌ $page - EXCEPTION: " . $e->getMessage() . "\n";
        echo "   Location: " . basename($e->getFile()) . ":" . $e->getLine() . "\n";
        $errors++;
    }
}

echo "\n=== RÉSUMÉ ===\n";
echo "✅ Succès: $success\n";
echo "⚠️  Warnings: $warnings\n";
echo "❌ Erreurs: $errors\n";

if ($errors == 0) {
    echo "\n🎉 TOUS LES TESTS CRITIQUES SONT PASSÉS!\n";
} else {
    echo "\n⚠️  Il reste des erreurs à corriger\n";
}
