<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Our Website</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        line-height: 1.6;
        margin: 0;
        padding: 20px;
        background-color: #f4f4f4;
    }

    .container {
        max-width: 800px;
        margin: 0 auto;
        background-color: #fff;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    h1 {
        color: #333;
        text-align: center;
    }

    .auth-links {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin-top: 30px;
    }

    .auth-links a {
        display: inline-block;
        padding: 10px 20px;
        background-color: #007bff;
        color: #fff;
        text-decoration: none;
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }

    .auth-links a:hover {
        background-color: #0056b3;
    }
    </style>
</head>

<body>
    <div class="container">
        <h1>Welcome to Our Website</h1>

        <?php
        session_start();
        if (isset($_SESSION['user_id'])) {
            echo "<p>Hello, " . htmlspecialchars($_SESSION['username']) . "! You are logged in.</p>";
            echo "<p><a href='logout.php'>Logout</a></p>";
        } else {
            echo "<p>Please log in or register to access our services.</p>";
            echo "<div class='auth-links'>";
            echo "<a href='login.php'>Login</a>";
            echo "<a href='register.php'>Register</a>";
            echo "</div>";
        }
        ?>

        <p>This is a simple landing page for our website. Here you can find links to our authentication system and other
            important pages.</p>

        <h2>Features:</h2>
        <ul>
            <li>User Registration</li>
            <li>User Login</li>
            <li>Password Reset</li>
            <li>Protection against brute force attacks</li>
        </ul>

        <?php
        if (!isset($_SESSION['user_id'])) {
            echo "<p>If you've forgotten your password, you can <a href='forgot_password.php'>reset it here</a>.</p>";
        }
        ?>
    </div>
</body>

</html>