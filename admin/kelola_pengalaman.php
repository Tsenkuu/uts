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


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_pengalaman'])) {
    try {
        $posisi = trim($_POST['posisi']);
        $instansi = trim($_POST['instansi']);
        $tanggal = trim($_POST['tanggal']);
        $deskripsi = trim($_POST['deskripsi']);
        $tipe = trim($_POST['tipe']);
        $id_to_update = $_POST['id_pengalaman'] ?? null;

        if ($id_to_update) {
           
            $sql = "UPDATE pengalaman SET posisi = ?, instansi = ?, tanggal = ?, deskripsi = ?, tipe = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$posisi, $instansi, $tanggal, $deskripsi, $tipe, $id_to_update]);
            $message = "Pengalaman berhasil diperbarui!";
        } else {
           
            $sql = "INSERT INTO pengalaman (posisi, instansi, tanggal, deskripsi, tipe) VALUES (?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$posisi, $instansi, $tanggal, $deskripsi, $tipe]);
            $message = "Pengalaman baru berhasil ditambahkan!";
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
           
            $stmt = $pdo->prepare("DELETE FROM pengalaman WHERE id = ?");
            $stmt->execute([$id]);
            header("Location: kelola_pengalaman.php?status=deleted");
            exit;

        } elseif ($_GET['action'] == 'edit') {
           
            $stmt = $pdo->prepare("SELECT * FROM pengalaman WHERE id = ?");
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
    $message = "Pengalaman berhasil dihapus!";
    $message_type = 'success';
}


$daftar_pengalaman = [];
try {
    $stmt = $pdo->query("SELECT * FROM pengalaman");
    $daftar_pengalaman = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $message = "Gagal mengambil daftar pengalaman: " . $e->getMessage();
    $message_type = 'error';
}

$admin_username = $_SESSION['admin_username'] ?? 'Admin';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pengalaman</title>
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
                <li><a href="kelola_pengalaman.php" class="active"><i class="fas fa-briefcase"></i> <span>Kelola Pengalaman</span></a></li>
                <li><a href="kelola_keahlian.php"><i class="fas fa-star"></i> <span>Kelola Keahlian</span></a></li>
                <li><a href="kelola_pesan.php"><i class="fas fa-envelope"></i> <span>Kelola Pesan</span></a></li>
            </ul>
        </aside>

        
        <main class="admin-main-content">
            
            <header class="admin-header">
                <h1>Kelola Pengalaman</h1>
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
                    <form class="admin-form single-column" action="kelola_pengalaman.php" method="POST">
                        
                        <h3 class="form-title"><?php echo $edit_mode ? 'Edit Pengalaman' : 'Tambah Pengalaman Baru'; ?></h3>
                        
                        <?php if ($edit_mode): ?>
                            <input type="hidden" name="id_pengalaman" value="<?php echo htmlspecialchars($item_to_edit['id']); ?>">
                        <?php endif; ?>

                        <div class="form-section">
                            <div class="form-group-admin">
                                <label for="posisi">Posisi / Jabatan</label>
                                <input type="text" id="posisi" name="posisi" value="<?php echo htmlspecialchars($item_to_edit['posisi'] ?? ''); ?>" required>
                            </div>
                            
                            <div class="form-group-admin">
                                <label for="instansi">Nama Instansi / Perusahaan</label>
                                <input type="text" id="instansi" name="instansi" value="<?php echo htmlspecialchars($item_to_edit['instansi'] ?? ''); ?>" required>
                            </div>

                            <div class="form-group-admin">
                                <label for="tanggal">Tanggal (Contoh: 2021 - Sekarang)</label>
                                <input type="text" id="tanggal" name="tanggal" value="<?php echo htmlspecialchars($item_to_edit['tanggal'] ?? ''); ?>" required>
                            </div>

                            <div class="form-group-admin">
                                <label for="deskripsi">Deskripsi Singkat</label>
                                <textarea id="deskripsi" name="deskripsi" rows="4"><?php echo htmlspecialchars($item_to_edit['deskripsi'] ?? ''); ?></textarea>
                            </div>

                            <div class="form-group-admin">
                                <label for="tipe">Posisi di Timeline</label>
                                <select id="tipe" name="tipe" required>
                                    <option value="kiri" <?php echo ($item_to_edit['tipe'] ?? '') == 'kiri' ? 'selected' : ''; ?>>Kiri</option>
                                    <option value="kanan" <?php echo ($item_to_edit['tipe'] ?? '') == 'kanan' ? 'selected' : ''; ?>>Kanan</option>
                                </select>
                                <small>Ini menentukan posisi item di timeline (kiri atau kanan).</small>
                            </div>
                        </div>

                        <div class="form-actions">
                            <?php if ($edit_mode): ?>
                                <a href="kelola_pengalaman.php" class="btn-delete" style="background: #777; float: left;">Batal Edit</a>
                            <?php endif; ?>
                            <button type="submit" name="submit_pengalaman" class="btn-save">
                                <i class="fas fa-save"></i> <?php echo $edit_mode ? 'Simpan Perubahan' : 'Tambah Pengalaman'; ?>
                            </button>
                        </div>

                    </form>
                </div>

                
                <div class="admin-table-container" data-aos="fade-up" data-aos-delay="100">
                    <h3 class="table-title">Daftar Pengalaman</h3>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Posisi</th>
                                <th>Instansi</th>
                                <th>Tanggal</th>
                                <th>Deskripsi</th>
                                <th>Posisi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($daftar_pengalaman)): ?>
                                <tr>
                                    <td colspan="7" style="text-align: center;">Belum ada data.</td>
                                </tr>
                            <?php else: ?>
                                <?php $i = 1; foreach ($daftar_pengalaman as $item): ?>
                                    <tr>
                                        <td><?php echo $i++; ?></td>
                                        <td><?php echo htmlspecialchars($item['posisi']); ?></td>
                                        <td><?php echo htmlspecialchars($item['instansi']); ?></td>
                                        <td><?php echo htmlspecialchars($item['tanggal']); ?></td>
                                        <td><?php echo substr(htmlspecialchars($item['deskripsi']), 0, 50) . '...'; ?></td>
                                        <td><?php echo htmlspecialchars($item['tipe']); ?></td>
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
