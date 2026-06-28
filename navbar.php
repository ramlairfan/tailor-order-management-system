<div class="topbar">
  <span class="topbar-brand"><i class="bi bi-scissors me-2"></i>Tailor Management</span>
  <div class="topbar-right">
    <div class="topbar-user">
      <div class="topbar-avatar"><?php echo strtoupper(substr($_SESSION['full_name'] ?? 'A', 0, 1)); ?></div>
      <div><strong><?php echo htmlspecialchars($_SESSION['full_name'] ?? 'Admin'); ?></strong><br>
        <span style="font-size:11px;"><?php echo ucfirst($_SESSION['role'] ?? ''); ?></span></div>
    </div>
    <a href="<?php echo BASE_URL; ?>logout.php" class="btn-logout"><i class="bi bi-box-arrow-left"></i> Logout</a>
  </div>
</div>