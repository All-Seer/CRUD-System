<?php

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'bookstore';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $authorID = $_POST['authorID'];
        $authorName = $_POST['authorName'];
        $biography = $_POST['biography'];
        
        $stmt = $pdo->prepare("INSERT INTO authorTable (authorID, authorName, biography) VALUES (?, ?, ?)");
        $stmt->execute([$authorID, $authorName, $biography]);
        
        header("Location: /CRUD System/navigations/authors.php");
        exit();
    }
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Author</title>
    <link rel="stylesheet" href="authors.css">
</head>
<body>
    <div class="mainContainer">
        <div class="dashboard">
            <h1 class="dashboard-title">Add New Author</h1>
            <div class="dashboard-content">
                <form method="POST" action="add_author.php">
                    <div class="form-group">
                        <label for="authorID">Author ID:</label>
                        <input type="text" id="authorID" name="authorID" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="authorName">Author Name:</label>
                        <input type="text" id="authorName" name="authorName" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="biography">Biography:</label>
                        <textarea id="biography" name="biography" rows="5"></textarea>
                    </div>
                    
                    <button type="submit" class="submit-btn">Add Author</button>
                    <a href="/CRUD System/navigations/authors.php" class="cancel-btn">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>