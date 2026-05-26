<?php

define('LARAVEL_START', microtime(true));

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

// Register the Composer autoloader...
require __DIR__ . '/../vendor/autoload.php';

// Bootstrap Laravel
/** @var Illuminate\Foundation\Application $app */
$app = require_once __DIR__ . '/../bootstrap/app.php';

if ($dbCreated) {
    // Run migrations programmatically
    try {
        Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
    } catch (\Throwable $e) {
        // Silence or log error
    }
}

// Handle the request...
$app->handleRequest(Illuminate\Http\Request::capture());
