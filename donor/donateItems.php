<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


if(!isset($_SESSION['donor_id'])){
    die("Login first");
}
if(isset($_POST['cancel'])){
    header('Location: ../index.php');
    return;
}

require_once "../pdo.php";
if (isset($_POST['item']) && isset($_POST['quantity']) && isset($_POST['description'])){
    if(strlen($_POST['item']) > 0 && strlen($_POST['quantity']) > 0 && is_numeric($_POST['quantity'])){
        $stmt1 = $pdo->prepare('INSERT INTO `items`(`item`, `quantity`, `description`, `donor_id`) VALUES (:it, :qty, :desc, :did)');
        $stmt1->execute([
            ':it' => $_POST['item'],
            ':qty' => $_POST['quantity'],
            ':desc' => $_POST['description'],
            ':did' => $_SESSION['donor_id']
        ]);
        $_SESSION['success'] = "Item inserted successfully";
        header('Location: ../index.php');
        return;
    } else {
        $_SESSION['error'] = "All fields are required and quantity must be numeric";
        header("Location: donateitems.php");
        return;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donate Items</title>
    <?php include("bootstrap.php");?>
    <style>
        .container {
            max-width: 400px;
            margin: auto;
            margin-top: 50px;
        }
    </style>
</head>
<body class="text-center">
<div class="container">
    <h1 class="h3 mb-3 font-weight-normal">Donate an Item</h1>
    <?php if(isset($_SESSION['success'])): ?>
        <div class="alert alert-success"> <?= $_SESSION['success']; unset($_SESSION['success']); ?> </div>
    <?php endif; ?>
    <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"> <?= $_SESSION['error']; unset($_SESSION['error']); ?> </div>
    <?php endif; ?>
    
    <form method="post" class="bg-light p-4 rounded shadow">
        <div class="form-group">
            <label for="item">Item Name:</label>
            <input type="text" class="form-control" name="item" required>
        </div>
        <div class="form-group">
            <label for="quantity">Quantity:</label>
            <input type="number" class="form-control" name="quantity" required>
        </div>
        <div class="form-group">
            <label for="description">Description:</label>
            <textarea class="form-control" name="description" rows="3"></textarea>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Submit</button>
        <button type="submit" name="cancel" class="btn btn-secondary btn-block">Cancel</button>
    </form>
</div>
</body>
</html>
