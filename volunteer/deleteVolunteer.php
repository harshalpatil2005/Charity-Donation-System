<?php
session_start();
require_once "../pdo.php";

// Check if the volunteer_id is present and valid
if (isset($_GET['volunteer_id']) && is_numeric($_GET['volunteer_id'])) {
    $volunteer_id = (int) $_GET['volunteer_id'];

    try {
        // Unassign the volunteer from all tasks first
        $stmt = $pdo->prepare("UPDATE task SET volunteer_id = NULL, status = 0 WHERE volunteer_id = :volunteer_id");
        $stmt->execute([':volunteer_id' => $volunteer_id]);

        // Now delete the volunteer from the database
        $stmt = $pdo->prepare("DELETE FROM volunteer WHERE volunteer_id = :volunteer_id");
        $stmt->execute([':volunteer_id' => $volunteer_id]);

        $_SESSION['success'] = "Volunteer and their assigned tasks deleted successfully.";
        header('Location: ../adminV.php');
        exit();
    } catch (Exception $e) {
        // If there is an issue, show an error message
        $_SESSION['error'] = "An error occurred: " . $e->getMessage();
        header('Location: ../adminV.php');
        exit();
    }
} else {
    $_SESSION['error'] = "Invalid parameter.";
    header('Location: ../adminV.php');
    exit();
}
