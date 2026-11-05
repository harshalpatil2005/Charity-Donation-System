
<?php
session_start();
include('pdo.php'); // Ensure pdo.php sets up $pdo

include('../bootstrap.php');  // जर मुख्य फोल्डरमध्ये असेल तर

// // Check if admin is logged in
// if (!isset($_SESSION['adminLogged_in'])) {
//     header("Location: adminLogin.php");
//     exit();
// }

// Handle remove donor action
if (isset($_POST['remove_donor'])) {
    $donor_id = $_POST['donor_id'];
    $query = "DELETE FROM donor WHERE id = :donor_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":donor_id", $donor_id, PDO::PARAM_INT);
    
    if ($stmt->execute()) {
        $success_message = "Donor removed successfully!";
    } else {
        $error_message = "Failed to remove donor.";
    }
}

// Fetch donors grouped by city
$query = "SELECT city, id, name, email, amount FROM donor ORDER BY city";
$stmt = $pdo->query($query);
$donors_by_city = [];

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $donors_by_city[$row['city']][] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 20px;
        }
        .navbar {
            background-color: #007bff;
        }
        .navbar-brand, .nav-link {
            color: white !important;
        }
        .nav-link:hover {
            text-decoration: underline;
        }
        .card {
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
        }
        .table th {
            background-color: #007bff;
            color: white;
        }
        .remove-btn {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
        }
        .remove-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="#">Admin Panel</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="edit_profile.php">Edit Profile</a></li>
                <li class="nav-item"><a class="nav-link" href="volunteerindex.php">Volunteers</a></li>
                <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    <h2 class="mt-3">Donors List</h2>

    <?php if (isset($success_message)): ?>
        <div class="alert alert-success"><?= $success_message; ?></div>
    <?php endif; ?>
    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger"><?= $error_message; ?></div>
    <?php endif; ?>

    <?php if (empty($donors_by_city)): ?>
        <div class="alert alert-info">No donors available.</div>
    <?php else: ?>
        <?php foreach ($donors_by_city as $city => $donors): ?>
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0"><?= htmlspecialchars($city); ?></h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Donation (₹)</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($donors as $index => $donor): ?>
                                <tr>
                                    <td><?= $index + 1; ?></td>
                                    <td><?= htmlspecialchars($donor['name']); ?></td>
                                    <td><?= htmlspecialchars($donor['email']); ?></td>
                                    <td>₹ <?= number_format($donor['amount'], 2); ?></td>
                                    <td>
                                        <form method="post" style="display:inline;">
                                            <input type="hidden" name="donor_id" value="<?= $donor['id']; ?>">
                                            <button type="submit" name="remove_donor" class="remove-btn">Remove</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

</body>
</html>
