<?php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/header.php';

$search = mysqli_real_escape_string($conn, $_GET['search'] ?? '');
$query = "SELECT * FROM customers
          WHERE customer_name LIKE '%$search%' OR phone LIKE '%$search%'
          ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>
<?php include '../../includes/navbar.php'; ?>
<div class="app-layout">
<?php include '../../includes/sidebar.php'; ?>
<div class="main-wrap"><div class="page-content">

<div class="page-header">
  <div class="breadcrumb-bar"><a href="../dashboard.php">Home</a><span class="sep">/</span><span>Customers</span></div>
  <h2>Customers</h2>
</div>

<?php if(isset($_SESSION['success'])): ?>
<div class="alert-tms alert-success-tms"><i class="bi bi-check-circle"></i><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
<?php endif; ?>

<div class="tms-card">
  <div class="tms-card-header">
    <h5><i class="bi bi-people me-2" style="color:var(--gold2)"></i>All Customers</h5>
    <div style="display:flex;gap:12px;align-items:center;">
      <form method="GET" style="margin:0;">
        <div class="search-wrap">
          <i class="bi bi-search"></i>
          <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>"
                 placeholder="Search name or phone…" class="form-control-tms" style="padding-left:38px;">
        </div>
      </form>
      <a href="create.php" class="btn-tms btn-gold"><i class="bi bi-plus-lg"></i> Add Customer</a>
    </div>
  </div>
  <div class="tms-table-wrap">
    <table class="tms-table">
      <thead><tr><th>#</th><th>Name</th><th>Phone</th><th>Email</th><th>Gender</th><th>Actions</th></tr></thead>
      <tbody>
      <?php if(mysqli_num_rows($result)>0):
        while($row=mysqli_fetch_assoc($result)): ?>
      <tr>
        <td style="color:var(--muted)"><?php echo $row['id']; ?></td>
        <td><strong><?php echo htmlspecialchars($row['customer_name']); ?></strong></td>
        <td><?php echo htmlspecialchars($row['phone']); ?></td>
        <td style="color:var(--muted)"><?php echo htmlspecialchars($row['email'] ?? '—'); ?></td>
        <td><?php echo htmlspecialchars($row['gender'] ?? '—'); ?></td>
        <td style="display:flex;gap:6px;">
          <a href="view.php?id=<?php echo $row['id']; ?>" class="btn-tms btn-info-tms btn-sm-tms"><i class="bi bi-eye"></i></a>
          <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn-tms btn-ghost btn-sm-tms"><i class="bi bi-pencil"></i></a>
          <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn-tms btn-danger-tms btn-sm-tms" onclick="return confirm('Delete this customer?')"><i class="bi bi-trash"></i></a>
        </td>
      </tr>
      <?php endwhile; else: ?>
      <tr><td colspan="6"><div class="empty-state"><i class="bi bi-people"></i><p>No customers found</p></div></td></tr>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

</div></div></div>
<?php require_once '../../includes/footer.php'; ?>