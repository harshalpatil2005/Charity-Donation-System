<?php 
require_once "../pdo.php";
session_start();
if(!isset($_SESSION['admin_id'])){
    die("Login first");
}
if(isset($_POST['Cancel'])){
    header('Location: ../index.php');
    return;
}

if(isset($_POST['name']) && isset($_POST['email']) && isset($_POST['city']) && isset($_POST['phone'])){
    if(strlen($_POST['name']) > 0 && strlen($_POST['email']) > 0 && strlen($_POST['city']) > 0 && strlen($_POST['phone']) > 0){   
        $stmt = $pdo->prepare("UPDATE `admin` SET `email`= :em, `city_id`= :ci, `phone`= :ph, `name`= :nm WHERE `admin_id` = :admin_id");
        $stmt->execute([
            ':nm' => $_POST['name'],
            ':em' => $_POST['email'],
            ':ci' => $_POST['city'],
            ':ph' => $_POST['phone'],
            ':admin_id' => $_SESSION['admin_id']
        ]);
        $_SESSION['success'] = "Profile updated successfully";
        header('Location: ../index.php');
        return;
    } else {
        $_SESSION['error'] = "All fields are required";
        header("Location: adminUpdate.php");  
        return;         
    }
}

$stmt4 = $pdo->prepare("SELECT * FROM `admin` WHERE `admin_id` = :admin_id");
$stmt4->execute([':admin_id' => $_SESSION['admin_id']]);
$rows3 = $stmt4->fetch(PDO::FETCH_ASSOC);

$name = htmlentities($rows3['name']);
$email = htmlentities($rows3['email']);
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
    <title>Admin Profile Update</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
        }
        .form-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            width: 350px;
            text-align: center;
        }
        input, select {
            width: 100%;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<div class="form-container">
    <?php 
        if(isset($_SESSION['error'])){
            echo '<div class="alert alert-danger" role="alert">'.$_SESSION['error'].'</div>';
            unset($_SESSION['error']);
        }
    ?>
    
    <form method="post">
        <h3 class="h3 mb-3 font-weight-normal">Edit Your Admin Profile</h3>  

        <p><label for="name">Name:</label>
        <input type="text" name="name" id="name" class="form-control" value="<?= $name ?>"></p>

        <p><label for="email">Email:</label>
        <input type="email" name="email" id="email" class="form-control" value="<?= $email ?>"></p>

        <p><label for="phone">Phone:</label>
        <input type="text" name="phone" id="phone" class="form-control" value="<?= $phone ?>"></p>

        <p><label for="city">City:</label>
        <select id="city" name="city" class="form-control">
            <?php
            foreach($rows as $row){
                $selected = ($row['city_id'] == $city) ? "selected" : "";
                echo "<option value='{$row['city_id']}' $selected>".htmlentities($row['cname'])."</option>";
            } 
            ?>
        </select></p>

        <p>
            <input type="submit" class="btn btn-lg btn-primary" value="Update Profile">  
            <input type="submit" class="btn btn-lg btn-secondary" name="Cancel" value="Cancel">
        </p>
    </form>
</div>

</body>
</html>
