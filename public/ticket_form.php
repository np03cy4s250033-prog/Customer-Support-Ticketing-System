<?php
require_once '../config/db.php';
require_once '../includes/functions.php';
require_once '../includes/header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$ticket = [
    'customer_name'  => '',
    'customer_email' => '',
    'issue_type'     => 'technical',
    'priority'       => 'medium',
    'description'    => '',
];

if ($id) {
    $existing = get_ticket($pdo, $id);
    if ($existing) {
        $ticket = $existing;
    }
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_name  = trim($_POST['customer_name'] ?? '');
    $customer_email = trim($_POST['customer_email'] ?? '');
    $issue_type     = $_POST['issue_type'] ?? 'technical';
    $priority       = $_POST['priority'] ?? 'medium';
    $description    = trim($_POST['description'] ?? '');

    if ($customer_name === '') $errors[] = 'Name is required.';
    if ($customer_email === '') $errors[] = 'Email is required.';
    if ($description === '') $errors[] = 'Description is required.';

    if (!$errors) {
        if ($id && $existing) {
            $stmt = $pdo->prepare("UPDATE tickets
                                   SET customer_name = :name,
                                       customer_email = :email,
                                       issue_type = :issue_type,
                                       priority = :priority,
                                       description = :description
                                   WHERE id = :id");
            $stmt->execute([
                ':name' => $customer_name,
                ':email' => $customer_email,
                ':issue_type' => $issue_type,
                ':priority' => $priority,
                ':description' => $description,
                ':id' => $id
            ]);
            $_SESSION['flash'] = 'Ticket updated.';
        } else {
            $code = generate_ticket_code();
            $stmt = $pdo->prepare("INSERT INTO tickets
                (ticket_code, customer_name, customer_email, issue_type, priority, description)
                VALUES (:code, :name, :email, :issue_type, :priority, :description)");
            $stmt->execute([
                ':code' => $code,
                ':name' => $customer_name,
                ':email' => $customer_email,
                ':issue_type' => $issue_type,
                ':priority' => $priority,
                ':description' => $description
            ]);
            $_SESSION['flash'] = 'Ticket submitted. Your code is ' . $code;
        }
        header('Location: index.php');
        exit;
    } else {
        $ticket = compact('customer_name','customer_email','issue_type','priority','description');
    }
}
?>
<section class="card" aria-labelledby="ticket-form-title">
    <header>
        <h1 id="ticket-form-title"><?php echo $id ? 'Edit ticket' : 'Submit a ticket'; ?></h1>
        <p class="card-subtitle">Users submit support tickets with issue type and priority.</p>
    </header>

    <?php if ($errors): ?>
        <ul style="margin:8px 0 12px;color:#fecaca;font-size:0.85rem;">
            <?php foreach ($errors as $err): ?>
                <li><?php echo e($err); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="post" class="form-grid">
        <div>
            <label for="customer_name">Your name</label>
            <input id="customer_name" type="text" name="customer_name"
                   value="<?php echo e($ticket['customer_name']); ?>">
        </div>
        <div>
            <label for="customer_email">Email</label>
            <input id="customer_email" type="email" name="customer_email"
                   value="<?php echo e($ticket['customer_email']); ?>">
        </div>
        <div>
            <label for="issue_type">Issue type</label>
            <select id="issue_type" name="issue_type">
                <?php foreach (['billing','technical','account','other'] as $type): ?>
                    <option value="<?php echo $type; ?>"
                        <?php echo $ticket['issue_type'] === $type ? 'selected' : ''; ?>>
                        <?php echo ucfirst($type); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label for="priority">Priority</label>
            <select id="priority" name="priority">
                <?php foreach (['low','medium','high','urgent'] as $p): ?>
                    <option value="<?php echo $p; ?>"
                        <?php echo $ticket['priority'] === $p ? 'selected' : ''; ?>>
                        <?php echo ucfirst($p); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div style="grid-column:1/-1;">
            <label for="description">Issue description</label>
            <textarea id="description" name="description"><?php echo e($ticket['description']); ?></textarea>
        </div>
        <div style="grid-column:1/-1;margin-top:10px;">
            <button class="btn" type="submit">Save</button>
            <a class="btn btn-secondary" href="index.php">Cancel</a>
        </div>
    </form>
</section>
<?php require_once '../includes/footer.php'; ?>
