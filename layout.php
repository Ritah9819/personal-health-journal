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

function page_header(string $title): void
{
    $logged = !empty($_SESSION['user_id']);
    echo '<!DOCTYPE html><html lang="en"><head><meta charset="utf-8">';
    echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
    echo '<title>' . e($title) . ' - Personal Health Journal</title>';
    echo '<link rel="stylesheet" href="css/style.css"></head><body>';
    echo '<header class="top"><span class="brand">Personal Health Journal</span><nav>';
    if ($logged) {
        echo '<a href="dashboard.php">Dashboard</a><a href="logout.php">Log out</a>';
    } else {
        echo '<a href="login.php">Log in</a><a href="register.php">Sign up</a>';
    }
    echo '</nav></header><main>';
}

function page_footer(): void
{
    echo '</main></body></html>';
}
