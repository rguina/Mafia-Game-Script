<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Don't output anything before sglobals to avoid session issues
include "sglobals.php";

echo "AFTER sglobals<br>";

echo "AFTER sglobals<br>";
echo "User: {$ir['username']}<br>";
echo "Level: {$ir['user_level']}<br>";

if($ir['user_level'] > 2) {
    die("Access Denied");
}

echo "<h1>SUCCESS! Staff panel would go here.</h1>";

$h->endpage();
?>
