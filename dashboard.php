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

$total = count($entries);
$byCat = [];
foreach ($entries as $en) {
    $byCat[$en['category']] = ($byCat[$en['category']] ?? 0) + 1;
}
$lastDate = $total ? $entries[0]['entry_date'] : null;

function badge_class(string $cat): string
{
    return strtolower($cat) === 'symptom' ? 'symptom'
        : (strtolower($cat) === 'medication' ? 'medication'
        : (strtolower($cat) === 'allergy' ? 'allergy'
        : (strtolower($cat) === 'appointment' ? 'appointment' : 'note')));
}

page_header('Dashboard');
?>
<div class="container">
    <h1>Hello, <?= e($_SESSION['user_name']) ?> 👋</h1>
    <p class="card-sub" style="margin:-8px 0 18px;">Here's what's happening in your health journal.</p>

    <div class="stats">
        <div class="stat">
            <div class="num"><?= $total ?></div>
            <div class="lbl">Total entries</div>
        </div>
        <div class="stat">
            <div class="num"><?= count($byCat) ?></div>
            <div class="lbl">Categories used</div>
        </div>
        <div class="stat">
            <div class="num"><?= $lastDate ? e(substr($lastDate, 5)) : '—' ?></div>
            <div class="lbl"><small>Most recent entry</small></div>
        </div>
    </div>

    <div class="card">
        <h2>➕ Add an entry</h2>
        <?php if (isset($_GET['saved'])): ?>
            <div class="msg success">Entry saved. Nice work!</div>
        <?php endif; ?>
        <?php foreach ($errors as $err): ?>
            <div class="msg error"><?= e($err) ?></div>
        <?php endforeach; ?>
        <form method="post">
            <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
            <input type="hidden" name="action" value="add">
            <div class="form-row">
                <div>
                    <label>Date</label>
                    <input type="date" name="entry_date" value="<?= date('Y-m-d') ?>" required>
                </div>
                <div>
                    <label>Category</label>
                    <select name="category">
                        <?php foreach ($categories as $c): ?>
                            <option><?= e($c) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <label>Details</label>
            <textarea name="details" rows="3" placeholder="What would you like to record?" required></textarea>
            <div class="form-actions">
                <button type="submit" class="btn">Save entry</button>
            </div>
        </form>
    </div>

    <div class="card">
        <h2>📋 Your entries</h2>
        <?php if (!$entries): ?>
            <div class="empty">
                <div class="big">🗒️</div>
                <p>No entries yet. Add your first health record above!</p>
            </div>
        <?php else: ?>
            <div class="entries">
                <?php foreach ($entries as $en): ?>
                    <div class="entry">
                        <div>
                            <div class="entry-top">
                                <span class="badge <?= badge_class($en['category']) ?>"><?= e($en['category']) ?></span>
                                <span class="entry-date"><?= e($en['entry_date']) ?></span>
                            </div>
                            <div class="entry-details"><?= nl2br(e($en['details'])) ?></div>
                        </div>
                        <div class="entry-actions">
                            <form method="post" onsubmit="return confirm('Delete this entry?');">
                                <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= (int)$en['id'] ?>">
                                <button type="submit" class="btn danger sm">Delete</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php page_footer();
