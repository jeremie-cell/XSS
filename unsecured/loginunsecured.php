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
body {
    font-family: Arial;
    background: #eee;
    margin: 0;
    padding: 20px;
}
.card {
    max-width: 400px;
    margin: 30px auto;
    padding: 20px;
    background: white;
    border: 1px solid #ccc;
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
    color: #216e35;
    background: #eef9f0;
    border: 1px solid #9ac9a5;
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