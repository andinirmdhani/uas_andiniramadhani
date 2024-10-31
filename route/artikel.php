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

// Query untuk mengambil data dari tabel artikel
$sql = "SELECT id, judul, isi, kategori, author, tanggal_publikasi, images, views FROM artikel";
$result = $conn->query($sql);

// Memeriksa apakah ada data yang ditemukan
if ($result->num_rows > 0) {
    // Looping data
    while ($row = $result->fetch_assoc()) {
        ?>
        <div class="grids5-info img-block-mobile">
            <div class="blog-info align-self">
                <span class="category"><?php echo htmlspecialchars($row['kategori']); ?></span>
                <a href="#blog-single" class="blog-desc mt-0"><?php echo htmlspecialchars($row['judul']); ?></a>
                <p><?php echo htmlspecialchars($row['isi']); ?></p>
                <div class="author align-items-center mt-3 mb-1">
                    <a href="#author"><?php echo htmlspecialchars($row['author']); ?></a> in 
                    <a href="#url"><?php echo htmlspecialchars($row['kategori']); ?></a>
                </div>
                <ul class="blog-meta">
                    <li class="meta-item blog-lesson">
                        <span class="meta-value"><?php echo date("F d, Y", strtotime($row['tanggal_publikasi'])); ?></span>
                    </li>
                    <li class="meta-item blog-students">
                        <span class="meta-value"><?php echo htmlspecialchars($row['views']); ?> read</span>
                    </li>
                </ul>
            </div>
            <a href="#blog-single" class="d-block zoom mt-md-0 mt-3">
                <img src="upload/<?php echo htmlspecialchars($row['images']); ?>" alt="" class="img-fluid radius-image news-image">
            </a>
        </div>
        <?php
    }
} else {
    echo "Tidak ada data yang ditemukan.";
}

// Menutup koneksi
$conn->close();
?>
