<?php

// Tangkap request dan arahkan langsung ke core Laravel di folder public
$_SERVER['SCRIPT_NAME'] = '/index.php';

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);

// Jika mengakses file fisik publik (seperti css, js, gambar), biarkan lewat
if ($uri !== '/' && file_exists(__DIR__ . '/../public' . $uri)) {
    return false;
}

// Jalankan entry point utama Laravel
require_once __DIR__ . '/../public/index.php';