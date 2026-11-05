<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
require_once 'pdo.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donor Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-lg p-3">
  <a class="navbar-brand font-weight-bold" href="index.php">CHARITY</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav ml-auto">
      <li class="nav-item active">
        <a class="nav-link" href="#">Donor</a>
      </li>
      <li class="nav-item">
        <a class="nav-link font-weight-bold" href="logout.php">
          <?php 
          $stmt3 = $pdo->query("SELECT name FROM donor WHERE donor_id =".$_SESSION['donor_id']);
          $rows2 = $stmt3->fetchAll(PDO::FETCH_ASSOC);
          echo htmlentities($rows2[0]['name']);
          ?>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="update/donorUpdate.php">Edit Profile</a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-danger font-weight-bold" href="logout.php">Logout</a>
      </li>
    </ul>
  </div>
</nav>

<div class="container mt-4">

  <?php 
  if(isset($_SESSION['success'])){
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">';
    echo $_SESSION['success'];
    unset ($_SESSION['success']);
    echo '<button type="button" class="close" data-dismiss="alert">&times;</button>';
    echo '</div>';
  }

  if(isset($_SESSION['error'])){
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
    echo $_SESSION['error'];
    unset($_SESSION['error']);
    echo '<button type="button" class="close" data-dismiss="alert">&times;</button>';
    echo '</div>';
  }
  ?>

  <div class="row">
    <div class="col-md-8 offset-md-2 text-center">
      <img src="images/index/donate.jpg" alt="Donate" class="img-fluid rounded shadow-lg">
    </div>
  </div>
  <br>

  <div class="row">
    <?php
    $stmt3 = $pdo->query("SELECT donationS FROM ngo_account WHERE donor_id=".$_SESSION['donor_id']);
    $rows = $stmt3->fetchAll(PDO::FETCH_ASSOC);
    if(count($rows) > 0){
      echo "<div class='col-md-6'>";
      echo "<h2 class='p-3 bg-light rounded text-center shadow-sm'>Your Total Donations: ₹ ".htmlentities($rows[0]['donationS'])."</h2>";
      echo "</div>";
      echo "<div class='col-md-6 text-center'>";
      echo "<a class='btn btn-success btn-lg mx-2' href='donor/donateMoney.php'>Donate Money</a>";
      echo "<a class='btn btn-info btn-lg mx-2' href='donor/transactions.php'>Check Transactions</a>";
      echo "</div>";
    } else {
      echo "<div class='col-md-6'>";
      echo "<h2 class='p-3 bg-warning text-dark rounded text-center shadow-sm'>Fill Your Details to Donate</h2>";
      echo "</div>";
      echo "<div class='col-md-6 text-center'>";
      echo "<a class='btn btn-primary btn-lg' href='donor/details.php'>Fill Details</a>";
      echo "</div>";
    }
    ?>
  </div>

  <div class="row mt-4">
    <div class="col-md-6">
      <h2 class="p-3 bg-light rounded text-center shadow-sm">Donate Items Here</h2>  
    </div>
    <div class="col-md-6 text-center">
      <a class="btn btn-primary btn-lg" href="donor/donateItems.php">Donate Items</a>
    </div>
  </div>

  <div class="row mt-4">
    <div class="col-md-10 offset-md-1">
      <h2 class="text-center">Items You Donated</h2>
      <table class="table table-striped table-bordered shadow-sm">
        <thead class="thead-dark">
          <tr>
            <th>Sno</th>
            <th>Item Name</th>
            <th>QTY</th>
          </tr>
        </thead>
        <tbody>
           <?php 
           $stmt3 = $pdo->query("SELECT * FROM items WHERE donor_id=".$_SESSION['donor_id']);
           $rows = $stmt3->fetchAll(PDO::FETCH_ASSOC);
           $count = 1;
           foreach($rows as $row){
              echo "<tr>";
              echo "<th scope='row'>".$count."</th>";
              echo "<td>".htmlentities($row['item'])."</td>";
              echo "<td>".htmlentities($row['quantity'])."</td>";
              echo "</tr>";
              $count++;
           }
           ?>
        </tbody>
      </table>
    </div>
  </div>

  <div class="row mt-4">
    <div class="col-md-10 offset-md-1">
      <h2 class="text-center">Current Active Tasks</h2>
      <table class="table table-striped table-bordered shadow-sm">
        <thead class="thead-dark">
          <tr>
            <th>Sno</th>
            <th>Task Name</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
           <?php 
           $stmt = $pdo->query("SELECT * FROM task WHERE status='Active'");
           $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
           $count = 1;
           foreach($tasks as $task){
              echo "<tr>";
              echo "<th scope='row'>".$count."</th>";
              echo "<td>".htmlentities($task['task'])."</td>";
              echo "<td>".htmlentities($task['status'])."</td>";
              echo "</tr>";
              $count++;
           }
           ?>
        </tbody>
      </table>
    </div>
  </div>

</div>

</body>
</html>
