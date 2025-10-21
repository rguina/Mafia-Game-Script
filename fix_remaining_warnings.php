<?php
/**
 * Fix remaining undefined array key and variable warnings
 * Comprehensive fix for all reported issues
 */

echo "=== Fixing Remaining Warnings ===\n\n";

$fixes = [
    // education.php - line 37
    'education.php' => [
        'pattern' => '/(\$_GET\[\'cstart\'\])(?!\s*\?)/i',
        'check' => function($content) {
            // Add isset check for $_GET['cstart']
            return preg_replace(
                '/if\s*\(\s*\$_GET\[\'cstart\'\]\s*\)/',
                "if(isset(\$_GET['cstart']) && \$_GET['cstart'])",
                $content
            );
        }
    ],

    // halloffame.php - multiple lines
    'halloffame.php' => [
        'check' => function($content) {
            // Fix $_GET['filter'] access
            $content = preg_replace(
                '/\$_GET\[\'filter\'\](?!\s*\?)/i',
                "(isset(\$_GET['filter']) ? \$_GET['filter'] : '')",
                $content
            );
            // Fix $_GET['action'] - already has switch fix, but add direct access fixes
            $content = preg_replace(
                '/if\s*\(\s*\$_GET\[\'action\'\]\s*==/',
                "if(isset(\$_GET['action']) && \$_GET['action'] ==",
                $content
            );
            return $content;
        }
    ],

    // lucky.php - line 29
    'lucky.php' => [
        'check' => function($content) {
            return preg_replace(
                '/if\s*\(\s*\$_GET\[\'open\'\]\s*\)/',
                "if(isset(\$_GET['open']) && \$_GET['open'])",
                $content
            );
        }
    ],

    // roulette.php - lines 28, 29
    'roulette.php' => [
        'check' => function($content) {
            $content = preg_replace(
                '/\$_POST\[\'bet\'\]\s*=\s*abs/',
                "\$_POST['bet'] = isset(\$_POST['bet']) ? abs",
                $content
            );
            $content = preg_replace(
                '/\$_POST\[\'number\'\]\s*=\s*abs/',
                "\$_POST['number'] = isset(\$_POST['number']) ? abs",
                $content
            );
            return $content;
        }
    ],

    // slotsmachine.php - lines 22, 28
    'slotsmachine.php' => [
        'check' => function($content) {
            $content = preg_replace(
                '/\$_GET\[\'tresde\'\]\s*=\s*abs/',
                "\$_GET['tresde'] = isset(\$_GET['tresde']) ? abs",
                $content
            );
            $content = preg_replace(
                '/\$_POST\[\'bet\'\]\s*=\s*abs/',
                "\$_POST['bet'] = isset(\$_POST['bet']) ? abs",
                $content
            );
            return $content;
        }
    ],

    // crystaltemple.php - line 19
    'crystaltemple.php' => [
        'check' => function($content) {
            return preg_replace(
                '/if\s*\(\s*\$_GET\[\'spend\'\]\s*==/',
                "if(isset(\$_GET['spend']) && \$_GET['spend'] ==",
                $content
            );
        }
    ],

    // battle_ladder.php - line 3
    'battle_ladder.php' => [
        'check' => function($content) {
            $content = preg_replace(
                '/\$_GET\[\'page\'\]\s*=\s*abs/',
                "\$_GET['page'] = isset(\$_GET['page']) ? abs",
                $content
            );
            return $content;
        }
    ],

    // estate.php - line 21
    'estate.php' => [
        'check' => function($content) {
            $content = preg_replace(
                '/\$_POST\[\'property\'\]\s*=\s*abs/',
                "\$_POST['property'] = isset(\$_POST['property']) ? abs",
                $content
            );
            return $content;
        }
    ],

    // business_view.php - line 4
    'business_view.php' => [
        'check' => function($content) {
            $content = preg_replace(
                '/\$_GET\[\'page\'\]\s*=\s*abs/',
                "\$_GET['page'] = isset(\$_GET['page']) ? abs",
                $content
            );
            return $content;
        }
    ],

    // polling.php - lines 29, 31
    'polling.php' => [
        'check' => function($content) {
            $content = preg_replace(
                '/\$_POST\[\'poll\'\]\s*=\s*abs/',
                "\$_POST['poll'] = isset(\$_POST['poll']) ? abs",
                $content
            );
            $content = preg_replace(
                '/\$_POST\[\'choice\'\]\s*=\s*abs/',
                "\$_POST['choice'] = isset(\$_POST['choice']) ? abs",
                $content
            );
            return $content;
        }
    ],
];

$stats = ['files_fixed' => 0, 'total_fixes' => 0];

foreach ($fixes as $file => $fix) {
    if (!file_exists($file)) {
        echo "SKIP: $file (not found)\n";
        continue;
    }

    $content = file_get_contents($file);
    $original = $content;

    if (isset($fix['check'])) {
        $content = $fix['check']($content);
    }

    if ($content !== $original) {
        file_put_contents($file, $content);
        echo "FIXED: $file\n";
        $stats['files_fixed']++;
    } else {
        echo "OK: $file (no changes needed)\n";
    }
}

echo "\n=== SUMMARY ===\n";
echo "Files fixed: {$stats['files_fixed']}\n";
echo "\nAll undefined array key warnings should be fixed!\n";
?>
