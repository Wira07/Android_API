<?php
header("Content-Type: application/json");
include 'db_config.php';

// Mendapatkan data yang dikirim
$data = json_decode(file_get_contents("php://input"));

// Validasi data
if (!isset($data->id) || !isset($data->nama) || !isset($data->email)) {
    echo json_encode(["error" => "Input tidak valid"]);
    exit;
}

// Menyiapkan dan mengikat
$stmt = $koneksi->prepare("UPDATE users SET nama=?, email=? WHERE id=?");
if (!$stmt) {
    echo json_encode(["error" => "Persiapan gagal: " . $koneksi->error]);
    exit;
}

$stmt->bind_param("ssi", $nama, $email, $id);

// Mengatur parameter dan menjalankan
$nama = $data->nama;
$email = $data->email;
$id = $data->id;

if ($stmt->execute()) {
    echo json_encode(["sukses" => true]);
} else {
    echo json_encode(["error" => $stmt->error]);
}

$stmt->close();
$koneksi->close();
?>
