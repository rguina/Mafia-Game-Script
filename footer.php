<?php
/**
 * FOOTER.PHP - Main Footer (version simplifiée)
 * Remplace le fichier encodé qui ne fonctionne pas avec PHP 8.x
 */
?>
<!-- Main Footer -->
<div class="main-footer">
    <div class="footer-content">
        <p>
            Powered by <a href="http://www.ravan.info/" target="_blank">Ravan Scripts</a> - Online Mafia Game
            <br>
            &copy; <?php echo date('Y'); ?> <?php echo isset($set['game_name']) ? $set['game_name'] : 'Mafia Game'; ?>
        </p>
    </div>
</div>
</body>
</html>
