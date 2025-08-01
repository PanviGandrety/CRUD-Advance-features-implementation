<?php
require 'db.php';
$success = false;
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);


    $check = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $check->bind_param("s", $username);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $error = "Username already taken. Please choose a different one.";
    } else {
        
        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        if ($stmt) {
            $stmt->bind_param("ss", $username, $password);
            if ($stmt->execute()) {
                $success = true;
            } else {
                $error = "Error: " . $stmt->error;
            }
        }
    }
}
?>

<link rel="stylesheet" href="style.css">

<div class="auth-container">
    <?php if ($success): ?>
        <div class="success-message">
            Registered successfully. <a href="login.php">Login</a>
        </div>
    <?php elseif ($error): ?>
        <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <h2 class="center-text">Register</h2>

    <form method="POST">
        <input name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Register</button>
    </form>

    <p class="center-text">Already registered? <a href="login.php">Login here</a></p>
</div>
