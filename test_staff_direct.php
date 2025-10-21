<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "START OF PAGE<br>";

// Manual session and user check
session_start();

if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != 1) {
    die("NOT LOGGED IN - <a href='login.php'>Login</a>");
}

echo "Session OK<br>";

// Connect to database
try {
    echo "Loading config.php...<br>";
    require "config.php";
    echo "Config loaded<br>";

    global $_CONFIG;
    echo "Driver: {$_CONFIG['driver']}<br>";

    // Define MONO_ON before loading database class
    define("MONO_ON", 1);
    echo "MONO_ON defined<br>";

    $db_class_file = "class/class_db_{$_CONFIG['driver']}.php";
    echo "Loading database class: $db_class_file<br>";
    require $db_class_file;
    echo "Database class loaded<br>";

    $db = new database;
    echo "Database object created<br>";

    $db->configure($_CONFIG['hostname'], $_CONFIG['username'], $_CONFIG['password'], $_CONFIG['database'], $_CONFIG['persistent']);
    echo "Database configured<br>";

    $db->connect();
    echo "Database connected!<br>";
} catch (Throwable $e) {
    echo "<b style='color:red'>ERROR: " . $e->getMessage() . "</b><br>";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "<br>";
    die();
}

// Load user data
$userid = $_SESSION['userid'];
$is = $db->query("SELECT u.*,us.* FROM users u LEFT JOIN userstats us ON u.userid=us.userid WHERE u.userid=$userid");
$ir = $db->fetch_row($is);

echo "User loaded: " . $ir['username'] . "<br>";
echo "User level: " . $ir['user_level'] . "<br>";

// Check admin access
if($ir['user_level'] > 2) {
    die("403 - Access Denied (level {$ir['user_level']} > 2)");
}

echo "Admin access OK<br>";
echo "<h1>This would be the staff panel!</h1>";
echo "<p>If you see this, the page works correctly.</p>";
?>
