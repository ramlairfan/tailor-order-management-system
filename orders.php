<?php

require_once '../includes/auth.php';
require_once '../config/database.php';
require_once '../includes/header.php';

if ($_SESSION['role'] != 'user') {
    header("Location: ../admin/dashboard.php");
    exit;
}

$uid = $_SESSION['user_id'];

?>

<?php include '../includes/navbar.php'; ?>

<div class="d-flex">

<?php include '../includes/sidebar.php'; ?>

<div class="container-fluid p-4">

    <h3>My Orders</h3>

    <table class="table table-bordered table-striped">

        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Dress</th>
                <th>Total</th>
                <th>Status</th>
                <th>Delivery</th>
            </tr>
        </thead>

        <tbody>

        <?php

        $query = mysqli_query($conn, "
            SELECT * FROM orders 
            WHERE customer_id='$uid'
            ORDER BY id DESC
        ");

        if (mysqli_num_rows($query) > 0) {

            while($row = mysqli_fetch_assoc($query)) {

        ?>

            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['dress_type']; ?></td>
                <td>Rs <?php echo $row['total_amount']; ?></td>

                <td>
                    <span class="badge bg-warning">
                        <?php echo $row['order_status']; ?>
                    </span>
                </td>

                <td><?php echo $row['delivery_date']; ?></td>
            </tr>

        <?php
            }
        } else {
        ?>

            <tr>
                <td colspan="5" class="text-center text-muted">
                    No orders found
                </td>
            </tr>

        <?php } ?>

        </tbody>

    </table>

</div>

</div>

<?php require_once '../includes/footer.php'; ?>