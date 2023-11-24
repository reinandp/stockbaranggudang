<?php
// Buat koneksi ke database
$db = new mysqli('localhost', 'root', '', 'stockbarangikan');
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kode_barang = $_POST['kode_barang'];
    $jumlah_kg = intval($_POST['jumlah_kg']); // Pastikan jumlah_kg adalah bilangan bulat

    // Query untuk mendapatkan jumlah_stock berdasarkan kode_barang
    $sql = "SELECT jumlah_stock FROM stock_barang WHERE kode_barang = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("s", $kode_barang);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $jumlah_stock = $row['jumlah_stock'];

        if ($jumlah_stock !== null && $jumlah_stock >= $jumlah_kg) {
            echo 'Stok tersedia';
        } else if ($jumlah_stock !== null && $jumlah_stock < $jumlah_kg) {
            echo 'Melebihi stok'; // Jika jumlah_kg melebihi jumlah_stock
        } else {
            echo 'Stok tidak tersedia';
        }
    } else {
        echo 'Kode barang tidak ditemukan'; // Jika kode_barang tidak ditemukan
    }
}

// Tutup koneksi database
$db->close();