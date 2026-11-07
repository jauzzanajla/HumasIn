<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\RoleMiddleware;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => RoleMiddleware::class,
        ]);
        $middleware->trustProxies(at: '*');
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();

// Arahkan storage ke /tmp dan buat foldernya secara otomatis jika di Vercel
if (isset($_ENV['VERCEL']) || getenv('VERCEL')) {
    $storagePath = '/tmp/storage';
    $app->useStoragePath($storagePath);

    foreach ([
        '',
        '/framework',
        '/framework/views',
        '/framework/cache',
        '/framework/sessions',
        '/logs',
    ] as $folder) {
        if (!is_dir($storagePath . $folder)) {
            mkdir($storagePath . $folder, 0755, true);
        }
    }
}

return $app;