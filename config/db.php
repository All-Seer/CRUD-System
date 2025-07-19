<?php 
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'bookstore';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
}
catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}