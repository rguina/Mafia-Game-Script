<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Start session BEFORE including staff.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$_SESSION['loggedin'] = 1;
$_SESSION['userid'] = 1;
$_SERVER['HTTP_HOST'] = 'localhost';
$_SERVER['REQUEST_URI'] = '/staff.php';

echo "=== Testing staff.php ===<br>";
flush();

ob_start();

// Register shutdown function to catch fatal errors
register_shutdown_function(function() {
    $error = error_get_last();
    if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        ob_end_clean();
        echo "<br><b style='color:red'>FATAL ERROR:</b><br>";
        echo "Message: " . $error['message'] . "<br>";
        echo "File: " . $error['file'] . "<br>";
        echo "Line: " . $error['line'] . "<br>";
    }
});

try {
    include 'staff.php';
    $output = ob_get_contents();
    ob_end_clean();

    if(empty($output)) {
        echo "ERROR: Empty output (page is blank)<br>";
    } else {
        echo "SUCCESS: Page has output (" . strlen($output) . " bytes)<br>";

        // Check if it contains expected content
        if(strpos($output, 'Staff Panel') !== false) {
            echo "✓ Staff Panel title found<br>";
        }
        if(strpos($output, 'Cron Jobs Management') !== false) {
            echo "✓ Cron Jobs Management link found<br>";
        }
        if(strpos($output, 'Fatal error') !== false) {
            echo "❌ Fatal error found in output<br>";
            echo "First 1000 chars:<br><pre>" . htmlentities(substr($output, 0, 1000)) . "</pre>";
        }
    }
} catch (Throwable $e) {
    ob_end_clean();
    echo "ERROR: " . $e->getMessage() . "<br>";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "<br>";
}
?>
