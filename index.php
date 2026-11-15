<?php
require_once __DIR__ . '/layout.php';
if (!empty($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}
page_header('Welcome');
?>
<div class="card">
    <h1>Welcome</h1>
    <p>Keep track of your health: symptoms, medications, allergies and appointments, all in one place.</p>
    <p><a href="register.php">Create an account</a> or <a href="login.php">log in</a>.</p>
</div>
<?php page_footer();
