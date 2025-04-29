<?php

/**
 * CONFIGURATION FILE -- saves database credentials
 */

$host       = "localhost";
$username   = "root";
$password   = "";       //blank password on my DB
$dbname     = "fruitdb";   // Database name 
$dsn        = "mysql:host=$host;dbname=$dbname";
$options    = array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
);

?>
