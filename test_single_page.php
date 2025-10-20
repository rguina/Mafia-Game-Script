<?php
/**
 * Test d'une seule page à la fois
 */
$page = $argv[1] ?? 'explore.php';

if (!file_exists($page)) {
    echo "❌ $page not found\n";
    exit(1);
}

error_reporting(E_ERROR | E_PARSE); // Only show errors, not warnings
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$_SESSION['loggedin'] = 1;
$_SESSION['userid'] = 1;
$_SERVER['HTTP_HOST'] = 'localhost';
$_SERVER['REQUEST_METHOD'] = 'GET';

ob_start();
try {
    include $page;
    $output = ob_get_clean();

    if (stripos($output, 'fatal error') !== false) {
        echo "❌ $page - FATAL ERROR in output\n";
        exit(1);
    } else {
        echo "✅ $page - OK (" . strlen($output) . " bytes)\n";
        exit(0);
    }
} catch (Throwable $e) {
    ob_end_clean();
    echo "❌ $page - EXCEPTION: " . $e->getMessage() . "\n";
    echo "   Location: " . $e->getFile() . ":" . $e->getLine() . "\n";
    exit(1);
}
