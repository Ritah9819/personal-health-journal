<?php
require_once __DIR__ . '/layout.php';

$logged = !empty($_SESSION['user_id']);
$name   = $logged ? ($_SESSION['user_name'] ?? '') : '';

page_header('Welcome');
?>
<div class="container">
    <section class="hero">
        <?php if ($logged): ?>
            <p class="hero-note">Signed in as <?= e($name) ?> — continue to your dashboard after logging in.</p>
        <?php endif; ?>
        <h1>Your health, all in one place</h1>
        <p>Track symptoms, medications, allergies and appointments — easily, securely and in one clean journal.</p>
        <div class="hero-actions">
            <a class="btn" href="login.php">Log in</a>
            <a class="btn ghost" href="register.php">Create account</a>
        </div>
    </section>

    <section class="features">
        <div class="feature">
            <div class="feature-icon">🩺</div>
            <h3>Symptoms</h3>
            <p>Log how you feel each day and spot patterns over time.</p>
        </div>
        <div class="feature">
            <div class="feature-icon">💊</div>
            <h3>Medications</h3>
            <p>Keep a record of the medicines you take and when.</p>
        </div>
        <div class="feature">
            <div class="feature-icon">⚠️</div>
            <h3>Allergies</h3>
            <p>Note allergies and sensitivities so you never forget them.</p>
        </div>
        <div class="feature">
            <div class="feature-icon">📅</div>
            <h3>Appointments</h3>
            <p>Store upcoming visits and follow-up notes in one place.</p>
        </div>
    </section>
</div>
<?php page_footer();
