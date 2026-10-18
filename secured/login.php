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
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: "Segoe UI", Arial, sans-serif;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 30px 16px;
            color: #f8fafc;
            background-color: #090b1a;
            background-image: radial-gradient(
                rgba(59, 131, 246, 0.432) 1px,
                transparent 1px
            );
            background-size: 26px 26px;
        }

        .login-card {
            width: 100%;
            max-width: 440px;
            padding: 36px;
            background: rgba(17, 27, 48, 0.70);
            border: 1px solid #303454;
            border-radius: 22px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.35);
        }

        .logo {
            width: 56px;
            height: 56px;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto 22px;
            border-radius: 15px;
            background: linear-gradient(135deg, #3765ff, #008cff);
            box-shadow: 0 8px 24px rgba(112, 0, 255, 0.25);
            font-size: 26px;
            font-weight: 800;
            color: white;
        }

        h1 {
            margin-bottom: 10px;
            text-align: center;
            font-size: 28px;
            font-weight: 750;
            letter-spacing: -0.6px;
        }

        .subtitle {
            margin-bottom: 30px;
            text-align: center;
            color: #a1a1b5;
            font-size: 14px;
            line-height: 1.6;
        }

        .form-group {
            margin-bottom: 21px;
        }

        label {
            display: block;
            margin-bottom: 9px;
            color: #dedeea;
            font-size: 13px;
            font-weight: 600;
        }

        input {
            display: block;
            width: 100%;
            height: 46px;
            padding: 0 14px;
            border: 1px solid #393b53;
            border-radius: 9px;
            outline: none;
            background: #0c0d1d;
            color: #ffffff;
            font-size: 14px;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        input::placeholder {
            color: #77788c;
        }

        input:focus {
            border-color: #008cff;
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.15);
        }

        .login-button {
            display: block;
            width: 100%;
            min-height: 46px;
            margin-top: 7px;
            border: 1px solid #008cff;
            border-radius: 9px;
            background: linear-gradient(110deg, #3765ff, #008cff);
            color: white;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: transform 0.2s, filter 0.2s;
        }

        .login-button:hover {
            filter: brightness(1.15);
            transform: translateY(-1px);
        }
        .login-link {
            display: block;
            margin-top: 16px;
            text-align: center;
            color: #008cff;
            font-size: 14px;
            text-decoration: none;
        }

        .login-link:hover {
            color: #ffffff;
            text-decoration: underline;
        }
        .message {
            padding: 12px 14px;
            margin-bottom: 22px;
            border-radius: 9px;
            font-size: 13px;
            line-height: 1.5;
            overflow-wrap: anywhere;
        }

        .message.error {
            color: #fecaca;
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(248, 113, 113, 0.35);
        }

        .login-link {
            display: block;
            margin-top: 16px;
            text-align: center;
            color: #008cff;
            font-size: 14px;
            text-decoration: none;
        }

        .login-link:hover {
            color: white;
            text-decoration: underline;
        }

        .footer {
            margin-top: 28px;
            text-align: center;
            color: #77788c;
            font-size: 12px;
        }

        @media (max-width: 480px) {
            body {
                padding: 20px 14px;
            }

            .login-card {
                padding: 28px 22px;
            }

            h1 {
                font-size: 25px;
            }
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
                Sign In &rarr;
            </button>

        </form>
    <a href="register.php" class="login-link">
        Don't have an account yet? Register
    </a>

        <p class="footer">
            Secure Portal &bull; Account Authentication
        </p>


    </main>

</body>
</html>