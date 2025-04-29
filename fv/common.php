<?php

// Sanitization
// --------------------------------

function escape($data) {
    // convert special characters to HTML entities
    $data = htmlspecialchars($data, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
    // trim whitespace at beginning and end
    $data = trim($data);
    // remove backslashes
    $data = stripslashes($data);

    return $data;
}

?>