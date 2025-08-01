<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: dashboard.php");
exit();
?>
