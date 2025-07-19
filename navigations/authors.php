<?php
// Database connection
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'bookstore';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Handle soft delete
    if (isset($_GET['delete'])) {
        $authorID = $_GET['delete'];
        $stmt = $pdo->prepare("UPDATE authorTable SET is_deleted = TRUE WHERE authorID = ?");
        $stmt->execute([$authorID]);
        header("Location: authors.php?deleted=true");
        exit();
    }
    
    // Handle restore
    if (isset($_GET['restore'])) {
        $authorID = $_GET['restore'];
        $stmt = $pdo->prepare("UPDATE authorTable SET is_deleted = FALSE WHERE authorID = ?");
        $stmt->execute([$authorID]);
        header("Location: authors.php?restored=true");
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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Author Management</title>
  <link rel="stylesheet" href="authors.css">
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
            <md-filled-text-field class="search-field" id="search-field" placeholder="Search"
              oninput="searchTable(this.value)">
              <md-icon slot="leading-icon">search</md-icon>
            </md-filled-text-field>
          </div>

          <mdc-dialog id="search-view">
            <div class="search-results">
              <!-- Dynamic search results will be displayed here -->
            </div>
          </mdc-dialog>
        </header>
      </li>
      <li class="navtitle">Books Store</li>
      <li><a href="./dashboard.php">Dashboard</a></li>
      <li><a href="./books.php">Book Management</a></li>
      <li><a href="./authors.php" class="active">Author Management</a></li>
      <li><a href="./publishers.php">Publisher Management</a></li>
      <li style="float:right;"><a href="/3rd Year/CRUD System/api/add_author.php">Add Author</a></li>
    </ul>
  </section>
  <div class="mainContainer">
    <div class="dashboard">
      <h1 class="dashboard-title">Authors</h1>
      
      <?php if (isset($_GET['deleted'])): ?>
        <div class="alert alert-success">Author marked as deleted successfully!</div>
      <?php endif; ?>
      
      <?php if (isset($_GET['restored'])): ?>
        <div class="alert alert-success">Author restored successfully!</div>
      <?php endif; ?>

      <div class="dashboard-content">
        <div class="table-container">
          <table class="author-table">
            <thead>
              <tr>
                <th>Author ID</th>
                <th>Author Name</th>
                <th>Biography</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $query = "SELECT * FROM authorTable WHERE is_deleted = FALSE";
              $stmt = $pdo->query($query);
              
              while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                <tr>
                  <td><?= htmlspecialchars($row['authorID']) ?></td>
                  <td><?= htmlspecialchars($row['authorName']) ?></td>
                  <td><?= htmlspecialchars(substr($row['biography'], 0, 100)) . (strlen($row['biography']) > 100 ? '...' : '') ?></td>
                  <td class="action-buttons">
                    <a href="/3rd Year/CRUD System/api/edit_author.php?id=<?= urlencode($row['authorID']) ?>" class="edit-btn">Edit</a>
                    <a href="authors.php?delete=<?= urlencode($row['authorID']) ?>" 
                      class="delete-btn"
                      onclick="return confirm('Mark this author as deleted?')">Delete</a>
                  </td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
        
        <!-- Deleted Authors Section -->
        <div class="deleted-section"> 
        <h2><span class="material-symbols-outlined">delete</span> Recently Deleted Authors</h2>
          <table class="deleted-authors">
            <thead>
              <tr>
                <th>Author ID</th>
                <th>Author Name</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $query = "SELECT * FROM authorTable WHERE is_deleted = TRUE ORDER BY id DESC LIMIT 5";
              $stmt = $pdo->query($query);
              
              while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                <tr>
                  <td><?= htmlspecialchars($row['authorID']) ?></td>
                  <td><?= htmlspecialchars($row['authorName']) ?></td>
                  <td>
                    <a href="authors.php?restore=<?= urlencode($row['authorID']) ?>" 
                       class="restore-btn"
                       onclick="return confirm('Restore this author?')">Restore</a>
                  </td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  
  <script>
    function searchTable(value) {
      value = value.toLowerCase();
      const rows = document.querySelectorAll('tbody tr');
      
      rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(value) ? '' : 'none';
      });
    }
  </script>
</body>
</html>