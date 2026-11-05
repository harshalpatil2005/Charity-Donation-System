<?php
require_once "../pdo.php";
session_start();

if (!isset($_SESSION['volunteer_id'])) {
    $_SESSION['error'] = "Please log in first.";
    header("Location: ../volunteer/volunteerLogin.php");
    return;
}

if (isset($_GET['task_id'])) {
    $task_id = $_GET['task_id'];
    $volunteer_id = $_SESSION['volunteer_id'];

    # Check if the volunteer actually accepted this task
    $stmt = $pdo->prepare("SELECT * FROM task WHERE task_id = ? AND volunteer_id = ?");
    $stmt->execute([$task_id, $volunteer_id]);
    $task = $stmt->fetch();

    if (!$task) {
        $_SESSION['error'] = "You cannot complete a task that is not assigned to you.";
        header("Location: volunteerIndex.php");
        return;
    }

    # Mark the task as completed
    $stmt = $pdo->prepare("UPDATE task SET status = 2 WHERE task_id = ?");
    $stmt->execute([$task_id]);

    $_SESSION['success'] = "Task marked as completed!";
    header("Location: volunteerIndex.php");
    return;
} else {
    $_SESSION['error'] = "Invalid task.";
    header("Location: volunteerIndex.php");
    return;
}
?>
