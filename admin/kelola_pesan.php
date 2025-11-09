<?php

session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

require_once 'db_connect.php';

$message = '';
$message_type = '';


if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['action'])) {
    try {
        $id = $_GET['id'] ?? null;
        if (!$id) throw new Exception("ID tidak ditemukan.");

        if ($_GET['action'] == 'delete') {
           
            $stmt = $pdo->prepare("DELETE FROM pesan WHERE id = ?");
            $stmt->execute([$id]);
            header("Location: kelola_pesan.php?status=deleted");
            exit;
        }
    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
        $message_type = 'error';
    }
}


if (isset($_GET['status']) && $_GET['status'] == 'deleted') {
    $message = "Pesan berhasil dihapus!";
    $message_type = 'success';
}


$daftar_pesan = [];
try {
   
    $stmt = $pdo->query("SELECT * FROM pesan ORDER BY tanggal_kirim DESC");
    $daftar_pesan = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $message = "Gagal mengambil daftar pesan: " . $e->getMessage();
    $message_type = 'error';
}

$admin_username = $_SESSION['admin_username'] ?? 'Admin';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pesan</title>
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
                <li><a href="kelola_keahlian.php"><i class="fas fa-star"></i> <span>Kelola Keahlian</span></a></li>
                <li><a href="kelola_pesan.php" class="active"><i class="fas fa-envelope"></i> <span>Kelola Pesan</span></a></li>
            </ul>
        </aside>

        
        <main class="admin-main-content">
            
            <header class="admin-header">
                <h1>Kelola Pesan</h1>
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

                
                <div class="admin-table-container" data-aos="fade-up">
                    <h3 class="table-title">Kotak Masuk</h3>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Pengirim</th>
                                <th>Pesan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($daftar_pesan)): ?>
                                <tr>
                                    <td colspan="5" style="text-align: center;">Tidak ada pesan masuk.</td>
                                </tr>
                            <?php else: ?>
                                <?php $i = 1; foreach ($daftar_pesan as $pesan): ?>
                                    <tr>
                                        <td><?php echo $i++; ?></td>
                                        <td><?php echo date('d M Y, H:i', strtotime($pesan['tanggal_kirim'])); ?></td>
                                        <td>
                                            <strong><?php echo htmlspecialchars($pesan['nama']); ?></strong><br>
                                            <a href="mailto:<?php echo htmlspecialchars($pesan['email']); ?>" class="pesan-email"><?php echo htmlspecialchars($pesan['email']); ?></a>
                                        </td>
                                        <td class="pesan-konten"><?php echo nl2br(htmlspecialchars($pesan['pesan'])); ?></td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="?action=delete&id=<?php echo $pesan['id']; ?>" class="btn-delete" onclick="return confirm('Yakin ingin menghapus pesan ini?');"><i class="fas fa-trash"></i></a>
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
