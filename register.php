<?php
require_once __DIR__ . '/layout.php';

$errors = [];
$name = $email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_ok()) {
        $errors[] = 'Session expired. Please try again.';
    }
    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm'] ?? '';

    if ($name === '')                                $errors[] = 'Name is required.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))  $errors[] = 'Enter a valid email address.';
    if (strlen($password) < 6)                       $errors[] = 'Password must be at least 6 characters.';
    if ($password !== $confirm)                      $errors[] = 'Passwords do not match.';

    if (!$errors) {
        $check = db()->prepare('SELECT id FROM hj_users WHERE email = ?');
        $check->execute([$email]);
        if ($check->fetch()) {
            $errors[] = 'That email is already registered.';
        } else {
            $ins = db()->prepare('INSERT INTO hj_users (name, email, password_hash) VALUES (?, ?, ?)');
            $ins->execute([$name, $email, password_hash($password, PASSWORD_DEFAULT)]);
            header('Location: login.php?registered=1');
            exit;
        }
    }
}
page_header('Sign up');
?>
<div class="auth-wrap">
    <div class="auth">
        <div class="auth-brand">
            <?php brand_mark(); ?>
            <h1>Create your account</h1>
            <p>Start your health journal in seconds</p>
        </div>

        <?php foreach ($errors as $err): ?>
            <div class="msg error"><?= e($err) ?></div>
        <?php endforeach; ?>

        <form method="post">
            <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
            <label>Full name</label>
            <input type="text" name="name" value="<?= e($name) ?>" placeholder="Jane Doe" required>
            <label>Email</label>
            <input type="email" name="email" value="<?= e($email) ?>" placeholder="you@example.com" required>
            <label>Password</label>
            <input type="password" name="password" placeholder="At least 6 characters" required>
            <label>Confirm password</label>
            <input type="password" name="confirm" placeholder="Repeat your password" required>
            <div class="form-actions">
                <button type="submit" class="btn block">Create account</button>
            </div>
        </form>

        <p class="auth-foot">Already registered? <a href="login.php">Log in</a></p>
    </div>
</div>
<?php page_footer();
