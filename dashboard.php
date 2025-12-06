<?php
session_start();
require "db.php";

// Check login
if (!isset($_SESSION['admin_logged']) || $_SESSION['admin_logged'] !== true) {
    header("Location: admin.php");
    exit;
}

// Pagination
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1) $page = 1;
$limit = 20;
$start = ($page - 1) * $limit;

// Total transactions
$totalRows = $conn->query("SELECT COUNT(*) as total FROM transactions")->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $limit);

// Fetch transactions
$rows = $conn->query("SELECT * FROM transactions ORDER BY id DESC LIMIT $start, $limit");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Transactions Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
</head>
<body class="container mt-5">

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Transactions Dashboard</h3>
    <div>
        <a href="dashboard.php?page=<?= $page ?>" class="btn btn-info me-2">Refresh</a>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
</div>

<?php if ($rows->num_rows == 0): ?>
    <div class="alert alert-info text-center">
        No records found.
    </div>
<?php else: ?>
<table class="table table-bordered table-striped table-hover align-middle text-center">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Authority</th>
            <th>RefID</th>
            <th>Amount</th>
            <th>Description</th>
            <th>Email</th>
            <th>Mobile</th>
            <th>Status</th>
            <th>Created At</th>
        </tr>
    </thead>
    <tbody>
    <?php while ($r = $rows->fetch_assoc()): ?>
        <tr>
            <td><?= $r['id'] ?></td>
            <td><?= htmlspecialchars($r['authority'] ?? '') ?></td>
            <td><?= htmlspecialchars($r['ref_id'] ?? '') ?></td>
            <td><?= number_format($r['amount']) ?> Toman</td>
            <td><?= htmlspecialchars($r['description'] ?? '') ?></td>
            <td><?= htmlspecialchars($r['email'] ?? '') ?></td>
            <td><?= htmlspecialchars($r['mobile'] ?? '') ?></td>
            <td>
                <?php 
                $status = $r['status'] ?? '';
                if ($status === 'success') echo '<span class="badge bg-success">Success</span>';
                elseif ($status === 'failed') echo '<span class="badge bg-danger">Failed</span>';
                elseif ($status === 'canceled') echo '<span class="badge bg-warning">Canceled</span>';
                else echo '<span class="badge bg-secondary">'.htmlspecialchars($status).'</span>';
                ?>
            </td>
            <td><?= $r['created_at'] ?></td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>

<div class="d-flex justify-content-between mt-4">
    <a href="dashboard.php?page=<?= max(1, $page - 1) ?>" class="btn btn-secondary <?= $page <= 1 ? 'disabled' : '' ?>">Previous</a>
    <a href="dashboard.php?page=<?= min($totalPages, $page + 1) ?>" class="btn btn-primary <?= $page >= $totalPages ? 'disabled' : '' ?>">Next</a>
</div>
<p class="text-center mt-3 text-muted">Page <?= $page ?> of <?= $totalPages ?></p>
<?php endif; ?>

</body>
</html>
