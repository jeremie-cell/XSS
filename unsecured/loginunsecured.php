 <?php
    $loginMessage = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $conn = mysqli_connect("localhost", "root", "", "unsecured");

        if (!$conn) {
            die("Database connection failed.");
        }

        $email = $_POST['email'] ?? '';
        $Password = $_POST['Password'] ?? '';

        $sql = "SELECT * FROM unsecuredtable WHERE email='$email' AND Password='$Password'";

        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            header("Location: unsecuredxss.html");
        } else {
             $loginMessage = "Login Failed";
        }

        mysqli_close($conn);
    }
    ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unsecured Login</title>

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
        .error-message {
            color: #fecaca;
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(248, 113, 113, 0.35);
            border-radius: 9px;
            padding: 12px;
            margin-bottom: 20px;
            text-align: center;
            font-size: 13px;
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
            border: 1px solid #8050ff;
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
            color: #b69aff;
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

</head>
<body>

    <main class="login-card">

        <div class="logo">XSS</div>

        <h1>Welcome Back</h1>

        <p class="subtitle">
            Sign in to your account to continue.
        </p>
        <?php if ($loginMessage !== ''): ?>
    <p class="error-message">
        <?= htmlspecialchars($loginMessage, ENT_QUOTES, 'UTF-8') ?>
    </p>
<?php endif; ?>

        <form method="POST" action="loginunsecured.php">

            <div class="form-group">
                <label for="email">Email Address</label>
                <input
                    type="text"
                    id="email"
                    name="email"
                    placeholder="Enter your email"
                    required
                >
            </div>

            <div class="form-group">
                <label for="Password">Password</label>
                <input
                    type="text"
                    id="Password"
                    name="Password"
                    placeholder="Enter your password"
                    required
                >
            </div>

            <button type="submit" class="login-button">
                Sign In
            </button>

        </form>

        <p class="footer">Unsecured Testing Portal</p>

    </main>
    
</body>
</html>