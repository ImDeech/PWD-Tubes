<?php
session_start();

// Redirect jika belum login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../xhtml/login.xhtml");
    exit;
}

$namaUser = $_SESSION['nama'] ?? "User";

// Connect DB
require_once "../auth/db_connection.php";

// Query ambil kost + gambar thumbnail + harga kamar termurah
$sql = "
    SELECT 
    ks.kost_id,
    ks.nama_kost,
    ks.alamat,
    k.kamar_id,
    k.harga,
    ki.kost_path
FROM kost ks
JOIN kamar k ON ks.kost_id = k.kost_id
LEFT JOIN kost_image ki ON ks.kost_id = ki.kost_id AND ki.thumbnail = 1
WHERE k.status_kamar = 'tersedia'
LIMIT 20;

";

$result = $conn->query($sql);
$kostList = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $kostList[] = $row;
    }
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Numpang - Beranda</title>
    <link rel="stylesheet" type="text/css" href="/css/home/home.css" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet" />
</head>
<body>

    <div class="header-top">
        <div class="container header-content">
            <div class="user-profile-top">
                <img src="/media/pfp.png" alt="Profile" class="avatar" />
                <div class="user-info">
                    <span class="name"><?= htmlspecialchars($namaUser) ?></span>
                    <span class="location"><i class="ri-map-pin-line"></i> Yogyakarta, UAJY</span>
                </div>
            </div>
            <div class="header-right-actions">
                <div class="search-circle"><i class="ri-search-line"></i></div>
                <div class="notif-circle"><i class="ri-notification-3-line"></i></div>
            </div>
        </div>
    </div>


    <div class="container main-content">

        <div class="hero-banner">
            <div class="hero-text">
                <div class="icon-loc"><i class="ri-map-pin-user-fill"></i></div>
                <span>Ubah lokasi untuk melihat kost terdekat</span>
            </div>
            <i class="ri-arrow-right-s-line" style="font-size: 24px;"></i>
        </div>

        <div class="section-top-space">
            <div class="section-title">
                <h2>Rekomendasi untuk Anda</h2>
                <a href="#">Lihat Semua</a>
            </div>

            <div class="filter-buttons">
                <button class="filter-btn active">Semua</button>
                <button class="filter-btn">Kost Putri</button>
                <button class="filter-btn">Kost Putra</button>
            </div>

            <div class="recommendation-list">

                <?php if (empty($kostList)): ?>
                    <p style="text-align:center; color:#666;">Belum ada kost terdaftar.</p>
                <?php else: ?>

                <?php foreach ($kostList as $kost): ?>
                    <div class="list-item">
                        <div class="thumb">
                            <img src="/media/<?= htmlspecialchars($kost['kost_path']) ?>"
                                 alt="<?= htmlspecialchars($kost['nama_kost']) ?>" />
                        </div>

                        <div class="info">
                            <div class="info-head">
                                <h3><?= htmlspecialchars($kost['nama_kost']) ?></h3>
                            </div>

                            <p class="loc">
                                <i class="ri-map-pin-line"></i> 
                                <?= htmlspecialchars($kost['alamat']) ?>
                            </p>
                            
                            <div class="action-row">
                                <p class="price">
                                    Rp <?= $kost['harga'] ? number_format($kost['harga'], 0, ',', '.') : "N/A" ?>
                                    <span>/bln</span>
                                </p>
                                
                                <div class="btn-group-right">
                                    <a href="/php/pages/keranjang.php?kost_id=<?= $kost['kost_id'] ?>" class="btn-cart-mini">
                                        <i class="ri-shopping-cart-2-line"></i>
                                    </a>

                                    <a href="/php/pages/fasilitas.php?kamar_id=<?= $kost['kamar_id'] ?>" class="btn-book-small">
                                        Sewa
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

                <?php endif; ?>

            </div>
        </div>

    </div>


    <div class="bottom-navbar">
        <div class="container nav-items-wrapper">
            <a href="#" class="nav-btn active">
                <i class="ri-home-5-fill"></i>
                <span>Beranda</span>
            </a>
            <a href="/php/pages/keranjang.php" class="nav-btn">
                <i class="ri-file-list-3-line"></i>
                <span>Keranjang</span>
            </a>
            <a href="/php/pages/profile.php" class="nav-btn">
                <i class="ri-user-line"></i>
                <span>Profil</span>
            </a>
        </div>
    </div>

</body>
</html>
