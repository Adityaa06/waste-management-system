<?php

define('LARAVEL_START', microtime(true));

// ─── 1. Create all writable directories in /tmp ──────────────────────────────
$dirs = [
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/logs',
    '/tmp/bootstrap/cache',
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// ─── 2. Copy bootstrap/cache PHP files to /tmp (read-only FS workaround) ─────
$srcCache = __DIR__ . '/../bootstrap/cache';
$dstCache = '/tmp/bootstrap/cache';
foreach (glob($srcCache . '/*.php') as $file) {
    $dest = $dstCache . '/' . basename($file);
    if (!file_exists($dest)) {
        copy($file, $dest);
    }
}

// ─── 3. Set SQLite database path in /tmp ─────────────────────────────────────
$dbCreated = false;
if (getenv('DB_CONNECTION') === 'sqlite') {
    $dbPath = '/tmp/database.sqlite';
    if (!file_exists($dbPath) || filesize($dbPath) === 0) {
        touch($dbPath);
        $dbCreated = true;
    }
    putenv("DB_DATABASE={$dbPath}");
    $_ENV['DB_DATABASE'] = $dbPath;
    $_SERVER['DB_DATABASE'] = $dbPath;
}

// ─── 4. Register the Composer autoloader ─────────────────────────────────────
require __DIR__ . '/../vendor/autoload.php';

// ─── 5. Bootstrap Laravel ────────────────────────────────────────────────────
/** @var \Illuminate\Foundation\Application $app */
$app = require_once __DIR__ . '/../bootstrap/app.php';

// Override bootstrap & storage paths to writable /tmp locations
$app->useBootstrapPath('/tmp/bootstrap');
$app->useStoragePath('/tmp/storage');

// ─── 6. Run migrations & seeders on first cold-start (SQLite) ────────────────
if ($dbCreated) {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
    } catch (\Throwable $e) {
        // Log silently; don't crash the request
        error_log('Migration/Seed error: ' . $e->getMessage());
    }
}

// ─── 7. Handle the incoming HTTP request ─────────────────────────────────────
$app->handleRequest(\Illuminate\Http\Request::capture());
