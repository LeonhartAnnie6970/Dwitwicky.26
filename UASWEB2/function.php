<?php
require_once 'config.php';

function registerUser($username, $email, $password) {
    global $conn;
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $hashed_password);
    
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

function loginUser($username, $password) {
    global $conn;
    
    $stmt = $conn->prepare("SELECT id, password, login_attempts, last_login_attempt FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        // Check for brute force attempts
        if ($user['login_attempts'] >= 5 && strtotime($user['last_login_attempt']) > (time() - 900)) {
            return "Too many failed attempts. Please try again later.";
        }
        
        if (password_verify($password, $user['password'])) {
            // Reset login attempts on successful login
            $stmt = $conn->prepare("UPDATE users SET login_attempts = 0 WHERE id = ?");
            $stmt->bind_param("i", $user['id']);
            $stmt->execute();
            
            return "Login successful";
        } else {
            // Increment login attempts
            $stmt = $conn->prepare("UPDATE users SET login_attempts = login_attempts + 1, last_login_attempt = NOW() WHERE id = ?");
            $stmt->bind_param("i", $user['id']);
            $stmt->execute();
            
            return "Invalid username or password";
        }
    } else {
        return "Invalid username or password";
    }
}

function forgotPassword($email) {
    global $conn;
    
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $token = bin2hex(random_bytes(32));
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        $stmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_token_expiry = ? WHERE id = ?");
        $stmt->bind_param("ssi", $token, $expiry, $user['id']);
        $stmt->execute();
        
        // Send email with reset link (implement your own email sending function)
        $reset_link = "http://yourwebsite.com/reset_password.php?token=" . $token;
        // sendResetEmail($email, $reset_link);
        
        return "Password reset link sent to your email";
    } else {
        return "Email not found";
    }
}

function resetPassword($token, $new_password) {
    global $conn;
    
    $stmt = $conn->prepare("SELECT id FROM users WHERE reset_token = ? AND reset_token_expiry > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        
        $stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE id = ?");
        $stmt->bind_param("si", $hashed_password, $user['id']);
        $stmt->execute();
        
        return "Password reset successful";
    } else {
        return "Invalid or expired token";
    }
}