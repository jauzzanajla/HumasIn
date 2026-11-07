<?php

// Teruskan semua request dari Vercel ke Laravel (public/index.php).
// File statis (build, css, js, images) sudah diatur oleh "routes" di vercel.json.

$_SERVER['SCRIPT_NAME']     = '/index.php';
$_SERVER['SCRIPT_FILENAME'] = __DIR__ . '/../public/index.php';

require __DIR__ . '/../public/index.php';