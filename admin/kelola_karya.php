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
$karya_to_edit = [];



define('UPLOAD_DIR_DB', 'uploads/');

define('UPLOAD_DIR_SERVER', '../' . UPLOAD_DIR_DB);


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_karya'])) {
    try {
        $judul = trim($_POST['judul']);
        $deskripsi = trim($_POST['deskripsi']);
        $tipe = trim($_POST['tipe']);
        $id_to_update = $_POST['id_karya'] ?? null;
        $file_url = $_POST['file_lama'] ?? '';

       
        if (isset($_FILES['file_url']) && $_FILES['file_url']['error'] == 0) {
            
            if (!is_dir(UPLOAD_DIR_SERVER)) {
                mkdir(UPLOAD_DIR_SERVER, 0755, true);
            }

            $file = $_FILES['file_url'];
            $file_name = uniqid() . '-' . basename($file['name']);
            $target_path_server = UPLOAD_DIR_SERVER . $file_name;
            $target_path_db = UPLOAD_DIR_DB . $file_name;

           
            $allowed_types = ['image/jpeg', 'image/png', 'image/jpg', 'video/mp4'];
            if (in_array($file['type'], $allowed_types) && $file['size'] < 20000000) {
                
                if (move_uploaded_file($file['tmp_name'], $target_path_server)) {
                    $file_url = $target_path_db;
                    
                   
                    if ($id_to_update && !empty($_POST['file_lama'])) {
                        $old_file_server = '../' . trim($_POST['file_lama']);
                        if (file_exists($old_file_server)) {
                            unlink($old_file_server);
                        }
                    }
                } else {
                    throw new Exception("Gagal memindahkan file.");
                }
            } else {
                throw new Exception("File tidak valid. Pastikan format (JPG/PNG/MP4) dan ukuran (< 20MB) sesuai.");
            }
        } elseif (empty($file_url) && !$id_to_update) {
            throw new Exception("File wajib di-upload untuk karya baru.");
        }

        if ($id_to_update) {
           
            $sql = "UPDATE karya SET judul = ?, deskripsi = ?, file_url = ?, tipe = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$judul, $deskripsi, $file_url, $tipe, $id_to_update]);
            $message = "Karya berhasil diperbarui!";
        } else {
           
            $sql = "INSERT INTO karya (judul, deskripsi, file_url, tipe) VALUES (?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$judul, $deskripsi, $file_url, $tipe]);
            $message = "Karya baru berhasil ditambahkan!";
        }
        $message_type = 'success';

    } catch (Exception $e) {
        $message = "Gagal memproses karya: " . $e->getMessage();
        $message_type = 'error';
    }
}


if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['action'])) {
    try {
        $id = $_GET['id'] ?? null;
        if (!$id) throw new Exception("ID tidak ditemukan.");

        if ($_GET['action'] == 'delete') {
           
           
            $stmt = $pdo->prepare("SELECT file_url FROM karya WHERE id = ?");
            $stmt->execute([$id]);
            $file_to_delete = $stmt->fetchColumn();

           
            if ($file_to_delete) {
                $file_path_server = '../' . $file_to_delete;
                if (file_exists($file_path_server)) {
                    unlink($file_path_server);
                }
            }
            
           
            $stmt = $pdo->prepare("DELETE FROM karya WHERE id = ?");
            $stmt->execute([$id]);

            header("Location: kelola_karya.php?status=deleted");
            exit;

        } elseif ($_GET['action'] == 'edit') {
           
            $stmt = $pdo->prepare("SELECT * FROM karya WHERE id = ?");
            $stmt->execute([$id]);
            $karya_to_edit = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($karya_to_edit) {
                $edit_mode = true;
            } else {
                throw new Exception("Data karya tidak ditemukan.");
            }
        }
    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
        $message_type = 'error';
    }
}


if (isset($_GET['status']) && $_GET['status'] == 'deleted') {
    $message = "Karya berhasil dihapus!";
    $message_type = 'success';
}


$daftar_karya = [];
try {
    $stmt = $pdo->query("SELECT * FROM karya");
    $daftar_karya = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $message = "Gagal mengambil daftar karya: " . $e->getMessage();
    $message_type = 'error';
}

$admin_username = $_SESSION['admin_username'] ?? 'Admin';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Karya</title>
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
                <li><a href="kelola_karya.php" class="active"><i class="fas fa-paint-brush"></i> <span>Kelola Karya</span></a></li>
                <li><a href="kelola_pengalaman.php"><i class="fas fa-briefcase"></i> <span>Kelola Pengalaman</span></a></li>
                <li><a href="kelola_keahlian.php"><i class="fas fa-star"></i> <span>Kelola Keahlian</span></a></li>
                <li><a href="kelola_pesan.php"><i class="fas fa-envelope"></i> <span>Kelola Pesan</span></a></li>
            </ul>
        </aside>

        
        <main class="admin-main-content">
            
            <header class="admin-header">
                <h1>Kelola Karya</h1>
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
                    <form class="admin-form" action="kelola_karya.php" method="POST" enctype="multipart/form-data">
                        
                        <h3 class="form-title"><?php echo $edit_mode ? 'Edit Karya' : 'Tambah Karya Baru'; ?></h3>
                        
                        
                        <?php if ($edit_mode): ?>
                            <input type="hidden" name="id_karya" value="<?php echo htmlspecialchars($karya_to_edit['id']); ?>">
                            <input type="hidden" name="file_lama" value="<?php echo htmlspecialchars($karya_to_edit['file_url']); ?>">
                        <?php endif; ?>

                        
                        <div class="form-section">
                            <div class="form-group-admin">
                                <label for="judul">Judul Karya</label>
                                <input type="text" id="judul" name="judul" value="<?php echo htmlspecialchars($karya_to_edit['judul'] ?? ''); ?>" required>
                            </div>

                            <div class="form-group-admin">
                                <label for="deskripsi">Deskripsi Singkat</label>
                                <input type="text" id="deskripsi" name="deskripsi" value="<?php echo htmlspecialchars($karya_to_edit['deskripsi'] ?? ''); ?>" required>
                            </div>

                            <div class="form-group-admin">
                                <label for="tipe">Tipe File</label>
                                <select id="tipe" name="tipe" required>
                                    <option value="image" <?php echo ($karya_to_edit['tipe'] ?? '') == 'image' ? 'selected' : ''; ?>>Gambar (Image)</option>
                                    <option value="video" <?php echo ($karya_to_edit['tipe'] ?? '') == 'video' ? 'selected' : ''; ?>>Video (MP4)</option>
                                </select>
                            </div>
                        </div>

                        
                        <div class="form-section">
                            <div class="form-group-admin">
                                <label>File Saat Ini</label>
                                <?php if ($edit_mode && !empty($karya_to_edit['file_url'])): ?>
                                    <div class="current-image-preview">
                                        <?php if ($karya_to_edit['tipe'] == 'video'): ?>
                                            <video muted controls class="table-thumbnail">
                                                <source src="<?php echo '../' . htmlspecialchars($karya_to_edit['file_url']); ?>" type="video/mp4">
                                            </video>
                                        <?php else: ?>
                                            <img src="<?php echo '../' . htmlspecialchars($karya_to_edit['file_url']); ?>" alt="Preview">
                                        <?php endif; ?>
                                    </div>
                                <?php else: ?>
                                    <p><small>Belum ada file.</small></p>
                                <?php endif; ?>
                            </div>
                            
                            <div class="form-group-admin">
                                <label for="file_url"><?php echo $edit_mode ? 'Ganti File (Opsional)' : 'Upload File Baru'; ?></label>
                                <input type="file" id="file_url" name="file_url" accept="image/png, image/jpeg, video/mp4" <?php echo $edit_mode ? '' : 'required'; ?>>
                                <small>Max 20MB. (JPG/PNG/MP4). <?php echo $edit_mode ? 'Kosongkan jika tidak ingin ganti.' : ''; ?></small>
                            </div>
                        </div>

                        <div class="form-actions">
                            <?php if ($edit_mode): ?>
                                <a href="kelola_karya.php" class="btn-delete" style="background: #777; float: left;">Batal Edit</a>
                            <?php endif; ?>
                            <button type="submit" name="submit_karya" class="btn-save">
                                <i class="fas fa-save"></i> <?php echo $edit_mode ? 'Simpan Perubahan' : 'Tambah Karya'; ?>
                            </button>
                        </div>

                    </form>
                </div>

                
                <div class="admin-table-container" data-aos="fade-up" data-aos-delay="100">
                    <h3 class="table-title">Daftar Karya</h3>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>File</th>
                                <th>Judul</th>
                                <th>Deskripsi</th>
                                <th>Tipe</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($daftar_karya)): ?>
                                <tr>
                                    <td colspan="6" style="text-align: center;">Belum ada karya.</td>
                                </tr>
                            <?php else: ?>
                                <?php $i = 1; foreach ($daftar_karya as $karya): ?>
                                    <tr>
                                        <td><?php echo $i++; ?></td>
                                        <td>
                                            <?php if ($karya['tipe'] == 'video'): ?>
                                                <video muted class="table-thumbnail">
                                                    <source src="<?php echo '../' . htmlspecialchars($karya['file_url']); ?>" type="video/mp4">
                                                </video>
                                            <?php else: ?>
                                                <img src="<?php echo '../' . htmlspecialchars($karya['file_url']); ?>" alt="Thumbnail" class="table-thumbnail">
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($karya['judul']); ?></td>
                                        <td><?php echo htmlspecialchars($karya['deskripsi']); ?></td>
                                        <td><span style="background: #eee; padding: 3px 8px; border-radius: 5px; font-size: 0.8rem;"><?php echo htmlspecialchars($karya['tipe']); ?></span></td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="?action=edit&id=<?php echo $karya['id']; ?>" class="btn-edit"><i class="fas fa-edit"></i></a>
                                                <a href="?action=delete&id=<?php echo $karya['id']; ?>" class="btn-delete" onclick="return confirm('Apakah Anda yakin ingin menghapus karya ini? File akan dihapus permanen.');"><i class="fas fa-trash"></i></a>
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
