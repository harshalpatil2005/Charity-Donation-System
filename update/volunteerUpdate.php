<?php 
require_once "../pdo.php";
session_start();

if(!isset($_SESSION['volunteer_id'])){
    die("Login first");
}

if(isset($_POST['cancel'])){
    header('Location: ../index.php');
    return;
}

if(isset($_POST['name'], $_POST['email'], $_POST['intrests'], $_POST['date'], $_POST['city'], $_POST['phone'])){
    if(strlen($_POST['name']) > 0 && strlen($_POST['email']) > 0 && strlen($_POST['date']) > 0 && strlen($_POST['intrests']) > 0 && strlen($_POST['city']) > 0 && strlen($_POST['phone']) > 0){   
        $stmt = $pdo->prepare("UPDATE `volunteer` 
            SET `name` = :nm, `email` = :em, `intrests` = :inn, `dob` = :db, `city_id` = :ci, `phone` = :ph 
            WHERE `volunteer_id` = :vid");
        $stmt->execute([
            ':nm' => $_POST['name'],
            ':em' => $_POST['email'],
            ':inn' => $_POST['intrests'],
            ':db' => $_POST['date'],
            ':ci' => $_POST['city'],
            ':ph' => $_POST['phone'],
            ':vid' => $_SESSION['volunteer_id']
        ]);

        $_SESSION['success'] = "Record updated successfully!";
        header('Location: ../index.php');
        return;
    } else {
        $_SESSION['error'] = "All fields are required!";
        header("Location: volunteerUpdate.php");  
        return;         
    }
}

$stmt4 = $pdo->prepare("SELECT * FROM `volunteer` WHERE `volunteer_id` = :vid");
$stmt4->execute([':vid' => $_SESSION['volunteer_id']]);
$rows3 = $stmt4->fetch(PDO::FETCH_ASSOC);

$name = htmlentities($rows3['name']);
$email = htmlentities($rows3['email']);
$intrests = htmlentities($rows3['intrests']);
$date = htmlentities($rows3['dob']);
$city = htmlentities($rows3['city_id']);
$phone = htmlentities($rows3['phone']);

$stmt3 = $pdo->query("SELECT * FROM city");
$rows = $stmt3->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volunteer Update</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="d-flex justify-content-center align-items-center vh-100 bg-light">

<div class="container">
    <div class="card shadow p-3" style="max-width: 400px; margin: auto;">
        <h4 class="text-center">Edit Profile</h4>

        <?php 
        if(isset($_SESSION['error'])){
            echo '<div class="alert alert-danger p-2 text-center">' . $_SESSION['error'] . '</div>';
            unset($_SESSION['error']);
        }
        if(isset($_SESSION['success'])){
            echo '<div class="alert alert-success p-2 text-center">' . $_SESSION['success'] . '</div>';
            unset($_SESSION['success']);
        }
        ?>

        <form method="post">
            <div class="mb-2">
                <input type="text" class="form-control" name="name" value="<?= $name ?>" placeholder="Name" required>
            </div>
            <div class="mb-2">
                <input type="email" class="form-control" name="email" value="<?= $email ?>" placeholder="Email" required>
            </div>
            <div class="mb-2">
                <input type="text" class="form-control" name="intrests" value="<?= $intrests ?>" placeholder="Address" required>
            </div>
            <div class="mb-2">
                <input type="date" class="form-control" name="date" value="<?= $date ?>" required>
            </div>
            <div class="mb-2">
                <input type="text" class="form-control" name="phone" value="<?= $phone ?>" placeholder="Phone" required>
            </div>
            <div class="mb-2">
                <select class="form-select" name="city" required>
                    <?php
                    foreach($rows as $row){
                        echo "<option value='".$row['city_id']."'".($row['city_id'] == $city ? " selected" : "").">";
                        echo htmlentities($row['cname']);
                        echo "</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                <button type="submit" class="btn btn-secondary btn-sm" name="cancel">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
