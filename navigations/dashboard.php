<?php
session_start();
if (!isset($_SESSION['isAuthenticated'])) {
    header('Location: ../index.php');
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
      <li style="float:right;"><a href="#" onclick="confirmSetup()">Generate Database</a></li>
      <li class="navtitle">Books Store</li>
      <li><a href="./dashboard.php" class="active">Dashboard</a></li>
      <li><a href="./books.php">Book Management</a></li>
      <li><a href="./authors.php">Author Management</a></li>
      <li><a href="./publishers.php">Publisher Management</a></li>
    </ul>
  </section>
  
  <!-- Database Setup Confirmation Dialog -->
  <md-dialog id="setupDialog">
    <div slot="headline">Confirm Database Setup</div>
    <form slot="content" id="setupForm" method="dialog">
      This will create or reset the bookstore database with all required tables. Are you sure you want to proceed?
    </form>
    <div slot="actions">
      <md-text-button form="setupForm" value="cancel">Cancel</md-text-button>
      <md-text-button form="setupForm" value="setup" autofocus>Setup Database</md-text-button>
    </div>
  </md-dialog>
  
  <!-- Success Notification Dialog -->
  <md-dialog id="successDialog">
    <div slot="headline" class="containerImg">
      <span class="material-symbols-outlined" style="color: green; font-size: 48px;">check_circle</span>
    </div>
    <div slot="headline" class="dialogHead" style="text-align: center;">
      Success
    </div>
    <div slot="content">
      <div class="dialogContent" style="text-align: center;">
        Database setup completed successfully.
      </div>
    </div>
    <div slot="actions">
      <md-text-button onclick="document.getElementById('successDialog').close()">Close</md-text-button>
    </div>
  </md-dialog>
  
  <!-- Error Notification Dialog -->
  <md-dialog id="errorDialog">
    <div slot="headline" class="containerImg">
      <span class="material-symbols-outlined" style="color: red; font-size: 48px;">error</span>
    </div>
    <div slot="headline" class="dialogHead" style="text-align: center;">
      Error
    </div>
    <div slot="content">
      <div class="dialogContent" style="text-align: center;" id="errorMessage">
        Database setup failed.
      </div>
    </div>
    <div slot="actions">
      <md-text-button onclick="document.getElementById('errorDialog').close()">Close</md-text-button>
    </div>
  </md-dialog>

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
  <div class="logout-fab-container">
    <md-fab class="logout-fab" label="Logout" aria-label="Logout" onclick="logout()">
      <md-icon slot="icon">logout</md-icon>
    </md-fab>
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
    
    function logout() {
      fetch('/CRUD System/logout.php')
        .then(response => {
          if (response.ok) {
            window.location.href = '../index.php';
          }
        })
        .catch(error => console.error('Error:', error));
    }
    
    function confirmSetup() {
      const dialog = document.getElementById('setupDialog');
      dialog.addEventListener('close', () => {
        if (dialog.returnValue === 'setup') {
          setupDatabase();
        }
      });
      dialog.show();
    }
    
    function setupDatabase() {
      fetch('/CRUD System/api/setup_database.php')
        .then(response => {
          if (response.ok) {
            return response.text();
          }
          throw new Error('Network response was not ok.');
        })
        .then(text => {
          document.getElementById('successDialog').show();
          // Reload the page after a short delay to show updated data
          setTimeout(() => window.location.reload(), 2000);
        })
        .catch(error => {
          document.getElementById('errorMessage').textContent = error.message;
          document.getElementById('errorDialog').show();
        });
    }
  </script>
</body>
</html>