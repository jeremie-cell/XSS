<?php
declare(strict_types=1);

session_start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $error = 'Please enter your email and password.';
    } else {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        try {
            $conn = new mysqli(
                'localhost',
                'root',
                '',
                'databasexss'
            );

            $conn->set_charset('utf8mb4');

            $stmt = $conn->prepare(
                'SELECT id, email, password
                 FROM client
                 WHERE email = ?
                 LIMIT 1'
            );

            $stmt->bind_param('s', $email);
            $stmt->execute();

            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            if (
                !$user ||
                !password_verify($password, $user['password'])
            ) {
                $error = 'Invalid email or password.';
            } else {
                session_regenerate_id(true);

                $_SESSION['user_id'] = (int) $user['id'];
                $_SESSION['email'] = $user['email'];

                $stmt->close();
                $conn->close();

                header('Location: securedxss.html');
                exit;
            }

            $stmt->close();
            $conn->close();

        } catch (mysqli_sql_exception $e) {
            error_log('Login database error: ' . $e->getMessage());

            http_response_code(500);
            $error = 'Database error. Check the PHP error log.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
<style>
body {
    font-family: Arial;
    background: #eee;
    margin: 0;
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
    box-sizing: border-box;
}
.card {
    width: 100%;
    max-width: 400px;
    padding: 20px;
    background: white;
    border: 1px solid #ccc;
    box-sizing: border-box;
}
h1 {
    text-align: center;
}
label {
    display: block;
    margin-top: 12px;
}
input {
    width: 100%;
    padding: 8px;
    margin-top: 5px;
    box-sizing: border-box;
}
button {
    width: 100%;
    margin-top: 15px;
    padding: 10px;
    background: #2868b2;
    color: white;
    border: none;
    cursor: pointer;
}
a {
    color: #2868b2;
}
.error-message {
    padding: 10px;
    margin-bottom: 10px;
    color: #a00;
    background: #fee;
    border: 1px solid #d99;
}
.message {
    padding: 10px;
    margin-bottom: 10px;
    color: green;
    background: #eef9f0;
    border: 1px solid #9ac9a5;
}
</style>

    <title>Login</title>
</head>
<body>

   <main class="login-card">

        <div class="logo" aria-hidden="true">XSS</div>

        <h1>Welcome Back</h1>

        <p class="subtitle">
            Sign in to your account to continue.
        </p>

        <?php if ($error !== ''): ?>
            <p class="error-message" role="alert">
                <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
            </p>
        <?php endif; ?>

        <div class="card">
<form method="POST" action="login.php">

            <div class="form-group">
                <label for="email">Email Address</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="you@example.com"
                    maxlength="255"
                    autocomplete="username"
                    required
                >
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Enter your password"
                    autocomplete="current-password"
                    required
                >
            </div>

            <button type="submit" class="login-button">
                Sign In
            </button>

        </form>
</div>
    <a href="register.php" class="login-link">
        Don't have an account yet? Register
    </a>

        <p class="footer">
            Secure Portal Account Authentication
        </p>


    </main>

</body>
</html>