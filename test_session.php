<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

echo "<h1>Session Debug</h1>";
echo "<pre>";
echo "Session ID: " . session_id() . "\n\n";
echo "Session Data:\n";
print_r($_SESSION);
echo "\n\nServer Info:\n";
echo "PHP Version: " . PHP_VERSION . "\n";
echo "Current URL: " . $_SERVER['REQUEST_URI'] . "\n";
echo "</pre>";

if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == 1) {
    echo "<p style='color: green;'>✓ You are logged in</p>";
    echo "<p>User ID: " . (isset($_SESSION['userid']) ? $_SESSION['userid'] : 'NOT SET') . "</p>";

    // Check user level from database
    require "config.php";
    global $_CONFIG;
    require "class/class_db_{$_CONFIG['driver']}.php";
    $db = new database;
    $db->configure($_CONFIG['hostname'], $_CONFIG['username'], $_CONFIG['password'], $_CONFIG['database'], $_CONFIG['persistent']);
    $db->connect();

    if(isset($_SESSION['userid'])) {
        $q = $db->query("SELECT userid, username, user_level FROM users WHERE userid={$_SESSION['userid']}");
        if($db->num_rows($q) > 0) {
            $user = $db->fetch_row($q);
            echo "<p>Username: " . $user['username'] . "</p>";
            echo "<p>User Level: " . $user['user_level'] . "</p>";

            if($user['user_level'] <= 2) {
                echo "<p style='color: green;'>✓ You have admin access (level <= 2)</p>";
                echo "<p><a href='staff.php'>Go to Staff Panel</a></p>";
                echo "<p><a href='staff_crons.php'>Go to Cron Management</a></p>";
            } else {
                echo "<p style='color: red;'>✗ You don't have admin access (level > 2)</p>";
            }
        }
    }
} else {
    echo "<p style='color: red;'>✗ You are not logged in</p>";
    echo "<p><a href='login.php'>Login here</a></p>";
}
?>
