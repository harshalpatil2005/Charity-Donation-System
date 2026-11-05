<?php 
require_once "../pdo.php";
session_start();

if(isset($_POST['cancel'])){
    header('Location: ../login/adminLogin.php');
    return;
}

if (isset($_POST['username'])) {
    if (!empty($_POST['username']) && !empty($_POST['password'])) {
        $stmt5 = $pdo->prepare("SELECT `username` FROM `admin_login` WHERE `username` = ?");
        $stmt5->execute([$_POST['username']]);
        if ($stmt5->rowCount() > 0) {
            $_SESSION['error'] = "Username already exists. Choose a different username.";
            header('Location: adminSignup.php');
            return;
        }

        if (!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['city_id']) && !empty($_POST['phone']) && !empty($_POST['address'])) {
            $stmt5 = $pdo->prepare("SELECT `email` FROM `admin` WHERE `email` = ?");
            $stmt5->execute([$_POST['email']]);
            if ($stmt5->rowCount() > 0) {
                $_SESSION['error'] = "Email already exists. Choose a different email.";
                header('Location: adminSignup.php');
                return;
            }

            $stmt5 = $pdo->prepare("SELECT `phone` FROM `admin` WHERE `phone` = ?");
            $stmt5->execute([$_POST['phone']]);
            if ($stmt5->rowCount() > 0) {
                $_SESSION['error'] = "Phone already exists. Choose a different phone.";
                header('Location: adminSignup.php');
                return;
            }

            $stmt = $pdo->prepare('INSERT INTO admin (name, email, city_id, address, phone) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([$_POST['name'], $_POST['email'], $_POST['city_id'], $_POST['address'], $_POST['phone']]);

            $admin_id = $pdo->lastInsertId();

            $stmt1 = $pdo->prepare('INSERT INTO admin_login(username, password, admin_id) VALUES (?, ?, ?)');
            $stmt1->execute([$_POST['username'], $_POST['password'], $admin_id]);

            $_SESSION['success'] = "Record inserted";
            header('Location: ../login/adminLogin.php');
            return;
        } else {
            $_SESSION['error'] = "All fields are required.";
            header("Location: adminSignup.php");
            return;
        }
    }
}

$stmt3 = $pdo->query("SELECT * FROM city");
$rows = $stmt3->fetchAll(PDO::FETCH_ASSOC);
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Signup</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f8f9fa;
        }
        .form-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        .alert {
            margin-bottom: 15px;
        }
        input, button, select {
            width: 100%;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<?php 
if(isset($_SESSION['error'])){
    echo '<div class="alert alert-danger" role="alert">'.$_SESSION['error'].'</div>';
    unset($_SESSION['error']);
}
?>

<div class="form-container">
    <form method="post">
        <h3>Admin Signup</h3>  

        <input type="text" name="username" class="form-control" placeholder="Username" required>
        <input type="password" name="password" class="form-control" placeholder="Password" required>
        <input type="text" name="name" class="form-control" placeholder="Full Name" required>
        <input type="email" name="email" class="form-control" placeholder="Email" required>
        <input type="text" name="phone" class="form-control" placeholder="Phone Number" required>
        <input type="text" name="address" class="form-control" placeholder="Address" required>

        <select name="city_id" class="form-control" required>
            <option value="">Select City</option>
            <?php foreach ($rows as $row) {
                echo "<option value='{$row['city_id']}'>{$row['cname']}</option>";
            } ?>
        </select>

        <button type="submit" class="btn btn-primary">Submit</button>
        <button type="submit" name="cancel" class="btn btn-secondary">Cancel</button>
    </form>
</div>

</body>
</html>
