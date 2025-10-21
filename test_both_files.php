<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "1. Loading core.php...<br>";
flush();

try {
    require_once "core.php";
    echo "2. core.php loaded OK<br>";
    flush();
} catch (Throwable $e) {
    die("ERROR in core.php: " . $e->getMessage());
}

echo "3. Loading global_func.php...<br>";
flush();

// Register error handler to catch fatal errors
register_shutdown_function(function() {
    $error = error_get_last();
    if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        echo "<br><b style='color:red'>FATAL ERROR:</b><br>";
        echo "Message: " . $error['message'] . "<br>";
        echo "File: " . $error['file'] . "<br>";
        echo "Line: " . $error['line'] . "<br>";
    }
});

try {
    require "global_func.php";
    echo "4. global_func.php loaded OK<br>";
    flush();
} catch (Throwable $e) {
    die("ERROR in global_func.php: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine());
}

echo "5. BOTH FILES LOADED SUCCESSFULLY!<br>";
?>
