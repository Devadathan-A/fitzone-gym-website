<?php
/**
 * FitZone Gym - Modular Footer Template
 * 
 * Demonstrates:
 * - Module 3: File inclusion & reusable PHP templates
 * - Dynamic content generation with PHP
 */
?>
    <footer>
        <h2>FIT<span>ZONE</span></h2>
        <p><?php echo GYM_NAME; ?> | <?php echo GYM_TAGLINE; ?></p>
        <p>
            <a href="#home">Back to Top</a> |
            <a href="mailto:fitzone@example.com">Email Us</a> |
            <a href="members_list.php">Member Directory</a> |
            <a href="setup_database.php">Database Setup</a>
        </p>
        <p class="copyright">
            &copy; <?php echo date("Y"); ?> <?php echo GYM_NAME; ?>. Web Technology Assignment (Module 3 PHP &amp; Module 4 MySQL Integration).
        </p>
    </footer>

    <script src="script.js"></script>
</body>
</html>

