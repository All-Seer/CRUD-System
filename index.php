<?php
session_start();

session_unset();

$error = isset($_GET['error']) ? 'Invalid username or password' : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Bookstore Admin</title>
  <link rel="stylesheet" href="index.css">
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
        import {
            styles as typescaleStyles
        } from '@material/web/typography/md-typescale-styles.js';

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
  <div class="parentContainer">
    <div class="leftContainer">
      <p class="welcome">Welcome Back</p>
      <p class="loginmess">To gain immediate access, please use your administrator privileges.</p>
      
    </div>
    <div class="rightContainer">
      <form method="POST" action="authenticate.php">
        <p class="login">Admin Panel</p>
        <md-outlined-text-field label="Username" name="username" placeholder="CSDL_ADMIN" required></md-outlined-text-field>
        <md-outlined-text-field label="Password" name="password" type="password" placeholder="CSDL_ADMIN" required></md-outlined-text-field>
        <md-filled-button type="submit">Login</md-filled-button>

        <?php if (isset($_GET['error'])): ?>
        <div class="error-message">
          <span class="material-symbols-outlined">error</span>
          <?php echo htmlspecialchars($error); ?>
        <?php endif; ?> 
      
      </form>
    </div>
  </div>
</body>
</html>