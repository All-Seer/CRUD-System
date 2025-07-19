<?php 
$host = 'localhost';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("CREATE DATABASE IF NOT EXISTS bookstore");
    $pdo->exec("USE bookstore");

    $pdo->exec("CREATE TABLE IF NOT EXISTS authorTable (
        id INT AUTO_INCREMENT PRIMARY KEY,
        authorID VARCHAR(255) NOT NULL UNIQUE,
        authorName VARCHAR(255) NOT NULL,
        biography TEXT
    )");
    
    $pdo->exec("CREATE TABLE IF NOT EXISTS publisherTable (
        id INT AUTO_INCREMENT PRIMARY KEY,
        publisherID VARCHAR(255) NOT NULL UNIQUE,
        publisherName VARCHAR(255) NOT NULL,
        address TEXT
    )");
    
    $pdo->exec("CREATE TABLE IF NOT EXISTS bookTable (
        id INT AUTO_INCREMENT PRIMARY KEY,
        bookID VARCHAR(255) NOT NULL,
        title VARCHAR(255) NOT NULL,
        authorID VARCHAR(255) NOT NULL,
        totalPages INT NOT NULL,
        publisherID VARCHAR(255) NOT NULL,
        publicationDate DATE,
        price DECIMAL(10, 2),
        is_deleted BOOLEAN DEFAULT FALSE,
        FOREIGN KEY (authorID) REFERENCES authorTable(authorID),
        FOREIGN KEY (publisherID) REFERENCES publisherTable(publisherID)
    )");
    echo "Database setup completed successfully.";
} catch (PDOException $e) {
    die("Database setup failed: " . $e->getMessage());
}