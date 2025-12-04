<?php
session_start();
require_once "../auth/db_connection.php";

// Cek login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../xhtml/login.xhtml?error=Silakan login dulu");
    exit;
}

$nama_user = $_SESSION['nama'];

// Ambil kost_id
$kost_id = isset($_GET['kost_id']) ? intval($_GET['kost_id']) : 0;
if ($kost_id <= 0) {
    die("ID kost tidak ditemukan.");
}

// Ambil data kost
$stmt = $conn->prepare("SELECT * FROM kost WHERE kost_id = ?");
$stmt->bind_param("i", $kost_id);
$stmt->execute();
$kost = $stmt->get_result()->fetch_assoc();

// Ambil 1 kamar default (kamar tersedia)
$stmt = $conn->prepare("
    SELECT * FROM kamar 
    WHERE kost_id = ? AND status_kamar != 'terisi' 
    ORDER BY kamar_id ASC LIMIT 1
");
$stmt->bind_param("i", $kost_id);
$stmt->execute();
$kamar = $stmt->get_result()->fetch_assoc();

// Jika tidak ada kamar tersedia, ambil kamar pertama
if (!$kamar) {
    $stmt = $conn->prepare("SELECT * FROM kamar WHERE kost_id = ? LIMIT 1");
    $stmt->bind_param("i", $kost_id);
    $stmt->execute();
    $kamar = $stmt->get_result()->fetch_assoc();
}

// Ambil gambar kost thumbnail
$stmt = $conn->prepare("SELECT kost_path FROM kost_image WHERE kost_id = ? AND thumbnail = 1 LIMIT 1");
$stmt->bind_param("i", $kost_id);
$stmt->execute();
$img = $stmt->get_result()->fetch_assoc();
$img_path = $img ? "/media/" . $img['kost_path'] : "/media/default.jpg";

// Fungsi untuk parse teks CSV dan memasukkan icon UI tetap
function build_items($csv, $icons) {
    $parts = array_map('trim', explode(',', $csv));
    $html = "";

    foreach ($parts as $index => $text) {
        $icon = isset($icons[$index]) ? $icons[$index] : $icons[0];

        $html .= '
            <div class="spec-item">
                <i class="' . $icon . '"></i>
                <span>' . htmlspecialchars($text) . '</span>
            </div>
        ';
    }
    return $html;
}

// ICON yang dipakai layout asli
$icon_kamar = [
    "ri-ruler-2-line",
    "ri-flashlight-fill"
];

$icon_fasilitas = [
    "ri-hotel-bed-fill",
    "ri-archive-drawer-line",
    "ri-window-fill",
    "ri-user-smile-line"
];

$icon_mandi = [
    "ri-drop-fill",
    "ri-door-open-line",
    "ri-cup-fill"
];

$icon_peraturan = [
    "ri-group-line",
    "ri-time-line"
];

// Buat list dinamis
$list_spesifikasi = build_items($kamar['spesifikasi_kamar'], $icon_kamar);
$list_fasilitas = build_items($kamar['fasilitas_kamar'], $icon_fasilitas);
$list_mandi = build_items($kamar['fasilitas_kamar_mandi'], $icon_mandi);
$list_peraturan = build_items($kamar['peraturan'], $icon_peraturan);

// Load template XHTML
$template = file_get_contents("../../xhtml/fasilitas.xhtml");

// Replace placeholder UI
$template = str_replace("{{GAMBAR_KAMAR}}", $img_path, $template);
$template = str_replace("{{NAMA_KOST}}", htmlspecialchars($kost['nama_kost']), $template);
$template = str_replace("{{ALAMAT}}", htmlspecialchars($kost['alamat']), $template);
$template = str_replace("{{HARGA}}", number_format($kamar['harga'], 0, ',', '.'), $template);
$template = str_replace("{{KAMAR_ID}}", $kamar['kamar_id'], $template);

$template = str_replace("{{LIST_SPESIFIKASI}}", $list_spesifikasi, $template);
$template = str_replace("{{LIST_FASILITAS_KAMAR}}", $list_fasilitas, $template);
$template = str_replace("{{LIST_FASILITAS_MANDI}}", $list_mandi, $template);
$template = str_replace("{{LIST_PERATURAN}}", $list_peraturan, $template);

// Tampilkan
echo $template;
?>
