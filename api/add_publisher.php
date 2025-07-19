<?php
// Database connection
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'bookstore';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $publisherID = $_POST['publisherID'];
        $publisherName = $_POST['publisherName'];
        $address = $_POST['address'];
        
        $stmt = $pdo->prepare("INSERT INTO publisherTable (publisherID, publisherName, address) VALUES (?, ?, ?)");
        $stmt->execute([$publisherID, $publisherName, $address]);
        
        header("Location: publishers.php?added=true");
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
    <title>Add New Publisher</title>
    <link rel="stylesheet" href="publishers.css">
    <!-- Material Design Web Components Import -->
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
  <script type="importmap">
    {
      "imports": {
        "@material/web/": "https://esm.run/@material/web/"
      }
    }
  </script>
  <script type="module">
    import '@material/web/all.js';
    import { styles as typescaleStyles } from '@material/web/typography/md-typescale-styles.js';

    document.adoptedStyleSheets.push(typescaleStyles.styleSheet);
  </script>

  <!-- Poppin Font Import -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
    rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

  <!-- Material Icons -->
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
</head>
<body>
    <section class="navbar">
        <ul>
            <li style="float:right;" class="search-bar">
                <header class="header">
                    <div class="search-container">
                        <md-filled-text-field class="search-field" placeholder="Search">
                            <md-icon slot="leading-icon">search</md-icon>
                        </md-filled-text-field>
                    </div>
                </header>
            </li>
            <li class="navtitle">Books Store</li>
            <li><a href="./dashboard.php">Dashboard</a></li>
            <li><a href="./books.php">Book Management</a></li>
            <li><a href="./authors.php">Author Management</a></li>
            <li><a href="./publishers.php" class="active">Publisher Management</a></li>
            <li style="float:right;"><a href="add_publisher.php">Add Publisher</a></li>
        </ul>
    </section>
    
    <div class="mainContainer">
        <div class="dashboard">
            <h1 class="dashboard-title">Add New Publisher</h1>
            <div class="form-container">
                <form method="POST" action="add_publisher.php">
                    <div class="form-group">
                        <label for="publisherID">Publisher ID:</label>
                        <md-outlined-text-field id="publisherID" name="publisherID" required>
                            <md-icon slot="leading-icon">badge</md-icon>
                        </md-outlined-text-field>
                    </div>
                    
                    <div class="form-group">
                        <label for="publisherName">Publisher Name:</label>
                        <md-outlined-text-field id="publisherName" name="publisherName" required>
                            <md-icon slot="leading-icon">business</md-icon>
                        </md-outlined-text-field>
                    </div>
                    
                    <div class="form-group">
                        <label for="address">Address:</label>
                        <md-outlined-text-field id="address" name="address" required>
                            <md-icon slot="leading-icon">location_on</md-icon>
                        </md-outlined-text-field>
                    </div>
                    
                    <div class="form-actions">
                        <md-filled-button type="submit" class="submit-btn">
                            <md-icon slot="icon">add</md-icon>
                            Add Publisher
                        </md-filled-button>
                        <md-outlined-button href="/3rd Year/CRUD System/navigations/publishers.php" class="cancel-btn">
                            Cancel
                        </md-outlined-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>