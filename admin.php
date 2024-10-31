<?php
// --- Database Connection ---
$host = '127.0.0.1';
$db   = 'uas_5a';
$user = 'root'; // Ganti dengan username database
$pass = '';     // Ganti dengan password database

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Koneksi gagal: " . $e->getMessage();
}

// --- Handle Requests ---
$action = $_GET['action'] ?? 'view'; // default to 'view' action
$id = $_GET['id'] ?? null;

if ($action === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $judul = $_POST['judul'];
    $isi = $_POST['isi'];
    $kategori = $_POST['kategori'];
    $author = $_POST['author'];
    $tanggal_publikasi = $_POST['tanggal_publikasi'];
    
    // Proses upload file gambar
    $image_name = '';
    if ($_FILES['images']['error'] == 0) {
        $image_name = uniqid() . '-' . basename($_FILES['images']['name']);
        $target_path = 'upload/' . $image_name;

        // Pastikan direktori upload ada
        if (!is_dir('upload')) {
            mkdir('upload', 0755, true);
        }

        if (move_uploaded_file($_FILES['images']['tmp_name'], $target_path)) {
            // Gambar berhasil di-upload
        } else {
            echo "Gambar gagal di-upload.";
        }
    }

    // Simpan ke database
    $stmt = $pdo->prepare("INSERT INTO artikel (judul, isi, kategori, author, tanggal_publikasi, images, views) VALUES (?, ?, ?, ?, ?, ?, 0)");
    $stmt->execute([$judul, $isi, $kategori, $author, $tanggal_publikasi, $image_name]);
    header("Location: ?action=view");
}

if ($action === 'edit' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle editing an article
    $judul = $_POST['judul'];
    $isi = $_POST['isi'];
    $kategori = $_POST['kategori'];
    $author = $_POST['author'];
    $tanggal_publikasi = $_POST['tanggal_publikasi'];
    $image_name = $_POST['existing_image']; // Simpan nama gambar yang sudah ada

    // Proses upload file gambar baru jika ada
    if ($_FILES['images']['error'] == 0) {
        $image_name = uniqid() . '-' . basename($_FILES['images']['name']);
        $target_path = 'upload/' . $image_name;
        
        if (move_uploaded_file($_FILES['images']['tmp_name'], $target_path)) {
            // Gambar berhasil di-upload
        } else {
            echo "Gambar gagal di-upload.";
        }
    }

    $stmt = $pdo->prepare("UPDATE artikel SET judul = ?, isi = ?, kategori = ?, author = ?, tanggal_publikasi = ?, images = ? WHERE id = ?");
    $stmt->execute([$judul, $isi, $kategori, $author, $tanggal_publikasi, $image_name, $id]);
    header("Location: ?action=view");
}

if ($action === 'delete' && $id) {
    // Handle deleting an article
    $stmt = $pdo->prepare("DELETE FROM artikel WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: ?action=view");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1>Dashboard Admin</h1>

    <?php if ($action === 'view'): ?>
        <!-- View All Articles -->
        <a href="?action=add" class="btn btn-primary mb-3">Tambah Artikel Baru</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Judul</th>
                    <th>Kategori</th>
                    <th>Author</th>
                    <th>Tanggal Publikasi</th>
                    <th>Views</th>
                    <th>Gambar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $pdo->query("SELECT * FROM artikel ORDER BY tanggal_publikasi DESC");
                while ($row = $stmt->fetch()):
                ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['judul']) ?></td>
                        <td><?= htmlspecialchars($row['kategori']) ?></td>
                        <td><?= htmlspecialchars($row['author']) ?></td>
                        <td><?= htmlspecialchars($row['tanggal_publikasi']) ?></td>
                        <td><?= $row['views'] ?></td>
                        <td>
                            <?php if (!empty($row['images'])): ?>
                                <img src="upload/<?= htmlspecialchars($row['images']) ?>" alt="Image" width="100" height="100">
                            <?php else: ?>
                                <p>Tidak ada gambar</p>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="?action=edit&id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                            <a href="?action=delete&id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus artikel ini?');">Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

    <?php elseif ($action === 'add' || ($action === 'edit' && $id)): ?>
        <?php
        // Fetch article data if editing
        $article = ['judul' => '', 'isi' => '', 'kategori' => '', 'author' => '', 'tanggal_publikasi' => '', 'images' => ''];
        if ($action === 'edit' && $id) {
            $stmt = $pdo->prepare("SELECT * FROM artikel WHERE id = ?");
            $stmt->execute([$id]);
            $article = $stmt->fetch();
        }
        ?>

        <!-- Add/Edit Article Form -->
        <form method="post" enctype="multipart/form-data" class="container mt-5">
            <h2><?= $action === 'add' ? 'Tambah' : 'Edit' ?> Artikel</h2>
            <input type="text" name="judul" value="<?= htmlspecialchars($article['judul']) ?>" placeholder="Judul" class="form-control mb-2" required>
            <textarea name="isi" placeholder="Isi Artikel" class="form-control mb-2" required><?= htmlspecialchars($article['isi']) ?></textarea>
            <select name="kategori" class="form-control mb-2" required>
                <option value="Technology" <?= $article['kategori'] === 'Technology' ? 'selected' : '' ?>>Technology</option>
                <option value="LifeStyle" <?= $article['kategori'] === 'LifeStyle' ? 'selected' : '' ?>>Lifestyle</option>
            </select>
            <input type="text" name="author" value="<?= htmlspecialchars($article['author']) ?>" placeholder="Author" class="form-control mb-2" required>
            <input type="date" name="tanggal_publikasi" value="<?= $article['tanggal_publikasi'] ?>" class="form-control mb-2" required>
            <input type="hidden" name="existing_image" value="<?= htmlspecialchars($article['images']) ?>">
            <input type="file" name="images" accept="image/*">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="?action=view" class="btn btn-secondary">Batal</a>
        </form>

    <?php endif; ?>
</div>
</body>
</html>
