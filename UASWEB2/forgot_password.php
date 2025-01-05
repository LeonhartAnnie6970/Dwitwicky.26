<?php
require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    
    $result = forgotPassword($email);
    echo $result;
}
?>

<form method="POST">
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="submit" value="Reset Password">
</form>