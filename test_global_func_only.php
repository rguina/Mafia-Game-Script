<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
set_time_limit(5); // 5 second timeout

echo "Loading global_func.php...<br>";

$start = microtime(true);

try {
    require "global_func.php";
    $end = microtime(true);
    $time = round(($end - $start) * 1000, 2);
    echo "SUCCESS! Loaded in {$time}ms<br>";
} catch (Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "<br>";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "<br>";
}

echo "Script completed.";
?>
