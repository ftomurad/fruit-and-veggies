<?php
/**
 * 1. Connect to MySQL
 * 2. Create DB if not exist …
 * 3. Reconnect with dbname
 * 4. Load data/init.sql
 */

require 'config.php';           // $host, $username, $password, $dbname, $dsn, $options

// 1. connect to the server without DB name
$connection = new PDO("mysql:host=$host", $username, $password, $options);
echo "Connected to MySQL.<br>";

// 2. create database if not exist
$connection->exec("CREATE DATABASE IF NOT EXISTS `$dbname`");
echo "Database '$dbname' ready.<br>";

// 3. connect again, this time with db name
$connection = new PDO($dsn, $username, $password, $options);
echo "Connected to '$dbname'.<br>";

// 4. run the SQL file
$sql = file_get_contents('data/init.sql');
if ($sql !== false) {
    $connection->exec($sql);
    echo "Tables created and sample data inserted.<br>";
} else {
    echo "init.sql could not be read. Check the path.";
}