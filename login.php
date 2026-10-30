
<?php

session_start();

require_once "config/database.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    if (empty($username) || empty($password)) {

        $error = "Username dan password wajib diisi!";

    } else {

        $query = mysqli_prepare(
            $conn,
            "SELECT * FROM users WHERE username = ?"
        );

        mysqli_stmt_bind_param($query, "s", $username);
        mysqli_stmt_execute($query);

        $result = mysqli_stmt_get_result($query);
        $user = mysqli_fetch_assoc($result);

        if ($user && password_verify($password, $user["password"])) {

            session_regenerate_id(true);

            $_SESSION["user_id"] = $user["id"];
            $_SESSION["username"] = $user["username"];
            $_SESSION["bahasa"] = "id";

            header("Location: dashboard.php");
            exit;

        } else {

            $error = "Username atau password salah!";

        }
    }
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - LaporWarga</title>

    <link rel="stylesheet" href="assets/css/login.css">
</head>
<body>

    <div class="login-container">
        <div class="login-card">

            <div class="login-header">
                <div class="login-icon">📋</div>

                <h2>Login Admin</h2>

                <p>
                    LaporWarga<br>
                    Sistem Informasi Manajemen Data Pengaduan Masyarakat
                </p>
            </div>

            <?php if (isset($error) && $error != ""): ?>
                <div class="login-error">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">

                <div class="form-group">
                    <label for="username">Username</label>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        placeholder="Masukkan username"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Masukkan password"
                        required
                    >
                </div>

                <button type="submit" class="btn-login">
                    Login
                </button>

            </form>

            <div class="login-footer">
                &copy; 2026 LaporWarga. All Rights Reserved.
            </div>

        </div>
    </div>

</body>
</html>
