<?php
session_start();

// Cek apakah login DAN apakah admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: ../../xhtml/login.xhtml?error=Akses ditolak");
    exit;
}

require_once "../auth/db_connection.php";

// Ambil data sewa lengkap
$sql = "
    SELECT 
        s.sewa_id,
        s.kamar_id,
        s.user_id,
        s.tanggal_sewa,
        s.status_sewa,
        k.nomor_kamar,
        ks.nama_kost,
        u.nama AS nama_user,
        ki.kost_path
    FROM sewa s
    JOIN kamar k ON s.kamar_id = k.kamar_id
    JOIN kost ks ON k.kost_id = ks.kost_id
    JOIN users u ON s.user_id = u.user_id
    LEFT JOIN kost_image ki ON ks.kost_id = ki.kost_id AND ki.thumbnail = 1
    ORDER BY s.sewa_id DESC
";

$result = $conn->query($sql);
$sewaList = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $sewaList[] = $row;
    }
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin - Kelola Sewa</title>

    <link rel="stylesheet" type="text/css" href="/css/home/home.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">

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
            margin-top: 12px;
            display: flex;
            gap: 8px;
        }

        .logout-btn {
            position: absolute;
            right: 20px;
            top: 15px;
            padding: 8px 14px;
            background: #e74c3c;
            color: white;
            border-radius: 8px;
            font-size: 14px;
            font-weight: bold;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .logout-btn i {
            font-size: 16px;
        }

        .logout-btn:hover {
            background: #c0392b;
        }

    </style>
</head>

<body>

    <!-- header -->
    <div class="header-top">
        <a href="/php/auth/logout.php" class="logout-btn">
            <i class="ri-logout-circle-r-line"></i> Logout
        </a>
        <div class="container header-content">
            <div class="user-profile-top">
                <img src="/media/pfp_admin.png" class="avatar" alt="Admin">
                <div class="user-info">
                    <span class="name">Admin Panel</span>
                    <span class="location"><i class="ri-file-list-3-line"></i> Sewa</span>
                </div>
            </div>
        </div>
    </div>

    <!-- main -->
    <div class="container main-content">

        <a href="/php/CRUDs/sewa/create_sewa.php" class="admin-btn-add">
            <i class="ri-add-circle-line"></i> Tambah Sewa Baru
        </a>

        <div class="section-title">
            <h2>Data Sewa</h2>
        </div>

        <div class="recommendation-list">

            <?php if (empty($sewaList)): ?>
                <p style="text-align:center; color:#777;">Belum ada data sewa.</p>
            <?php else: ?>

            <?php foreach ($sewaList as $s): ?>
                <div class="list-item">

                    <div class="thumb">
                        <img src="/media/<?= htmlspecialchars($s['kost_path'] ?? "default_kost.png") ?>"
                             alt="<?= htmlspecialchars($s['nama_kost']) ?>">
                    </div>

                    <div class="info">
                        <div class="info-head">
                            <h3>Kamar No. <?= htmlspecialchars($s['nomor_kamar']) ?></h3>
                        </div>

                        <p class="loc">
                            <i class="ri-home-2-line"></i> <?= htmlspecialchars($s['nama_kost']) ?>
                        </p>

                        <p style="margin:5px 0 0 0; font-size:13px;">
                            <i class="ri-user-line"></i> Penyewa: <strong><?= htmlspecialchars($s['nama_user']) ?></strong>
                        </p>

                        <p style="margin:8px 0 0 0; font-size:13px;">
                            <i class="ri-calendar-line"></i> <?= htmlspecialchars($s['tanggal_sewa']) ?>
                        </p>

                        <p style="margin:8px 0 0 0; font-size:13px;">
                            Status: <strong><?= htmlspecialchars($s['status_sewa']) ?></strong>
                        </p>

                        <div class="action-row-admin">
                            <a href="/php/CRUDs/sewa/update_sewa.php?sewa_id=<?= $s['sewa_id'] ?>" class="edit-btn">
                                Edit
                            </a>

                            <a href="/php/CRUDs/sewa/delete_sewa.php?sewa_id=<?= $s['sewa_id'] ?>"
                               class="delete-btn"
                               onclick="return confirm('Yakin ingin menghapus data sewa ini?')">
                                Hapus
                            </a>
                        </div>

                    </div>

                </div>
            <?php endforeach; ?>

            <?php endif; ?>

        </div>

    </div>

    <!-- navbar admin -->
    <div class="bottom-navbar">
        <div class="container nav-items-wrapper">

            <a href="/php/admin/admin_kost.php" class="nav-btn">
                <i class="ri-home-5-line"></i>
                <span>Kost</span>
            </a>

            <a href="/php/admin/admin_kamar.php" class="nav-btn">
                <i class="ri-door-line"></i>
                <span>Kamar</span>
            </a>

            <a href="/php/admin/admin_sewa.php" class="nav-btn active">
                <i class="ri-file-list-3-line"></i>
                <span>Sewa</span>
            </a>

        </div>
    </div>

</body>
</html>
