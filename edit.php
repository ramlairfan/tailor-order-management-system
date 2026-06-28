<?php
require_once '../../includes/auth.php';
require_once '../../config/database.php';
require_once '../../includes/header.php';
$id=(int)($_GET['id']??0);
$data=mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM customers WHERE id=$id"));
if(!$data){header("Location: index.php");exit;}
$error='';
if($_SERVER['REQUEST_METHOD']=='POST'){
  $name   =mysqli_real_escape_string($conn,trim($_POST['name']));
  $phone  =mysqli_real_escape_string($conn,trim($_POST['phone']));
  $email  =mysqli_real_escape_string($conn,trim($_POST['email']??''));
  $gender =mysqli_real_escape_string($conn,$_POST['gender']);
  $address=mysqli_real_escape_string($conn,trim($_POST['address']??''));
  if(empty($name)||empty($phone)){$error="Name and phone are required.";}
  else{
    mysqli_query($conn,"UPDATE customers SET customer_name='$name',phone='$phone',email='$email',gender='$gender',address='$address' WHERE id=$id");
    $_SESSION['success']="Customer updated.";header("Location: index.php");exit;
  }
}
?>
<?php include '../../includes/navbar.php';?>
<div class="app-layout">
<?php include '../../includes/sidebar.php';?>
<div class="main-wrap"><div class="page-content">
<div class="page-header">
  <div class="breadcrumb-bar"><a href="../dashboard.php">Home</a><span class="sep">/</span><a href="index.php">Customers</a><span class="sep">/</span><span>Edit</span></div>
  <h2>Edit Customer</h2>
</div>
<?php if($error):?><div class="alert-tms alert-danger-tms"><i class="bi bi-exclamation-circle"></i><?php echo $error;?></div><?php endif;?>
<div class="tms-card" style="max-width:640px;">
  <div class="tms-card-header"><h5><i class="bi bi-pencil-square me-2" style="color:var(--gold2)"></i>Edit Information</h5></div>
  <div class="tms-card-body">
    <form method="POST">
      <div class="form-row">
        <div class="form-group"><label class="form-label-tms">Full Name *</label>
          <input type="text" name="name" class="form-control-tms" required value="<?php echo htmlspecialchars($data['customer_name']);?>"></div>
        <div class="form-group"><label class="form-label-tms">Phone *</label>
          <input type="text" name="phone" class="form-control-tms" required value="<?php echo htmlspecialchars($data['phone']);?>"></div>
      </div>
      <div class="form-row">
        <div class="form-group"><label class="form-label-tms">Email</label>
          <input type="email" name="email" class="form-control-tms" value="<?php echo htmlspecialchars($data['email']??'');?>"></div>
        <div class="form-group"><label class="form-label-tms">Gender</label>
          <select name="gender" class="form-control-tms">
            <?php foreach(['Male','Female','Other'] as $g):?>
            <option <?php if(($data['gender']??'')==$g) echo 'selected';?>><?php echo $g;?></option>
            <?php endforeach;?>
          </select></div>
      </div>
      <div class="form-group"><label class="form-label-tms">Address</label>
        <textarea name="address" class="form-control-tms" rows="3"><?php echo htmlspecialchars($data['address']??'');?></textarea></div>
      <div style="display:flex;gap:12px;">
        <button type="submit" class="btn-tms btn-gold"><i class="bi bi-check-lg"></i> Update</button>
        <a href="index.php" class="btn-tms btn-ghost"><i class="bi bi-x"></i> Cancel</a>
      </div>
    </form>
  </div>
</div>
</div></div></div>
<?php require_once '../../includes/footer.php';?>
