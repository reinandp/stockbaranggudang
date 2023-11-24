<?php
$db = new mysqli('localhost', 'root', '', 'stockbarangikan');
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

include('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id_kategori'])) {
    $id_kategori = $_GET['id_kategori'];

    // Lakukan query database untuk mendapatkan data yang sesuai berdasarkan id_kategori
    $query = "SELECT kode_barang, nama_barang FROM nama_barang WHERE id_kategori = " . $id_kategori;
    $result = $db->query($query);

    if ($result) {
        $data = array();

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        // Mengembalikan data dalam format JSON
        echo json_encode($data);
    } else {
        // Mengembalikan JSON kosong atau pesan kesalahan jika ada kesalahan dalam query
        echo json_encode(array('error' => 'Terjadi kesalahan dalam query.'));
    }
} else {
    // Mengembalikan JSON kosong atau pesan kesalahan jika permintaan tidak valid
    echo json_encode(array('error' => 'Permintaan tidak valid.'));
}
