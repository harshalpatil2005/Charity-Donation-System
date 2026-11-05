<?php 
session_start();

if(!isset($_SESSION['donor_id'])){
    die("Login first");
}
if(isset($_POST['cancel'])){
    header('Location: ../index.php');
    return;
}

require_once "../pdo.php";
if ((isset($_POST['donation']))){
    if(($_POST['donation'] > 0)){
        $stmt = $pdo->query("UPDATE `ngo_account`
        SET `donationS` = `donationS` + ".$_POST['donation']." WHERE `donor_id` = '".$_SESSION['donor_id']."'");

        $_SESSION['success'] = "Donated Rs: " . $_POST['donation'];
        header('Location: ../index.php');
        return;
    }
    else {
        $_SESSION['error'] = "Please enter a valid donation amount!";
        header("Location: donateMoney.php");
        return;
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donate Money</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="d-flex justify-content-center align-items-center vh-100 bg-light">

<div class="container">
    <div class="card shadow p-4" style="max-width: 400px; margin: auto;">
        <h2 class="text-center mb-4">Enter Amount to Donate</h2>

        <?php
        if(isset($_SESSION['success'])){
            echo '<div class="alert alert-success">' . $_SESSION['success'] . '</div>';
            unset ($_SESSION['success']);
        }

        if(isset($_SESSION['error'])){
            echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
            unset($_SESSION['error']);
        }
        ?>

        <form method="post">
            <div class="mb-3">
                <label for="donation" class="form-label">Donation Amount:</label>
                <input type="number" class="form-control" name="donation" value="0" min="1" required>
            </div>
            
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">Donate</button>
                <button type="submit" class="btn btn-secondary" name="cancel">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
