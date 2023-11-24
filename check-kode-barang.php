<?php
// Sesuaikan ini dengan koneksi database Anda
$db = new mysqli('localhost', 'u346095446_reinand', 'XiaoWang22', 'u346095446_stockbarang');
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}
// Pastikan Anda telah memulai sesi dan menghubungkan ke database di sini

if (isset($_GET['id_kategori'])) {
    $id_kategori = $_GET['id_kategori'];

    // Buat query SQL untuk mengambil data "Kode Barang" dan "Nama Barang" berdasarkan id_kategori
    $query = "SELECT kode_barang, nama_barang FROM nama_barang WHERE id_kategori = $id_kategori";

    $result = $db->query($query);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $data = array(
            'kode_barang' => $row['kode_barang'],
            'nama_barang' => $row['nama_barang']
        );

        // Mengembalikan data dalam format JSON
        echo json_encode($data);
    } else {
        // Jika tidak ada hasil, mengembalikan data kosong dalam format JSON
        $data = array(
            'kode_barang' => '',
            'nama_barang' => ''
        );

        echo json_encode($data);
    }
} else {
    // Jika id_kategori tidak ditemukan dalam permintaan, mengembalikan data kosong dalam format JSON
    $data = array(
        'kode_barang' => '',
        'nama_barang' => ''
    );

    echo json_encode($data);
}
