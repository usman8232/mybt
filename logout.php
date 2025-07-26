<?php
include 'db_actions.php';
logout();

?>

<div class="container my-5">
    <h2 class="text-center mb-4">Logout</h2>
    <section id="logout" class="text-center">
        <div class="row justify-content-center">
            <!-- Card -->
            <div class="col-lg-6">
                <div class="card h-100 bg-success">
                    <div class="card-body">
                        <h5 class="card-title text-light">Session Expired</h5>
                        <p class="card-text text-light">
                            You have been logged out successfully.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php
include 'includes/footer.php';
?>