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

if ((isset($_POST['bank'])) && (isset($_POST['ifsc_code'])) && (isset($_POST['acount'])) && (isset($_POST['donation']))){
    if((strlen($_POST['bank']) > 0) && (strlen($_POST['ifsc_code']) > 0) && (strlen($_POST['acount']) > 0) && ($_POST['donation'] > 0)){

        // Fetch donor's city_id
        $stmt3 = $pdo->query("SELECT * FROM `donor` WHERE `donor_id` = '".$_SESSION['donor_id']."'");
        $rows2 = $stmt3->fetchAll(PDO::FETCH_ASSOC);

        // Insert into ngo_account
        $stmt = $pdo->prepare('INSERT INTO ngo_account
        (bank, ifsc_code, acount, donor_id, city_id, donations) VALUES (:bn, :ifs, :ac, :don, :ci, :d)');
        
        $stmt->execute(array(
            ':bn' => $_POST['bank'],
            ':ifs' => $_POST['ifsc_code'],
            ':ac' => $_POST['acount'],
            ':don' => $_SESSION['donor_id'],
            ':ci' => $rows2[0]['city_id'],
            ':d' => $_POST['donation']
        ));

        // Insert into transactions table
        $dateTime = date('Y-m-d H:i:s');
        $stmt2 = $pdo->prepare('INSERT INTO transaction (donor_id, amount, tdate) VALUES (:donor_id, :amount, :tdate)');
        $stmt2->execute(array(
            ':donor_id' => $_SESSION['donor_id'],
            ':amount' => $_POST['donation'],
            ':tdate' => $dateTime
        ));

        $_SESSION['success'] = "Details saved and donation recorded successfully!";
        header('Location: ../index.php');
        return;
    }
    else {
        $_SESSION['error'] = "All fields are required and donation should be greater than 0!";
        header("Location: details.php");
        return;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donor Details</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="d-flex justify-content-center align-items-center vh-100 bg-light">

<div class="container">
    <div class="card shadow p-4" style="max-width: 500px; margin: auto;">
        <h2 class="text-center mb-4">Enter Your Details</h2>

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
                <label for="bank" class="form-label">Bank Name:</label>
                <input type="text" class="form-control" name="bank" required>
            </div>

            <div class="mb-3">
                <label for="ifsc_code" class="form-label">IFSC Code:</label>
                <input type="text" class="form-control" name="ifsc_code" required>
            </div>

            <div class="mb-3">
                <label for="acount" class="form-label">Account No:</label>
                <input type="text" class="form-control" name="acount" required>
            </div>

            <div class="mb-3">
                <label for="donation" class="form-label">Initial Donation:</label>
                <input type="number" class="form-control" name="donation" value="0" min="1" required>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">Submit</button>
                <button type="submit" class="btn btn-secondary" name="cancel">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
