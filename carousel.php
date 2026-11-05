<div class="container">
    <div class="row justify-content-center">
        <br>
    </div>
    <div>
        <img class='background img-fluid' src='images/index/img.png'>
    </div>

    <div class="text-center my-4">
        <h2 class="font-weight-bold">Your small help can bring a big change!</h2>
    </div>

    <!-- Volunteer & Donor Tabs -->
     <center>
    <div class="container my-5 text-center">
        <ul class="nav nav-tabs" id="volunteerDonorTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="volunteer-tab" data-toggle="tab" href="#volunteer" role="tab">Volunteer</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="donor-tab" data-toggle="tab" href="#donor" role="tab">Donate</a>
            </li>
        </ul>
        <div class="tab-content" id="volunteerDonorTabsContent">
            <div class="tab-pane fade show active" id="volunteer" role="tabpanel">
                <h2 class="font-weight-bold mt-3">Why Become a Volunteer?</h2>
                <p>Make an impact, build new skills, and be part of a passionate community helping those in need.</p>
                <a href="signup/volunteerSignup.php" class="btn btn-primary">Join as Volunteer</a>
            </div>
            <div class="tab-pane fade" id="donor" role="tabpanel">
                <h2 class="font-weight-bold mt-3">Want to Make a Donation?</h2>
                <p>Your generosity helps us serve the needy and bring smiles to many faces.</p>
                <a href="signup/donorSignup.php" class="btn btn-danger">Donate Now</a>
            </div>
        </div>
    </div>
</center>
    <!-- Impact Section -->
    <div class="container text-center my-5">
        <h2 class="font-weight-bold">Our Impact</h2>
        <div class="row">
            <div class="col-md-4">
                <h3>500+</h3>
                <p>Volunteers</p>
            </div>
            <div class="col-md-4">
                <h3>10,000+</h3>
                <p>People Helped</p>
            </div>
            <div class="col-md-4">
                <h3>50+</h3>
                <p>Fundraising Events</p>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="text-white text-center py-3" style="background-color: #2C3E50; width: 100%;">
    <div class="container-fluid">
        <p>&copy; 2025 Charity Foundation. All Rights Reserved.</p>
        <p>Follow us on 
            <a href="#" class="text-white">Facebook</a> | 
            <a href="#" class="text-white">Instagram</a> | 
            <a href="#" class="text-white">Twitter</a>
        </p>
    </div>
</footer>

<style>
    .background {
        width: 100%;
        height: auto;
    }
    .nav-tabs .nav-link {
        color: #2C3E50;
    }
    .nav-tabs .nav-link.active {
        background-color: #2C3E50;
        color: white;
    }
</style>
