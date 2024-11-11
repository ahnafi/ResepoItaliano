<?php
spl_autoload_register(function ($class) {
    // Konversi namespace menjadi path yang sesuai
    $path = __DIR__ . '/app/' . str_replace('\\', '/', $class) . '.php';

    if (file_exists($path)) {
        require_once $path;
    } else {
        // Untuk debug, tampilkan pesan error jika file tidak ditemukan
        error_log("Class file for $class not found at $path");
    }
});
