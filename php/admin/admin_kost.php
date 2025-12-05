<?php
session_start();

// Cek apakah login DAN apakah admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: ../../xhtml/login.xhtml?error=Akses ditolak");
    exit;
}

require_once "../auth/db_connection.php";

// Ambil data kost + thumbnail jika ada
$sql = "
    SELECT 
        k.kost_id,
        k.nama_kost,
        k.alamat,
        k.deskripsi,
        ki.kost_path
    FROM kost k
    LEFT JOIN kost_image ki ON k.kost_id = ki.kost_id AND ki.thumbnail = 1
    ORDER BY k.kost_id DESC
";

$result = $conn->query($sql);
$kostList = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $kostList[] = $row;
    }
}
?>

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Admin - Kelola Kost</title>
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

    <!-- HEADER -->
    <div class="header-top">
        <a href="/php/auth/logout.php" class="logout-btn">
            <i class="ri-logout-circle-r-line"></i> Logout
        </a>
        <div class="container header-content">
            <div class="user-profile-top">
                <img src="/media/pfp_admin.png" alt="Profile" class="avatar" />
                <div class="user-info">
                    <span class="name">Admin Panel</span>
                    <span class="location"><i class="ri-shield-user-line"></i> Kost</span>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="container main-content">

        <a href="/php/CRUDs/kost/create_kost.php" class="admin-btn-add">
            <i class="ri-add-circle-line"></i> Tambah Kost Baru
        </a>

        <div class="section-title">
            <h2>Daftar Kost</h2>
        </div>

        <div class="recommendation-list">

            <?php if (empty($kostList)): ?>
                <p style="text-align:center; color:#666;">Belum ada kost terdaftar.</p>
            <?php else: ?>

            <?php foreach ($kostList as $kost): ?>
                <div class="list-item">

                    <div class="thumb">
                        <img src="/media/<?= htmlspecialchars($kost['kost_path'] ?? 'default_kost.png') ?>" 
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

                        <p style="font-size:13px; color:#555; margin-top:6px;">
                            <?= nl2br(htmlspecialchars($kost['deskripsi'])) ?>
                        </p>

                        <div class="action-row-admin">
                            <a href="/php/CRUDs/kost/update_kost.php?kost_id=<?= $kost['kost_id'] ?>" class="edit-btn">
                                Edit
                            </a>
                            <a href="/php/CRUDs/kost/delete_kost.php?kost_id=<?= $kost['kost_id'] ?>" 
                               class="delete-btn"
                               onclick="return confirm('Yakin ingin menghapus kost ini?')">
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

            <a href="/php/admin/admin_kost.php" class="nav-btn active">
                <i class="ri-home-5-line"></i>
                <span>Kost</span>
            </a>

            <a href="/php/admin/admin_kamar.php" class="nav-btn">
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
