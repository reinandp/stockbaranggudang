<?php
$db = new mysqli('localhost', 'root', '', 'stockbarangikan');
if ($db->connect_error) {
    die("Koneksi gagal: " . $db->connect_error);
}

$kode_barang = $_GET['kode_barang'];

$query = "SELECT harga_satuan FROM nama_barang WHERE kode_barang = '$kode_barang'";
$result = $db->query($query);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();

    $data = array(
        'harga_satuan' => $row['harga_satuan']
    );

    header('Content-Type: application/json');
    echo json_encode($data);
} else {
    $data = array(
        'harga_satuan' => null
    );

    header('Content-Type: application/json');
    echo json_encode($data);
}

$db->close();
