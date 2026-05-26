<?php

// Create required writable directories in /tmp for serverless runtime
$dirs = [
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/cache',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/bootstrap/cache',
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// Set SQLite path in /tmp if using sqlite
$dbCreated = false;
if (getenv('DB_CONNECTION') === 'sqlite') {
    $dbPath = '/tmp/database.sqlite';
    if (!file_exists($dbPath) || filesize($dbPath) === 0) {
        touch($dbPath);
        $dbCreated = true;
    }
    putenv("DB_DATABASE={$dbPath}");
    $_ENV['DB_DATABASE'] = $dbPath;
}

if ($dbCreated) {
    // Run migrations automatically
    shell_exec('php ' . __DIR__ . '/../artisan migrate --force');
}

// Forward Vercel requests to normal index.php
require __DIR__ . '/../public/index.php';
