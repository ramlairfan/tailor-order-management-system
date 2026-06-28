<?php
require_once '../../includes/auth.php';
require_once '../../config/database.php';
require_once '../../includes/header.php';
$id=(int)($_GET['id']??0);
$data=mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM customers WHERE id=$id"));
if(!$data){header("Location: index.php");exit;}
$orders=mysqli_query($conn,"SELECT * FROM orders WHERE customer_id=$id ORDER BY id DESC");
$meas=mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM measurements WHERE customer_id=$id ORDER BY id DESC LIMIT 1"));
?>
<?php include '../../includes/navbar.php';?>
<div class="app-layout">
<?php include '../../includes/sidebar.php';?>
<div class="main-wrap"><div class="page-content">
<div class="page-header">
  <div class="breadcrumb-bar"><a href="../dashboard.php">Home</a><span class="sep">/</span><a href="index.php">Customers</a><span class="sep">/</span><span>View</span></div>
  <h2><?php echo htmlspecialchars($data['customer_name']);?></h2>
</div>
<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px;">
  <div class="tms-card">
    <div class="tms-card-header"><h5><i class="bi bi-person me-2" style="color:var(--gold2)"></i>Customer Details</h5>
      <a href="edit.php?id=<?php echo $id;?>" class="btn-tms btn-ghost btn-sm-tms"><i class="bi bi-pencil"></i> Edit</a></div>
    <div class="tms-card-body" style="display:flex;flex-direction:column;gap:12px;">
      <?php foreach(['customer_name'=>'Name','phone'=>'Phone','email'=>'Email','gender'=>'Gender','address'=>'Address'] as $k=>$l):?>
      <div style="display:flex;justify-content:space-between;padding-bottom:10px;border-bottom:1px solid var(--border);">
        <span style="color:var(--muted);font-size:12px;text-transform:uppercase;letter-spacing:.1em;"><?php echo $l;?></span>
        <span><?php echo htmlspecialchars($data[$k]??'—');?></span>
      </div>
      <?php endforeach;?>
    </div>
  </div>
  <?php if($meas):?>
  <div class="tms-card">
    <div class="tms-card-header"><h5><i class="bi bi-rulers me-2" style="color:var(--gold2)"></i>Latest Measurements</h5></div>
    <div class="tms-card-body" style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
      <?php foreach(['chest'=>'Chest','waist'=>'Waist','shoulder'=>'Shoulder','sleeve'=>'Sleeve','length_value'=>'Length'] as $k=>$l):?>
      <div style="background:var(--navy3);border-radius:10px;padding:14px;text-align:center;">
        <div style="font-size:20px;color:var(--gold2);font-weight:600;"><?php echo $meas[$k]??'—';?>"</div>
        <div style="font-size:11px;color:var(--muted);margin-top:4px;"><?php echo $l;?></div>
      </div>
      <?php endforeach;?>
    </div>
  </div>
  <?php endif;?>
</div>
<div class="tms-card">
  <div class="tms-card-header"><h5><i class="bi bi-bag-check me-2" style="color:var(--gold2)"></i>Orders</h5></div>
  <div class="tms-table-wrap">
    <table class="tms-table">
      <thead><tr><th>#</th><th>Dress</th><th>Total</th><th>Status</th><th>Delivery</th><th>Action</th></tr></thead>
      <tbody>
      <?php $badges=['Pending'=>'badge-pending','In Progress'=>'badge-progress','Ready'=>'badge-ready','Delivered'=>'badge-delivered','Cancelled'=>'badge-cancelled'];
      if(mysqli_num_rows($orders)>0): while($o=mysqli_fetch_assoc($orders)): $bc=$badges[$o['order_status']]??'badge-pending';?>
      <tr>
        <td style="color:var(--muted)">#<?php echo $o['id'];?></td>
        <td><?php echo htmlspecialchars($o['dress_type']);?></td>
        <td>Rs <?php echo number_format($o['total_amount']);?></td>
        <td><span class="badge-tms <?php echo $bc;?>"><?php echo $o['order_status'];?></span></td>
        <td style="color:var(--muted)"><?php echo $o['delivery_date']?date('d M Y',strtotime($o['delivery_date'])):'—';?></td>
        <td><a href="../orders/view.php?id=<?php echo $o['id'];?>" class="btn-tms btn-info-tms btn-sm-tms"><i class="bi bi-eye"></i></a></td>
      </tr>
      <?php endwhile; else:?>
      <tr><td colspan="6"><div class="empty-state"><i class="bi bi-bag-x"></i><p>No orders</p></div></td></tr>
      <?php endif;?>
      </tbody>
    </table>
  </div>
</div>
</div></div></div>
<?php require_once '../../includes/footer.php';?>
