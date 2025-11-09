<?php



require_once 'admin/db_connect.php';


$data = [];
try {
   
    $stmt = $pdo->query("SELECT * FROM profil WHERE id = 1 LIMIT 1");
    $data['profil'] = $stmt->fetch(PDO::FETCH_ASSOC);

   
    $stmt_kiri = $pdo->prepare("SELECT * FROM pengalaman WHERE tipe = 'kiri'");
    $stmt_kiri->execute();
    $data['pengalaman_kiri'] = $stmt_kiri->fetchAll(PDO::FETCH_ASSOC);
    
    $stmt_kanan = $pdo->prepare("SELECT * FROM pengalaman WHERE tipe = 'kanan'");
    $stmt_kanan->execute();
    $data['pengalaman_kanan'] = $stmt_kanan->fetchAll(PDO::FETCH_ASSOC);

   
    $stmt = $pdo->query("SELECT * FROM keahlian");
    $data['keahlian'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

   
    $stmt = $pdo->query("SELECT * FROM karya");
    $data['karya'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
   
    die("Error mengambil data dari database: " . $e->getMessage());
}


$profil = $data['profil'] ?: [
    'nama_utama' => 'Pixel Yoga Pratama',
    'deskripsi_singkat' => 'Desain Grafis dan Video Grafis — Media Sosial Spesialis',
    'link_wa' => '#',
    'link_ig' => '#',
    'foto_profil_url' => 'https://placehold.co/600x600/2C2E63/FFFFFF?text=PYP',
    'profil_lengkap_1' => 'Data profil belum diatur.',
    'profil_lengkap_2' => 'Silakan login ke panel admin untuk melengkapi data.'
];

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    
    <title>Portofolio | <?php echo htmlspecialchars($profil['nama_utama']); ?></title>
    
    <meta name="description" content="<?php echo htmlspecialchars($profil['deskripsi_singkat']); ?>">
    
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    
    <link rel="stylesheet" href="style.css">

</head>
<body>

    
    <nav class="navbar" id="navbar">
        <div class="container">
            <a href="#" class="nav-logo"><?php echo htmlspecialchars($profil['nama_utama']); ?></a>
            <ul class="nav-menu" id="nav-menu">
                <li><a href="#home" class="active">Home</a></li>
                <li><a href="#profil">Profil</a></li>
                <li><a href="#pengalaman">Pengalaman</a></li>
                <li><a href="#keahlian">Keahlian</a></li>
                <li><a href="#karya">Karya</a></li>
                <li><a href="#kontak">Kontak</a></li>
            </ul>
            <button class="nav-toggle" id="nav-toggle" aria-label="Buka menu">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </nav>

    
    <section id="home">
        <div class="container">
            
            <div class="home-content" data-aos="fade-right">
                <h1><?php echo htmlspecialchars($profil['nama_utama']); ?></h1>
                <p class="subtitle"><?php echo htmlspecialchars($profil['deskripsi_singkat']); ?></p>
                <div class="home-socials">
                    <a href="<?php echo htmlspecialchars($profil['link_wa']); ?>" target="_blank" class="social-icon wa" aria-label="WhatsApp">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                    <a href="<?php echo htmlspecialchars($profil['link_ig']); ?>" target="_blank" class="social-icon ig" aria-label="Instagram">
                        <i class="fab fa-instagram"></i>
                    </a>
                </div>
                <a href="#kontak" class="btn btn-primary">Hubungi Saya</a>
            </div>
            
            <div class="home-img" data-aos="fade-left">
                <?php
                   
                    $foto_url = $profil['foto_profil_url'];
                    $display_url = (filter_var($foto_url, FILTER_VALIDATE_URL)) ? $foto_url : '' . $foto_url;
                ?>
                <img src="<?php echo htmlspecialchars($display_url); ?>" alt="Foto Profil <?php echo htmlspecialchars($profil['nama_utama']); ?>">
            </div>
        </div>
    </section>

    
    <section id="profil">
        <div class="container" data-aos="fade-up">
            
            
            
            <div class="profil-content">
                <h2 class="section-title" style="text-align: left; margin-bottom: 2rem;">Tentang Saya</h2>
                <h3><?php echo htmlspecialchars($profil['nama_utama']); ?></h3>
                <p><?php echo nl2br(htmlspecialchars($profil['profil_lengkap_1'])); ?></p>
                <p><?php echo nl2br(htmlspecialchars($profil['profil_lengkap_2'])); ?></p>
            </div>
            
            <div class="profil-img">
                <img src="<?php echo htmlspecialchars($display_url); ?>" alt="Foto <?php echo htmlspecialchars($profil['nama_utama']); ?>">
            </div>
            
            
        </div>
    </section>

    
    <section id="pengalaman">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Pengalaman</h2>
            
            <div class="timeline">
                <?php
               
                if (!empty($data['pengalaman_kiri'])) {
                    foreach ($data['pengalaman_kiri'] as $item) {
                        echo '<div class="timeline-item kiri" data-aos="fade-right">';
                        echo '  <div class="timeline-dot"></div>';
                        echo '  <div class="timeline-content">';
                        echo '      <h3>' . htmlspecialchars($item['posisi']) . '</h3>';
                        echo '      <div class="date">' . htmlspecialchars($item['instansi']) . ' (' . htmlspecialchars($item['tanggal']) . ')</div>';
                        echo '      <p>' . nl2br(htmlspecialchars($item['deskripsi'])) . '</p>';
                        echo '  </div>';
                        echo '</div>';
                    }
                }

               
                if (!empty($data['pengalaman_kanan'])) {
                    foreach ($data['pengalaman_kanan'] as $item) {
                        echo '<div class="timeline-item kanan" data-aos="fade-left">';
                        echo '  <div class="timeline-dot"></div>';
                        echo '  <div class="timeline-content">';
                        echo '      <h3>' . htmlspecialchars($item['posisi']) . '</h3>';
                        echo '      <div class="date">' . htmlspecialchars($item['instansi']) . ' (' . htmlspecialchars($item['tanggal']) . ')</div>';
                        echo '      <p>' . nl2br(htmlspecialchars($item['deskripsi'])) . '</p>';
                        echo '  </div>';
                        echo '</div>';
                    }
                }
                
                if (empty($data['pengalaman_kiri']) && empty($data['pengalaman_kanan'])) {
                    echo '<p style="text-align:center;">Belum ada data pengalaman. Silakan tambahkan melalui panel admin.</p>';
                }
                ?>
            </div>
        </div>
    </section>

    
    <section id="keahlian">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Keahlian</h2>
            
            <div class="grid">
                <?php
                if (!empty($data['keahlian'])) {
                    $delay = 0;
                    foreach ($data['keahlian'] as $item) {
                        echo '<div class="keahlian-card" data-aos="fade-up" data-aos-delay="' . $delay . '">';
                        echo '  <div class="icon"><i class="' . htmlspecialchars($item['ikon']) . '"></i></div>';
                        echo '  <h3>' . htmlspecialchars($item['judul']) . '</h3>';
                        echo '  <p>' . nl2br(htmlspecialchars($item['deskripsi'])) . '</p>';
                        echo '</div>';
                        $delay += 100;
                    }
                } else {
                    echo '<p style="text-align:center; grid-column: 1 / -1;">Belum ada data keahlian. Silakan tambahkan melalui panel admin.</p>';
                }
                ?>
            </div>
        </div>
    </section>

    
    <section id="karya">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Karya Saya</h2>
            
            <div class="grid">
                <?php
                if (!empty($data['karya'])) {
                    $delay = 0;
                    foreach ($data['karya'] as $item) {
                        $file_path = '' . htmlspecialchars($item['file_url']);
                        $judul = htmlspecialchars($item['judul']);
                        $deskripsi = htmlspecialchars($item['deskripsi']);
                        $tipe = $item['tipe'];
                        
                        echo '<div class="karya-item" data-aos="fade-up" data-aos-delay="' . $delay . '" 
                                 onclick="openModal(this, \''.$tipe.'\', \''.$file_path.'\', \''.$judul.'\', \''.$deskripsi.'\')">';
                        
                        if ($tipe == 'video') {
                            echo '<video muted loop playsinline poster="">';
                            echo '  <source src="' . $file_path . '" type="video/mp4">';
                            echo '  Browser Anda tidak mendukung tag video.';
                            echo '</video>';
                        } else {
                            echo '<img src="' . $file_path . '" alt="' . $judul . '">';
                        }
                        
                        echo '  <div class="karya-overlay">';
                        echo '      <h3>' . $judul . '</h3>';
                        echo '  </div>';
                        echo '</div>';
                        $delay += 100;
                    }
                } else {
                    echo '<p style="text-align:center; grid-column: 1 / -1;">Belum ada data karya. Silakan tambahkan melalui panel admin.</p>';
                }
                ?>
            </div>
        </div>
    </section>

    
    <div id="karya-modal" class="modal">
        <span class="modal-close" id="modal-close">&times;</span>
        <div class="modal-content" id="modal-content-container">
            
        </div>
        <div class="modal-caption" id="modal-caption">
            
        </div>
    </div>

    
    <section id="kontak">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Hubungi Saya</h2>
            
            <div class="grid" data-aos="fade-up" data-aos-delay="100">
                <div class="contact-info">
                    <h3>Mari Bicara</h3>
                    <p>Ada proyek yang ingin Anda diskusikan? Atau hanya ingin menyapa? Silakan isi formulir di samping atau hubungi saya melalui media sosial.</p>
                    <div class="home-socials" style="margin-bottom: 0;">
                        <a href="<?php echo htmlspecialchars($profil['link_wa']); ?>" target="_blank" class="social-icon wa" aria-label="WhatsApp">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                        <a href="<?php echo htmlspecialchars($profil['link_ig']); ?>" target="_blank" class="social-icon ig" aria-label="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                </div>
                
                <form id="contact-form" class="contact-form" method="POST" action="send_message.php">
                    <div class="form-group">
                        <input type="text" id="nama" name="nama" placeholder="Nama Anda" required>
                    </div>
                    <div class="form-group">
                        <input type="email" id="email" name="email" placeholder="Email Anda" required>
                    </div>
                    <div class="form-group">
                        <textarea id="pesan" name="pesan" placeholder="Pesan Anda..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary" id="submit-btn">Kirim Pesan</button>
                    <p id="form-message" style="display: none;"></p>
                </form>
            </div>
        </div>
    </section>

    
    <footer class="footer">
        <div class="container">
            <p><?php echo htmlspecialchars($profil['nama_utama']); ?>. 24161562003.</p>
        </div>
    </footer>

    
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <script>
       
        AOS.init({
            duration: 800,
            once: true,
            offset: 50
        });

       
        const navbar = document.getElementById('navbar');
        window.onscroll = () => {
            if (window.scrollY > 50) {
                navbar.classList.add('sticky');
            } else {
                navbar.classList.remove('sticky');
            }
        };

       
        const navToggle = document.getElementById('nav-toggle');
        const navMenu = document.getElementById('nav-menu');
        const navLinks = navMenu.querySelectorAll('a');

        navToggle.addEventListener('click', () => {
            navMenu.classList.toggle('active');
           
            const icon = navToggle.querySelector('i');
            if (icon.classList.contains('fa-bars')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            } else {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        });

       
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                if (navMenu.classList.contains('active')) {
                    navMenu.classList.remove('active');
                    navToggle.querySelector('i').classList.remove('fa-times');
                    navToggle.querySelector('i').classList.add('fa-bars');
                }
            });
        });

       
        const modal = document.getElementById('karya-modal');
        const modalContent = document.getElementById('modal-content-container');
        const modalCaption = document.getElementById('modal-caption');
        const closeModal = document.getElementById('modal-close');

        function openModal(element, tipe, src, judul, deskripsi) {
            modal.style.display = 'block';
            
           
            modalContent.innerHTML = '';
            
            if (tipe === 'video') {
                const video = document.createElement('video');
                video.src = src;
                video.controls = true;
                video.autoplay = true;
                video.style.width = '100%';
                modalContent.appendChild(video);
            } else {
                const img = document.createElement('img');
                img.src = src;
                img.alt = judul;
                modalContent.appendChild(img);
            }
            
            modalCaption.innerHTML = `<h3>${judul}</h3><p>${deskripsi}</p>`;
        }

        closeModal.onclick = () => {
            modal.style.display = 'none';
           
            const video = modalContent.querySelector('video');
            if (video) {
                video.pause();
                video.src = '';
            }
        };

       
        window.onclick = (event) => {
            if (event.target === modal) {
                modal.style.display = 'none';
                const video = modalContent.querySelector('video');
                if (video) {
                    video.pause();
                    video.src = '';
                }
            }
        };
        
       
        const sections = document.querySelectorAll('section');
        const navA = document.querySelectorAll('.nav-menu a');

        window.addEventListener('scroll', () => {
            let current = '';
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
               
                if (window.scrollY >= sectionTop - 150) { 
                    current = section.getAttribute('id');
                }
            });

            navA.forEach(a => {
                a.classList.remove('active');
                if (a.getAttribute('href') === '#' + current) {
                    a.classList.add('active');
                }
            });
            
           
            if (window.scrollY < 300) {
                 navA.forEach(a => a.classList.remove('active'));
                 document.querySelector('.nav-menu a[href="#home"]').classList.add('active');
            }
        });


       
        const contactForm = document.getElementById('contact-form');
        const formMessage = document.getElementById('form-message');
        const submitBtn = document.getElementById('submit-btn');

        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const originalBtnText = submitBtn.innerHTML;
            
           
            submitBtn.innerHTML = 'Mengirim...';
            submitBtn.disabled = true;
            formMessage.style.display = 'none';
            formMessage.classList.remove('form-success', 'form-error');

            fetch('send_message.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    formMessage.textContent = data.message;
                    formMessage.className = 'form-success';
                    contactForm.reset();
                } else {
                    formMessage.textContent = data.message;
                    formMessage.className = 'form-error';
                }
                formMessage.style.display = 'block';
            })
            .catch(error => {
                formMessage.textContent = 'Terjadi kesalahan. Silakan coba lagi.';
                formMessage.className = 'form-error';
                formMessage.style.display = 'block';
            })
            .finally(() => {
               
                submitBtn.innerHTML = originalBtnText;
                submitBtn.disabled = false;
            });
        });

    </script>
</body>
</html>

