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
<div class="auth-wrap">
    <div class="auth">
        <div class="auth-brand">
            <?php brand_mark(); ?>
            <h1>Welcome back</h1>
            <p>Log in to your health journal</p>
        </div>

        <?php if (isset($_GET['registered'])): ?>
            <div class="msg success">Account created. You can now log in.</div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="msg error"><?= e($error) ?></div>
        <?php endif; ?>

        <form method="post">
            <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
            <label>Email</label>
            <input type="email" name="email" placeholder="you@example.com" required>
            <label>Password</label>
            <input type="password" name="password" placeholder="••••••••" required>
            <div class="form-actions">
                <button type="submit" class="btn block">Log in</button>
            </div>
        </form>

        <p class="auth-foot">No account yet? <a href="register.php">Sign up</a></p>
    </div>
</div>
<?php page_footer();
