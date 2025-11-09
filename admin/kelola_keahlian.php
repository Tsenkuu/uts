<?php

session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

require_once 'db_connect.php';

$message = '';
$message_type = '';
$edit_mode = false;
$item_to_edit = [];


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_keahlian'])) {
    try {
        $ikon = trim($_POST['ikon']);
        $judul = trim($_POST['judul']);
        $deskripsi = trim($_POST['deskripsi']);
        $id_to_update = $_POST['id_keahlian'] ?? null;

        if (empty($ikon) || empty($judul)) {
            throw new Exception("Ikon dan Judul wajib diisi.");
        }

        if ($id_to_update) {
           
            $sql = "UPDATE keahlian SET ikon = ?, judul = ?, deskripsi = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$ikon, $judul, $deskripsi, $id_to_update]);
            $message = "Keahlian berhasil diperbarui!";
        } else {
           
            $sql = "INSERT INTO keahlian (ikon, judul, deskripsi) VALUES (?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$ikon, $judul, $deskripsi]);
            $message = "Keahlian baru berhasil ditambahkan!";
        }
        $message_type = 'success';

    } catch (Exception $e) {
        $message = "Gagal memproses: " . $e->getMessage();
        $message_type = 'error';
    }
}


if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['action'])) {
    try {
        $id = $_GET['id'] ?? null;
        if (!$id) throw new Exception("ID tidak ditemukan.");

        if ($_GET['action'] == 'delete') {
           
            $stmt = $pdo->prepare("DELETE FROM keahlian WHERE id = ?");
            $stmt->execute([$id]);
            header("Location: kelola_keahlian.php?status=deleted");
            exit;

        } elseif ($_GET['action'] == 'edit') {
           
            $stmt = $pdo->prepare("SELECT * FROM keahlian WHERE id = ?");
            $stmt->execute([$id]);
            $item_to_edit = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($item_to_edit) {
                $edit_mode = true;
            } else {
                throw new Exception("Data tidak ditemukan.");
            }
        }
    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
        $message_type = 'error';
    }
}


if (isset($_GET['status']) && $_GET['status'] == 'deleted') {
    $message = "Keahlian berhasil dihapus!";
    $message_type = 'success';
}


$daftar_keahlian = [];
try {
    $stmt = $pdo->query("SELECT * FROM keahlian");
    $daftar_keahlian = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $message = "Gagal mengambil daftar keahlian: " . $e->getMessage();
    $message_type = 'error';
}

$admin_username = $_SESSION['admin_username'] ?? 'Admin';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Keahlian</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="admin_style.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
</head>
<body>

    <div class="admin-wrapper">
        
        
        <aside class="admin-sidebar">
            <div class="sidebar-header">
                <h2>Admin</h2>
            </div>
            <ul class="sidebar-nav">
                <li><a href="index.php"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a></li>
                <li><a href="kelola_profil.php"><i class="fas fa-user-edit"></i> <span>Kelola Profil</span></a></li>
                <li><a href="kelola_karya.php"><i class="fas fa-paint-brush"></i> <span>Kelola Karya</span></a></li>
                <li><a href="kelola_pengalaman.php"><i class="fas fa-briefcase"></i> <span>Kelola Pengalaman</span></a></li>
                <li><a href="kelola_keahlian.php" class="active"><i class="fas fa-star"></i> <span>Kelola Keahlian</span></a></li>
                <li><a href="kelola_pesan.php"><i class="fas fa-envelope"></i> <span>Kelola Pesan</span></a></li>
            </ul>
        </aside>

        
        <main class="admin-main-content">
            
            <header class="admin-header">
                <h1>Kelola Keahlian</h1>
                <div class="admin-user">
                    <span>Selamat datang, <strong><?php echo htmlspecialchars($admin_username); ?></strong></span>
                    <a href="logout.php" class="btn-logout">Logout</a>
                </div>
            </header>

            <section class="admin-content">
                
                <?php if (!empty($message)): ?>
                    <div class="alert alert-<?php echo $message_type; ?>" data-aos="fade-down">
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>

                
                <div class="admin-form-container" data-aos="fade-up">
                    <form class="admin-form single-column" action="kelola_keahlian.php" method="POST">
                        
                        <h3 class="form-title"><?php echo $edit_mode ? 'Edit Keahlian' : 'Tambah Keahlian Baru'; ?></h3>
                        
                        <?php if ($edit_mode): ?>
                            <input type="hidden" name="id_keahlian" value="<?php echo htmlspecialchars($item_to_edit['id']); ?>">
                        <?php endif; ?>

                        <div class="form-section">
                            <div class="form-group-admin">
                                <label for="ikon">Ikon (Font Awesome)</label>
                                <input type="text" id="ikon" name="ikon" value="<?php echo htmlspecialchars($item_to_edit['ikon'] ?? 'fas fa-check'); ?>" required>
                                <small>Contoh: <strong>fas fa-palette</strong>, <strong>fas fa-video</strong>. Cari ikon di <a href="https://fontawesome.com/v6/search?m=free&s=solid" target="_blank">Font Awesome</a>.</small>
                            </div>
                            
                            <div class="form-group-admin">
                                <label for="judul">Judul Keahlian</label>
                                <input type="text" id="judul" name="judul" value="<?php echo htmlspecialchars($item_to_edit['judul'] ?? ''); ?>" required>
                            </div>

                            <div class="form-group-admin">
                                <label for="deskripsi">Deskripsi Singkat</label>
                                <textarea id="deskripsi" name="deskripsi" rows="4"><?php echo htmlspecialchars($item_to_edit['deskripsi'] ?? ''); ?></textarea>
                            </div>
                        </div>

                        <div class="form-actions">
                            <?php if ($edit_mode): ?>
                                <a href="kelola_keahlian.php" class="btn-delete" style="background: #777; float: left;">Batal Edit</a>
                            <?php endif; ?>
                            <button type="submit" name="submit_keahlian" class="btn-save">
                                <i class="fas fa-save"></i> <?php echo $edit_mode ? 'Simpan Perubahan' : 'Tambah Keahlian'; ?>
                            </button>
                        </div>

                    </form>
                </div>

                
                <div class="admin-table-container" data-aos="fade-up" data-aos-delay="100">
                    <h3 class="table-title">Daftar Keahlian</h3>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Ikon</th>
                                <th>Judul</th>
                                <th>Deskripsi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($daftar_keahlian)): ?>
                                <tr>
                                    <td colspan="5" style="text-align: center;">Belum ada data.</td>
                                </tr>
                            <?php else: ?>
                                <?php $i = 1; foreach ($daftar_keahlian as $item): ?>
                                    <tr>
                                        <td><?php echo $i++; ?></td>
                                        <td><i class="<?php echo htmlspecialchars($item['ikon']); ?>" style="font-size: 1.5rem; color: var(--bg-dark-2);"></i></td>
                                        <td><?php echo htmlspecialchars($item['judul']); ?></td>
                                        <td><?php echo substr(htmlspecialchars($item['deskripsi']), 0, 70) . '...'; ?></td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="?action=edit&id=<?php echo $item['id']; ?>" class="btn-edit"><i class="fas fa-edit"></i></a>
                                                <a href="?action=delete&id=<?php echo $item['id']; ?>" class="btn-delete" onclick="return confirm('Yakin ingin menghapus data ini?');"><i class="fas fa-trash"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            </section>
        </main>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            once: true,
            offset: 20
        });
    </script>
</body>
</html>
