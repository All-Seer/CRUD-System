<?php

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'bookstore';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    

    $authors = $pdo->query("SELECT authorID, authorName FROM authorTable")->fetchAll();
    $publishers = $pdo->query("SELECT publisherID, publisherName FROM publisherTable")->fetchAll();
    

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $bookID = $_POST['bookID'];
        $title = $_POST['title'];
        $authorID = $_POST['authorID'];
        $totalPages = $_POST['totalPages'];
        $publisherID = $_POST['publisherID'];
        $publicationDate = $_POST['publicationDate'];
        $price = $_POST['price'];
        
        $stmt = $pdo->prepare("INSERT INTO bookTable (bookID, title, authorID, totalPages, publisherID, publicationDate, price) 
                              VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$bookID, $title, $authorID, $totalPages, $publisherID, $publicationDate, $price]);
        
        header("Location: /CRUD System/navigations/books.php");
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
    <title>Add New Book</title>
    <link rel="stylesheet" href="books.css">
    <!-- Include your CSS and JS files as in books.php -->
</head>
<body>
    <div class="mainContainer">
        <div class="dashboard">
            <h1 class="dashboard-title">Add New Book</h1>
            <div class="dashboard-content">
                <form method="POST" action="add_book.php">
                    <div class="form-group">
                        <label for="bookID">Book ID:</label>
                        <input type="text" id="bookID" name="bookID" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="title">Title:</label>
                        <input type="text" id="title" name="title" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="authorID">Author:</label>
                        <select id="authorID" name="authorID" required>
                            <?php foreach ($authors as $author): ?>
                                <option value="<?= htmlspecialchars($author['authorID']) ?>">
                                    <?= htmlspecialchars($author['authorName']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="totalPages">Total Pages:</label>
                        <input type="number" id="totalPages" name="totalPages" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="publisherID">Publisher:</label>
                        <select id="publisherID" name="publisherID" required>
                            <?php foreach ($publishers as $publisher): ?>
                                <option value="<?= htmlspecialchars($publisher['publisherID']) ?>">
                                    <?= htmlspecialchars($publisher['publisherName']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="publicationDate">Publication Date:</label>
                        <input type="date" id="publicationDate" name="publicationDate" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="price">Price:</label>
                        <input type="number" step="0.01" id="price" name="price" required>
                    </div>
                    
                    <button type="submit">Add Book</button>
                    <a href="/CRUD System/navigations/books.php" class="cancel-btn">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>