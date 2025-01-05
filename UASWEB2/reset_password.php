<?php
require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $new_password = $_POST['new_password'];
    
    $result = resetPassword($token, $new_password);
    echo $result;
}

$token = $_GET['token'] ?? '';
?>

<form method="POST">
    <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
    <input type="password" name="new_password" placeholder="New Password" required><br>
    <input type="submit" value="Reset Password">
</form>