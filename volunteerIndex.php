<?php
require_once "pdo.php";

// session_start();

// Check if volunteer is logged in
if (!isset($_SESSION['volunteer_id'])) {
    header("Location: login.php");
    return;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Volunteer Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .table thead {
            background-color: black;
            color: white;
        }
        .btn-primary, .btn-success {
            font-size: 14px;
            padding: 5px 10px;
        }
        h3 {
            font-weight: bold;
        }
        body {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <a class="navbar-brand" href="index.php">CHARITY</a>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav ml-auto">
      <li class="nav-item active">
        <a class="nav-link" href="#">Volunteer<span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="update/volunteerUpdate.php">Edit Profile</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="logout.php">Logout (<?php 
          $stmt3 = $pdo->query("SELECT `name` FROM `volunteer` WHERE `volunteer_id` =".$_SESSION['volunteer_id']);
          $rows2 = $stmt3->fetchAll(PDO::FETCH_ASSOC);
          echo htmlentities($rows2[0]['name']);
        ?>)</a>
      </li>
    </ul>
  </div>
</nav>

<div class="container mt-4">
<?php 
    if(isset($_SESSION['success'])){
        echo '<div class="alert alert-success" role="alert">'.$_SESSION['success'].'</div>';
        unset ($_SESSION['success']);
    }

    if(isset($_SESSION['error'])){
        echo '<div class="alert alert-danger" role="alert">'.$_SESSION['error'].'</div>';
        unset($_SESSION['error']);
    }
?>

  <div class="d-flex justify-content-between mb-4">
      <h3>Available Tasks</h3>
  </div>

  <table class="table table-bordered">
    <thead>
      <tr>
        <th scope="col">S.No</th>
        <th scope="col">Task Name</th>
        <th scope="col">Status</th>
        <th scope="col">Action</th>
      </tr>
    </thead>
    <tbody>
       <?php 
         $stmt3 = $pdo->query("SELECT * FROM task");
         $rows = $stmt3->fetchAll(PDO::FETCH_ASSOC);
         $count = 1;
          foreach($rows as $row){
            echo "<tr>";
            echo "<th scope='row'>".$count."</th>";
            echo "<td>".htmlentities($row['task'])."</td>";
            echo "<td>".($row['status'] == 1 ? 'Active' : 'Inactive')."</td>";
             echo "<td>";

            if ($row['volunteer_id'] == $_SESSION['volunteer_id']) {
                echo "<a href='volunteer/markComplete.php?task_id=".$row['task_id']."' class='btn btn-success btn-sm'>Complete Task</a>";
            } else if ($row['volunteer_id'] == null || $row['volunteer_id'] == 0) {
                echo "<a href='volunteer/acceptTask.php?task_id=".$row['task_id']."' class='btn btn-primary btn-sm'>Accept Task</a>";
            } else {
                echo "Assigned";
            }
            
            echo "</td>";
            echo "</tr>";
          $count++;
          }
        ?>
    </tbody>
  </table>
</div>  

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
