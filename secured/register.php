<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Register</title>

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

.register-card {
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

button[type="submit"] {
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

button[type="submit"]:hover {
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

.message.success {
    color: #a7f3d0;
    background: rgba(16, 185, 129, 0.1);
    border: 1px solid rgba(52, 211, 153, 0.35);
}

.message.error {
    color: #fecaca;
    background: rgba(239, 68, 68, 0.1);
    border: 1px solid rgba(248, 113, 113, 0.35);
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
        align-items: flex-start;
    }

    .register-card {
        padding: 28px 22px;
        margin: auto 0;
    }

    h1 {
        font-size: 25px;
    }
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


        <form action="register.php" method="POST">

            <div class="form-group">
                <label for="email">Email Address</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="Enter your email"
                    autocomplete="email"
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

        <p class="footer">Secure Registration Portal</p>
</div>
</body>
</html>

<?php
require "db.php";

if (isset($_POST["register"])) {

   $email = trim($_POST["email"]);
   $password = $_POST["password"];
   $confirm_password = $_POST["confirm_password"];

   if (empty($email) || empty($password) || empty($confirm_password)) {
       die("All fields are required.");
   }

   if ($password !== $confirm_password) {
       die("Passwords do not match.");
   }

   $check = $conn->prepare("SELECT id FROM client WHERE email = ?");
   $check->bind_param("s", $email);
   $check->execute();
   $result = $check->get_result();

   if ($result->num_rows > 0) {
       die("Email already exists.");
   }

   $hashed_password = password_hash($password, PASSWORD_DEFAULT);

   $stmt = $conn->prepare("INSERT INTO client (email, password) VALUES (?, ?)");
   $stmt->bind_param("ss", $email, $hashed_password);

   if ($stmt->execute()) {
       echo "Registration successful!";
   } else {
       echo "Error: " . $stmt->error;
   }

   $stmt->close();
   $check->close();
   $conn->close();
}
?>