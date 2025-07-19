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
    
    $bookID = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM bookTable WHERE bookID = ?");
    $stmt->execute([$bookID]);
    $book = $stmt->fetch();
    
    if (!$book) {
        die("Book not found");
    }
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = $_POST['title'];
        $authorID = $_POST['authorID'];
        $totalPages = $_POST['totalPages'];
        $publisherID = $_POST['publisherID'];
        $publicationDate = $_POST['publicationDate'];
        $price = $_POST['price'];
        
        $stmt = $pdo->prepare("UPDATE bookTable SET 
                              title = ?, 
                              authorID = ?, 
                              totalPages = ?, 
                              publisherID = ?, 
                              publicationDate = ?, 
                              price = ? 
                              WHERE bookID = ?");
        $stmt->execute([$title, $authorID, $totalPages, $publisherID, $publicationDate, $price, $bookID]);
        
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
    <title>Edit Book</title>
    <link rel="stylesheet" href="books.css">
</head>
<body>
    <section class="navbar">
    </section>
    
    <div class="mainContainer">
        <div class="dashboard">
            <h1 class="dashboard-title">Edit Book</h1>
            <div class="dashboard-content">
                <form method="POST" action="edit_book.php?id=<?= htmlspecialchars($bookID) ?>">
                    <div class="form-group">
                        <label for="bookID">Book ID:</label>
                        <input type="text" id="bookID" name="bookID" value="<?= htmlspecialchars($book['bookID']) ?>" readonly>
                    </div>
                    
                    <div class="form-group">
                        <label for="title">Title:</label>
                        <input type="text" id="title" name="title" value="<?= htmlspecialchars($book['title']) ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="authorID">Author:</label>
                        <select id="authorID" name="authorID" required>
                            <?php foreach ($authors as $author): ?>
                                <option value="<?= htmlspecialchars($author['authorID']) ?>" 
                                    <?= $author['authorID'] == $book['authorID'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($author['authorName']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="totalPages">Total Pages:</label>
                        <input type="number" id="totalPages" name="totalPages" value="<?= htmlspecialchars($book['totalPages']) ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="publisherID">Publisher:</label>
                        <select id="publisherID" name="publisherID" required>
                            <?php foreach ($publishers as $publisher): ?>
                                <option value="<?= htmlspecialchars($publisher['publisherID']) ?>" 
                                    <?= $publisher['publisherID'] == $book['publisherID'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($publisher['publisherName']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="publicationDate">Publication Date:</label>
                        <input type="date" id="publicationDate" name="publicationDate" value="<?= htmlspecialchars($book['publicationDate']) ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="price">Price:</label>
                        <input type="number" step="0.01" id="price" name="price" value="<?= htmlspecialchars($book['price']) ?>" required>
                    </div>
                    
                    <button type="submit">Update Book</button>
                    <a href="/CRUD System/navigations/books.php" class="cancel-btn">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>