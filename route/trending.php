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

$limit = 4;

$sql = "SELECT id, judul, kategori, author, tanggal_publikasi, views 
        FROM artikel 
        ORDER BY views DESC 
        LIMIT ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $limit);
$stmt->execute();
$result = $stmt->get_result();

// Tampilkan artikel trending
?>

<div class="trending-articles">
    <?php
    $index = 1;
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            ?>
            <div class="grids5-info">
                <h4><?php echo sprintf("%02d", $index); ?>.</h4>
                <div class="blog-info">
                <a href="detail.php?id=<?php echo $row['id']; ?>" class="blog-desc1 mt-0"><?php echo htmlspecialchars($row['judul']); ?></a>
                    <div class="author align-items-center mt-2 mb-1">
                        <a href="#author"><?php echo htmlspecialchars($row['author']); ?></a> in 
                        <a href="#url"><?php echo htmlspecialchars($row['kategori']); ?></a>
                    </div>
                    <ul class="blog-meta">
                        <li class="meta-item blog-lesson">
                            <span class="meta-value"><?php echo date("F d, Y", strtotime($row['tanggal_publikasi'])); ?></span>
                        </li>
                        <li class="meta-item blog-students">
                            <span class="meta-value"><?php echo htmlspecialchars($row['views']); ?> views</span>
                        </li>
                    </ul>
                </div>
            </div>
            <?php
            $index++;
        }
    } else {
        echo "<p>Tidak ada artikel trending saat ini.</p>";
    }
    ?>
</div>

<?php
$conn->close();
?>
