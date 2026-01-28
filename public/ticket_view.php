<?php
require_once '../config/db.php';
require_once '../includes/functions.php';
require_once '../includes/header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$ticket = $id ? get_ticket($pdo, $id) : null;

if (!$ticket) {
    echo '<section class="card"><p>Ticket not found.</p></section>';
    require_once '../includes/footer.php';
    exit;
}

function status_badge_class(string $status): string {
    if ($status === 'open') return 'badge-warning';
    if ($status === 'in_progress') return 'badge-warning';
    if ($status === 'resolved') return 'badge-success';
    if ($status === 'closed') return 'badge-danger';
    return 'badge-warning';
}
?>
<section class="card" aria-labelledby="ticket-view-title">
    <header>
        <h1 id="ticket-view-title">Ticket <?php echo e($ticket['ticket_code']); ?></h1>
        <p class="card-subtitle">Admins can change ticket status with live Ajax updates.</p>
    </header>

    <input type="hidden" id="ticket-id" value="<?php echo $ticket['id']; ?>">

    <section class="table-wrapper" aria-label="Ticket details" style="margin-top:10px;">
        <table>
            <tbody>
            <tr>
                <th scope="row">Customer</th>
                <td><?php echo e($ticket['customer_name']); ?> (<?php echo e($ticket['customer_email']); ?>)</td>
            </tr>
            <tr>
                <th scope="row">Issue type</th>
                <td><?php echo e(ucfirst($ticket['issue_type'])); ?></td>
            </tr>
            <tr>
                <th scope="row">Priority</th>
                <td><?php echo e(ucfirst($ticket['priority'])); ?></td>
            </tr>
            <tr>
                <th scope="row">Status</th>
                <td>
                    <span id="ticket-status-badge"
                          class="badge <?php echo status_badge_class($ticket['status']); ?>">
                        <?php echo e($ticket['status']); ?>
                    </span>
                </td>
            </tr>
            <tr>
                <th scope="row">Created</th>
                <td><?php echo e($ticket['created_at']); ?></td>
            </tr>
            <tr>
                <th scope="row">Description</th>
                <td><?php echo nl2br(e($ticket['description'])); ?></td>
            </tr>
            </tbody>
        </table>
    </section>

    <section style="margin-top:16px;">
        <h2 style="font-size:1rem;margin-bottom:6px;">Change status (admin)</h2>
        <form>
            <label for="ticket-status">Ticket status</label>
            <select id="ticket-status" name="status">
                <?php foreach (['open','in_progress','resolved','closed'] as $st): ?>
                    <option value="<?php echo $st; ?>" <?php echo $ticket['status'] === $st ? 'selected' : ''; ?>>
                        <?php echo ucfirst(str_replace('_',' ', $st)); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <p style="margin-top:6px;font-size:0.8rem;color:var(--muted);">
                Status updates are saved instantly using Ajax.
            </p>
        </form>
    </section>

    <section style="margin-top:16px;">
        <a class="btn btn-secondary" href="index.php">Back to tickets</a>
        <a class="btn btn-secondary" href="ticket_form.php?id=<?php echo $ticket['id']; ?>">Edit ticket</a>
    </section>
</section>
<?php require_once '../includes/footer.php'; ?>
