<?php
require_once __DIR__ . '/layout.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_ok()) {
        $error = 'Session expired. Please try again.';
    } else {
        $email = trim($_POST['email'] ?? '');
        $stmt = db()->prepare('SELECT id, name, password_hash FROM hj_users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if ($user && password_verify($_POST['password'] ?? '', $user['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['user_id']   = (int)$user['id'];
            $_SESSION['user_name'] = $user['name'];
            header('Location: dashboard.php');
            exit;
        }
        $error = 'Incorrect email or password.';
    }
}
page_header('Log in');
?>
<div class="card">
    <h1>Log in</h1>
    <?php if (isset($_GET['registered'])): ?>
        <div class="msg success">Account created. You can now log in.</div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="msg error"><?= e($error) ?></div>
    <?php endif; ?>
    <form method="post">
        <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
        <label>Email</label>
        <input type="email" name="email" required>
        <label>Password</label>
        <input type="password" name="password" required>
        <button type="submit">Log in</button>
    </form>
    <p>No account? <a href="register.php">Sign up</a></p>
</div>
<?php page_footer();
