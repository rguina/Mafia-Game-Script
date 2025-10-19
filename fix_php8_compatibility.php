<?php
/**
 * Script de compatibilité PHP 8.x pour Mafia Game Script
 * Corrige les erreurs get_magic_quotes_gpc() qui n'existe plus dans PHP 8.0+
 */

echo "=== Correction de la compatibilité PHP 8.x ===\n\n";

// Liste des fichiers à corriger
$files = [
    'authenticate.php',
    'dlarchive.php',
    'macro2.php',
    'sglobals.php',
    'votetwg.php',
    'votetrpg.php',
    'gangs/config.php'
];

$pattern_simple = "if(get_magic_quotes_gpc() == 0)";
$replacement_simple = "// PHP 8.x compatibility fix\nif(!function_exists('get_magic_quotes_gpc') || get_magic_quotes_gpc() == 0)";

$pattern_gang1 = "if (get_magic_quotes_gpc()) {";
$replacement_gang1 = "if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) {";

$fixed_count = 0;

foreach ($files as $file) {
    $filepath = __DIR__ . '/' . $file;

    if (!file_exists($filepath)) {
        echo "SKIP: $file (fichier non trouvé)\n";
        continue;
    }

    $content = file_get_contents($filepath);
    $original = $content;

    // Correction pour les fichiers simples
    $content = str_replace($pattern_simple, $replacement_simple, $content);

    // Correction pour gangs/config.php
    $content = str_replace($pattern_gang1, $replacement_gang1, $content);

    if ($content !== $original) {
        file_put_contents($filepath, $content);
        echo "FIXED: $file\n";
        $fixed_count++;
    } else {
        echo "OK: $file (déjà corrigé ou pas de changement)\n";
    }
}

echo "\n=== Résumé ===\n";
echo "Fichiers corrigés: $fixed_count\n";
echo "Total fichiers vérifiés: " . count($files) . "\n";
echo "\nCompatibilité PHP 8.x appliquée avec succès !\n";
?>
