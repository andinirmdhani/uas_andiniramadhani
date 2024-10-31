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

// Tentukan kategori artikel yang akan ditampilkan
$kategori = 'technology';

// Tentukan jumlah artikel per halaman
$limit = 6;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Query untuk menghitung total artikel dengan kategori tertentu
$total_sql = "SELECT COUNT(*) FROM artikel WHERE kategori = ?";
$stmt_total = $conn->prepare($total_sql);
$stmt_total->bind_param('s', $kategori);
$stmt_total->execute();
$stmt_total->bind_result($total_rows);
$stmt_total->fetch();
$stmt_total->close();

// Hitung jumlah halaman
$total_pages = ceil($total_rows / $limit);

// Query untuk mengambil artikel dengan batasan halaman dan kategori
$sql = "SELECT id, judul, isi, kategori, author, tanggal_publikasi, images, views FROM artikel WHERE kategori = ? LIMIT ?, ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('sii', $kategori, $offset, $limit);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="row">
    <?php
    $item_count = 0;
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Tentukan kelas div berdasarkan urutan artikel
            if ($item_count == 0) {
                $item_class = 'col-md-12 item';
            } else {
                $item_class = 'col-lg-6 col-md-6 item mt-5 pt-lg-3';
            }
            ?>
            <div class="<?php echo $item_class; ?>">
                <div class="card">
                    <div class="card-header p-0 position-relative">
                        <a href="#blog-single">
                            <img class="card-img-bottom d-block radius-image" src="upload/<?php echo htmlspecialchars($row['images']); ?>" alt="Card image cap">
                        </a>
                    </div>
                    <div class="card-body p-0 blog-details">
                        <a href="#blog-single" class="blog-desc"><?php echo htmlspecialchars($row['judul']); ?></a>
                        <p><?php echo htmlspecialchars($row['isi']); ?></p>
                        <div class="author align-items-center mt-3 mb-1">
                            <a href="#author"><?php echo htmlspecialchars($row['author']); ?></a> in <a href="#url"><?php echo htmlspecialchars($row['kategori']); ?></a>
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
                </div>
            </div>
            <?php
            $item_count++;
        }
    } else {
        echo "<p>Tidak ada data yang ditemukan.</p>";
    }
    ?>
</div>

<!-- Pagination control -->
<div class="pagination-wrapper mt-5">
    <ul class="page-pagination">
        <?php if ($page > 1): ?>
            <li><a class="next" href="?page=<?php echo $page - 1; ?>"><span class="fa fa-angle-left"></span></a></li>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <li>
                <a class="page-numbers <?php echo $i == $page ? 'current' : ''; ?>" href="?page=<?php echo $i; ?>">
                    <?php echo $i; ?>
                </a>
            </li>
        <?php endfor; ?>

        <?php if ($page < $total_pages): ?>
            <li><a class="next" href="?page=<?php echo $page + 1; ?>"><span class="fa fa-angle-right"></span></a></li>
        <?php endif; ?>
    </ul>
</div>

<?php
// Menutup koneksi
$conn->close();
?>
