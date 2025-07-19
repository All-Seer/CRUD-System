<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Validate credentials
    if (empty($username) || empty($password)) {
        header('Location: index.php?error=1');
        exit();
    }

    // Check credentials (in a real app, use database and password hashing)
    if ($username === 'CSDL_ADMIN' && $password === 'CSDL_ADMIN') {
        $_SESSION['isAuthenticated'] = true;
        header('Location: /3rd Year/CRUD System/navigations/dashboard.php');
        exit();
    }
}

// If authentication fails or wrong method
header('Location: index.php?error=1');
exit();
?>