<?php 
require_once "../pdo.php";
session_start();
if(!isset($_SESSION['donor_id'])){
    die("Login first");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donor Transactions</title>
    <?php require_once "bootstrap.php" ?>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="index.php">CHARITY</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item active">
                <a class="nav-link" href="../index.php">Donor <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php"><?php 
                  $stmt3 = $pdo->query("SELECT `name` FROM `donor` WHERE `donor_id` =".$_SESSION['donor_id']);
                  $rows2 = $stmt3->fetchAll(PDO::FETCH_ASSOC);
                  echo $rows2[0]['name'];
                ?></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../logout.php">Logout</a>
            </li>
        </ul>
    </div>
</nav>

<div class="container mt-4">
    <div class="row mb-3">
        <div class="col-6"><h3>Your Transactions</h3></div>
        <div class="col-6 text-right">
            <a class='btn btn-primary' style='width: 100px;' href='../index.php' role='button'>Go Back</a>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <table class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">Sno</th>
                        <th scope="col">Date And Time</th>
                        <th scope="col">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $stmt3 = $pdo->query("SELECT * FROM `transaction` WHERE `donor_id` =".$_SESSION['donor_id']);
                        $rows = $stmt3->fetchAll(PDO::FETCH_ASSOC);
                        $count = 1;
                        foreach($rows as $row){
                            echo "<tr>";
                            echo "<th scope='row'>".$count."</th>";
                            echo "<td>".htmlentities($row['tdate'])."</td>";
                            echo "<td>".htmlentities($row['amount'])."</td>";
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
