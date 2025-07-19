<?php
session_start();
if (!isset($_SESSION['isAuthenticated']) || $_SESSION['isAuthenticated'] !== true) {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="dashboard.css">
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

            </div>
          </mdc-dialog>
        </header>
      </li>
      <li class="navtitle">Books Store</li>
      <li><a href="./dashboard.php" class="active">Dashboard</a></li>
      <li><a href="./books.php">Book Management</a></li>
      <li><a href="./authors.php">Author Management</a></li>
      <li><a href="./publishers.php">Publisher Management</a></li>
    </ul>
  </section>
  <div class="mainContainer">
    <div class="dashboard">
      <h1 class="dashboard-title">Dashboard</h1>
      <div class="dashboard-content">
        <table>
          <thead>
            <tr>
              <th>Book ID</th>
              <th>Title</th>
              <th>Author</th>
              <th>Publisher</th>
              <th>Total Pages</th>
              <th>Publication Date</th>
              <th>Price</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $host = 'localhost';
            $username = 'root';
            $password = '';
            $database = 'bookstore';
            
            try {
                $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $username, $password);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                $query = "SELECT b.bookID, b.title, a.authorName, p.publisherName, b.totalPages, b.publicationDate, b.price 
                          FROM bookTable b
                          JOIN authorTable a ON b.authorID = a.authorID
                          JOIN publisherTable p ON b.publisherID = p.publisherID";
                $stmt = $pdo->query($query);
                
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['bookID']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['authorName']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['publisherName']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['totalPages']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['publicationDate']) . "</td>";
                    echo "<td>$" . number_format($row['price'], 2) . "</td>";
                    echo "</tr>";
                }
            } catch (PDOException $e) {
                echo "<tr><td colspan='7'>Error loading data: " . $e->getMessage() . "</td></tr>";
            }
            ?>
          </tbody>
        </table>
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