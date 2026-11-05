<?php
require_once "../pdo.php";
session_start();

if (isset($_POST['cancel'])) {
    header('Location: ../login/volunteerLogin.php');
    return;
}

if (isset($_POST['username'])) {
    if (!empty($_POST['username']) && !empty($_POST['password'])) {
        $stmt = $pdo->prepare("SELECT username FROM volunteer_login WHERE username = :username");
        $stmt->execute([':username' => $_POST['username']]);
        $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingUser) {
            $_SESSION['error'] = "Username already exists. Choose a different username.";
            header('Location: volunteerSignup.php');
            return;
        }

        if (!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['intrests']) && !empty($_POST['date']) && !empty($_POST['city']) && !empty($_POST['phone'])) {
            $stmt = $pdo->prepare("SELECT email FROM volunteer WHERE email = :email");
            $stmt->execute([':email' => $_POST['email']]);
            $existingEmail = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($existingEmail) {
                $_SESSION['error'] = "Email already exists. Choose a different email.";
                header('Location: volunteerSignup.php');
                return;
            }

            $stmt = $pdo->prepare("SELECT phone FROM volunteer WHERE phone = :phone");
            $stmt->execute([':phone' => $_POST['phone']]);
            $existingPhone = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($existingPhone) {
                $_SESSION['error'] = "Phone number already exists. Choose a different phone number.";
                header('Location: volunteerSignup.php');
                return;
            }

            $stmt = $pdo->prepare("INSERT INTO volunteer (name, email, intrests, dob, city_id, phone) 
                                   VALUES (:name, :email, :intrests, :dob, :city_id, :phone)");
            $stmt->execute([
                ':name' => $_POST['name'],
                ':email' => $_POST['email'],
                ':intrests' => $_POST['intrests'],
                ':dob' => $_POST['date'],
                ':city_id' => $_POST['city'],
                ':phone' => $_POST['phone']
            ]);

            $volunteer_id = $pdo->lastInsertId();

            $stmt = $pdo->prepare("INSERT INTO volunteer_login (username, password, volunteer_id) 
                                   VALUES (:username, :password, :volunteer_id)");
            $stmt->execute([
                ':username' => $_POST['username'],
                ':password' => $_POST['password'], // Plain text password as requested
                ':volunteer_id' => $volunteer_id
            ]);

            $_SESSION['success'] = "Registration successful!";
            header('Location: ../login/volunteerLogin.php');
            return;
        } else {
            $_SESSION['error'] = "All fields are required!";
            header("Location: volunteerSignup.php");
            return;
        }
    }
}

$stmt = $pdo->query("SELECT * FROM city");
$cities = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volunteer Signup</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f8f9fa;
            margin: 0;
        }
        .form-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
    </style>
</head>
<body>
<div class="form-container">
    <?php
    if (isset($_SESSION['error'])) {
        echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
        unset($_SESSION['error']);
    }
    if (isset($_SESSION['success'])) {
        echo '<div class="alert alert-success">' . $_SESSION['success'] . '</div>';
        unset($_SESSION['success']);
    }
    ?>
    <form method="post">
        <h3 class="mb-4 text-center">Volunteer Signup</h3>

        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" name="username" id="username" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="name" class="form-label">Full Name</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="intrests" class="form-label">Address</label>
            <input type="text" name="intrests" id="intrests" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="date" class="form-label">Date of Birth</label>
            <input type="date" name="date" id="date" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">Phone Number</label>
            <input type="text" name="phone" id="phone" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="city" class="form-label">Select City</label>
            <select name="city" id="city" class="form-select" required>
                <option value="">--Select City--</option>
                <?php foreach ($cities as $city) : ?>
                    <option value="<?= $city['city_id']; ?>"><?= htmlentities($city['cname']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-primary">Register</button>
            <button type="submit" name="cancel" class="btn btn-secondary">Cancel</button>
        </div>
    </form>
</div>
</body>
</html>
