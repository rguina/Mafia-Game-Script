<?php
/**
 * Script to fix all auto-increment INSERT queries
 * Changes VALUES('', ...) to VALUES(NULL, ...)
 *
 * MySQL 8.0 strict mode doesn't accept empty string '' for auto-increment columns
 */

$files = [
    'preport.php',
    'staff_jobs.php',
    'forums.php',
    'signup.php',
    'streets.php',
    'staff_shops.php',
    'staff_special.php',
    'staff_punit.php',
    'staff_items.php',
    'secpanel.php',
    'jailuser.php',
    'itemsend.php',
    'ipn_wp.php',
    'ipn_donator.php',
    'cron_srun_day.php',
    'cron_run_day.php',
    'creategang.php',
    'createshop.php',
    'contactlist.php',
    'cmarket.php',
    'blacklist.php',
    'attacktake.php',
    'attackwon.php',
    'attacklost.php',
    'attackbeat.php',
];

$total_replacements = 0;
$files_modified = 0;

foreach ($files as $file) {
    if (!file_exists($file)) {
        echo "SKIP: $file (not found)\n";
        continue;
    }

    $content = file_get_contents($file);
    $original_content = $content;

    // Replace all variations of INSERT INTO ... VALUES('', with VALUES(NULL,
    $patterns = [
        "/VALUES\s*\(\s*''\s*,/i" => "VALUES(NULL,",
        "/VALUES\s*\(\s*\"\"\s*,/i" => "VALUES(NULL,",
    ];

    $file_replacements = 0;
    foreach ($patterns as $pattern => $replacement) {
        $new_content = preg_replace($pattern, $replacement, $content, -1, $count);
        if ($count > 0) {
            $content = $new_content;
            $file_replacements += $count;
        }
    }

    if ($file_replacements > 0) {
        file_put_contents($file, $content);
        echo "FIXED: $file ($file_replacements replacements)\n";
        $files_modified++;
        $total_replacements += $file_replacements;
    } else {
        echo "OK: $file (no changes needed)\n";
    }
}

echo "\n=== SUMMARY ===\n";
echo "Files modified: $files_modified\n";
echo "Total replacements: $total_replacements\n";
echo "\nDone! All auto-increment INSERT queries have been fixed.\n";
?>
