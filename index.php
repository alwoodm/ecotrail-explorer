<?php
declare(strict_types=1);

require_once __DIR__ . '/app/app.php';

$slug = isset($_GET['site']) ? (string) $_GET['site'] : 'home';

if ($slug === '') {
    $slug = 'home';
}

renderPage($slug);
