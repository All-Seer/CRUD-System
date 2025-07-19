<?php
// Database connection
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'bookstore';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get author details
    $authorID = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM authorTable WHERE authorID = ?");
    $stmt->execute([$authorID]);
    $author = $stmt->fetch();
    
    if (!$author) {
        die("Author not found");
    }
    
    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $authorName = $_POST['authorName'];
        $biography = $_POST['biography'];
        
        $stmt = $pdo->prepare("UPDATE authorTable SET 
                              authorName = ?, 
                              biography = ? 
                              WHERE authorID = ?");
        $stmt->execute([$authorName, $biography, $authorID]);
        
        header("Location: authors.php");
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
    <title>Edit Author</title>
    <link rel="stylesheet" href="authors.css">
</head>
<body>
    
    <div class="mainContainer">
        <div class="dashboard">
            <h1 class="dashboard-title">Edit Author</h1>
            <div class="dashboard-content">
                <form method="POST" action="edit_author.php?id=<?= htmlspecialchars($authorID) ?>">
                    <div class="form-group">
                        <label for="authorID">Author ID:</label>
                        <input type="text" id="authorID" name="authorID" value="<?= htmlspecialchars($author['authorID']) ?>" readonly>
                    </div>
                    
                    <div class="form-group">
                        <label for="authorName">Author Name:</label>
                        <input type="text" id="authorName" name="authorName" value="<?= htmlspecialchars($author['authorName']) ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="biography">Biography:</label>
                        <textarea id="biography" name="biography" rows="5"><?= htmlspecialchars($author['biography']) ?></textarea>
                    </div>
                    
                    <button type="submit" class="submit-btn">Update Author</button>
                    <a href="/3rd Year/CRUD System/navigations/authors.php" class="cancel-btn">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>