<?php
include('pdo.php');
if (!isset($_SESSION['admin_id'])) {
    header("Location: adminLogin.php");
    exit();
}

$stmtAdmin = $pdo->prepare("SELECT `name` FROM `admin` WHERE `admin_id` = ?");
$stmtAdmin->execute([$_SESSION['admin_id']]);
$admin = $stmtAdmin->fetch(PDO::FETCH_ASSOC);

$stmtTotal = $pdo->query("SELECT SUM(donationS) AS total_donations FROM ngo_account");
$totalDonations = $stmtTotal->fetch(PDO::FETCH_ASSOC)['total_donations'] ?? 0;

$stmtCities = $pdo->query("SELECT * FROM city");
$cities =$stmtCities->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body { background-color: #f8f9fa; }
        h2 { font-weight: 700; text-align: center; margin-bottom: 40px; }
        .city-section { display: grid; grid-template-columns: 1fr 1fr; gap: 40px; }
        .city-card { background-color: white; padding: 20px; border: 1px solid #ddd; }
        .city-card h4 { border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-bottom: 20px; }
        table { margin-bottom: 0; }
        .table thead th {
        background-color: #000;
        color: #fff;
        border-color: #000;
}

    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <a class="navbar-brand" href="index.php">CHARITY</a>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item active"><a class="nav-link" href="#">Admin</a></li>
            <li class="nav-item"><a class="nav-link" href="logout.php"><?= htmlentities($admin['name'] ?? 'Admin') ?></a></li>
            <li class="nav-item"><a class="nav-link" href="update/adminUpdate.php">Edit Profile</a></li>
            <li class="nav-item"><a class="nav-link" href="adminVolunteer.php">Volunteers</a></li>
            <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
        </ul>
    </div>
</nav>

<div class="container">
<div class="container mb-4">
    <div class="card text-white bg-primary">
        <div class="card-body text-center">
            <h4 class="card-title">Total Donations Collected</h4>
            <h2 class="card-text">₹ <?php echo number_format($totalDonations, 2); ?></h2>
        </div>
    </div>
</div>

    <!-- <h2>Total Donations: ₹ <?= number_format($totalDonations, 2) ?></h2> -->

    <div class="city-section">
    <?php foreach ($cities as $city): 
        $stmtDonors = $pdo->prepare("SELECT ngo_account.donor_id, donor.name, ngo_account.donationS FROM donor JOIN ngo_account ON donor.donor_id = ngo_account.donor_id WHERE ngo_account.city_id = ?");
        $stmtDonors->execute([$city['city_id']]);
        $donors = $stmtDonors->fetchAll(PDO::FETCH_ASSOC);

        if ($donors): ?>
        <div class="city-card">
            <h4><?= htmlentities($city['cname']) ?> Donors</h4>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Amount</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $count = 1; foreach ($donors as $donor): ?>
                    <tr>
                        <td><?= $count++ ?></td>
                        <td><?= htmlentities($donor['name']) ?></td>
                        <td>₹ <?= number_format($donor['donationS'], 2) ?></td>
                        <td><a href="admin/delete.php?donor_id=<?= $donor['donor_id'] ?>" class="btn btn-danger btn-sm">Remove</a></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; endforeach; ?>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
