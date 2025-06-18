 <?php
session_start();
include_once '../config/db.php';

// =========================
// LOGIN USER
// =========================
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Ambil data user dari database
    $query = "SELECT * FROM users WHERE username = ?";
    $stmt  = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Cek apakah user ditemukan
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verifikasi password
        if (password_verify($password, $user['password'])) {
            // Simpan data ke sesi
            $_SESSION['user_id']  = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role']     = $user['role']; // jika ada

            header("Location: ../views/dashboard.php");
            exit;
        } else {
            $_SESSION['error'] = "Password salah.";
            header("Location: ../views/login.php");
            exit;
        }
    } else {
        $_SESSION['error'] = "Akun tidak ditemukan.";
        header("Location: ../views/login.php");
        exit;
    }
}

// =========================
// LOGOUT USER
// =========================
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: ../views/login.php");
    exit;
}

