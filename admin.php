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
    // Handle adding new article
    $judul = $_POST['judul'];
    $isi = $_POST['isi'];
    $kategori = $_POST['kategori'];
    $author = $_POST['author'];
    $tanggal_publikasi = $_POST['tanggal_publikasi'];
    $images = $_POST['images'];

    $stmt = $pdo->prepare("INSERT INTO artikel (judul, isi, kategori, author, tanggal_publikasi, images, views) VALUES (?, ?, ?, ?, ?, ?, 0)");
    $stmt->execute([$judul, $isi, $kategori, $author, $tanggal_publikasi, $images]);
    header("Location: ?action=view");
}

if ($action === 'edit' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle editing an article
    $judul = $_POST['judul'];
    $isi = $_POST['isi'];
    $kategori = $_POST['kategori'];
    $author = $_POST['author'];
    $tanggal_publikasi = $_POST['tanggal_publikasi'];
    $images = $_POST['images'];

    $stmt = $pdo->prepare("UPDATE artikel SET judul = ?, isi = ?, kategori = ?, author = ?, tanggal_publikasi = ?, images = ? WHERE id = ?");
    $stmt->execute([$judul, $isi, $kategori, $author, $tanggal_publikasi, $images, $id]);
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
                        <td><?= $row['judul'] ?></td>
                        <td><?= $row['kategori'] ?></td>
                        <td><?= $row['author'] ?></td>
                        <td><?= $row['tanggal_publikasi'] ?></td>
                        <td><?= $row['views'] ?></td>
                        <td><img src="<?= $row['images'] ?>" alt="Image" width="50"></td>
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
        <form method="post" class="container mt-5">
            <h2><?= $action === 'add' ? 'Tambah' : 'Edit' ?> Artikel</h2>
            <input type="text" name="judul" value="<?= $article['judul'] ?>" placeholder="Judul" class="form-control mb-2" required>
            <textarea name="isi" placeholder="Isi Artikel" class="form-control mb-2" required><?= $article['isi'] ?></textarea>
            <select name="kategori" class="form-control mb-2" required>
                <option value="Technology" <?= $article['kategori'] === 'Technology' ? 'selected' : '' ?>>Technology</option>
                <option value="LifeStyle" <?= $article['kategori'] === 'LifeStyle' ? 'selected' : '' ?>>Lifestyle</option>
            </select>
            <input type="text" name="author" value="<?= $article['author'] ?>" placeholder="Author" class="form-control mb-2" required>
            <input type="date" name="tanggal_publikasi" value="<?= $article['tanggal_publikasi'] ?>" class="form-control mb-2" required>
            <input type="text" name="images" value="<?= $article['images'] ?>" placeholder="URL Gambar" class="form-control mb-2" required>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="?action=view" class="btn btn-secondary">Batal</a>
        </form>

    <?php endif; ?>
</div>
</body>
</html>
