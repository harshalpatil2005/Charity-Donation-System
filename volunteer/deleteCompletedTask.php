<?php
session_start();
require_once "../pdo.php";

// Check if the task is completed and delete it
try {
    // Query to get all completed tasks
    $stmt = $pdo->query("SELECT task_id, status FROM task WHERE status = 1");  // Assuming 1 represents completed tasks
    
    // Fetch all completed tasks
    $completed_tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Check if there are completed tasks
    if (count($completed_tasks) > 0) {
        foreach ($completed_tasks as $task) {
            $task_id = $task['task_id'];

            // Delete the completed task from the database
            $deleteStmt = $pdo->prepare("DELETE FROM task WHERE task_id = :task_id");
            $deleteStmt->execute([':task_id' => $task_id]);
        }

        $_SESSION['success'] = "Completed tasks deleted successfully.";
    } else {
        $_SESSION['error'] = "No completed tasks found.";
    }
    
    // Redirect back to the admin panel
    header('Location: adminV.php');
    exit();
} catch (Exception $e) {
    // Handle any error during the deletion process
    $_SESSION['error'] = "An error occurred: " . $e->getMessage();
    header('Location: adminV.php');  // Redirect back to admin panel if error occurs
    exit();
}
?>
