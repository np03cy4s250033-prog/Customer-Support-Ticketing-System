<?php
require_once '../config/db.php';
require_once '../includes/functions.php';
require_once '../includes/header.php';

$filters = [
    'issue_type' => $_GET['issue_type'] ?? '',
    'priority'   => $_GET['priority'] ?? '',
    'date_from'  => $_GET['date_from'] ?? '',
    'date_to'    => $_GET['date_to'] ?? '',
    'q'          => $_GET['q'] ?? '',
];

$tickets = search_tickets($pdo, $filters);
?>
<section class="card" aria-labelledby="tickets-title">
    <header style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;">
        <div>
            <h1 id="tickets-title">Support tickets</h1>
            <p class="card-subtitle">Search by issue type, priority, or date.</p>
        </div>
        <a class="btn" href="ticket_form.php">Submit ticket</a>
    </header>

    <form method="get" class="form-grid" aria-label="Filter tickets" style="margin-top:8px;">
        <div>
            <label for="issue_type">Issue type</label>
            <select id="issue_type" name="issue_type">
                <option value="">Any</option>
                <?php foreach (['billing','technical','account','other'] as $type): ?>
                    <option value="<?php echo $type; ?>" <?php echo $filters['issue_type'] === $type ? 'selected' : ''; ?>>
                        <?php echo ucfirst($type); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label for="priority">Priority</label>
            <select id="priority" name="priority">
                <option value="">Any</option>
                <?php foreach (['low','medium','high','urgent'] as $p): ?>
                    <option value="<?php echo $p; ?>" <?php echo $filters['priority'] === $p ? 'selected' : ''; ?>>
                        <?php echo ucfirst($p); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label for="date_from">From date</label>
            <input id="date_from" type="date" name="date_from" value="<?php echo e($filters['date_from']); ?>">
        </div>
        <div>
            <label for="date_to">To date</label>
            <input id="date_to" type="date" name="date_to" value="<?php echo e($filters['date_to']); ?>">
        </div>
        <div>
            <label for="q">Keyword</label>
            <input id="q" type="text" name="q" placeholder="Ticket code, name, email" value="<?php echo e($filters['q']); ?>">
        </div>
        <div style="align-self:end;">
            <button class="btn btn-secondary" type="submit">Filter</button>
        </div>
    </form>

    <section class="table-wrapper" aria-label="Ticket list" style="margin-top:10px;">
        <table>
            <thead>
            <tr>
                <th scope="col">Code</th>
                <th scope="col">Customer</th>
                <th scope="col">Issue</th>
                <th scope="col">Priority</th>
                <th scope="col">Status</th>
                <th scope="col">Created</th>
                <th scope="col">View</th>
            </tr>
            </thead>
            <tbody>
            <?php if (!$tickets): ?>
                <tr><td colspan="7">No tickets found.</td></tr>
            <?php endif; ?>
            <?php foreach ($tickets as $t): ?>
                <tr>
                    <td><?php echo e($t['ticket_code']); ?></td>
                    <td><?php echo e($t['customer_name']); ?></td>
                    <td><?php echo e(ucfirst($t['issue_type'])); ?></td>
                    <td>
                        <?php
                        $pCls = 'badge-warning';
                        if ($t['priority'] === 'low') $pCls = 'badge-success';
                        elseif ($t['priority'] === 'high') $pCls = 'badge-danger';
                        elseif ($t['priority'] === 'urgent') $pCls = 'badge-danger';
                        ?>
                        <span class="badge <?php echo $pCls; ?>"><?php echo e($t['priority']); ?></span>
                    </td>
                    <td>
                        <?php
                        $sCls = 'badge-warning';
                        if ($t['status'] === 'resolved' || $t['status'] === 'closed') $sCls = 'badge-success';
                        ?>
                        <span class="badge <?php echo $sCls; ?>"><?php echo e($t['status']); ?></span>
                    </td>
                    <td><?php echo e($t['created_at']); ?></td>
                    <td>
                        <a class="btn btn-secondary" href="ticket_view.php?id=<?php echo $t['id']; ?>">Open</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </section>
</section>
<?php require_once '../includes/footer.php'; ?>
