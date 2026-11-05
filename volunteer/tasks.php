<?php 
session_start();
require_once "../pdo.php";

// Check if either an admin or a volunteer is logged in
if (!isset($_SESSION['volunteer_id']) && !isset($_SESSION['admin_id'])) {
    die("Login first");
}

// Handle task submission
if (isset($_POST['task'])) {
    if (strlen($_POST['task']) > 0) {
        $stmt = $pdo->prepare('INSERT INTO `task` (`task`, `volunteer_id`, `status`) VALUES (:it, :vid, :status)');
        $stmt->execute(array(
            ':it' => $_POST['task'],
            ':vid' => $_SESSION['volunteer_id'] ?? NULL,  // Use NULL for admin
            ':status' => 1  // Set task status to active
        ));

        $_SESSION['success'] = "Task added successfully!";
        
        // Redirect based on who added the task
        if (isset($_SESSION['admin_id'])) {
            header('Location: ../adminV.php'); // Redirect to admin panel
        } else {
            header('Location: ../index.php'); // Redirect for volunteers
        }
        exit;
    } else {
        $_SESSION['error'] = "All fields are required!";
        header("Location: task.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volunteer Tasks</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="d-flex justify-content-center align-items-center vh-100 bg-light">

<div class="container">
    <div class="card shadow p-4" style="max-width: 400px; margin: auto;">
        <h3 class="text-center mb-4">Enter Task</h3>

        <?php
        if(isset($_SESSION['success'])){
            echo '<div class="alert alert-success">' . $_SESSION['success'] . '</div>';
            unset($_SESSION['success']);
        }

        if(isset($_SESSION['error'])){
            echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
            unset($_SESSION['error']);
        }
        ?>

        <form method="post">
            <div class="mb-3">
                <label for="task" class="form-label">Task Name:</label>
                <input type="text" class="form-control" name="task" required>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">Submit</button>
                <a href="../index.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
