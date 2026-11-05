<?php
require_once "../pdo.php";
session_start();

if (!isset($_SESSION['volunteer_id'])) {
    $_SESSION['error'] = "Please log in first.";
    header("Location: ../volunteer/volunteerLogin.php");
    return;
}

$volunteer_id = $_SESSION['volunteer_id'];

// Check how many tasks the volunteer already has
$stmt = $pdo->prepare("SELECT COUNT(*) FROM task WHERE volunteer_id = ? AND status = 1");
$stmt->execute([$volunteer_id]);
$task_count = $stmt->fetchColumn();

// Allow only 2 tasks to be assigned to the volunteer
if ($task_count >= 2) {
    $_SESSION['error'] = "You can only accept two tasks at a time. Complete a task before accepting a new one!";
    header("Location: volunteerIndex.php");
    return;
}

if (isset($_GET['task_id'])) {
    $task_id = $_GET['task_id'];

    $stmt = $pdo->prepare("UPDATE task SET volunteer_id = ?, status = 1 WHERE task_id = ?");
    $stmt->execute([$volunteer_id, $task_id]);

    $_SESSION['success'] = "Task accepted successfully!";
    header("Location: volunteerIndex.php");
    return;
} else {
    $_SESSION['error'] = "Invalid task.";
    header("Location: volunteerIndex.php");
    return;
}
?>
