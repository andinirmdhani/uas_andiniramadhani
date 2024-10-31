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

// Tentukan jumlah artikel per halaman
$limit = 5;

// Ambil halaman saat ini dari URL, default ke halaman 1
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Query untuk menghitung total artikel
$total_sql = "SELECT COUNT(*) FROM artikel";
$total_result = $conn->query($total_sql);
$total_rows = $total_result->fetch_row()[0];

// Hitung jumlah halaman
$total_pages = ceil($total_rows / $limit);

// Query untuk mengambil artikel dengan batasan halaman
$sql = "SELECT id, judul, isi, kategori, author, tanggal_publikasi, images, views FROM artikel LIMIT $offset, $limit";
$result = $conn->query($sql);

// Memeriksa apakah ada data yang ditemukan
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        ?>
        <div class="grids5-info img-block-mobile mt-5">
            <div class="blog-info align-self">
                <span class="category"><?php echo htmlspecialchars($row['kategori']); ?></span>
                <a href="detail.php?id=<?php echo $row['id']; ?>" class="blog-desc mt-0"><?php echo htmlspecialchars($row['judul']); ?></a>
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

?>
<div class="pagination-wrapper mt-5">
    <ul class="page-pagination">
        <!-- Tombol Sebelumnya -->
        <?php if ($page > 1): ?>
            <li><a class="next" href="?page=<?php echo $page - 1; ?>"><span class="fa fa-angle-left"></span></a></li>
        <?php endif; ?>

        <!-- Nomor Halaman -->
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <li>
                <a class="page-numbers <?php echo $i == $page ? 'current' : ''; ?>" href="?page=<?php echo $i; ?>">
                    <?php echo $i; ?>
                </a>
            </li>
        <?php endfor; ?>

        <!-- Tombol Berikutnya -->
        <?php if ($page < $total_pages): ?>
            <li><a class="next" href="?page=<?php echo $page + 1; ?>"><span class="fa fa-angle-right"></span></a></li>
        <?php endif; ?>
    </ul>
</div>

<?php
// Menutup koneksi
$conn->close();
?>
