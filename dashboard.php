<?php
// File: dashboard.php
// HANYA BISA DIAKSES SETELAH LOGIN

session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.xhtml?error=" . urlencode("Silakan login terlebih dahulu"));
    exit;
}

// Cek session expiry
if (isset($_SESSION['expiry_time']) && time() > $_SESSION['expiry_time']) {
    // Session expired, logout user
    session_destroy();
    header("Location: login.xhtml?error=" . urlencode("Session telah berakhir. Silakan login kembali"));
    exit;
}

// Extend session expiry (opsional)
$_SESSION['expiry_time'] = time() + (8 * 60 * 60);
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Dashboard - Sistem Kost</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .dashboard-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
        }
        
        .welcome-message {
            font-size: 1.5em;
            margin-bottom: 10px;
        }
        
        .user-info {
            background: rgba(255, 255, 255, 0.1);
            padding: 15px;
            border-radius: 5px;
            margin-top: 15px;
        }
        
        .quick-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .stat-number {
            font-size: 2.5em;
            font-weight: bold;
            color: #4CAF50;
            margin: 10px 0;
        }
        
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin: 30px 0;
        }
        
        .action-btn {
            display: block;
            padding: 15px;
            background: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
            transition: transform 0.2s;
        }
        
        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        
        .admin-btn {
            background: #2196F3;
        }
        
        .logout-btn {
            background: #f44336;
        }
    </style>
</head>
<body>
    <div class="dashboard-header">
        <h1>🏠 Dashboard Sistem Kost</h1>
        
        <div class="welcome-message">
            Selamat datang, <strong><?php echo htmlspecialchars($_SESSION['nama']); ?>!</strong>
        </div>
        
        <div class="user-info">
            <p><strong>Username:</strong> <?php echo htmlspecialchars($_SESSION['username']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['email']); ?></p>
            <p><strong>Role:</strong> <?php echo htmlspecialchars($_SESSION['role']); ?></p>
            <p><strong>Login Time:</strong> <?php echo date('d/m/Y H:i:s', $_SESSION['login_time'] ?? time()); ?></p>
        </div>
        
        <div style="margin-top: 20px;">
            <a href="index.xhtml" style="color: white; margin-right: 15px;">🏠 Home</a>
            <a href="php/auth/logout.php" style="color: white;">🚪 Logout</a>
        </div>
    </div>
    
    <div class="quick-stats">
        <div class="stat-card">
            <h3>📋 Total Kost</h3>
            <div class="stat-number" id="totalKost">0</div>
            <p>Kost yang Anda miliki</p>
        </div>
        
        <div class="stat-card">
            <h3>🛏️ Total Kamar</h3>
            <div class="stat-number" id="totalKamar">0</div>
            <p>Kamar yang tersedia</p>
        </div>
        
        <div class="stat-card">
            <h3>👥 Role</h3>
            <div class="stat-number"><?php echo strtoupper($_SESSION['role']); ?></div>
            <p>Hak akses sistem</p>
        </div>
    </div>
    
    <h2>Quick Actions</h2>
    <div class="quick-actions">
        <a href="manage-kost.php" class="action-btn">➕ Tambah Kost</a>
        <a href="manage-kamar.php" class="action-btn">🛏️ Kelola Kamar</a>
        <a href="profile.php" class="action-btn">👤 Edit Profile</a>
        
        <?php if ($_SESSION['role'] === 'admin'): ?>
        <a href="admin/users.php" class="action-btn admin-btn">👥 Manage Users</a>
        <a href="admin/reports.php" class="action-btn admin-btn">📊 View Reports</a>
        <?php endif; ?>
        
        <a href="php/auth/logout.php" class="action-btn logout-btn">🚪 Logout</a>
    </div>
    
    <script>
        // JavaScript untuk dashboard
        document.addEventListener('DOMContentLoaded', function() {
            // Animate stat numbers
            function animateCounter(elementId, targetValue) {
                let element = document.getElementById(elementId);
                let current = 0;
                let increment = targetValue / 50;
                
                let timer = setInterval(function() {
                    current += increment;
                    if (current >= targetValue) {
                        current = targetValue;
                        clearInterval(timer);
                    }
                    element.textContent = Math.floor(current);
                }, 20);
            }
            
            // Simulasi data (dalam aplikasi nyata, data ini dari PHP)
            animateCounter('totalKost', 5);
            animateCounter('totalKamar', 15);
            
            // Auto logout warning setelah 7.5 jam (30 menit sebelum expiry)
            setTimeout(function() {
                if (confirm('Session Anda akan segera berakhir. Apakah ingin memperpanjang session?')) {
                    // Refresh page untuk extend session
                    window.location.reload();
                }
            }, 7.5 * 60 * 60 * 1000); // 7.5 jam
            
            // Update time setiap menit
            setInterval(function() {
                const now = new Date();
                document.getElementById('currentTime').textContent = 
                    now.toLocaleTimeString('id-ID');
            }, 60000);
        });
    </script>
    
    <div style="text-align: center; margin-top: 40px; color: #666;">
        <p>Waktu saat ini: <span id="currentTime"><?php echo date('H:i:s'); ?></span></p>
        <p>© <?php echo date('Y'); ?> Sistem Kost - All rights reserved</p>
    </div>
</body>
</html>