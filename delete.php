<?php
require_once '../../includes/auth.php';
require_once '../../config/database.php';
$id=(int)($_GET['id']??0);
if($id) mysqli_query($conn,"DELETE FROM customers WHERE id=$id");
$_SESSION['success']="Customer deleted.";
header("Location: index.php");exit;
