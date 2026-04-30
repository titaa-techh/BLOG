<?php
header('Content-Type: application/json');
require 'koneksi.php';

$id = (int)($_POST['id'] ?? 0);

if ($id <= 0) {
    echo json_encode(['status' => 'error', 'pesan' => 'ID tidak valid']);
    exit;
}

// Cek apakah kategori masih dipakai artikel
$cek = $koneksi->prepare("SELECT COUNT(*) AS total FROM artikel WHERE id_kategori = ?");
$cek->bind_param('i', $id);
$cek->execute();
$total = $cek->get_result()->fetch_assoc()['total'];
$cek->close();

if ($total > 0) {
    echo json_encode(['status' => 'error', 'pesan' => 'Kategori tidak dapat dihapus karena masih digunakan oleh artikel']);
    exit;
}

$stmt = $koneksi->prepare("DELETE FROM kategori_artikel WHERE id = ?");
$stmt->bind_param('i', $id);

if ($stmt->execute()) {
    echo json_encode(['status' => 'sukses', 'pesan' => 'Kategori berhasil dihapus']);
} else {
    echo json_encode(['status' => 'error', 'pesan' => 'Gagal menghapus kategori']);
}

$stmt->close();
$koneksi->close();
