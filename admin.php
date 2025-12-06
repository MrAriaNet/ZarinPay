<?php
session_start();

$admin_password = "Password_Strong";

if (isset($_SESSION['admin_logged']) && $_SESSION['admin_logged'] === true) {
    header("Location: dashboard.php");
    exit;
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';
    if ($password === $admin_password) {
        $_SESSION['admin_logged'] = true;
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Incorrect password";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
</head>
<body class="bg-light d-flex justify-content-center align-items-center vh-100">
<div class="card p-4 shadow col-md-4">
    <h4 class="text-center mb-4">Admin Login</h4>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="post">
        <label>Password:</label>
        <input type="password" name="password" class="form-control mb-3" required>
        <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>
</div>
</body>
</html>
