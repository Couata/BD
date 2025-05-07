<?php
$host = 'db';
$dbname = 'group7';
$username = 'group7';
$password = 'agent007';
$charset = 'utf8';

$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

$maxRetries = 20;
$retryCount = 0;

while ($retryCount < $maxRetries) {
    try {
        $pdo = new PDO($dsn, $username, $password, $options);
        break; 
    } catch (PDOException $e) {
        $retryCount++;
        if ($retryCount >= $maxRetries) {
            echo "Connection failed after $maxRetries attempts: " . $e->getMessage();
            exit();
        }
        sleep(2); 
    }
}
?>
