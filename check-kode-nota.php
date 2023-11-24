<?php
// Sambungkan ke database
$db = new mysqli('localhost', 'u346095446_reinand', 'XiaoWang22', 'u346095446_stockbarang');
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

$kodeNota = $_GET['kode_nota'];

// Periksa apakah kode nota sudah tersedia di tabel stock_keluar
$query = "SELECT COUNT(*) AS count FROM stock_keluar WHERE kode_nota = '$kodeNota'";
$result = $db->query($query);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $count = $row['count'];
    if ($count > 0) {
        echo "available";
    } else {
        echo "not available";
    }
} else {
    echo "not available";
}

// Tutup koneksi database
$db->close();
?>