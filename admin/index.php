<?php



session_start();



if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}


require_once 'db_connect.php';


try {
    $count_karya = $pdo->query("SELECT COUNT(id) FROM karya")->fetchColumn();
    $count_pesan = $pdo->query("SELECT COUNT(id) FROM pesan")->fetchColumn();
    $count_keahlian = $pdo->query("SELECT COUNT(id) FROM keahlian")->fetchColumn();
} catch (PDOException $e) {
   
    $count_karya = $count_pesan = $count_keahlian = 'Error';
}

$admin_username = $_SESSION['admin_username'] ?? 'Admin';

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>

    
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
                <li><a href="index.php" class="active"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a></li>
                <li><a href="kelola_profil.php"><i class="fas fa-user-edit"></i> <span>Kelola Profil</span></a></li>
                <li><a href="kelola_karya.php"><i class="fas fa-paint-brush"></i> <span>Kelola Karya</span></a></li>
                <li><a href="kelola_pengalaman.php"><i class="fas fa-briefcase"></i> <span>Kelola Pengalaman</span></a></li>
                <li><a href="kelola_keahlian.php"><i class="fas fa-star"></i> <span>Kelola Keahlian</span></a></li>
                <li><a href="kelola_pesan.php"><i class="fas fa-envelope"></i> <span>Kelola Pesan</span></a></li>
            </ul>
        </aside>

        
        <main class="admin-main-content">
            
            
            <header class="admin-header">
                <h1>Dashboard</h1>
                <div class="admin-user">
                    <span>Selamat datang, <strong><?php echo htmlspecialchars($admin_username); ?></strong></span>
                    <a href="logout.php" class="btn-logout">Logout</a>
                </div>
            </header>

            
            <section class="admin-content">
                
                <h2 style="color: #555; font-weight: 500;">Ringkasan Data</h2>
                
                
                <div class="summary-cards">
                    
                    
                    <div class="summary-card" data-aos="fade-up" data-aos-delay="100">
                        <div class="icon karya">
                            <i class="fas fa-paint-brush"></i>
                        </div>
                        <div class="info">
                            <h3><?php echo $count_karya; ?></h3>
                            <p>Total Karya</p>
                        </div>
                    </div>

                    
                    <div class="summary-card" data-aos="fade-up" data-aos-delay="200">
                        <div class="icon pesan">
                            <i class="fas fa-envelope-open-text"></i>
                        </div>
                        <div class="info">
                            <h3><?php echo $count_pesan; ?></h3>
                            <p>Total Pesan</p>
                        </div>
                    </div>

                    
                    <div class="summary-card" data-aos="fade-up" data-aos-delay="300">
                        <div class="icon keahlian">
                            <i class="fas fa-star"></i>
                        </div>
                        <div class="info">
                            <h3><?php echo $count_keahlian; ?></h3>
                            <p>Total Keahlian</p>
                        </div>
                    </div>

                </div>

                
                <div style="margin-top: 3rem; background: white; padding: 2rem; border-radius: 8px;" data-aos="fade-up" data-aos-delay="400">
                    <h3 style="margin-top: 0; color: var(--bg-dark-2);">Langkah Selanjutnya</h3>
                    <p>Ini adalah halaman dasbor utama Anda. Fungsi CRUD (Create, Read, Update, Delete) untuk mengelola konten portofolio ada di halaman terpisah.</p>
                    <p>Silakan klik menu di samping (misalnya "Kelola Karya" atau "Kelola Profil") untuk mulai menambahkan, mengedit, atau menghapus data. Halaman-halaman tersebut perlu dibuat selanjutnya.</p>
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
