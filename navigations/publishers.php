<?php
session_start();
if (!isset($_SESSION['isAuthenticated'])) {
    header('Location: ../index.php');
    exit();
}
?>

<?php
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'bookstore';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    if (isset($_GET['delete'])) {
        $publisherID = $_GET['delete'];
        $stmt = $pdo->prepare("UPDATE publisherTable SET is_deleted = TRUE WHERE publisherID = ?");
        $stmt->execute([$publisherID]);
        header("Location: publishers.php?deleted=true");
        exit();
    }
    
    if (isset($_GET['restore'])) {
        $publisherID = $_GET['restore'];
        $stmt = $pdo->prepare("UPDATE publisherTable SET is_deleted = FALSE WHERE publisherID = ?");
        $stmt->execute([$publisherID]);
        header("Location: publishers.php?restored=true");
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
  <title>Publisher Management</title>
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
            <md-filled-text-field class="search-field" id="search-field" placeholder="Search"
              oninput="searchTable(this.value)">
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
      <li style="float:right;"><a href="/CRUD System/api/add_publisher.php">Add Publisher</a></li>
    </ul>
  </section>

  <!-- Delete Confirmation Dialog -->
  <md-dialog id="deleteDialog">
    <div slot="headline">Confirm deletion</div>
    <form slot="content" id="deleteForm" method="dialog">
      Are you sure you wish to delete this publisher? This action can be undone by restoring from the Recently Deleted section.
    </form>
    <div slot="actions">
      <md-text-button form="deleteForm" value="cancel">Cancel</md-text-button>
      <md-text-button form="deleteForm" value="delete" autofocus>Delete</md-text-button>
    </div>
  </md-dialog>
  
  <!-- Restore Confirmation Dialog -->
  <md-dialog id="restoreDialog">
    <div slot="headline">Confirm restoration</div>
    <form slot="content" id="restoreForm" method="dialog">
      Are you sure you wish to restore this publisher?
    </form>
    <div slot="actions">
      <md-text-button form="restoreForm" value="cancel">Cancel</md-text-button>
      <md-text-button form="restoreForm" value="restore" autofocus>Restore</md-text-button>
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
        Operation completed successfully
      </div>
    </div>
    <div slot="actions">
      <md-text-button onclick="document.getElementById('successDialog').close()">Close</md-text-button>
    </div>
  </md-dialog>

  <div class="mainContainer">
    <div class="dashboard">
      <h1 class="dashboard-title">Publishers</h1>
      
      <div class="dashboard-content">
        <div class="table-container">
          <table class="publisher-table">
            <thead>
              <tr>
                <th>Publisher ID</th>
                <th>Publisher Name</th>
                <th>Address</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $query = "SELECT * FROM publisherTable WHERE is_deleted = FALSE";
              $stmt = $pdo->query($query);
              
              while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                <tr>
                  <td><?= htmlspecialchars($row['publisherID']) ?></td>
                  <td><?= htmlspecialchars($row['publisherName']) ?></td>
                  <td><?= htmlspecialchars(substr($row['address'], 0, 50)) . (strlen($row['address']) > 50 ? '...' : '') ?></td>
                  <td class="action-buttons">
                    <a href="/CRUD System/api/edit_publisher.php?id=<?= urlencode($row['publisherID']) ?>" class="action-btn edit-btn">
                      <span class="material-symbols-outlined">edit</span>
                      Edit
                    </a>
                    <a href="#" onclick="confirmDelete('<?= urlencode($row['publisherID']) ?>')" class="action-btn delete-btn">
                      <span class="material-symbols-outlined">delete</span>
                      Delete
                    </a>
                  </td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>

        <div class="deleted-section">
          <h2><span class="material-symbols-outlined">delete</span> Recently Deleted Publishers</h2>
          <div class="table-container">
            <table class="deleted-publishers">
              <thead>
                <tr>
                  <th>Publisher ID</th>
                  <th>Publisher Name</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $query = "SELECT * FROM publisherTable WHERE is_deleted = TRUE ORDER BY id DESC LIMIT 5";
                $stmt = $pdo->query($query);
                
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                  <tr>
                    <td><?= htmlspecialchars($row['publisherID']) ?></td>
                    <td><?= htmlspecialchars($row['publisherName']) ?></td>
                    <td>
                      <a href="#" onclick="confirmRestore('<?= urlencode($row['publisherID']) ?>')" class="action-btn restore-btn">
                        <span class="material-symbols-outlined">restore</span>
                        Restore
                      </a>
                    </td>
                  </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <script>
    function searchTable(value) {
      value = value.toLowerCase();
      const rows = document.querySelectorAll('.publisher-table tbody tr');
      
      rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(value) ? '' : 'none';
      });
    }

    // Delete confirmation
    function confirmDelete(publisherID) {
      const dialog = document.getElementById('deleteDialog');
      dialog.addEventListener('close', () => {
        if (dialog.returnValue === 'delete') {
          window.location.href = `publishers.php?delete=${publisherID}`;
        }
      });
      dialog.show();
    }
    
    // Restore confirmation
    function confirmRestore(publisherID) {
      const dialog = document.getElementById('restoreDialog');
      dialog.addEventListener('close', () => {
        if (dialog.returnValue === 'restore') {
          window.location.href = `publishers.php?restore=${publisherID}`;
        }
      });
      dialog.show();
    }
    
    // Show success dialog if URL has success parameters
    document.addEventListener('DOMContentLoaded', () => {
      const urlParams = new URLSearchParams(window.location.search);
      if (urlParams.has('deleted') || urlParams.has('restored')) {
        document.getElementById('successDialog').show();
      }
    });
  </script>
</body>
</html>