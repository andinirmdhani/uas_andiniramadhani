<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 20px;
    }

    .detail-article {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        padding: 20px;
        margin: 20px auto;
        max-width: 800px;
    }

    .detail-article h1 {
        font-size: 2.5em;
        margin-bottom: 15px;
        color: #333;
    }

    .detail-article p {
        font-size: 1.2em;
        line-height: 1.6em;
        margin-bottom: 15px;
        color: #555;
    }

    .detail-article span {
        display: inline-block;
        margin-right: 15px;
        font-size: 0.9em;
        color: #888;
    }

    .detail-article img {
        width: 100%;
        height: auto;
        border-radius: 8px;
        margin-top: 20px;
    }

    .detail-article .meta-info {
        margin-top: 20px;
        border-top: 1px solid #eaeaea;
        padding-top: 15px;
    }
</style>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Artikel</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 20px;
    }

    .detail-article {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        padding: 20px;
        margin: 20px auto;
        max-width: 800px;
    }

    .detail-article h1 {
        font-size: 2.5em;
        margin-bottom: 15px;
        color: #333;
    }

    .detail-article p {
        font-size: 1.2em;
        line-height: 1.6em;
        margin-bottom: 15px;
        color: #555;
    }

    .detail-article span {
        display: inline-block;
        margin-right: 15px;
        font-size: 0.9em;
        color: #888;
    }

    .detail-article img {
        width: 100%;
        height: auto;
        border-radius: 8px;
        margin-top: 20px;
    }

    .detail-article .meta-info {
        margin-top: 20px;
        border-top: 1px solid #eaeaea;
        padding-top: 15px;
    }
</style>
</head>
<body>
    <!-- Konten detail artikel di sini -->
     
<?php
// Konfigurasi koneksi database
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'uas_5a';

// Membuat koneksi
$conn = new mysqli($host, $username, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil ID artikel dari URL
$artikel_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Tambah view ke kolom views pada tabel artikel
if ($artikel_id > 0) {
    // Update views di tabel artikel
    $update_views_sql = "UPDATE artikel SET views = views + 1 WHERE id = $artikel_id";
    $conn->query($update_views_sql);
}

// Query untuk mengambil detail artikel
$sql = "SELECT * FROM artikel WHERE id = $artikel_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    ?>
    <div class="detail-article">
        <h1><?php echo htmlspecialchars($row['judul']); ?></h1> <!-- Judul Artikel -->
        <p><?php echo htmlspecialchars($row['isi']); ?></p> <!-- Isi Artikel -->
        <span>Author: <?php echo htmlspecialchars($row['author']); ?></span> <!-- Penulis -->
        <span>Kategori: <?php echo htmlspecialchars($row['kategori']); ?></span> <!-- Kategori -->
        <span>Tanggal: <?php echo date("F d, Y", strtotime($row['tanggal_publikasi'])); ?></span> <!-- Tanggal Publikasi -->
        <span>Views: <?php echo htmlspecialchars($row['views']); ?></span> <!-- Jumlah Views -->
        <img src="upload/<?php echo htmlspecialchars($row['images']); ?>" alt="" class="img-fluid"> <!-- Gambar -->
    </div>
    <?php
} else {
    echo "Artikel tidak ditemukan."; // Pesan jika artikel tidak ditemukan
}

// Menutup koneksi
$conn->close();
?>

</body>
</html>
