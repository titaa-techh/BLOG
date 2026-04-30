<?php
header('Content-Type: application/json');
require 'koneksi.php';

$id = (int)($_POST['id'] ?? 0);

if ($id <= 0) {
    echo json_encode(['status' => 'error', 'pesan' => 'ID tidak valid']);
    exit;
}

// Cek apakah penulis masih punya artikel
$cek_artikel = $koneksi->prepare("SELECT COUNT(*) AS total FROM artikel WHERE id_penulis = ?");
$cek_artikel->bind_param('i', $id);
$cek_artikel->execute();
$total = $cek_artikel->get_result()->fetch_assoc()['total'];
$cek_artikel->close();

if ($total > 0) {
    echo json_encode(['status' => 'error', 'pesan' => 'Penulis tidak dapat dihapus karena masih memiliki artikel']);
    exit;
}

// Ambil nama foto sebelum hapus
$cek = $koneksi->prepare("SELECT foto FROM penulis WHERE id = ?");
$cek->bind_param('i', $id);
$cek->execute();
$row = $cek->get_result()->fetch_assoc();
$cek->close();

if (!$row) {
    echo json_encode(['status' => 'error', 'pesan' => 'Data tidak ditemukan']);
    exit;
}

// Hapus dari database
$stmt = $koneksi->prepare("DELETE FROM penulis WHERE id = ?");
$stmt->bind_param('i', $id);

if ($stmt->execute()) {
    // Hapus foto jika bukan default
    if ($row['foto'] !== 'default.png') {
        $path = __DIR__ . '/uploads_penulis/' . $row['foto'];
        if (file_exists($path)) unlink($path);
    }
    echo json_encode(['status' => 'sukses', 'pesan' => 'Data penulis berhasil dihapus']);
} else {
    echo json_encode(['status' => 'error', 'pesan' => 'Gagal menghapus data']);
}

$stmt->close();
$koneksi->close();
