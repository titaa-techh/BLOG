<?php
header('Content-Type: application/json');
require 'koneksi.php';

$sql    = "SELECT * FROM penulis ORDER BY id ASC";
$result = $koneksi->query($sql);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode(['status' => 'sukses', 'data' => $data]);
$koneksi->close();
