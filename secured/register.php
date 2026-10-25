<?php
require "db.php";

$message = "";
$messageType = "message";

if (isset($_POST["register"])) {
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirm_password = $_POST["confirm_password"] ?? "";

    if (empty($email) || empty($password) || empty($confirm_password)) {
        $message = "All fields are required.";
        $messageType = "error-message";
    } elseif ($password !== $confirm_password) {
        $message = "Passwords do not match.";
        $messageType = "error-message";
    } else {
        $check = $conn->prepare("SELECT id FROM client WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            // Keep the duplicate-email message visible on the registration page.
            $message = "Email already exists.";
            $messageType = "error-message";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO client (email, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $email, $hashed_password);

            if ($stmt->execute()) {
                $message = "Registration successful! You can now sign in.";
                $messageType = "message";
            } else {
                $message = "Sorry, registration could not be completed. Please try again.";
                $messageType = "error-message";
            }

            $stmt->close();
        }

        $check->close();
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Register</title>


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


</head>
<body>
   <div class="register-card">

        <div class="logo">XSS</div>

    <h1>Create Account</h1>

    <p class="subtitle">
        Register your account to get started.
    </p>


        <?php if (!empty($message)): ?>
        <p class="<?php echo $messageType; ?>" role="alert"><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
    <?php endif; ?>

    <div class="card">
<form action="register.php" method="POST">

            <div class="form-group">
                <label for="email">Email Address</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="Enter your email"
                    autocomplete="email"
                    value="<?php echo htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Create a password"
                    autocomplete="new-password"
                    required
                >
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input
                    type="password"
                    id="confirm_password"
                    name="confirm_password"
                    placeholder="Re-enter your password"
                    autocomplete="new-password"
                    required
                >
            </div>

            <button type="submit" name="register">
                Create Account
            </button>
            <a href="login.php" class="login-link">
        Already have an account? Sign in
    </a>

        </form>
</div>

        <p class="footer">Secure Registration Portal</p>
</div>
</body>
</html>

