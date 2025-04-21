<?php

// Cleaner conenction to Database with some error handling
// file used as include 

// Database configuration
$host = 'db';
$dbname = 'group7';
$username = 'group7';
$password = 'agent007';
$charset = 'utf8';

// Data Source Name (DSN)
$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

// Options for PDO
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    // Create a new Conenction to Database
    $bdd = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit();
}
?>
