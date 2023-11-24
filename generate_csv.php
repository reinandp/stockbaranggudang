<?php
$db = new mysqli('localhost', 'root', '', 'stockbarangikan');
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

function generateCSV($title, $header, $data)
{
    $csv = fopen('php://output', 'w');
    
    // Set CSV header for download
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="combined_data.csv"');
    
    // Output the title
    echo "$title\n";

    // Output the CSV header
    fputcsv($csv, $header);

    while ($row = $data->fetch_assoc()) {
        fputcsv($csv, $row);
    }

    fclose($csv);
}

// Determine the type of report to generate based on the 'table' parameter in the URL
if (isset($_GET['table'])) {
    $table = $_GET['table'];

    $filename = '';
    switch ($table) {
        case 'stock_barang':
            $filename = 'stock_barang_data.csv';
            $query = "SELECT id, kode_barang, nama_barang, harga_satuan, jumlah_stock FROM stock_barang";
            $result = $db->query($query);
            generateCSV('Data Stock Barang', ['id', 'Kode Barang', 'Nama Barang', 'Harga Satuan', 'Jumlah Stock'], $result);
            break;
        case 'stock_masuk':
            $filename = 'stock_masuk_data.csv';
            $query = "SELECT sm.id, kb.nama_kategori, sm.kode_barang, sm.nama_barang, sm.nama_supplier, sm.jumlah_masuk, sm.tanggal_masuk 
                      FROM stock_masuk sm
                      JOIN kategori_barang kb ON sm.id_kategori = kb.id";
            $result = $db->query($query);
            generateCSV('Data Stock Masuk', ['id', 'Nama Kategori','Kode Barang', 'Nama Barang', 'Nama Supplier', 'Jumlah Masuk', 'Tanggal Masuk'], $result);
            break;
        case 'stock_keluar':
            $filename = 'stock_keluar_data.csv';
            $query = "SELECT id, kode_nota, nama_pembeli, kode_barang, nama_barang, harga_satuan, jumlah_kg, total_harga, tanggal_keluar, status_bayar FROM stock_keluar";
            $result = $db->query($query);
            generateCSV('Data Stock Keluar', ['id','Kode Nota', 'Nama Pembeli', 'Kode Barang', 'Nama Barang', 'Harga Satuan', 'Jumlah KG', 'Total Harga', 'Tanggal Keluar', 'Status Bayar'], $result);
            break;
        default:
            echo "Invalid table specified.";
    }

    if (!empty($filename)) {
        // Set the CSV header with the custom filename
        header('Content-Type: text/csv');
        header("Content-Disposition: attachment; filename=\"$filename\"");
    }
} else {
    echo "No table specified.";
}
?>