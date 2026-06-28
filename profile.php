<?php

require_once '../includes/auth.php';
require_once '../config/database.php';
require_once '../includes/header.php';

$uid = $_SESSION['user_id'];

$data = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT * FROM users WHERE id='$uid'")
);

?>

<?php include '../includes/navbar.php'; ?>

<div class="container p-4">

    <h3>My Profile</h3>

    <div class="card p-4">

        <p><b>Name:</b> <?php echo $data['name']; ?></p>
        <p><b>Email:</b> <?php echo $data['email']; ?></p>
        <p><b>Role:</b> <?php echo $data['role']; ?></p>

    </div>

</div>

<?php require_once '../includes/footer.php'; ?>