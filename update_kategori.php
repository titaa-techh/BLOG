<?php
header('Content-Type: application/json');
require 'koneksi.php';

$id            = (int)($_POST['id']            ?? 0);
$nama_kategori = trim($_POST['nama_kategori']  ?? '');
$keterangan    = trim($_POST['keterangan']     ?? '');

if ($id <= 0 || !$nama_kategori) {
    echo json_encode(['status' => 'error', 'pesan' => 'Field wajib tidak boleh kosong']);
    exit;
}

$stmt = $koneksi->prepare("UPDATE kategori_artikel SET nama_kategori=?, keterangan=? WHERE id=?");
$stmt->bind_param('ssi', $nama_kategori, $keterangan, $id);

if ($stmt->execute()) {
    echo json_encode(['status' => 'sukses', 'pesan' => 'Kategori berhasil diperbarui']);
} else {
    if ($koneksi->errno === 1062) {
        echo json_encode(['status' => 'error', 'pesan' => 'Nama kategori sudah ada']);
    } else {
        echo json_encode(['status' => 'error', 'pesan' => 'Gagal memperbarui kategori']);
    }
}

$stmt->close();
$koneksi->close();
