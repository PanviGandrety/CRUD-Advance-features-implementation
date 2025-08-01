<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['title'], $_POST['content'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];

    $stmt = $conn->prepare("INSERT INTO posts (user_id, title, content) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $title, $content);
    $stmt->execute();
}


$search = '';
if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $sql = "SELECT * FROM posts WHERE title LIKE '%$search%' OR content LIKE '%$search%' ORDER BY created_at DESC";
} else {
    $sql = "SELECT * FROM posts ORDER BY created_at DESC";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">
 
    <div class="d-flex justify-content-end mb-2">
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>


    <h2 class="text-center mb-4">Welcome, <?php echo htmlspecialchars($username); ?>!</h2>


    <form method="GET" action="dashboard.php" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search posts..." value="<?php echo htmlspecialchars($search); ?>">
            <button class="btn btn-primary" type="submit">Search</button>
        </div>
    </form>


    <div class="card mb-4">
        <div class="card-header bg-success text-white">Create New Post</div>
        <div class="card-body">
            <form method="POST">
                <div class="mb-3">
                    <input type="text" name="title" class="form-control" placeholder="Post title" required>
                </div>
                <div class="mb-3">
                    <textarea name="content" class="form-control" placeholder="Your content..." required></textarea>
                </div>
                <button type="submit" class="btn btn-success">Post</button>
            </form>
        </div>
    </div>

    <h3 class="text-center mb-3">Recent Posts</h3>

    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($row['title']); ?></h5>
                <p class="card-text"><?php echo nl2br(htmlspecialchars($row['content'])); ?></p>
                <p class="card-text"><small class="text-muted"><?php echo $row['created_at']; ?></small></p>
                <form method="POST" action="delete.php">
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                </form>
            </div>
        </div>
    <?php endwhile; ?>
</div>
</body>
</html>
