<?php
include '../../config/db.php';
session_start();

if (isset($_SESSION['user'])) {
    header("Location: ../../views/dashboard.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = trim($_POST['password']);

    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;
            header("Location: ../../views/dashboard.php");
            exit;
        } else {
            $_SESSION['error'] = "Invalid password.";
        }
    } else {
        $_SESSION['error'] = "Email not registered.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>MoneyTracker - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <div class="flex flex-col items-center justify-center h-screen">
        <h2 class="text-4xl font-bold mb-6">Hello, welcome!</h2>
        <form method="POST" action="login.php" class="bg-white p-6 rounded shadow-md w-80">
            <input type="email" name="email" placeholder="Email address" required class="border border-gray-300 mb-4 p-2 w-full">
            <input type="password" name="password" placeholder="Password" required class="border border-gray-300 mb-4 p-2 w-full">
            <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded w-full">Login</button>
            <p class="mt-4">Don't have an account? <a href="register.php" class="text-blue-600">Sign up</a></p>
        </form>
    </div>
</body>
</html>
