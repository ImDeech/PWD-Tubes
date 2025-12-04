<?php
session_start();
require_once "../auth/db_connection.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../xhtml/login.xhtml?error=Silakan login dulu");
    exit;
}

$kamar_id = $_GET['kamar_id'] ?? null;
if (!$kamar_id) {
    die("Kamar ID tidak ditemukan.");
}

/* -------------------------
   FUNGSI PARSE EMOJI -> ICON
----------------------------*/

function parseFacilityItems($text) {
    if ($text === null || trim($text) === '') return '';

    // Mapping emoji -> remixicon class (emoji keys may be multi-codepoint, e.g. "🍽️")
    $iconMap = [
        "📐"   => "ri-ruler-2-line",
        "⚡"   => "ri-flashlight-fill",
        "🍽️"  => "ri-restaurant-2-fill",
        "🛏️"  => "ri-hotel-bed-fill",
        "🗄️"  => "ri-archive-drawer-line",
        "🪟"   => "ri-window-fill",
        "☁️"   => "ri-user-smile-line",
        "💧"   => "ri-drop-fill",
        "🚪"   => "ri-door-open-line",
        "🪣"   => "ri-cup-fill",
        "👥"   => "ri-group-line",
        "🕛"   => "ri-time-line"
    ];

    $items = array_map('trim', explode(',', $text));
    $html = "";

    foreach ($items as $item) {
        if ($item === '') continue;

        $matchedEmoji = null;
        $label = $item;

        // Try to match any emoji key at the start of the string.
        // We check longer emoji keys first to avoid partial matches (e.g. 🍽️ vs 🍽).
        // Sort keys by length desc
        $keys = array_keys($iconMap);
        usort($keys, function($a, $b) {
            return mb_strlen($b, 'UTF-8') - mb_strlen($a, 'UTF-8');
        });

        foreach ($keys as $k) {
            $klen = mb_strlen($k, 'UTF-8');
            // substring of item starting at 0 with length klen
            $start = mb_substr($item, 0, $klen, 'UTF-8');
            if ($start === $k) {
                $matchedEmoji = $k;
                // label is the remainder after the emoji
                $label = trim(mb_substr($item, $klen, null, 'UTF-8'));
                break;
            }
        }

        // choose icon (mapped) or fallback
        $icon = $matchedEmoji && isset($iconMap[$matchedEmoji]) ? $iconMap[$matchedEmoji] : "ri-checkbox-circle-line";

        $html .= '<div class="spec-item">';
        $html .= '<i class="' . $icon . '"></i>';
        $html .= '<span>' . htmlspecialchars($label) . '</span>';
        $html .= '</div>';
    }

    return $html;
}

/* -------------------------
   QUERY KAMAR + KOST + GAMBAR
----------------------------*/

$sql = "
SELECT 
    k.kamar_id,
    k.harga,
    k.spesifikasi_kamar,
    k.fasilitas_kamar,
    k.fasilitas_kamar_mandi,
    k.peraturan,
    k.status_kamar,
    ks.nama_kost,
    ks.alamat,
    (
        SELECT ki.kost_path
        FROM kost_image ki
        WHERE ki.kost_id = ks.kost_id AND ki.thumbnail = 1
        LIMIT 1
    ) AS image_path
FROM kamar k
JOIN kost ks ON ks.kost_id = k.kost_id
WHERE k.kamar_id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $kamar_id);
$stmt->execute();
$res = $stmt->get_result();
$data = $res->fetch_assoc();

if (!$data) {
    die("Data kamar tidak ditemukan.");
}

/* -------------------------
   GENERATE HTML DINAMIS
----------------------------*/

$html = file_get_contents("../../xhtml/fasilitas.xhtml");

$html = str_replace("{{GAMBAR_KAMAR}}", $data['image_path'], $html);
$html = str_replace("{{NAMA_KOST}}", htmlspecialchars($data['nama_kost']), $html);
$html = str_replace("{{ALAMAT}}", htmlspecialchars($data['alamat']), $html);

$sisa_kamar = ($data['status_kamar'] === "tersedia") ? "Ada Kamar" : "Habis";
$html = str_replace("{{SISA_KAMAR}}", $sisa_kamar, $html);

$html = str_replace("{{HARGA}}", number_format($data['harga'], 0, ',', '.'), $html);
$html = str_replace("{{KAMAR_ID}}", $data['kamar_id'], $html);

$html = str_replace("{{SPESIFIKASI}}", parseFacilityItems($data['spesifikasi_kamar']), $html);
$html = str_replace("{{FASILITAS_KAMAR}}", parseFacilityItems($data['fasilitas_kamar']), $html);
$html = str_replace("{{FASILITAS_MANDI}}", parseFacilityItems($data['fasilitas_kamar_mandi']), $html);
$html = str_replace("{{PERATURAN}}", parseFacilityItems($data['peraturan']), $html);

echo $html;
