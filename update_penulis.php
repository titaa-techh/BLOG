<?php
header('Content-Type: application/json');
require 'koneksi.php';

$id            = (int)($_POST['id']            ?? 0);
$nama_depan    = trim($_POST['nama_depan']      ?? '');
$nama_belakang = trim($_POST['nama_belakang']   ?? '');
$user_name     = trim($_POST['user_name']       ?? '');
$password      = $_POST['password']              ?? '';

if ($id <= 0 || !$nama_depan || !$nama_belakang || !$user_name) {
    echo json_encode(['status' => 'error', 'pesan' => 'Field wajib tidak boleh kosong']);
    exit;
}

// Ambil data foto lama
$cek = $koneksi->prepare("SELECT foto FROM penulis WHERE id = ?");
$cek->bind_param('i', $id);
$cek->execute();
$res = $cek->get_result()->fetch_assoc();
$cek->close();

if (!$res) {
    echo json_encode(['status' => 'error', 'pesan' => 'Data tidak ditemukan']);
    exit;
}

$nama_foto = $res['foto'];

// Proses upload foto baru jika ada
if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    $tmp  = $_FILES['foto']['tmp_name'];
    $size = $_FILES['foto']['size'];

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

    $ekstensi  = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
    $nama_baru = uniqid('penulis_', true) . '.' . $ekstensi;
    $tujuan    = __DIR__ . '/uploads_penulis/' . $nama_baru;

    if (move_uploaded_file($tmp, $tujuan)) {
        // Hapus foto lama jika bukan default
        if ($nama_foto !== 'default.png') {
            $path_lama = __DIR__ . '/uploads_penulis/' . $nama_foto;
            if (file_exists($path_lama)) unlink($path_lama);
        }
        $nama_foto = $nama_baru;
    } else {
        echo json_encode(['status' => 'error', 'pesan' => 'Gagal menyimpan foto baru']);
        exit;
    }
}

// Update dengan atau tanpa password baru
if (!empty($password)) {
    $hash = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $koneksi->prepare(
        "UPDATE penulis SET nama_depan=?, nama_belakang=?, user_name=?, password=?, foto=? WHERE id=?"
    );
    $stmt->bind_param('sssssi', $nama_depan, $nama_belakang, $user_name, $hash, $nama_foto, $id);
} else {
    $stmt = $koneksi->prepare(
        "UPDATE penulis SET nama_depan=?, nama_belakang=?, user_name=?, foto=? WHERE id=?"
    );
    $stmt->bind_param('ssssi', $nama_depan, $nama_belakang, $user_name, $nama_foto, $id);
}

if ($stmt->execute()) {
    echo json_encode(['status' => 'sukses', 'pesan' => 'Data penulis berhasil diperbarui']);
} else {
    if ($koneksi->errno === 1062) {
        echo json_encode(['status' => 'error', 'pesan' => 'Username sudah digunakan']);
    } else {
        echo json_encode(['status' => 'error', 'pesan' => 'Gagal memperbarui data']);
    }
}

$stmt->close();
$koneksi->close();
