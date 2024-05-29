<?php
include 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image']) && isset($_POST['name']) && isset($_POST['email'])) {
    $image = $_FILES['image'];
    $name = $_POST['name'];
    $email = $_POST['email'];

    $target_dir = "uploads/";
    $target_file = $target_dir . basename($image["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Memeriksa apakah file gambar valid
    $check = getimagesize($image["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo json_encode(["error" => "File is not an image."]);
        $uploadOk = 0;
    }

    // Memeriksa apakah file sudah ada
    if (file_exists($target_file)) {
        echo json_encode(["error" => "File already exists."]);
        $uploadOk = 0;
    }

    // Memeriksa ukuran file
    if ($image["size"] > 500000) {
        echo json_encode(["error" => "File is too large."]);
        $uploadOk = 0;
    }

    // Memeriksa format file
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo json_encode(["error" => "Only JPG, JPEG, PNG & GIF files are allowed."]);
        $uploadOk = 0;
    }

    // Jika tidak ada kesalahan, mencoba mengunggah file
    if ($uploadOk == 1) {
        if (move_uploaded_file($image["tmp_name"], $target_file)) {
            $name = $koneksi->real_escape_string($name);
            $email = $koneksi->real_escape_string($email);
            $imageUrl = $koneksi->real_escape_string($target_file);
            $sql = "INSERT INTO users (name, email, gambar) VALUES ('$name', '$email', '$imageUrl')";
            if ($koneksi->query($sql) === TRUE) {
                echo json_encode(["success" => true]);
            } else {
                echo json_encode(["error" => $koneksi->error]);
            }
        } else {
            echo json_encode(["error" => "There was an error uploading your file."]);
        }
    } else {
        echo json_encode(["error" => "File was not uploaded."]);
    }
    $koneksi->close();
} else {
    echo json_encode(["error" => "Invalid request."]);
}
?>
