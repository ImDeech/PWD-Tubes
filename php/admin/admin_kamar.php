<?php
session_start();

// Cek apakah login DAN apakah admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: ../../xhtml/login.xhtml?error=Akses ditolak");
    exit;
}

require_once "../auth/db_connection.php";

// Ambil kamar + nama kost
$sql = "
    SELECT 
        k.kamar_id,
        k.nomor_kamar,
        k.harga,
        k.status_kamar,
        ks.nama_kost,
        ki.kost_path
    FROM kamar k
    JOIN kost ks ON k.kost_id = ks.kost_id
    LEFT JOIN kost_image ki ON ks.kost_id = ki.kost_id AND ki.thumbnail = 1
    ORDER BY k.kamar_id DESC
";

$result = $conn->query($sql);
$kamarList = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $kamarList[] = $row;
    }
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Admin - Kelola Kamar</title>

    <link rel="stylesheet" type="text/css" href="/css/home/home.css" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet" />

    <style>
        .admin-btn-add {
            display: block;
            width: 100%;
            background: #2659b6;
            padding: 12px;
            color: white;
            text-align: center;
            font-weight: 600;
            border-radius: 10px;
            margin-bottom: 18px;
            text-decoration: none;
        }

        .edit-btn, .delete-btn {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
        }

        .edit-btn {
            background: #1abc9c;
            color: white;
        }

        .delete-btn {
            background: #e74c3c;
            color: white;
        }

        .action-row-admin {
            margin-top: 10px;
            display: flex;
            gap: 8px;
        }
    </style>
</head>

<body>

    <!-- HEADER -->
    <div class="header-top">
        <div class="container header-content">
            <div class="user-profile-top">
                <img src="/media/pfp_admin.png" alt="Profile" class="avatar" />
                <div class="user-info">
                    <span class="name">Admin Panel</span>
                    <span class="location"><i class="ri-shield-user-line"></i> Kamar</span>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="container main-content">

        <a href="/php/CRUDs/kamar/create_kamar.php" class="admin-btn-add">
            <i class="ri-add-circle-line"></i> Tambah Kamar Baru
        </a>

        <div class="section-title">
            <h2>Daftar Kamar</h2>
        </div>

        <div class="recommendation-list">

            <?php if (empty($kamarList)): ?>
                <p style="text-align:center; color:#666;">Belum ada kamar terdaftar.</p>
            <?php else: ?>

            <?php foreach ($kamarList as $kamar): ?>
                <div class="list-item">

                    <div class="thumb">
                        <img src="/media/<?= htmlspecialchars($kamar['kost_path'] ?? 'default_kost.png') ?>" 
                             alt="<?= htmlspecialchars($kamar['nama_kost']) ?>" />
                    </div>

                    <div class="info">
                        <div class="info-head">
                            <h3><?= htmlspecialchars($kamar['nama_kost']) ?></h3>
                        </div>

                        <p class="loc">
                            <i class="ri-door-line"></i> 
                            Kamar No: <?= htmlspecialchars($kamar['nomor_kamar']) ?>
                        </p>

                        <p class="price" style="margin-top:8px;">
                            Rp <?= number_format($kamar['harga'], 0, ',', '.') ?>
                            <span>/bln</span>
                        </p>

                        <p style="font-size:13px; color:#555; margin-top:6px;">
                            Status: <strong><?= htmlspecialchars($kamar['status_kamar']) ?></strong>
                        </p>

                        <div class="action-row-admin">
                            <a href="/php/CRUDs/kamar/update_kamar.php?kamar_id=<?= $kamar['kamar_id'] ?>" class="edit-btn">
                                Edit
                            </a>
                            <a href="/php/CRUDs/kamar/delete_kamar.php?kamar_id=<?= $kamar['kamar_id'] ?>" 
                               class="delete-btn"
                               onclick="return confirm('Yakin ingin menghapus kamar ini?')">
                                Hapus
                            </a>
                        </div>

                    </div>
                </div>
            <?php endforeach; ?>

            <?php endif; ?>

        </div>

    </div>

    <!-- NAVBAR ADMIN -->
    <div class="bottom-navbar">
        <div class="container nav-items-wrapper">

            <a href="/php/admin/admin_kost.php" class="nav-btn">
                <i class="ri-home-5-line"></i>
                <span>Kost</span>
            </a>

            <a href="/php/admin/admin_kamar.php" class="nav-btn active">
                <i class="ri-door-line"></i>
                <span>Kamar</span>
            </a>

            <a href="/php/admin/admin_sewa.php" class="nav-btn">
                <i class="ri-file-list-3-line"></i>
                <span>Sewa</span>
            </a>

        </div>
    </div>

</body>
</html>
