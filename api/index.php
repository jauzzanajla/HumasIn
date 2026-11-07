<?php

// ===== DIAGNOSA SEMENTARA: buka /?cek-boot=1 =====
if (isset($_GET['cek-boot'])) {
    header('Content-Type: text/plain; charset=utf-8');
    try {
        require __DIR__ . '/../vendor/autoload.php';
        $app = require __DIR__ . '/../bootstrap/app.php';

        echo "PHP: " . PHP_VERSION . "\n";
        echo "pdo_pgsql: " . (extension_loaded('pdo_pgsql') ? 'ada' : 'TIDAK ADA') . "\n";
        echo "APP_KEY terisi: " . (getenv('APP_KEY') ? 'ya' : 'TIDAK') . "\n";
        echo "Cache packages: " . $app->getCachedPackagesPath() . "\n";
        echo "Cache services: " . $app->getCachedServicesPath() . "\n\n";

        $app->make(Illuminate\Contracts\Http\Kernel::class)->bootstrap();

        echo "BOOT BERHASIL\n";
        echo "view tersedia: " . ($app->bound('view') ? 'ya' : 'TIDAK') . "\n";

        DB::connection()->getPdo();
        echo "Database: TERHUBUNG\n";
    } catch (\Throwable $e) {
        echo "=== ERROR ===\n";
        do {
            echo get_class($e) . ":\n" . $e->getMessage() . "\n";
            echo "File: " . $e->getFile() . ':' . $e->getLine() . "\n\n";
        } while ($e = $e->getPrevious());
    }
    exit;
}
// ===== AKHIR DIAGNOSA =====

$_SERVER['SCRIPT_NAME']     = '/index.php';
$_SERVER['SCRIPT_FILENAME'] = __DIR__ . '/../public/index.php';

require __DIR__ . '/../public/index.php';