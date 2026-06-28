<?php
require_once '../../includes/auth.php';
require_once '../../config/database.php';
$id=(int)($_GET['id']??0);
if($id) mysqli_query($conn,"UPDATE orders SET order_status='Delivered' WHERE id=$id");
$_SESSION['success']="Order marked as delivered.";
header("Location: index.php");exit;
