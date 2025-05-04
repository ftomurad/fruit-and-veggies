<?php
/**
 * HOMEPAGE
 * -------------------------------------------------
 * 1. Header
 * 2. Gets current username from loginstatus
 * 3. Echo a welcome
 * 4. Text and image display
 * 5. Decorative image in the middle
 * 6. Footer
 */

require_once '../template/header.php';    // session_start amd navbar 
require_once '../loginstatus.php';        // for getUsername()

// capital first letter and rest lowercase
$username = ucfirst(strtolower(getUsername()));
?>