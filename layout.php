<?php
require_once __DIR__ . '/db.php';

if (!empty(config()['debug'])) {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
}
session_start();

function e($s): string
{
    return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(16));
    }
    return $_SESSION['csrf'];
}

function csrf_ok(): bool
{
    return isset($_POST['csrf'], $_SESSION['csrf'])
        && hash_equals($_SESSION['csrf'], $_POST['csrf']);
}

function require_login(): void
{
    if (empty($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }
}

function brand_mark(): void
{
    echo '<span class="brand-mark"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s-7-4.6-9.5-9C1 8.5 2.5 5.5 5.5 5.5c2 0 3.2 1.2 4 2.4.8-1.2 2-2.4 4-2.4 3 0 4.5 3 3 6.5C19 16.4 12 21 12 21z"/></svg></span>';
}

function page_header(string $title): void
{
    $logged = !empty($_SESSION['user_id']);
    echo '<!DOCTYPE html><html lang="en"><head><meta charset="utf-8">';
    echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
    echo '<title>' . e($title) . ' · Health Journal</title>';
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">';
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';
    echo '<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@600;700&display=swap" rel="stylesheet">';
    echo '<link rel="stylesheet" href="css/style.css"></head><body>';

    echo '<header class="top"><div class="container top-inner">';
    echo '<a class="brand" href="index.php">';
    brand_mark();
    echo '<span>Health Journal</span></a>';
    echo '<nav class="nav">';
    if ($logged) {
        echo '<a class="nav-link" href="dashboard.php">Dashboard</a>';
        echo '<a class="btn ghost sm" href="logout.php">Log out</a>';
    } else {
        echo '<a class="nav-link" href="login.php">Log in</a>';
        echo '<a class="btn sm" href="register.php">Get started</a>';
    }
    echo '</nav></div></header>';
    echo '<main>';
}

function page_footer(): void
{
    echo '</main>';
    echo '<footer class="footer"><div class="container">Health Journal — keep track of your wellbeing.</div></footer>';
    echo '</body></html>';
}
