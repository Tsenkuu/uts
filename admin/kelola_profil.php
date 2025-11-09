<?php



session_start();


if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}


require_once 'db_connect.php';

$message = '';
$message_type = '';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
       
        $nama_utama = trim($_POST['nama_utama']);
        $deskripsi_singkat = trim($_POST['deskripsi_singkat']);
        $link_wa = trim($_POST['link_wa']);
        $link_ig = trim($_POST['link_ig']);
        $profil_lengkap_1 = trim($_POST['profil_lengkap_1']);
        $profil_lengkap_2 = trim($_POST['profil_lengkap_2']);
        $foto_profil_url = trim($_POST['foto_lama']);

       
       
        if (isset($_FILES['foto_profil']) && $_FILES['foto_profil']['error'] == 0) {
            
           
           
            $upload_dir_db = 'uploads/'; 
           
            $upload_dir_server = '../' . $upload_dir_db; 

           
            if (!is_dir($upload_dir_server)) {
                mkdir($upload_dir_server, 0755, true);
            }

            $file = $_FILES['foto_profil'];
            $file_name = uniqid() . '-' . basename($file['name']);
            $target_path_server = $upload_dir_server . $file_name;
            $target_path_db = $upload_dir_db . $file_name;

           
            $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
            if (in_array($file['type'], $allowed_types) && $file['size'] < 5000000) {
                
               
                if (move_uploaded_file($file['tmp_name'], $target_path_server)) {
                    $foto_profil_url = $target_path_db;
                    
                   
                    $foto_lama_server = '../' . trim($_POST['foto_lama']);
                    if (file_exists($foto_lama_server) && !filter_var(trim($_POST['foto_lama']), FILTER_VALIDATE_URL)) {
                        unlink($foto_lama_server);
                    }
                } else {
                    throw new Exception("Gagal memindahkan file yang di-upload.");
                }
            } else {
                throw new Exception("File tidak valid. Pastikan format (JPG/PNG) dan ukuran (< 5MB) sesuai.");
            }
        }

       
        $sql = "UPDATE profil SET 
                    nama_utama = ?, 
                    deskripsi_singkat = ?, 
                    link_wa = ?, 
                    link_ig = ?, 
                    foto_profil_url = ?, 
                    profil_lengkap_1 = ?, 
                    profil_lengkap_2 = ?
                WHERE id = 1";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $nama_utama, 
            $deskripsi_singkat, 
            $link_wa, 
            $link_ig, 
            $foto_profil_url, 
            $profil_lengkap_1, 
            $profil_lengkap_2
        ]);

        $message = "Profil berhasil diperbarui!";
        $message_type = 'success';

    } catch (Exception $e) {
        $message = "Gagal memperbarui profil: " . $e->getMessage();
        $message_type = 'error';
    }
}


try {
    $stmt = $pdo->query("SELECT * FROM profil WHERE id = 1 LIMIT 1");
    $profil = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$profil) {
       
        $profil = [];
        $message = "Data profil tidak ditemukan. Harap hubungi administrator.";
        $message_type = 'error';
    }
} catch (PDOException $e) {
    $profil = [];
    $message = "Gagal mengambil data profil: " . $e->getMessage();
    $message_type = 'error';
}

$admin_username = $_SESSION['admin_username'] ?? 'Admin';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Profil</title>

    
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
                
                <li><a href="kelola_profil.php" class="active"><i class="fas fa-user-edit"></i> <span>Kelola Profil</span></a></li>
                <li><a href="kelola_karya.php"><i class="fas fa-paint-brush"></i> <span>Kelola Karya</span></a></li>
                <li><a href="kelola_pengalaman.php"><i class="fas fa-briefcase"></i> <span>Kelola Pengalaman</span></a></li>
                <li><a href="kelola_keahlian.php"><i class="fas fa-star"></i> <span>Kelola Keahlian</span></a></li>
                <li><a href="kelola_pesan.php"><i class="fas fa-envelope"></i> <span>Kelola Pesan</span></a></li>
            </ul>
        </aside>

        
        <main class="admin-main-content">
            
            
            <header class="admin-header">
                <h1>Kelola Profil</h1>
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
                    <form class="admin-form" action="kelola_profil.php" method="POST" enctype="multipart/form-data">
                        
                        <h3 class="form-title">Edit Data Profil</h3>
                        
                        
                        <div class="form-section">
                            <div class="form-group-admin">
                                <label for="nama_utama">Nama Utama</label>
                                <input type="text" id="nama_utama" name="nama_utama" value="<?php echo htmlspecialchars($profil['nama_utama'] ?? ''); ?>" required>
                            </div>

                            <div class="form-group-admin">
                                <label for="deskripsi_singkat">Deskripsi Singkat (Sub-judul)</label>
                                <input type="text" id="deskripsi_singkat" name="deskripsi_singkat" value="<?php echo htmlspecialchars($profil['deskripsi_singkat'] ?? ''); ?>" required>
                            </div>

                            <div class="form-group-admin">
                                <label for="link_wa">Link WhatsApp</label>
                                <input type="url" id="link_wa" name="link_wa" value="<?php echo htmlspecialchars($profil['link_wa'] ?? ''); ?>" placeholder="https://wa.me/62xxxx">
                            </div>

                            <div class="form-group-admin">
                                <label for="link_ig">Link Instagram</label>
                                <input type="url" id="link_ig" name="link_ig" value="<?php echo htmlspecialchars($profil['link_ig'] ?? ''); ?>" placeholder="https://instagram.com/username">
                            </div>

                            <div class="form-group-admin">
                                <label for="profil_lengkap_1">Paragraf Profil 1</label>
                                <textarea id="profil_lengkap_1" name="profil_lengkap_1" rows="5"><?php echo htmlspecialchars($profil['profil_lengkap_1'] ?? ''); ?></textarea>
                            </div>

                            <div class="form-group-admin">
                                <label for="profil_lengkap_2">Paragraf Profil 2</label>
                                <textarea id="profil_lengkap_2" name="profil_lengkap_2" rows="5"><?php echo htmlspecialchars($profil['profil_lengkap_2'] ?? ''); ?></textarea>
                            </div>
                        </div>

                        
                        <div class="form-section">
                            <div class="form-group-admin">
                                <label>Foto Profil Saat Ini</label>
                                <div class="current-image-preview">
                                    <?php 
                                    $foto_url = $profil['foto_profil_url'] ?? 'https://placehold.co/400x400/CCCCCC/FFFFFF?text=No+Image';
                                   
                                    $display_url = (filter_var($foto_url, FILTER_VALIDATE_URL)) ? $foto_url : '../' . $foto_url;
                                    ?>
                                    <img src="<?php echo htmlspecialchars($display_url); ?>" alt="Foto Profil Saat Ini">
                                </div>
                            </div>
                            
                            <div class="form-group-admin">
                                <label for="foto_profil">Ganti Foto Profil</label>
                                <input type="file" id="foto_profil" name="foto_profil" accept="image/png, image/jpeg">
                                <small>Kosongkan jika tidak ingin mengganti. (Max 5MB, .jpg/.png)</small>
                            </div>

                            
                            <input type="hidden" name="foto_lama" value="<?php echo htmlspecialchars($profil['foto_profil_url'] ?? ''); ?>">

                        </div>

                        
                        <div class="form-actions">
                            <button type="submit" class="btn-save">
                                <i class="fas fa-save"></i> Simpan Perubahan
                            </button>
                        </div>

                    </form>
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
