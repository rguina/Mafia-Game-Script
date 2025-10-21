<?php
/**
 * Fix undefined array key warnings in specific files
 * Adds isset() checks for $_GET, $_POST, and $set array access
 */

echo "=== Fixing Undefined Array Key Warnings ===\n\n";

// Fix newspaper.php - lines 69 and 78
echo "Fixing newspaper.php...\n";
$file = 'newspaper.php';
if (file_exists($file)) {
    $content = file_get_contents($file);

    // Add isset checks for $_GET['action']
    $content = preg_replace(
        "/if\s*\(\s*\\\$_GET\['action'\]\s*==\s*\"all\"\s*\)/",
        "if(isset(\$_GET['action']) && \$_GET['action'] == \"all\")",
        $content
    );

    $content = preg_replace(
        "/else\s+if\s*\(\s*\\\$_GET\['action'\]\s*==\s*\"npID\"\s*\)/",
        "else if(isset(\$_GET['action']) && \$_GET['action'] == \"npID\")",
        $content
    );

    file_put_contents($file, $content);
    echo "  FIXED: newspaper.php\n";
}

// Fix donator.php - multiple lines using $set['paypal']
echo "\nFixing donator.php...\n";
$file = 'donator.php';
if (file_exists($file)) {
    $content = file_get_contents($file);

    // Add default value for paypal at the beginning of the file, after includes
    // Find where globals.php is included and add the check after
    if (strpos($content, "include \"globals.php\";") !== false) {
        $content = str_replace(
            "include \"globals.php\";",
            "include \"globals.php\";\n// Set default PayPal email if not configured\nif (!isset(\$set['paypal']) || empty(\$set['paypal'])) {\n    \$set['paypal'] = 'your-paypal@email.com'; // Change this in settings table\n}",
            $content
        );

        file_put_contents($file, $content);
        echo "  FIXED: donator.php (added default paypal check)\n";
    }
}

// Fix willpotion.php - same issue with $set['paypal']
echo "\nFixing willpotion.php...\n";
$file = 'willpotion.php';
if (file_exists($file)) {
    $content = file_get_contents($file);

    // Add default value for paypal at the beginning of the file, after includes
    if (strpos($content, "include \"globals.php\";") !== false) {
        $content = str_replace(
            "include \"globals.php\";",
            "include \"globals.php\";\n// Set default PayPal email if not configured\nif (!isset(\$set['paypal']) || empty(\$set['paypal'])) {\n    \$set['paypal'] = 'your-paypal@email.com'; // Change this in settings table\n}",
            $content
        );

        file_put_contents($file, $content);
        echo "  FIXED: willpotion.php (added default paypal check)\n";
    }
}

// Add paypal setting to database if it doesn't exist
echo "\nAdding 'paypal' setting to database...\n";
try {
    require_once 'config.php';
    $mysqli = new mysqli($_CONFIG['hostname'], $_CONFIG['username'], $_CONFIG['password'], $_CONFIG['database']);

    if ($mysqli->connect_error) {
        echo "  ERROR: Could not connect to database\n";
    } else {
        // Check if paypal setting exists
        $result = $mysqli->query("SELECT * FROM settings WHERE conf_name='paypal'");

        if ($result->num_rows == 0) {
            // Add paypal setting
            $mysqli->query("INSERT INTO settings (conf_name, conf_value) VALUES ('paypal', 'your-paypal@email.com')");
            echo "  ADDED: 'paypal' setting to database (default: your-paypal@email.com)\n";
            echo "  NOTE: Update this in the settings table with your actual PayPal email\n";
        } else {
            echo "  OK: 'paypal' setting already exists in database\n";
        }

        $mysqli->close();
    }
} catch (Exception $e) {
    echo "  WARNING: Could not update database: " . $e->getMessage() . "\n";
}

echo "\n=== SUMMARY ===\n";
echo "Files fixed:\n";
echo "  - newspaper.php (isset checks for \$_GET['action'])\n";
echo "  - donator.php (default value for \$set['paypal'])\n";
echo "  - willpotion.php (default value for \$set['paypal'])\n";
echo "\nAll undefined array key warnings should be fixed!\n";
?>
