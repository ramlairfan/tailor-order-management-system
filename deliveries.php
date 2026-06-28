<?php

require_once '../includes/auth.php';
require_once '../config/database.php';
require_once '../includes/header.php';

$uid = $_SESSION['user_id'];

?>

<?php include '../includes/navbar.php'; ?>

<div class="d-flex">

<?php include '../includes/sidebar.php'; ?>

<div class="container p-4">

    <h3>My Deliveries</h3>

    <table class="table table-bordered">

        <thead class="table-dark">
            <tr>
                <th>Order ID</th>
                <th>Status</th>
                <th>Delivery Date</th>
            </tr>
        </thead>

        <tbody>

        <?php

        $query = mysqli_query($conn, "
            SELECT id, order_status, delivery_date
            FROM orders
            WHERE customer_id='$uid'
        ");

        while($row = mysqli_fetch_assoc($query)) {

        ?>

            <tr>
                <td><?php echo $row['id']; ?></td>

                <td>
                    <span class="badge bg-info">
                        <?php echo $row['order_status']; ?>
                    </span>
                </td>

                <td><?php echo $row['delivery_date']; ?></td>
            </tr>

        <?php } ?>

        </tbody>

    </table>

</div>

</div>

<?php require_once '../includes/footer.php'; ?>