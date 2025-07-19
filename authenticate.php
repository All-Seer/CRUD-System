<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        header('Location: index.php?error=1');
        exit();
    }

    if ($username === 'CSDL_ADMIN' && $password === 'CSDL_ADMIN') {
        $_SESSION['isAuthenticated'] = true;
        header('Location: /CRUD System/navigations/dashboard.php');
        exit();
    }
}

header('Location: index.php?error=1');
exit();
?>