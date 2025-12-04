<?php
include 'includes/header.php';

// Handle appointment actions
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $appointment_id = (int)$_GET['id'];
    $admin_notes = isset($_POST['admin_notes']) ? trim($_POST['admin_notes']) : '';
    
    if ($action == 'approve') {
        $stmt = $conn->prepare("UPDATE appointments SET status = 'approved', admin_notes = ? WHERE id = ?");
        $stmt->bind_param("si", $admin_notes, $appointment_id);
        if ($stmt->execute()) {
            header("Location: manage-appointments.php?approved=1");
            exit();
        }
        $stmt->close();
    } elseif ($action == 'reject') {
        $stmt = $conn->prepare("UPDATE appointments SET status = 'rejected', admin_notes = ? WHERE id = ?");
        $stmt->bind_param("si", $admin_notes, $appointment_id);
        if ($stmt->execute()) {
            header("Location: manage-appointments.php?rejected=1");
            exit();
        }
        $stmt->close();
    } elseif ($action == 'complete') {
        $stmt = $conn->prepare("UPDATE appointments SET status = 'completed' WHERE id = ?");
        $stmt->bind_param("i", $appointment_id);
        if ($stmt->execute()) {
            header("Location: manage-appointments.php?completed=1");
            exit();
        }
        $stmt->close();
    } elseif ($action == 'cancel') {
        $stmt = $conn->prepare("UPDATE appointments SET status = 'cancelled' WHERE id = ?");
        $stmt->bind_param("i", $appointment_id);
        if ($stmt->execute()) {
            header("Location: manage-appointments.php?cancelled=1");
            exit();
        }
        $stmt->close();
    }
}

// Filter by status
$status_filter = isset($_GET['status']) ? $_GET['status'] : 'all';

$query = "SELECT a.*, u.name as user_name, u.email as user_email 
          FROM appointments a 
          LEFT JOIN users u ON a.user_id = u.id";
          
if ($status_filter != 'all') {
    $query .= " WHERE a.status = ?";
    $query .= " ORDER BY a.appointment_date ASC, a.appointment_time ASC";
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("s", $status_filter);
        $stmt->execute();
        $appointments_result = $stmt->get_result();
    } else {
        $appointments_result = false;
    }
} else {
    $query .= " ORDER BY a.appointment_date ASC, a.appointment_time ASC";
    $appointments_result = $conn->query($query);
}
?>

<div class="admin-page-header">
    <h1>Manage Appointments</h1>
</div>

<?php if (isset($_GET['approved'])): ?>
    <div class="admin-message success">
        <p>Appointment approved successfully.</p>
    </div>
<?php endif; ?>

<?php if (isset($_GET['rejected'])): ?>
    <div class="admin-message error">
        <p>Appointment rejected.</p>
    </div>
<?php endif; ?>

<?php if (isset($_GET['completed'])): ?>
    <div class="admin-message success">
        <p>Appointment marked as completed.</p>
    </div>
<?php endif; ?>

<?php if (isset($_GET['cancelled'])): ?>
    <div class="admin-message error">
        <p>Appointment cancelled.</p>
    </div>
<?php endif; ?>

<div class="admin-filters">
    <div class="filter-buttons">
        <a href="?status=all" class="filter-btn <?php echo $status_filter == 'all' ? 'active' : ''; ?>">All</a>
        <a href="?status=pending" class="filter-btn <?php echo $status_filter == 'pending' ? 'active' : ''; ?>">Pending</a>
        <a href="?status=approved" class="filter-btn <?php echo $status_filter == 'approved' ? 'active' : ''; ?>">Approved</a>
        <a href="?status=rejected" class="filter-btn <?php echo $status_filter == 'rejected' ? 'active' : ''; ?>">Rejected</a>
        <a href="?status=completed" class="filter-btn <?php echo $status_filter == 'completed' ? 'active' : ''; ?>">Completed</a>
        <a href="?status=cancelled" class="filter-btn <?php echo $status_filter == 'cancelled' ? 'active' : ''; ?>">Cancelled</a>
    </div>
</div>

<table class="admin-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>User</th>
            <th>Contact</th>
            <th>Date & Time</th>
            <th>Purpose</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($appointments_result && $appointments_result->num_rows > 0): ?>
            <?php while($appointment = $appointments_result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $appointment['id']; ?></td>
                <td>
                    <strong><?php echo htmlspecialchars($appointment['name']); ?></strong><br>
                    <small><?php echo htmlspecialchars($appointment['user_email']); ?></small>
                </td>
                <td>
                    <?php echo htmlspecialchars($appointment['email']); ?><br>
                    <small><?php echo htmlspecialchars($appointment['phone']); ?></small>
                </td>
                <td>
                    <strong><?php echo date('M d, Y', strtotime($appointment['appointment_date'])); ?></strong><br>
                    <small><?php echo date('h:i A', strtotime($appointment['appointment_time'])); ?></small>
                </td>
                <td>
                    <div class="purpose-text"><?php echo htmlspecialchars(substr($appointment['purpose'], 0, 50)); ?><?php echo strlen($appointment['purpose']) > 50 ? '...' : ''; ?></div>
                    <?php if ($appointment['admin_notes']): ?>
                        <small class="admin-notes">Admin Notes: <?php echo htmlspecialchars($appointment['admin_notes']); ?></small>
                    <?php endif; ?>
                </td>
                <td>
                    <span class="status-badge status-<?php echo $appointment['status']; ?>">
                        <?php echo ucfirst($appointment['status']); ?>
                    </span>
                </td>
                <td class="actions">
                    <?php if ($appointment['status'] == 'pending'): ?>
                        <a href="#" class="edit-link" onclick="showApproveModal(<?php echo $appointment['id']; ?>, 'approve'); return false;">Approve</a>
                        <a href="#" class="delete-link" onclick="showApproveModal(<?php echo $appointment['id']; ?>, 'reject'); return false;">Reject</a>
                    <?php elseif ($appointment['status'] == 'approved'): ?>
                        <a href="?action=complete&id=<?php echo $appointment['id']; ?>" class="edit-link" onclick="return confirm('Mark this appointment as completed?');">Complete</a>
                        <a href="?action=cancel&id=<?php echo $appointment['id']; ?>" class="delete-link" onclick="return confirm('Cancel this appointment?');">Cancel</a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="7" style="text-align: center; padding: 2rem;">No appointments found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<!-- Modal for Approve/Reject -->
<div id="actionModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2 id="modalTitle">Approve Appointment</h2>
        <form method="POST" id="actionForm">
            <input type="hidden" name="appointment_id" id="appointment_id">
            <div class="form-group">
                <label for="admin_notes">Admin Notes (Optional)</label>
                <textarea name="admin_notes" id="admin_notes" rows="4" placeholder="Add any notes about this appointment..."></textarea>
            </div>
            <div class="modal-actions">
                <button type="submit" class="admin-button">Confirm</button>
                <button type="button" class="admin-button" onclick="closeModal()" style="background: var(--dark-grey);">Cancel</button>
            </div>
        </form>
    </div>
</div>

<style>
.admin-filters {
    margin-bottom: 2rem;
}

.filter-buttons {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.filter-btn {
    padding: 0.5rem 1rem;
    border-radius: 8px;
    text-decoration: none;
    background: var(--card-bg);
    border: 1px solid var(--card-border);
    color: var(--text-primary);
    transition: all 0.3s ease;
    font-size: 0.9rem;
}

.filter-btn:hover {
    background: rgba(37, 99, 235, 0.1);
    border-color: var(--blue-accent);
}

.filter-btn.active {
    background: linear-gradient(135deg, var(--dark-blue) 0%, var(--blue-accent) 100%);
    border-color: var(--blue-accent);
    color: var(--white);
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 12px;
    font-size: 0.875rem;
    font-weight: 500;
    display: inline-block;
}

.status-pending {
    background: rgba(251, 191, 36, 0.2);
    color: #fbbf24;
    border: 1px solid rgba(251, 191, 36, 0.3);
}

.status-approved {
    background: rgba(34, 197, 94, 0.2);
    color: #22c55e;
    border: 1px solid rgba(34, 197, 94, 0.3);
}

.status-rejected {
    background: rgba(220, 38, 38, 0.2);
    color: #dc2626;
    border: 1px solid rgba(220, 38, 38, 0.3);
}

.status-completed {
    background: rgba(59, 130, 246, 0.2);
    color: #3b82f6;
    border: 1px solid rgba(59, 130, 246, 0.3);
}

.status-cancelled {
    background: rgba(107, 114, 128, 0.2);
    color: #6b7280;
    border: 1px solid rgba(107, 114, 128, 0.3);
}

.purpose-text {
    margin-bottom: 0.5rem;
}

.admin-notes {
    color: var(--text-secondary);
    font-style: italic;
    display: block;
    margin-top: 0.5rem;
}

.modal {
    display: none;
    position: fixed;
    z-index: 2000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.7);
    backdrop-filter: blur(5px);
}

.modal-content {
    background: linear-gradient(135deg, var(--card-bg) 0%, #1a1f2e 100%);
    margin: 5% auto;
    padding: 2rem;
    border: 1px solid var(--card-border);
    border-radius: 12px;
    width: 90%;
    max-width: 500px;
    position: relative;
    animation: fadeIn 0.3s ease-out;
}

.close {
    color: var(--text-secondary);
    float: right;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
    transition: color 0.3s ease;
}

.close:hover {
    color: var(--text-primary);
}

.modal-actions {
    display: flex;
    gap: 1rem;
    margin-top: 1.5rem;
}

.modal-actions button {
    flex: 1;
}
</style>

<script>
function showApproveModal(appointmentId, action) {
    const modal = document.getElementById('actionModal');
    const form = document.getElementById('actionForm');
    const title = document.getElementById('modalTitle');
    const appointmentIdInput = document.getElementById('appointment_id');
    
    appointmentIdInput.value = appointmentId;
    form.action = '?action=' + action + '&id=' + appointmentId;
    title.textContent = action === 'approve' ? 'Approve Appointment' : 'Reject Appointment';
    modal.style.display = 'block';
}

function closeModal() {
    document.getElementById('actionModal').style.display = 'none';
    document.getElementById('admin_notes').value = '';
}

document.querySelector('.close').onclick = closeModal;

window.onclick = function(event) {
    const modal = document.getElementById('actionModal');
    if (event.target == modal) {
        closeModal();
    }
}
</script>

<?php
include 'includes/footer.php';
?>

