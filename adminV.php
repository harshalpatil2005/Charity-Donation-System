<?php
// session_start();
require_once "pdo.php";

// Manually Trigger Action
if (isset($_GET['trigger_task_action'])) {
    $task_id = $_GET['task_id'];
    $action = $_GET['action']; // Define 'remove', 'add', etc.

    if ($action == 'remove') {
        // Remove task from the database
        $stmt = $pdo->prepare("DELETE FROM task WHERE task_id = :task_id");
        $stmt->execute([':task_id' => $task_id]);

        // Log the removal action
        $stmt = $pdo->prepare("INSERT INTO task_logs (task_id, action) VALUES (:task_id, 'Task Removed')");
        $stmt->execute([':task_id' => $task_id]);

        // Notify user
        echo "<script>alert('Task removed successfully!'); window.location.href = 'admin_panel.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Charity Admin Panel</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    body { background-color: #f8f9fa; }
    h3 { font-weight: 600; margin-bottom: 20px; }
    .navbar-brand { font-weight: bold; font-size: 28px; letter-spacing: 1px; }
    table thead { background-color: black; color: white; }
    table { border: 1px solid #ddd; margin-bottom: 30px; }
    table th, table td { border: 1px solid #ddd; }
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-5">
  <a class="navbar-brand" href="index.php">CHARITY</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav ml-auto">
      <li class="nav-item active">
        <a class="nav-link" href="#">Admin</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="logout.php"><?php 
          $stmt3 = $pdo->query("SELECT name FROM admin WHERE admin_id =".$_SESSION['admin_id']);
          $rows2 = $stmt3->fetchAll(PDO::FETCH_ASSOC);
          echo $rows2[0]['name'];
        ?></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="update/adminUpdate.php">Edit Profile</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="admin.php">Donors</a>
      </li>      
      <li class="nav-item">
        <a class="nav-link" href="logout.php">Logout</a>
      </li>
    </ul>
  </div>
</nav>

<div class="container">
  <div class="row mb-4">
    <div class="col-md-6">
      <h3>Manage Tasks</h3>
      <a href="volunteer/tasks.php" class="btn btn-primary">Add Task</a>
    </div>
  </div>
  
  <div class="row mb-4">
    <div class="col-md-6">
      <h3>Running Tasks</h3>
      <table class="table bg-white">
        <thead>
          <tr>
            <th>S.No</th>
            <th>Task Name</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
           <?php 
             $stmt = $pdo->query("SELECT * FROM task");
             $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
             $count = 1;
              foreach($rows as $row){
                echo "<tr>";
                echo "<th scope='row'>".$count."</th>";
                echo "<td>".htmlentities($row['task'])."</td>";
                echo "<td><a class='btn btn-danger btn-sm' href='admin_panel.php?trigger_task_action=1&task_id=".$row['task_id']."&action=remove' onclick=\"return confirm('Are you sure you want to remove this task?')\">Remove Task</a></td>";
                echo "</tr>";
              $count++;
              }
            ?>
        </tbody>
      </table>
    </div>

   <div class="col-md-6">
     <h3>Assigned Tasks</h3>
     <table class="table bg-white">
       <thead>
         <tr>
           <th>S.No</th>
           <th>Task Name</th>
           <th>Volunteer</th>
           <th>Status</th>
         </tr>
       </thead>
       <tbody>
         <?php 
           $stmt = $pdo->query("SELECT task.task_id, task.task, volunteer.name AS volunteer_name, volunteer.volunteer_id,
                                CASE WHEN task.volunteer_id IS NULL THEN 'Not Assigned' ELSE 'Assigned' END AS status
                                FROM task LEFT JOIN volunteer ON task.volunteer_id = volunteer.volunteer_id");
           $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
           $count = 1;
           foreach ($rows as $row) {
               echo "<tr>";
               echo "<th scope='row'>" . $count . "</th>";
               echo "<td>" . htmlentities($row['task']) . "</td>";
               echo "<td>" . ($row['volunteer_name'] ? htmlentities($row['volunteer_name']) : 'Not Assigned') . "</td>";
               echo "<td>" . htmlentities($row['status']) . "</td>";

              //  if ($row['volunteer_id']) {
              //      echo "<td><a href='deleteVolunteer.php?volunteer_id=" . $row['volunteer_id'] . "' 
              //                onclick=\"return confirm('Are you sure you want to remove this volunteer?')\" 
              //                class='btn btn-danger btn-sm'>Remove</a></td>";
              //  } else {
              //      echo "<td></td>";
              //  }

               echo "</tr>";
               $count++;
           }
         ?>
       </tbody>
     </table>
   </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
