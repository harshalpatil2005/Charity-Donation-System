<?php 
session_start();
require_once "../pdo.php";

if (!isset($_SESSION['admin_id'])) {
    die("Login first");
}

if (!isset($_GET['donor_id'])) {
    die("No parameter found");
}

if (isset($_POST['cancel'])) {
    header('Location: ../index.php');
    exit();
} elseif (isset($_POST['submit'])) {
    $stmt = $pdo->prepare("DELETE FROM ngo_account WHERE donor_id = :donor_id");
    $stmt->execute(['donor_id' => $_GET['donor_id']]);
    header('Location: ../index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Deletion</title>
    <?php include("../bootstrap.php"); ?>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background: #ffffff;
        }
        .container {
            background: #ffffff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 90%;
            max-width: 400px;
            border: 1px solid #ddd;
        }
        h2 {
            color: #333;
            margin-bottom: 20px;
            font-weight: 600;
        }
        p {
            color: #555;
            margin-bottom: 20px;
        }
        .btn-group {
            display: flex;
            justify-content: center;
            gap: 15px;
        }
        button {
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s ease;
        }
        .btn-danger {
            background: #ff4b2b;
            color: white;
            font-weight: bold;
        }
        .btn-danger:hover {
            background: #ff3a1d;
        }
        .btn-secondary {
            background: #6c757d;
            color: white;
            font-weight: bold;
        }
        .btn-secondary:hover {
            background: #5a6268;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Are You Sure?</h2>
    <p>This action is irreversible. Please confirm before proceeding.</p>
    <form action="" method="post" class="btn-group">
        <button type="submit" class="btn-danger" name="submit">Yes, Delete</button>
        <button type="submit" class="btn-secondary" name="cancel">Cancel</button>
    </form>
</div>

</body>
</html>
