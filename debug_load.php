<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Disable output buffering completely
while (ob_get_level()) {
    ob_end_flush();
}

echo "1. Starting debug...<br>";
flush();

session_start();
echo "2. Session started<br>";
flush();

$_SESSION['loggedin'] = 1;
$_SESSION['userid'] = 1;
echo "3. Session vars set<br>";
flush();

try {
    echo "4. Loading core.php...<br>";
    flush();
    require_once "core.php";
    echo "5. core.php loaded OK<br>";
    flush();
} catch (Throwable $e) {
    die("ERROR in core.php: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine());
}

try {
    echo "6. Loading global_func.php...<br>";
    flush();
    require "global_func.php";
    echo "7. global_func.php loaded OK<br>";
    flush();
} catch (Throwable $e) {
    die("ERROR in global_func.php: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine());
}

$userid = $_SESSION['userid'];
echo "8. userid = $userid<br>";
flush();

try {
    echo "9. Loading config.php...<br>";
    flush();
    include "config.php";
    echo "10. config.php loaded OK<br>";
    flush();
} catch (Throwable $e) {
    die("ERROR in config.php: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine());
}

try {
    echo "11. Loading language.php...<br>";
    flush();
    include "language.php";
    echo "12. language.php loaded OK<br>";
    flush();
} catch (Throwable $e) {
    die("ERROR in language.php: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine());
}

global $_CONFIG;
echo "13. Config driver: {$_CONFIG['driver']}<br>";
flush();

define("MONO_ON", 1);
echo "14. MONO_ON defined<br>";
flush();

try {
    echo "15. Loading header.php...<br>";
    flush();
    require "header.php";
    echo "16. header.php loaded OK<br>";
    flush();
} catch (Throwable $e) {
    die("ERROR in header.php: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine());
}

echo "17. ALL FILES LOADED SUCCESSFULLY!<br>";
echo "If you see this, sglobals.php should work!<br>";
?>
