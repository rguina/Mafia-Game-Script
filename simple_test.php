<?php
/**
 * Test simple des pages principales
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Test 1: Pages publiques
echo "=== TEST 1: LOGIN.PHP ===\n";
$_SERVER['HTTP_HOST'] = 'localhost';
$_SERVER['REQUEST_METHOD'] = 'GET';
ob_start();
try {
    include 'login.php';
    $out = ob_get_clean();
    echo "✅ OK - " . strlen($out) . " bytes\n\n";
} catch (Throwable $e) {
    ob_end_clean();
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "   File: " . $e->getFile() . ":" . $e->getLine() . "\n\n";
}

// Test 2: Signup
echo "=== TEST 2: SIGNUP.PHP ===\n";
ob_start();
try {
    include 'signup.php';
    $out = ob_get_clean();
    echo "✅ OK - " . strlen($out) . " bytes\n\n";
} catch (Throwable $e) {
    ob_end_clean();
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "   File: " . $e->getFile() . ":" . $e->getLine() . "\n\n";
}

// Test 3: Forums (avec session)
echo "=== TEST 3: FORUMS.PHP (with session) ===\n";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$_SESSION['loggedin'] = 1;
$_SESSION['userid'] = 1;
ob_start();
try {
    include 'forums.php';
    $out = ob_get_clean();
    echo "✅ OK - " . strlen($out) . " bytes\n\n";
} catch (Throwable $e) {
    ob_end_clean();
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "   File: " . $e->getFile() . ":" . $e->getLine() . "\n\n";
}

echo "=== TEST TERMINÉ ===\n";
