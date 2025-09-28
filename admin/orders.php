<?php
session_start();

require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/csrf.php';

// Get database connection
$pdo = require __DIR__ . '/../db/connection.php';

// Include header (which includes require_admin())
require_once __DIR__ . '/includes/header.php';

// Handle status updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_status') {
    if (csrf_verify($_POST['_csrf'] ?? '')) {
        $order_id = (int)($_POST['order_id'] ?? 0);
        $status = $_POST['status'] ?? '';
        $notes = trim($_POST['notes'] ?? '');
        
        if ($order_id > 0) {
            $stmt = $pdo->prepare('UPDATE orders SET status = ?, notes = ?, updated_at = NOW() WHERE id = ?');
            $stmt->execute([$status, $notes, $order_id]);
            header('Location: orders.php?updated=1');
            exit;
        }
    }
}

// Get orders with user info
$stmt = $pdo->query('
    SELECT o.*, u.username as customer_name, p.name as package_name 
    FROM orders o 
    LEFT JOIN users u ON o.user_id = u.id 
    LEFT JOIN packages p ON o.package_id = p.id 
    ORDER BY o.created_at DESC
');
$orders = $stmt->fetchAll();
?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0">Manage Orders</h5>
    </div>

    <?php if (isset($_GET['updated'])): ?>
        <div class="alert alert-success">Order status updated successfully.</div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Package</th>
                            <th>Amount</th>
                            <th>Network</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td>#<?php echo $order['id']; ?></td>
                                <td><?php echo htmlspecialchars($order['customer_name'] ?? 'Guest'); ?></td>
                                <td><?php echo htmlspecialchars($order['package_name']); ?></td>
                                <td>Tsh <?php echo number_format($order['amount_paid'],0); ?></td>
                                <td><?php echo htmlspecialchars($order['network']); ?></td>
                                <td><?php echo htmlspecialchars($order['phone']); ?></td>
                                <td>
                                    <span class="badge bg-<?php
                                        echo match($order['status']) {
                                            'pending' => 'warning',
                                            'awaiting_confirmation' => 'info',
                                            'confirmed' => 'primary',
                                            'delivered' => 'success',
                                            'cancelled' => 'danger',
                                            default => 'secondary'
                                        };
                                    ?>">
                                        <?php echo ucfirst($order['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('M j, Y', strtotime($order['created_at'])); ?></td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-primary" 
                                            onclick="viewOrder(<?php echo htmlspecialchars(json_encode($order), ENT_QUOTES, 'UTF-8'); ?>)">
                                        Update Status
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Status Update Modal -->
<div class="modal fade" id="updateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Order Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="action" value="update_status">
                <input type="hidden" name="order_id" id="modal_order_id">
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="pending">Pending</option>
                            <option value="awaiting_confirmation">Awaiting Confirmation</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="delivered">Delivered</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function viewOrder(order) {
    document.getElementById('modal_order_id').value = order.id;
    document.querySelector('select[name="status"]').value = order.status;
    document.querySelector('textarea[name="notes"]').value = order.notes || '';
    
    new bootstrap.Modal(document.getElementById('updateModal')).show();
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
