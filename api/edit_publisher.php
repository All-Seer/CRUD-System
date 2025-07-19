<?php
// Database connection
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'bookstore';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get publisher details
    $publisherID = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM publisherTable WHERE publisherID = ?");
    $stmt->execute([$publisherID]);
    $publisher = $stmt->fetch();
    
    if (!$publisher) {
        die("Publisher not found");
    }
    
    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $publisherName = $_POST['publisherName'];
        $address = $_POST['address'];
        
        $stmt = $pdo->prepare("UPDATE publisherTable SET 
                              publisherName = ?, 
                              address = ? 
                              WHERE publisherID = ?");
        $stmt->execute([$publisherName, $address, $publisherID]);
        
        header("Location: /3rd Year/CRUD System/navigations/publishers.php");
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
    <title>Edit Publisher</title>
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

    
    <div class="mainContainer">
        <div class="dashboard">
            <h1 class="dashboard-title">Edit Publisher</h1>
            <div class="form-container">
                <form method="POST" action="edit_publisher.php?id=<?= htmlspecialchars($publisherID) ?>">
                    <div class="form-group">
                        <label for="publisherID">Publisher ID:</label>
                        <md-outlined-text-field id="publisherID" name="publisherID" 
                            value="<?= htmlspecialchars($publisher['publisherID']) ?>" readonly>
                            <md-icon slot="leading-icon">badge</md-icon>
                        </md-outlined-text-field>
                    </div>
                    
                    <div class="form-group">
                        <label for="publisherName">Publisher Name:</label>
                        <md-outlined-text-field id="publisherName" name="publisherName" 
                            value="<?= htmlspecialchars($publisher['publisherName']) ?>" required>
                            <md-icon slot="leading-icon">business</md-icon>
                        </md-outlined-text-field>
                    </div>
                    
                    <div class="form-group">
                        <label for="address">Address:</label>
                        <md-outlined-text-field id="address" name="address" 
                            value="<?= htmlspecialchars($publisher['address']) ?>" required>
                            <md-icon slot="leading-icon">location_on</md-icon>
                        </md-outlined-text-field>
                    </div>
                    
                    <div class="form-actions">
                        <md-filled-button type="submit" class="submit-btn">
                            <md-icon slot="icon">save</md-icon>
                            Update Publisher
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