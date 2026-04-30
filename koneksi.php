<?php
$host     = 'localhost';
$user     = 'root';
$password = '';
$database = 'db_blog';

$koneksi = new mysqli($host, $user, $password, $database);

if ($koneksi->connect_error) {
    http_response_code(500);
    die(json_encode(['status' => 'error', 'pesan' => 'Koneksi database gagal: ' . $koneksi->connect_error]));
}

$koneksi->set_charset('utf8mb4');
