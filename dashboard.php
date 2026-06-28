<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/auth.php';
if ($_SESSION['role'] !== 'admin') { header("Location: ../login.php"); exit; }
require_once __DIR__ . '/../includes/header.php';

$total_customers = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) c FROM customers"))['c'];
$total_orders    = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) c FROM orders"))['c'];
$pending_orders  = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) c FROM orders WHERE order_status='Pending'"))['c'];
$delivered       = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) c FROM orders WHERE order_status='Delivered'"))['c'];
$revenue         = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COALESCE(SUM(payment_amount),0) s FROM payments"))['s'];
$today_del       = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) c FROM orders WHERE delivery_date=CURDATE()"))['c'];

$recent_orders = mysqli_query($conn,"
  SELECT o.*,c.customer_name FROM orders o
  JOIN customers c ON o.customer_id=c.id
  ORDER BY o.id DESC LIMIT 8");
?>
<?php include '../includes/navbar.php'; ?>
<div class="app-layout">
<?php include '../includes/sidebar.php'; ?>
<div class="main-wrap">
<div class="page-content">

<div class="page-header">
  <div class="breadcrumb-bar"><a href="#">Home</a><span class="sep">/</span><span>Dashboard</span></div>
  <h2>Dashboard</h2>
  <p>Welcome back, <?php echo htmlspecialchars($_SESSION['full_name'] ?? 'Admin'); ?> — here's your atelier overview.</p>
</div>

<!-- STAT CARDS -->
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:18px;margin-bottom:28px;">
  <div class="stat-card">
    <div class="stat-icon gold"><i class="bi bi-people"></i></div>
    <div class="stat-value"><?php echo $total_customers; ?></div>
    <div class="stat-label">Total Customers</div>
  </div>
  <div class="stat-card">
    <div class="stat-icon blue"><i class="bi bi-bag-check"></i></div>
    <div class="stat-value"><?php echo $total_orders; ?></div>
    <div class="stat-label">Total Orders</div>
  </div>
  <div class="stat-card">
    <div class="stat-icon red"><i class="bi bi-hourglass-split"></i></div>
    <div class="stat-value"><?php echo $pending_orders; ?></div>
    <div class="stat-label">Pending Orders</div>
  </div>
  <div class="stat-card">
    <div class="stat-icon green"><i class="bi bi-check-circle"></i></div>
    <div class="stat-value"><?php echo $delivered; ?></div>
    <div class="stat-label">Delivered</div>
  </div>
  <div class="stat-card">
    <div class="stat-icon gold"><i class="bi bi-cash-coin"></i></div>
    <div class="stat-value">Rs <?php echo number_format($revenue); ?></div>
    <div class="stat-label">Total Revenue</div>
  </div>
  <div class="stat-card">
    <div class="stat-icon blue"><i class="bi bi-truck"></i></div>
    <div class="stat-value"><?php echo $today_del; ?></div>
    <div class="stat-label">Today's Deliveries</div>
  </div>
</div>

<!-- RECENT ORDERS -->
<div class="tms-card">
  <div class="tms-card-header">
    <h5><i class="bi bi-bag-check me-2" style="color:var(--gold2)"></i>Recent Orders</h5>
    <a href="orders/index.php" class="btn-tms btn-ghost btn-sm-tms">View All</a>
  </div>
  <div class="tms-table-wrap">
    <table class="tms-table">
      <thead><tr>
        <th>#</th><th>Customer</th><th>Dress</th>
        <th>Total</th><th>Status</th><th>Delivery</th><th>Action</th>
      </tr></thead>
      <tbody>
      <?php if(mysqli_num_rows($recent_orders)>0):
        while($r=mysqli_fetch_assoc($recent_orders)):
          $badges=['Pending'=>'badge-pending','In Progress'=>'badge-progress','Ready'=>'badge-ready','Delivered'=>'badge-delivered','Cancelled'=>'badge-cancelled'];
          $bc=$badges[$r['order_status']]??'badge-pending';
      ?>
      <tr>
        <td style="color:var(--muted)">#<?php echo $r['id']; ?></td>
        <td><?php echo htmlspecialchars($r['customer_name']); ?></td>
        <td><?php echo htmlspecialchars($r['dress_type']); ?></td>
        <td>Rs <?php echo number_format($r['total_amount']); ?></td>
        <td><span class="badge-tms <?php echo $bc; ?>"><?php echo $r['order_status']; ?></span></td>
        <td style="color:var(--muted)"><?php echo $r['delivery_date'] ? date('d M Y',strtotime($r['delivery_date'])) : '—'; ?></td>
        <td><a href="orders/view.php?id=<?php echo $r['id']; ?>" class="btn-tms btn-info-tms btn-sm-tms"><i class="bi bi-eye"></i></a></td>
      </tr>
      <?php endwhile; else: ?>
      <tr><td colspan="7"><div class="empty-state"><i class="bi bi-bag-x"></i><p>No orders yet</p></div></td></tr>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

</div><!-- /page-content -->
</div><!-- /main-wrap -->
</div><!-- /app-layout -->
<?php require_once '../includes/footer.php'; ?>