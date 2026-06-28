<?php
require_once '../../includes/auth.php';
require_once '../../config/database.php';
require_once '../../includes/header.php';
$error='';
if($_SERVER['REQUEST_METHOD']=='POST'){
  $name   =mysqli_real_escape_string($conn,trim($_POST['name']));
  $phone  =mysqli_real_escape_string($conn,trim($_POST['phone']));
  $email  =mysqli_real_escape_string($conn,trim($_POST['email']??''));
  $gender =mysqli_real_escape_string($conn,$_POST['gender']);
  $address=mysqli_real_escape_string($conn,trim($_POST['address']??''));
  if(empty($name)||empty($phone)){$error="Name and phone are required.";}
  else{
    $r=mysqli_query($conn,"INSERT INTO customers(customer_name,phone,email,gender,address) VALUES('$name','$phone','$email','$gender','$address')");
    if($r){$_SESSION['success']="Customer added.";header("Location: index.php");exit;}
    else{$error="DB error: ".mysqli_error($conn);}
  }
}
?>
<?php include '../../includes/navbar.php'; ?>
<div class="app-layout">
<?php include '../../includes/sidebar.php'; ?>
<div class="main-wrap"><div class="page-content">
<div class="page-header">
  <div class="breadcrumb-bar"><a href="../dashboard.php">Home</a><span class="sep">/</span><a href="index.php">Customers</a><span class="sep">/</span><span>Add</span></div>
  <h2>Add Customer</h2>
</div>
<?php if($error):?><div class="alert-tms alert-danger-tms"><i class="bi bi-exclamation-circle"></i><?php echo $error;?></div><?php endif;?>
<div class="tms-card" style="max-width:640px;">
  <div class="tms-card-header"><h5><i class="bi bi-person-plus me-2" style="color:var(--gold2)"></i>Customer Information</h5></div>
  <div class="tms-card-body">
    <form method="POST">
      <div class="form-row">
        <div class="form-group"><label class="form-label-tms">Full Name *</label>
          <input type="text" name="name" class="form-control-tms" placeholder="Ali Hassan" required value="<?php echo htmlspecialchars($_POST['name']??'');?>"></div>
        <div class="form-group"><label class="form-label-tms">Phone *</label>
          <input type="text" name="phone" class="form-control-tms" placeholder="03001234567" required value="<?php echo htmlspecialchars($_POST['phone']??'');?>"></div>
      </div>
      <div class="form-row">
        <div class="form-group"><label class="form-label-tms">Email</label>
          <input type="email" name="email" class="form-control-tms" placeholder="email@example.com" value="<?php echo htmlspecialchars($_POST['email']??'');?>"></div>
        <div class="form-group"><label class="form-label-tms">Gender</label>
          <select name="gender" class="form-control-tms"><option>Male</option><option>Female</option><option>Other</option></select></div>
      </div>
      <div class="form-group"><label class="form-label-tms">Address</label>
        <textarea name="address" class="form-control-tms" rows="3" placeholder="Street, City"><?php echo htmlspecialchars($_POST['address']??'');?></textarea></div>
      <div style="display:flex;gap:12px;">
        <button type="submit" class="btn-tms btn-gold"><i class="bi bi-check-lg"></i> Save Customer</button>
        <a href="index.php" class="btn-tms btn-ghost"><i class="bi bi-x"></i> Cancel</a>
      </div>
    </form>
  </div>
</div>
</div></div></div>
<?php require_once '../../includes/footer.php';?>
