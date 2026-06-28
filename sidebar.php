<?php
$cur = basename($_SERVER['PHP_SELF']);
$path = $_SERVER['PHP_SELF'];
function sideActive($segment) {
    return strpos($_SERVER['PHP_SELF'], $segment) !== false ? 'active' : '';
}
?>
<aside class="sidebar">
  <div class="sidebar-logo">
    <div class="sidebar-logo-icon"><i class="bi bi-scissors"></i></div>
    <span class="sidebar-logo-text">Atelier</span>
  </div>

  <p class="sidebar-section">Main</p>
  <ul class="sidebar-nav">
    <li><a href="<?php echo BASE_URL; ?>admin/dashboard.php" class="<?php echo sideActive('dashboard'); ?>">
      <i class="bi bi-grid-1x2"></i> Dashboard</a></li>
  </ul>

  <p class="sidebar-section">Management</p>
  <ul class="sidebar-nav">
    <li><a href="<?php echo BASE_URL; ?>admin/customers/index.php" class="<?php echo sideActive('customers'); ?>">
      <i class="bi bi-people"></i> Customers</a></li>
    <li><a href="<?php echo BASE_URL; ?>admin/measurements/index.php" class="<?php echo sideActive('measurements'); ?>">
      <i class="bi bi-rulers"></i> Measurements</a></li>
    <li><a href="<?php echo BASE_URL; ?>admin/orders/index.php" class="<?php echo sideActive('orders'); ?>">
      <i class="bi bi-bag-check"></i> Orders</a></li>
    <li><a href="<?php echo BASE_URL; ?>admin/payments/index.php" class="<?php echo sideActive('payments'); ?>">
      <i class="bi bi-cash-coin"></i> Payments</a></li>
    <li><a href="<?php echo BASE_URL; ?>admin/deliveries/index.php" class="<?php echo sideActive('deliveries'); ?>">
      <i class="bi bi-truck"></i> Deliveries</a></li>
  </ul>

  <hr class="sidebar-divider">
  <div class="sidebar-bottom">
    <ul class="sidebar-nav">
      <li><a href="<?php echo BASE_URL; ?>logout.php">
        <i class="bi bi-box-arrow-left"></i> Logout</a></li>
    </ul>
  </div>
</aside>