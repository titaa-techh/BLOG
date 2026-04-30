<?php
header('Content-Type: application/json');
require 'koneksi.php';

$id          = (int)($_POST['id']          ?? 0);
$judul       = trim($_POST['judul']        ?? '');
$id_penulis  = (int)($_POST['id_penulis']  ?? 0);
$id_kategori = (int)($_POST['id_kategori'] ?? 0);
$isi         = trim($_POST['isi']          ?? '');

if ($id <= 0 || !$judul || $id_penulis <= 0 || $id_kategori <= 0 || !$isi) {
    echo json_encode(['status' => 'error', 'pesan' => 'Field wajib tidak boleh kosong']);
    exit;
}

// Ambil gambar lama
$cek = $koneksi->prepare("SELECT gambar FROM artikel WHERE id = ?");
$cek->bind_param('i', $id);
$cek->execute();
$row = $cek->get_result()->fetch_assoc();
$cek->close();

if (!$row) {
    echo json_encode(['status' => 'error', 'pesan' => 'Data tidak ditemukan']);
    exit;
}

$nama_file = $row['gambar'];

// Proses upload gambar baru jika ada
if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
    $tmp  = $_FILES['gambar']['tmp_name'];
    $size = $_FILES['gambar']['size'];

    if ($size > 2 * 1024 * 1024) {
        echo json_encode(['status' => 'error', 'pesan' => 'Ukuran file maksimal 2 MB']);
        exit;
    }

    $finfo   = new finfo(FILEINFO_MIME_TYPE);
    $mime    = $finfo->file($tmp);
    $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

    if (!in_array($mime, $allowed)) {
        echo json_encode(['status' => 'error', 'pesan' => 'Tipe file tidak diizinkan']);
        exit;
    }

    $ekstensi  = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
    $nama_baru = uniqid('artikel_', true) . '.' . $ekstensi;
    $tujuan    = __DIR__ . '/uploads_artikel/' . $nama_baru;

    if (move_uploaded_file($tmp, $tujuan)) {
        // Hapus gambar lama
        $path_lama = __DIR__ . '/uploads_artikel/' . $nama_file;
        if (file_exists($path_lama)) unlink($path_lama);
        $nama_file = $nama_baru;
    } else {
        echo json_encode(['status' => 'error', 'pesan' => 'Gagal menyimpan gambar baru']);
        exit;
    }
}

$stmt = $koneksi->prepare(
    "UPDATE artikel SET id_penulis=?, id_kategori=?, judul=?, isi=?, gambar=? WHERE id=?"
);
$stmt->bind_param('iisssi', $id_penulis, $id_kategori, $judul, $isi, $nama_file, $id);

if ($stmt->execute()) {
    echo json_encode(['status' => 'sukses', 'pesan' => 'Artikel berhasil diperbarui']);
} else {
    echo json_encode(['status' => 'error', 'pesan' => 'Gagal memperbarui artikel']);
}

$stmt->close();
$koneksi->close();
