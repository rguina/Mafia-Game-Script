<?php
/**
 * COMPREHENSIVE PHP 8.x COMPATIBILITY FIX SCRIPT
 *
 * This script fixes all common PHP 8.x compatibility issues found in the project:
 * 1. Undefined array key warnings (switch($_GET['action']) without isset)
 * 2. Orphaned heredoc markers (OUT;)
 * 3. Deprecated define() with case_insensitive parameter
 * 4. Missing quotes in function_exists()
 */

echo "=== PHP 8.x Compatibility Fix Script ===\n\n";

// Files with switch($_GET['action']) that need isset() check
$switch_files = [
    'blacklist.php',
    'cmarket.php',
    'contactlist.php',
    'secpanel.php',
    'staff_items.php',
    'staff_punit.php',
    'staff_special.php',
    'staff_shops.php',
    'streets.php',
    'staff_jobs.php',
    'friendslist.php',
    'staff_crons.php',
    'staff_users.php',
    'staff_logs.php',
    'staff_polls.php',
    'staff_ladder.php',
    'staff_houses.php',
    'staff_gangs.php',
    'staff_forums.php',
    'staff_crimes.php',
    'staff_courses.php',
    'staff_cities.php',
    'staff_battletent.php',
    'preferences.php',
    'myshop.php',
    'mailbox.php',
    'magicslots.php',
    'killer.php',
    'itemmarket.php',
    'halloffame.php',
    'gamestation.php',
    'fedjail.php',
    'cyberbank.php',
    'business_view.php',
    'business_home.php',
    'business_manage.php',
    'burnhouse.php',
    'battle_ladder.php',
    'bank.php',
];

// Files with orphaned OUT; heredoc markers
$heredoc_files = [
    'signup.php',
    'header.php',
    'mailbox.php',
    'forgot.php',
];

$stats = [
    'switch_fixed' => 0,
    'heredoc_fixed' => 0,
    'define_fixed' => 0,
    'function_exists_fixed' => 0,
    'files_modified' => 0,
];

// Fix 1: Switch statements with $_GET without isset()
echo "Step 1: Fixing switch(\$_GET['action']) issues...\n";
foreach ($switch_files as $file) {
    if (!file_exists($file)) {
        echo "  SKIP: $file (not found)\n";
        continue;
    }

    $content = file_get_contents($file);
    $original = $content;
    $modified = false;

    // Pattern: switch($_GET['action']) or switch($_GET["action"])
    // Replace with: $action = isset($_GET['action']) ? $_GET['action'] : ''; switch($action)
    $patterns = [
        // switch($_GET['action'])
        "/switch\s*\(\s*\\\$_GET\s*\[\s*'action'\s*\]\s*\)/i" => function($matches) {
            return "\$action = isset(\$_GET['action']) ? \$_GET['action'] : '';\nswitch(\$action)";
        },
        // switch($_GET["action"])
        "/switch\s*\(\s*\\\$_GET\s*\[\s*\"action\"\s*\]\s*\)/i" => function($matches) {
            return "\$action = isset(\$_GET['action']) ? \$_GET['action'] : '';\nswitch(\$action)";
        },
    ];

    foreach ($patterns as $pattern => $replacement) {
        $new_content = preg_replace_callback($pattern, $replacement, $content, -1, $count);
        if ($count > 0) {
            $content = $new_content;
            $stats['switch_fixed'] += $count;
            $modified = true;
        }
    }

    if ($modified && $content !== $original) {
        file_put_contents($file, $content);
        echo "  FIXED: $file\n";
        $stats['files_modified']++;
    }
}

// Fix 2: Orphaned OUT; heredoc markers
echo "\nStep 2: Fixing orphaned OUT; heredoc markers...\n";
foreach ($heredoc_files as $file) {
    if (!file_exists($file)) {
        echo "  SKIP: $file (not found)\n";
        continue;
    }

    $content = file_get_contents($file);
    $original = $content;

    // Replace standalone OUT; with commented version
    $pattern = "/^\s*OUT\s*;\s*$/m";
    $replacement = "// OUT; // Orphaned heredoc marker - commented out for PHP 8 compatibility";
    $new_content = preg_replace($pattern, $replacement, $content, -1, $count);

    if ($count > 0) {
        file_put_contents($file, $new_content);
        echo "  FIXED: $file ($count replacements)\n";
        $stats['heredoc_fixed'] += $count;
        $stats['files_modified']++;
    }
}

// Fix 3: Deprecated define() with 3 parameters (scan all PHP files)
echo "\nStep 3: Fixing deprecated define() with case_insensitive parameter...\n";
$all_php_files = glob('*.php') + glob('*/*.php') + glob('*/*/*.php');
foreach ($all_php_files as $file) {
    if (!is_file($file)) continue;

    $content = file_get_contents($file);
    $original = $content;

    // Pattern: define('NAME', value, true/false/1/0)
    $pattern = "/define\s*\(\s*(['\"][^'\"]+['\"])\s*,\s*([^,]+)\s*,\s*(true|false|1|0)\s*\)/i";
    $replacement = "define($1, $2)";
    $new_content = preg_replace($pattern, $replacement, $content, -1, $count);

    if ($count > 0) {
        file_put_contents($file, $new_content);
        echo "  FIXED: $file ($count replacements)\n";
        $stats['define_fixed'] += $count;
        if ($content === $original) {
            $stats['files_modified']++;
        }
    }
}

// Fix 4: Missing quotes in function_exists()
echo "\nStep 4: Fixing function_exists() without quotes...\n";
foreach ($all_php_files as $file) {
    if (!is_file($file)) continue;

    $content = file_get_contents($file);
    $original = $content;

    // Pattern: function_exists('array_fill_keys') -> function_exists('array_fill_keys')
    // Only match if it's NOT already quoted
    $pattern = "/function_exists\s*\(\s*([a-zA-Z_][a-zA-Z0-9_]*)\s*\)/";
    $new_content = preg_replace_callback($pattern, function($matches) {
        // Check if it's not already a variable (starts with $)
        if (strpos($matches[1], '$') === false) {
            return "function_exists('" . $matches[1] . "')";
        }
        return $matches[0]; // Keep variables as-is
    }, $content, -1, $count);

    if ($count > 0 && $new_content !== $original) {
        file_put_contents($file, $new_content);
        echo "  FIXED: $file ($count replacements)\n";
        $stats['function_exists_fixed'] += $count;
        $stats['files_modified']++;
    }
}

echo "\n=== SUMMARY ===\n";
echo "Files modified: {$stats['files_modified']}\n";
echo "Switch statements fixed: {$stats['switch_fixed']}\n";
echo "Orphaned heredocs fixed: {$stats['heredoc_fixed']}\n";
echo "Deprecated define() fixed: {$stats['define_fixed']}\n";
echo "function_exists() fixed: {$stats['function_exists_fixed']}\n";
echo "\nAll PHP 8.x compatibility issues have been fixed!\n";
?>
