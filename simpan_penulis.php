<?php
header('Content-Type: application/json');
require 'koneksi.php';

$nama_depan   = trim($_POST['nama_depan']   ?? '');
$nama_belakang = trim($_POST['nama_belakang'] ?? '');
$user_name    = trim($_POST['user_name']    ?? '');
$password     = $_POST['password']           ?? '';

// Validasi field wajib
if (!$nama_depan || !$nama_belakang || !$user_name || !$password) {
    echo json_encode(['status' => 'error', 'pesan' => 'Semua field wajib diisi']);
    exit;
}

// Proses upload foto
$nama_foto = 'default.png';
if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    $tmp  = $_FILES['foto']['tmp_name'];
    $size = $_FILES['foto']['size'];

    // Validasi ukuran maks 2MB
    if ($size > 2 * 1024 * 1024) {
        echo json_encode(['status' => 'error', 'pesan' => 'Ukuran file maksimal 2 MB']);
        exit;
    }

    // Validasi tipe file menggunakan finfo
    $finfo     = new finfo(FILEINFO_MIME_TYPE);
    $mime      = $finfo->file($tmp);
    $allowed   = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

    if (!in_array($mime, $allowed)) {
        echo json_encode(['status' => 'error', 'pesan' => 'Tipe file tidak diizinkan. Gunakan JPG, PNG, GIF, atau WEBP']);
        exit;
    }

    $ekstensi  = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
    $nama_foto = uniqid('penulis_', true) . '.' . $ekstensi;
    $tujuan    = __DIR__ . '/uploads_penulis/' . $nama_foto;

    if (!move_uploaded_file($tmp, $tujuan)) {
        echo json_encode(['status' => 'error', 'pesan' => 'Gagal menyimpan foto']);
        exit;
    }
}

// Hash password
$hash_password = password_hash($password, PASSWORD_BCRYPT);

// Simpan ke database
$stmt = $koneksi->prepare(
    "INSERT INTO penulis (nama_depan, nama_belakang, user_name, password, foto)
     VALUES (?, ?, ?, ?, ?)"
);
$stmt->bind_param('sssss', $nama_depan, $nama_belakang, $user_name, $hash_password, $nama_foto);

if ($stmt->execute()) {
    echo json_encode(['status' => 'sukses', 'pesan' => 'Data penulis berhasil disimpan']);
} else {
    // Jika username duplikat
    if ($koneksi->errno === 1062) {
        echo json_encode(['status' => 'error', 'pesan' => 'Username sudah digunakan']);
    } else {
        echo json_encode(['status' => 'error', 'pesan' => 'Gagal menyimpan data: ' . $koneksi->error]);
    }
}

$stmt->close();
$koneksi->close();
