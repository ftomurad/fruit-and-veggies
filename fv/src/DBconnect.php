<?php
/**
 * DATABASE CONNECTION
 */

require_once '../config.php';   // $dsn, $username, $password, $options

try {
    $connection = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    echo "Connection error: " . $e->getMessage();
    exit;
}