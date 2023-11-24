<?php
$db = new mysqli('localhost', 'root', '', 'stockbarangikan');
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}


if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id_kategori']) && isset($_GET['nama_barang'])) {
    $id_kategori = $_GET['id_kategori'];
    $nama_barang = $_GET['nama_barang'];

    // Lakukan query database untuk mendapatkan kode_barang berdasarkan id_kategori dan nama_barang
    $query = "SELECT kode_barang FROM nama_barang WHERE id_kategori = " . $id_kategori . " AND nama_barang = '" . $nama_barang . "'";
    $result = $db->query($query);

    if ($result) {
        $row = $result->fetch_assoc();
        $kode_barang = $row['kode_barang'];

        // Mengembalikan kode_barang dalam format JSON
        echo json_encode(array('kode_barang' => $kode_barang));
    } else {
        // Mengembalikan JSON kosong atau pesan kesalahan jika ada kesalahan dalam query
        echo json_encode(array('error' => 'Terjadi kesalahan dalam query.'));
    }
} else {
    // Mengembalikan JSON kosong atau pesan kesalahan jika permintaan tidak valid
    echo json_encode(array('error' => 'Permintaan tidak valid.'));
}
