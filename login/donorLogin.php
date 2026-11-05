<?php
session_start();
require_once '../pdo.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['cancel'])) {
        header("Location: ../index.php");
        exit();
    }

    if (!empty($_POST['username']) && !empty($_POST['password'])) {

        $stmt = $pdo->prepare("SELECT * FROM donor_login WHERE username = :username");
        $stmt->execute([':username' => $_POST['username']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if ($_POST['password'] === $user['password']) { 
                $_SESSION['donor_id'] = $user['donor_id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['success'] = "Login successful!";
                
                header("Location: ../donorIndex.php"); 
                exit();
            } else {
                $_SESSION['error'] = "Invalid password.";
            }
        } else {
            $_SESSION['error'] = "User not found.";
        }
    } else {
        $_SESSION['error'] = "Username and password are required.";
    }

    header("Location: donorLogin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donor Login - Charity</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
       body {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        }
        .form-container {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
            width: 400px;
            text-align: center;
        }
        .form-container h3 {
            margin-bottom: 25px;
        }
        input {
            width: 100%;
            margin-bottom: 15px;
            height: 45px;
            font-size: 15px;
        }
        .btn-custom {
            width: 100%;
            height: 45px;
            font-size: 16px;
            margin-bottom: 10px;
        }
        .alert {
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h3>Donor Login</h3>

        <?php if(isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="username" class="form-control" placeholder="Enter Username" required>
            <input type="password" name="password" class="form-control" placeholder="Enter Password" required>
            
            <button type="submit" class="btn btn-primary btn-custom">Login</button>
            <button type="submit" name="cancel" class="btn btn-light btn-custom border">Cancel</button>
            <a href="../signup/donorSignup.php" class="btn btn-outline-secondary btn-custom">New User? Sign Up</a>
        </form>
    </div>
</body>
</html>
