<?php
// Menghubungkan ke database
include_once(__DIR__ . '/../config/db.php');

// Cek jika form dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Enkripsi password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Query insert data
    $query = "INSERT INTO users (nama, email, password) VALUES ('$nama', '$email', '$hashedPassword')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo "<script>alert('Registrasi berhasil! Silakan login.'); window.location.href='login.php';</script>";
    } else {
        echo "<script>alert('Registrasi gagal: " . mysqli_error($conn) . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Register</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-br from-blue-100 to-indigo-100 min-h-screen flex items-center justify-center px-4">
  <div class="flex flex-col md:flex-row bg-white shadow-2xl rounded-3xl overflow-hidden max-w-4xl w-full">
    
    <div class="md:w-1/2 flex">
    <img src="../assets/2.jpg" alt="Register Image" class="object-cover w-full h-full">


    </div>

    <div class="md:w-1/2 p-10">
      <h2 class="text-3xl font-bold text-gray-800 text-center mb-6">Create Account</h2>

      <?php if (isset($_SESSION['error'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
          <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
      <?php endif; ?>

      <form method="POST" action="register.php" class="space-y-5">
        <div>
          <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
          <input type="text" name="nama" id="nama" required
                 class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>

        <div>
          <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
          <input type="email" name="email" id="email" required
                 class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>

        <div>
          <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
          <input type="password" name="password" id="password" required
                 class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>
        
        <div>
          <label for="nomor" class="block text-sm font-medium text-gray-700">Phone Number</label>
          <input type="text" name="nomor" id="nomor" required
                 class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-400 focus:outline-none">
        </div>

        <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md shadow-md transition duration-300">
          Register
        </button>
      </form>

      <p class="mt-6 text-center text-sm text-gray-600">
        Already have an account?
        <a href="login.php" class="text-blue-600 hover:underline font-medium">Log in</a>
      </p>
    </div>
  </div>
</body>
</html>
