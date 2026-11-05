<?php 
session_start();
require_once "../pdo.php";
if(isset($_POST['cancel'])){
    header('Location: ../index.php');
    return;
}
if ( isset($_POST['username'])) {
    if((strlen($_POST['username'])>0) && (strlen($_POST['password'])>0)){
        $stmt3 = $pdo->query("SELECT admin_id FROM admin_login WHERE USERNAME = '".$_POST['username']."' AND PASSWORD ='".$_POST['password']."'");
        $rows2 = $stmt3->fetchAll(PDO::FETCH_ASSOC);
        if(count($rows2)>=1){
           $_SESSION['admin_id']=$rows2[0]['admin_id'];
           $_SESSION['role']= 2;
           header("Location:../index.php");
           return;
        } else {
            $_SESSION['error']="Wrong Username or Password.";
            header("Location: adminLogin.php");
            return;
        } 
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Charity</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .form-container {
            background: #fff;
            padding: 40px 30px;
            border-radius: 15px;
            box-shadow: 0px 4px 20px rgba(0,0,0,0.05);
            width: 400px;
        }
        .form-container h3 {
            margin-bottom: 25px;
        }
        input[type="text"],
        input[type="password"] {
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }
        .btn-custom {
            width: 48%;
        }
        .signup-link {
            margin-top: 20px;
            display: block;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h3>Admin Login</h3>

        <?php if(isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="username" class="form-control" placeholder="Username" required>
            <input type="password" name="password" class="form-control" placeholder="Password" required>
            <div class="d-flex justify-content-between">
                <button type="submit" name="submit" class="btn btn-primary btn-custom">Login</button>
                <button type="submit" name="cancel" class="btn btn-secondary btn-custom">Cancel</button>
            </div>
        </form>
        <a href="../signup/adminSignup.php" class="btn btn-outline-dark w-100 signup-link">Sign Up</a>
    </div>
</body>
</html>
