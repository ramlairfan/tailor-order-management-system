<?php
require_once '../../includes/auth.php';
require_once '../../config/database.php';
$id=(int)($_GET['id']??0);
$data=mysqli_fetch_assoc(mysqli_query($conn,"SELECT o.*,c.customer_name,c.phone,c.address,c.email FROM orders o JOIN customers c ON o.customer_id=c.id WHERE o.id=$id"));
if(!$data){header("Location: index.php");exit;}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Invoice #<?php echo $id;?> — Tailor Management</title>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,400&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
*{box-sizing:border-box;margin:0;padding:0;}
body{font-family:'DM Sans',sans-serif;background:#f4f0e8;color:#1a1200;padding:30px;}
.invoice{max-width:760px;margin:0 auto;background:#fff;border:1px solid #d4b060;border-radius:12px;overflow:hidden;}
.inv-header{background:linear-gradient(135deg,#080C18,#161D35);color:#EDE3C8;padding:36px 40px;display:flex;justify-content:space-between;align-items:flex-start;}
.inv-brand{font-family:'Cormorant Garamond',serif;font-size:28px;font-weight:300;color:#E8C27A;font-style:italic;}
.inv-brand small{display:block;font-size:11px;letter-spacing:.2em;text-transform:uppercase;color:#7A7D8C;margin-top:4px;font-style:normal;}
.inv-meta{text-align:right;font-size:13px;color:#7A7D8C;}
.inv-meta strong{color:#E8C27A;font-size:22px;display:block;}
.inv-body{padding:36px 40px;}
.inv-section{margin-bottom:28px;}
.inv-section h4{font-size:11px;letter-spacing:.2em;text-transform:uppercase;color:#9A7D3A;margin-bottom:14px;padding-bottom:8px;border-bottom:1px solid #e8d9b4;}
.inv-grid{display:grid;grid-template-columns:1fr 1fr;gap:8px 20px;}
.inv-row{display:flex;justify-content:space-between;font-size:13px;padding:5px 0;}
.inv-row span:first-child{color:#666;}
.inv-total-box{background:#f9f5eb;border:1px solid #d4b060;border-radius:10px;padding:20px 24px;margin-top:20px;}
.inv-total-row{display:flex;justify-content:space-between;font-size:14px;padding:7px 0;border-bottom:1px solid #e8d9b4;}
.inv-total-row:last-child{border:none;font-weight:700;font-size:16px;color:#1a1200;margin-top:8px;}
.inv-total-row.remaining{color:#c84b4b;}
.inv-footer{text-align:center;padding:24px;border-top:1px solid #e8d9b4;color:#888;font-size:12px;font-family:'Cormorant Garamond',serif;font-style:italic;font-size:15px;}
.status-badge{display:inline-block;padding:4px 12px;border-radius:20px;font-size:11px;font-weight:600;letter-spacing:.08em;background:#e8f5e9;color:#2e7d32;border:1px solid #a5d6a7;}
.print-bar{max-width:760px;margin:0 auto 20px;display:flex;gap:10px;}
.btn-print{padding:10px 24px;background:linear-gradient(135deg,#B8862E,#C9A84C);color:#1a1200;border:none;border-radius:8px;font-weight:600;cursor:pointer;font-size:13px;}
.btn-back{padding:10px 20px;background:transparent;border:1px solid #C6973F;border-radius:8px;color:#C6973F;font-size:13px;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;gap:6px;}
@media print{.print-bar{display:none;}body{background:#fff;padding:0;}.invoice{border:none;border-radius:0;}}
</style>
</head>
<body>
<div class="print-bar">
  <a href="index.php" class="btn-back">← Back</a>
  <button class="btn-print" onclick="window.print()">🖨 Print Invoice</button>
</div>
<div class="invoice">
  <div class="inv-header">
    <div>
      <div class="inv-brand">Tailor <em>Atelier</em><small>Management System</small></div>
    </div>
    <div class="inv-meta">
      <strong>INVOICE</strong>
      #<?php echo str_pad($id,4,'0',STR_PAD_LEFT);?><br>
      <?php echo date('d M Y');?><br>
      <span class="status-badge" style="margin-top:8px;display:inline-block;"><?php echo $data['order_status'];?></span>
    </div>
  </div>
  <div class="inv-body">
    <div class="inv-grid" style="margin-bottom:28px;">
      <div class="inv-section" style="margin:0">
        <h4>Bill To</h4>
        <div style="font-size:15px;font-weight:600;"><?php echo htmlspecialchars($data['customer_name']);?></div>
        <div style="font-size:13px;color:#666;margin-top:6px;"><?php echo htmlspecialchars($data['phone']);?></div>
        <?php if($data['email']):?><div style="font-size:13px;color:#666;"><?php echo htmlspecialchars($data['email']);?></div><?php endif;?>
        <?php if($data['address']):?><div style="font-size:13px;color:#666;margin-top:4px;"><?php echo htmlspecialchars($data['address']);?></div><?php endif;?>
      </div>
      <div class="inv-section" style="margin:0">
        <h4>Order Details</h4>
        <div class="inv-row"><span>Order Date</span><span><?php echo date('d M Y',strtotime($data['order_date']));?></span></div>
        <div class="inv-row"><span>Delivery Date</span><span><?php echo $data['delivery_date']?date('d M Y',strtotime($data['delivery_date'])):'TBD';?></span></div>
        <div class="inv-row"><span>Dress Type</span><span><?php echo htmlspecialchars($data['dress_type']);?></span></div>
        <div class="inv-row"><span>Fabric</span><span><?php echo htmlspecialchars($data['fabric_type']??'—');?></span></div>
        <div class="inv-row"><span>Quantity</span><span><?php echo $data['quantity'];?></span></div>
      </div>
    </div>
    <div class="inv-total-box">
      <div class="inv-total-row"><span>Total Amount</span><span>Rs <?php echo number_format($data['total_amount'],2);?></span></div>
      <div class="inv-total-row"><span>Advance Paid</span><span style="color:#2e7d32">- Rs <?php echo number_format($data['advance_payment'],2);?></span></div>
      <div class="inv-total-row remaining"><span>Balance Due</span><span>Rs <?php echo number_format($data['remaining_amount'],2);?></span></div>
    </div>
    <?php if($data['notes']):?>
    <div style="margin-top:20px;padding:14px;background:#f9f5eb;border-radius:8px;font-size:13px;color:#666;">
      <strong style="color:#1a1200;">Notes:</strong> <?php echo htmlspecialchars($data['notes']);?>
    </div>
    <?php endif;?>
  </div>
  <div class="inv-footer">Thank you for trusting us with your attire — we stitch with love ✦</div>
</div>
</body>
</html>
