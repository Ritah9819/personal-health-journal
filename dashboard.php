<?php
require_once __DIR__ . '/layout.php';
require_login();

$uid = $_SESSION['user_id'];
$categories = ['Symptom', 'Medication', 'Allergy', 'Appointment', 'Note'];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_ok()) {
        $errors[] = 'Session expired. Please try again.';
    } elseif (($_POST['action'] ?? '') === 'add') {
        $date     = $_POST['entry_date'] ?? '';
        $category = $_POST['category'] ?? '';
        $details  = trim($_POST['details'] ?? '');
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) $errors[] = 'Choose a valid date.';
        if (!in_array($category, $categories, true))     $errors[] = 'Choose a category.';
        if ($details === '')                             $errors[] = 'Details are required.';
        if (!$errors) {
            $ins = db()->prepare('INSERT INTO hj_entries (user_id, entry_date, category, details) VALUES (?, ?, ?, ?)');
            $ins->execute([$uid, $date, $category, $details]);
            header('Location: dashboard.php?saved=1');
            exit;
        }
    } elseif (($_POST['action'] ?? '') === 'delete') {
        $del = db()->prepare('DELETE FROM hj_entries WHERE id = ? AND user_id = ?');
        $del->execute([(int)($_POST['id'] ?? 0), $uid]);
        header('Location: dashboard.php');
        exit;
    }
}

$list = db()->prepare('SELECT id, entry_date, category, details FROM hj_entries WHERE user_id = ? ORDER BY entry_date DESC, id DESC');
$list->execute([$uid]);
$entries = $list->fetchAll();

page_header('Dashboard');
?>
<h1>Hello, <?= e($_SESSION['user_name']) ?></h1>

<div class="card">
    <h2>Add an entry</h2>
    <?php if (isset($_GET['saved'])): ?>
        <div class="msg success">Entry saved.</div>
    <?php endif; ?>
    <?php foreach ($errors as $err): ?>
        <div class="msg error"><?= e($err) ?></div>
    <?php endforeach; ?>
    <form method="post">
        <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
        <input type="hidden" name="action" value="add">
        <label>Date</label>
        <input type="date" name="entry_date" value="<?= date('Y-m-d') ?>" required>
        <label>Category</label>
        <select name="category">
            <?php foreach ($categories as $c): ?>
                <option><?= e($c) ?></option>
            <?php endforeach; ?>
        </select>
        <label>Details</label>
        <textarea name="details" rows="3" required></textarea>
        <button type="submit">Save entry</button>
    </form>
</div>

<div class="card">
    <h2>Your entries</h2>
    <?php if (!$entries): ?>
        <p>No entries yet.</p>
    <?php else: ?>
        <div class="scroll"><table>
            <tr><th>Date</th><th>Category</th><th>Details</th><th></th></tr>
            <?php foreach ($entries as $en): ?>
                <tr>
                    <td><?= e($en['entry_date']) ?></td>
                    <td><?= e($en['category']) ?></td>
                    <td><?= nl2br(e($en['details'])) ?></td>
                    <td>
                        <form method="post" onsubmit="return confirm('Delete this entry?');">
                            <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?= (int)$en['id'] ?>">
                            <button type="submit" class="danger">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table></div>
    <?php endif; ?>
</div>
<?php page_footer();
