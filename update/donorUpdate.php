<?php 
require_once "../pdo.php";
session_start();
if (!isset($_SESSION['donor_id'])) {
    die("Login first");
}

if (isset($_POST['Cancel'])) {
    header('Location: ../index.php');
    return;
}

if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['address']) && isset($_POST['city']) && isset($_POST['phone'])) {
    if (strlen($_POST['name']) > 0 && strlen($_POST['email']) > 0 && strlen($_POST['address']) > 0 && strlen($_POST['city']) > 0 && strlen($_POST['phone']) > 0) {
        $stmt = $pdo->prepare("UPDATE `donor` SET `email`= :em, `address`= :add, `city_id`= :ci, `phone`= :ph, `name`= :nm WHERE `donor_id` = :donor_id");
        $stmt->execute([
            ':nm' => $_POST['name'],
            ':em' => $_POST['email'],
            ':add' => $_POST['address'],
            ':ci' => $_POST['city'],
            ':ph' => $_POST['phone'],
            ':donor_id' => $_SESSION['donor_id']
        ]);
        $_SESSION['success'] = "Profile updated successfully";
        header('Location: ../index.php');
        return;
    } else {
        $_SESSION['error'] = "All fields are required";
        header("Location: donorUpdate.php");  
        return;
    }
}

$stmt4 = $pdo->prepare("SELECT * FROM `donor` WHERE `donor_id` = :donor_id");
$stmt4->execute([':donor_id' => $_SESSION['donor_id']]);
$rows3 = $stmt4->fetch(PDO::FETCH_ASSOC);

$name = htmlentities($rows3['name']);
$email = htmlentities($rows3['email']);
$address = htmlentities($rows3['address']);
$city = htmlentities($rows3['city_id']);
$phone = htmlentities($rows3['phone']);

$stmt3 = $pdo->query("SELECT * FROM city");
$rows = $stmt3->fetchAll(PDO::FETCH_ASSOC);
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donor Update</title>
    <?php include("bootstrap.php"); ?>
    <style>
        .container {
            max-width: 500px;
            margin-top: 50px;
        }
        .message {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger text-center message"> <?= $_SESSION['error']; unset($_SESSION['error']); ?> </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success text-center message"> <?= $_SESSION['success']; unset($_SESSION['success']); ?> </div>
    <?php endif; ?>
    
    <form method="post" class="bg-light p-4 rounded shadow">
        <h3 class="text-center mb-4">Edit Your Donor Profile</h3>  

        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" class="form-control" name="name" value="<?= $name ?>" required>
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" name="email" value="<?= $email ?>" required>
        </div>

        <div class="form-group">
            <label for="address">Address:</label>
            <input type="text" class="form-control" name="address" value="<?= $address ?>" required>
        </div>

        <div class="form-group">
            <label for="phone">Phone:</label>
            <input type="text" class="form-control" name="phone" value="<?= $phone ?>" required>
        </div>

        <div class="form-group">
            <label for="city">City:</label>
            <select id="city" class="form-control" name="city" required>
                <?php foreach ($rows as $row): ?>
                    <option value="<?= $row['city_id'] ?>" <?= ($row['city_id'] == $city) ? 'selected' : '' ?>>
                        <?= htmlentities($row['cname']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary btn-block">Submit</button>
        <button type="submit" name="Cancel" class="btn btn-secondary btn-block">Cancel</button>
    </form>
</div>
</body>
</html>
