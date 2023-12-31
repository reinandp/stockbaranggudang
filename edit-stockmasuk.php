<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$db = new mysqli('localhost', 'u346095446_reinand', 'XiaoWang22', 'u346095446_stockbarang');
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

$user_id = $_SESSION['user_id'];

// Fetch data from 'kategori_barang' table
$query_databarang = "SELECT id_kategori, kode_barang, nama_barang FROM nama_barang";
$result_databarang = $db->query($query_databarang);

// Fetch user's name from the database based on user_id
$query = "SELECT name FROM user_login WHERE id_user = $user_id";
$result = $db->query($query);

if ($result && $result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $user_name = $user['name'];
} else {
    $user_name = "Unknown"; // Default value if name is not found
}



// Fetch all data from 'Data Sepatu & Sendal' table for the userz
// $query_data = "SELECT id, nama_kategori FROM kategori_barang";
// $result_data = $db->query($query_data);
// Jika metode permintaan adalah POST
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $select_query = "SELECT * FROM stock_masuk WHERE id=?";
    $select = $db->prepare($select_query);
    $select->bind_param("i", $id);
    $select->execute();
    $result_update = $select->get_result();
    $data = $result_update->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari formulir
    $id_kategori = $_POST['id_kategori'];
    $kode_barang = $_POST['kode_barang'];
    $nama_barang = $_POST['nama_barang'];
    $nama_supplier = $_POST['nama_supplier'];
    $jumlah_masuk = $_POST['jumlah_masuk'];

    // Konversi format tanggal
    $tanggal_masuk = date("Y-m-d");

    // Ambil data jumlah_masuk sebelum diperbarui
    $query_stock_masuk = "SELECT jumlah_masuk FROM stock_masuk WHERE id = $id";
    $result_stock_masuk = $db->query($query_stock_masuk);

    if ($result_stock_masuk && $result_stock_masuk->num_rows > 0) {
        $row_stock_masuk = $result_stock_masuk->fetch_assoc();
        $jumlah_masuk_sebelumnya = $row_stock_masuk['jumlah_masuk'];
    }

    // Hitung selisih jumlah_masuk sebelumnya dengan jumlah_masuk yang baru
    $selisih_jumlah = $jumlah_masuk - $jumlah_masuk_sebelumnya;

    // Mulai transaksi
    $db->begin_transaction();

    // Pernyataan SQL untuk tabel stock_masuk
    $update_querymasuk = "UPDATE stock_masuk SET id_kategori = '$id_kategori', kode_barang = '$kode_barang', nama_barang = '$nama_barang', nama_supplier = '$nama_supplier', jumlah_masuk = '$jumlah_masuk', tanggal_masuk = '$tanggal_masuk' WHERE id = $id";

    // Pernyataan SQL untuk tabel stock_barang
    $harga_satuan = 0; // Inisialisasi harga satuan ke 0
    // Query untuk mengambil harga satuan dari tabel nama_barang berdasarkan kode_barang
    $query_harga = "SELECT harga_satuan FROM nama_barang WHERE kode_barang = '$kode_barang'";
    $result_harga = $db->query($query_harga);

    if ($result_harga && $result_harga->num_rows > 0) {
        $row_harga = $result_harga->fetch_assoc();
        $harga_satuan = $row_harga['harga_satuan'];
    }

    // Pernyataan SQL untuk mengupdate atau mengubah jumlah_stock di tabel stock_barang
    $update_querybarang = "UPDATE stock_barang SET jumlah_stock = jumlah_stock + $selisih_jumlah WHERE kode_barang = '$kode_barang'";

    if ($db->query($update_querymasuk) === TRUE && $db->query($update_querybarang) === TRUE) {
        // Commit transaksi jika semua pernyataan berhasil
        $db->commit();
        header("Location: data-stockmasuk.php");
        exit(); // Penting untuk keluar setelah pengalihan
    } else {
        // Rollback transaksi jika ada kesalahan
        $db->rollback();
        echo "Error: " . $db->error;
    }

    // Tutup koneksi database
    $db->close();
}


?>

<!DOCTYPE html>
<!--
Template Name: Metronic - Bootstrap 4 HTML, React, Angular 9 & VueJS Admin Dashboard Theme
Author: KeenThemes
Website: http://www.keenthemes.com/
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Dribbble: www.dribbble.com/keenthemes
Like: www.facebook.com/keenthemes
Purchase: https://1.envato.market/EA4JP
Renew Support: https://1.envato.market/EA4JP
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<html lang="en">
<!--begin::Head-->

<head>
    <base href="">
    <meta charset="utf-8" />
    <title>Stock Masuk</title>
    <meta name="description" content="Updates and statistics" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <!--end::Fonts-->
    <!--begin::Page Vendors Styles(used by this page)-->
    <link href="assets/plugins/custom/fullcalendar/fullcalendar.bundle.css?v=7.0.4" rel="stylesheet" type="text/css" />
    <!--end::Page Vendors Styles-->
    <!--begin::Global Theme Styles(used by all pages)-->
    <link href="assets/plugins/global/plugins.bundle.css?v=7.0.4" rel="stylesheet" type="text/css" />
    <link href="assets/plugins/custom/prismjs/prismjs.bundle.css?v=7.0.4" rel="stylesheet" type="text/css" />
    <link href="assets/css/style.bundle.css?v=7.0.4" rel="stylesheet" type="text/css" />
    <!--end::Global Theme Styles-->
    <!--begin::Layout Themes(used by all pages)-->
    <!--end::Layout Themes-->
    <link rel="shortcut icon" href="assets/media/logos/favicon.ico" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Bootstrap Datepicker CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">

    <!-- Bootstrap Datepicker JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</head>
<!--end::Head-->
<!--begin::Body-->

<body id="kt_body" class="header-fixed subheader-enabled page-loading">

    <!--begin::Main-->
    <div class="d-flex flex-column flex-root">
        <!--begin::Page-->
        <div class="d-flex flex-row flex-column-fluid page">
            <!--begin::Wrapper-->
            <div class="d-flex flex-column flex-row-fluid wrapper" id="kt_wrapper">
                <!--begin::Header Mobile-->
                <div id="kt_header_mobile" class="header-mobile">
                    <!--begin::Logo-->
                    <!-- <a href="index.html">
                        <img alt="Logo" src="assets/media/logos/logo-default.png" class="max-h-30px" />
                    </a> -->
                    <!--end::Logo-->
                    <!--begin::Toolbar-->
                    <div class="d-flex align-items-center">
                        <button class="btn p-0 burger-icon burger-icon-left ml-4" id="kt_header_mobile_toggle">
                            <span></span>
                        </button>
                        <button class="btn p-0 ml-2" id="kt_header_mobile_topbar_toggle">
                            <span class="svg-icon svg-icon-xl">
                                <!--begin::Svg Icon | path:assets/media/svg/icons/General/User.svg-->
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <polygon points="0 0 24 0 24 24 0 24" />
                                        <path d="M12,11 C9.790861,11 8,9.209139 8,7 C8,4.790861 9.790861,3 12,3 C14.209139,3 16,4.790861 16,7 C16,9.209139 14.209139,11 12,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                        <path d="M3.00065168,20.1992055 C3.38825852,15.4265159 7.26191235,13 11.9833413,13 C16.7712164,13 20.7048837,15.2931929 20.9979143,20.2 C21.0095879,20.3954741 20.9979143,21 20.2466999,21 C16.541124,21 11.0347247,21 3.72750223,21 C3.47671215,21 2.97953825,20.45918 3.00065168,20.1992055 Z" fill="#000000" fill-rule="nonzero" />
                                    </g>
                                </svg>
                                <!--end::Svg Icon-->
                            </span>
                        </button>
                    </div>
                    <!--end::Toolbar-->
                </div>
                <!--end::Header Mobile-->
                <!--begin::Header-->
                <div id="kt_header" class="header header-fixed">
                    <!--begin::Container-->
                    <div class="container">
                        <!--begin::Left-->
                        <div class="d-none d-lg-flex align-items-center mr-3">
                            <!--begin::Logo-->
                            <a href="index.php" class="mr-20">
                                <img alt="Logo" src="..." class="logo-default" style="max-height: 80px;" />
                            </a>
                            <!--end::Logo-->
                        </div>
                        <!--end::Left-->
                        <!--begin::Topbar-->
                        <div class="topbar topbar-minimize">
                            <!--begin::User-->
                            <div class="dropdown">
                                <!--begin::Toggle-->
                                <div class="topbar-item" data-toggle="dropdown" data-offset="0px,0px">
                                    <div class="btn btn-icon btn-clean h-40px w-40px btn-dropdown">
                                        <span class="svg-icon svg-icon-lg">
                                            <!--begin::Svg Icon | path:assets/media/svg/icons/General/User.svg-->
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <polygon points="0 0 24 0 24 24 0 24" />
                                                    <path d="M12,11 C9.790861,11 8,9.209139 8,7 C8,4.790861 9.790861,3 12,3 C14.209139,3 16,4.790861 16,7 C16,9.209139 14.209139,11 12,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                                    <path d="M3.00065168,20.1992055 C3.38825852,15.4265159 7.26191235,13 11.9833413,13 C16.7712164,13 20.7048837,15.2931929 20.9979143,20.2 C21.0095879,20.3954741 20.9979143,21 20.2466999,21 C16.541124,21 11.0347247,21 3.72750223,21 C3.47671215,21 2.97953825,20.45918 3.00065168,20.1992055 Z" fill="#000000" fill-rule="nonzero" />
                                                </g>
                                            </svg>
                                            <!--end::Svg Icon-->
                                        </span>
                                    </div>
                                </div>
                                <!--end::Toggle-->
                                <!--begin::Dropdown-->
                                <div class="dropdown-menu p-0 m-0 dropdown-menu-right dropdown-menu-anim-up dropdown-menu-lg p-0">
                                    <!--begin::Header-->
                                    <div class="d-flex align-items-center p-8 rounded-top">
                                        <!--begin::Symbol-->
                                        <div class="symbol symbol-md bg-light-primary mr-3 flex-shrink-0">
                                            <img src="assets/media/users/300_21.jpg" alt="" />
                                        </div>
                                        <!--end::Symbol-->
                                        <!--begin::Text-->
                                        <div class="text-dark m-0 flex-grow-1 mr-3 font-size-h5"><?php echo $user_name; ?></div>
                                        <!--end::Text-->
                                    </div>
                                    <!--end::Header-->
                                    <!--begin::Nav-->
                                    <div class="navi navi-spacer-x-0 pt-5">
                                        <!--begin::Footer-->
                                        <div class="navi-separator mt-3"></div>
                                        <div class="navi-footer px-8 py-5">
                                            <a href="login.php" class="btn btn-light-primary font-weight-bold">Sign Out</a>
                                        </div>
                                        <!--end::Footer-->
                                    </div>
                                    <!--end::Nav-->
                                </div>
                                <!--end::Dropdown-->
                            </div>
                            <!--end::User-->
                        </div>
                        <!--end::Topbar-->
                    </div>
                    <!--end::Container-->
                </div>
                <!--end::Header-->
                <!--begin::Header Menu Wrapper-->
                <div class="header-menu-wrapper header-menu-wrapper-left" id="kt_header_menu_wrapper">
                    <div class="container">
                        <!--begin::Header Menu-->
                        <div id="kt_header_menu" class="header-menu header-menu-left header-menu-mobile header-menu-layout-default header-menu-root-arrow">
                            <!--begin::Header Nav-->
                            <ul class="menu-nav">
                                <li class="menu-item" data-menu-toggle="click" aria-haspopup="true">
                                    <a href="index.php" class="menu-link">
                                        <span class="menu-text">Dashboard</span>
                                    </a>
                                </li>
                                <li class="menu-item menu-item-open menu-item-here menu-item-open menu-item-here" data-menu-toggle="click" aria-haspopup="true">
                                    <a href="#" class="menu-link">
                                        <span class="menu-text">Tambah Stock Masuk</span>
                                    </a>
                                </li>
                                <!-- <li class="menu-item" data-menu-toggle="click" aria-haspopup="true">
                                    <a href="data-namabarang.php" class="menu-link">
                                        <span class="menu-text">Data Nama Barang</span>
                                    </a>
                                </li> -->



                                <!--end::Header Nav-->
                        </div>
                        <!--end::Header Menu-->
                    </div>
                </div>
                <!--end::Header Menu Wrapper-->
                <!--begin::Container-->
                <div class="d-flex flex-row flex-column-fluid container">
                    <!--begin::Content Wrapper-->
                    <div class="main d-flex flex-column flex-row-fluid">
                        <!--begin::Card-->
                        <div class="card card-custom gutter-b">
                            <div class="card-header flex-wrap py-3">
                                <div class="card-title">
                                    <h3 class="card-label">Tambah Stock Masuk </h3>
                                    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
                                        <li class="breadcrumb-item">
                                            <a href="add-stockmasuk.php" class="text-muted">Tambah Stock</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="content flex-column-fluid" id="kt_content">
                                <div class="row">
                                    <div class="col-md-12">
                                        <!--begin::Card-->
                                        <div class="card card-custom gutter-b example example-compact">
                                            <!--begin::Form-->
                                            <form method="POST" onsubmit="return validateForm()">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="form-group col-6">
                                                            <label for="exampleSelect1">Kategori Barang</label>
                                                            <select class="form-control" name="id_kategori" id="exampleSelect1">
                                                                <option>Pilih Kategori</option>
                                                                <?php

                                                                $selected_kategori_id = $data['id_kategori'];
                                                                // Query untuk mengambil id_kategori dari tabel nama_barang dan menggantinya dengan nama_barang dari tabel kategori_barang
                                                                $query_id_kategori = "SELECT DISTINCT nb.id_kategori, kb.nama_kategori FROM nama_barang AS nb
                        LEFT JOIN kategori_barang AS kb ON nb.id_kategori = kb.id";
                                                                $result_id_kategori = $db->query($query_id_kategori);

                                                                if ($result_id_kategori && $result_id_kategori->num_rows > 0) {
                                                                    while ($row_id_kategori = $result_id_kategori->fetch_assoc()) {
                                                                        $id_kategori = $row_id_kategori['id_kategori'];
                                                                        $nama_kategori = $row_id_kategori['nama_kategori'];
                                                                        // Tambahkan setiap id_kategori (sekarang adalah nama_kategori) sebagai pilihan dalam dropdown
                                                                        $selected = ($id_kategori == $selected_kategori_id) ? 'selected' : '';

                                                                        echo "<option value=\"$id_kategori\" $selected>$nama_kategori</option>";
                                                                    }
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-6">
                                                            <label for="exampleSelect3">Nama Barang</label>
                                                            <select class="form-control" name="nama_barang" id="exampleSelect3">
                                                                <?php
                                                                // Ambil semua opsi nama_barang yang tersedia
                                                                $query_nama_barang = "SELECT DISTINCT nama_barang FROM stock_masuk";
                                                                $result_nama_barang = $db->query($query_nama_barang);

                                                                if ($result_nama_barang && $result_nama_barang->num_rows > 0) {
                                                                    while ($row_nama_barang = $result_nama_barang->fetch_assoc()) {
                                                                        $nama_barang_option = $row_nama_barang['nama_barang'];

                                                                        // Periksa apakah opsi saat ini sama dengan data yang berasal dari tabel
                                                                        $selected = ($selected_nama_barang == $nama_barang_option) ? 'selected' : '';

                                                                        // Tampilkan opsi dalam dropdown dengan atribut "selected" jika sesuai
                                                                        echo "<option value=\"$nama_barang_option\" $selected>$nama_barang_option</option>";
                                                                    }
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-6">
                                                            <label for="kode_barang">Kode Barang</label>
                                                            <input type="text" class="form-control" name="kode_barang" id="kode_barang" readonly value="<?php echo $data['kode_barang']; ?>">
                                                        </div>
                                                        <div class="form-group col-6">
                                                            <label for="exampleSelect1">Nama Supplier</label>
                                                            <select class="form-control" name="nama_supplier" id="exampleSelect4">
                                                                <option>Pilih Supplier</option>
                                                                <?php
                                                                // Ambil semua opsi nama_supplier yang tersedia
                                                                $query_supplier = "SELECT DISTINCT nama_supplier FROM stock_masuk";
                                                                $result_supplier = $db->query($query_supplier);
                                                                $selected_supplier = $data['nama_supplier'];

                                                                if ($result_supplier && $result_supplier->num_rows > 0) {
                                                                    while ($row_supplier = $result_supplier->fetch_assoc()) {
                                                                        $nama_supplier_option = $row_supplier['nama_supplier'];

                                                                        // Periksa apakah opsi saat ini sama dengan data yang berasal dari tabel
                                                                        $selected = ($selected_supplier == $nama_supplier_option) ? 'selected' : '';

                                                                        // Tampilkan opsi dalam dropdown dengan atribut "selected" jika sesuai
                                                                        echo "<option value=\"$nama_supplier_option\" $selected>$nama_supplier_option</option>";
                                                                    }
                                                                } else {
                                                                    echo "<option value=''>Tidak Ada Supplier Tersedia</option>";
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-6">
                                                            <label for="jumlah_masuk">Jumlah Masuk</label>
                                                            <input type="text" class="form-control" name="jumlah_masuk" id="jumlah_masuk" value="<?php echo $data['jumlah_masuk']; ?>">
                                                            <span class="form-text text-muted">Data dituliskan dalam bentuk nilai</span>
                                                            <span id="error_message" style="color: red; display: none;">Harus diisi dengan angka yang valid</span>
                                                        </div>
                                                        <div class="form-group col-6">
                                                            <label class="col-form-label" for="kt_datepicker_3">Tanggal Masuk Barang</label>
                                                            <div class="input-group date">
                                                                <input type="text" class="form-control" readonly value="<?php echo $data['tanggal_masuk']; ?>" id="kt_datepicker_3" />
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">
                                                                        <i class="la la-calendar"></i>
                                                                    </span>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer">
                                                    <button type="submit" class="btn btn-primary mr-2">Submit</button>
                                                </div>
                                            </form>
                                            <!--end::Form-->
                                        </div>
                                        <div class="alert alert-danger" id="empty_data_message" style="display: none;">
                                            Data harus diisi.
                                        </div>
                                        <!--end::Card-->

                                    </div>
                                </div>
                            </div>
                            <!--end::Content-->
                        </div>
                        <!--end::Card-->
                    </div>
                    <!--begin::Content Wrapper-->
                </div>
                <!--end::Container-->
                <!--begin::Footer-->
                <div class="footer py-4 d-flex flex-lg-column" id="kt_footer">
                    <!--begin::Container-->
                    <div class="container d-flex flex-column flex-md-row align-items-center justify-content-between">
                        <!--begin::Copyright-->
                        <div class="text-dark order-2 order-md-1">
                            <span class="text-muted font-weight-bold mr-2">2023©</span>
                            <a href="#" class="text-white text-hover-primary">Vultra</a>
                        </div>
                        <!--end::Copyright-->

                    </div>
                    <!--end::Container-->
                </div>
                <!--end::Footer-->
            </div>
            <!--end::Wrapper-->
        </div>
        <!--end::Page-->
    </div>
    <!--end::Main-->
    <!--end::Demo Panel-->

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const kategoriDropdown = document.getElementById("exampleSelect1");
            const namaDropdown = document.getElementById("exampleSelect3");
            const kodeInput = document.getElementById("kode_barang");

            // Tambahkan opsi "Pilih Nama Barang" ke dalam dropdown "Nama Barang" sebagai pilihan default
            const defaultOption = document.createElement("option");
            defaultOption.value = ""; // Kosongkan nilai value
            defaultOption.text = "Pilih Nama Barang";
            namaDropdown.appendChild(defaultOption);

            kategoriDropdown.addEventListener("change", function() {
                const selectedKategori = kategoriDropdown.value;

                // Buat permintaan AJAX untuk mengambil data "Nama Barang" dan "Kode Barang" berdasarkan id_kategori
                const ajaxRequest = new XMLHttpRequest();
                ajaxRequest.open("GET", "get-nama-kode-barang.php?id_kategori=" + selectedKategori, true);

                ajaxRequest.onreadystatechange = function() {
                    if (ajaxRequest.readyState === 4 && ajaxRequest.status === 200) {
                        const data = JSON.parse(ajaxRequest.responseText);

                        // Reset input "Kode Barang" dan dropdown "Nama Barang"
                        kodeInput.value = "";

                        // Hapus semua opsi sebelum menambahkan yang baru
                        namaDropdown.innerHTML = "";
                        namaDropdown.appendChild(defaultOption); // Kembalikan pilihan default

                        if (data.length > 0) {
                            data.forEach(function(item) {
                                // Buat opsi untuk dropdown "Nama Barang"
                                const namaOption = document.createElement("option");
                                namaOption.value = item.nama_barang;
                                namaOption.text = item.nama_barang;
                                namaDropdown.appendChild(namaOption);
                            });
                        }
                    }
                };

                ajaxRequest.send();
            });

            namaDropdown.addEventListener("change", function() {
                const selectedKategori = kategoriDropdown.value;
                const selectedNamaBarang = namaDropdown.value;

                // Buat permintaan AJAX untuk mengambil kode_barang berdasarkan id_kategori dan nama_barang
                const ajaxRequest = new XMLHttpRequest();
                ajaxRequest.open("GET", "fetch-kode-nama-barang.php?id_kategori=" + selectedKategori + "&nama_barang=" + selectedNamaBarang, true);

                ajaxRequest.onreadystatechange = function() {
                    if (ajaxRequest.readyState === 4 && ajaxRequest.status === 200) {
                        const kodeData = JSON.parse(ajaxRequest.responseText);

                        if (kodeData.kode_barang) {
                            kodeInput.value = kodeData.kode_barang;
                        } else {
                            kodeInput.value = "Kode Barang Tidak Ditemukan"; // Atur pesan default jika tidak ada kode_barang yang cocok
                        }
                    }
                };

                ajaxRequest.send();
            });
        });
    </script>

    <script>
        var HOST_URL = "https://keenthemes.com/metronic/tools/preview";
    </script>
    <!--begin::Global Config(global config for global JS scripts)-->
    <script>
        $(document).ready(function() {
            // Inisialisasi Bootstrap Datepicker
            $('#kt_datepicker_3').datepicker({
                format: 'mm/dd/yyyy', // Format tanggal
                autoclose: true // Untuk menutup kalender setelah memilih tanggal
            });

            // Set tanggal saat pertama kali halaman dimuat
            $('#kt_datepicker_3').datepicker('setDate', new Date());
        });
    </script>

    <script>
        function validateForm() {
            var jumlahMasuk = document.getElementById('jumlah_masuk').value;

            // Validasi jika jumlahMasuk bukan angka
            if (isNaN(jumlahMasuk) || jumlahMasuk <= 0) {
                document.getElementById('error_message').style.display = 'block';
                return false;
            } else {
                document.getElementById('error_message').style.display = 'none';
                return true;
            }
        }
        var KTAppSettings = {
            "breakpoints": {
                "sm": 576,
                "md": 768,
                "lg": 992,
                "xl": 1200,
                "xxl": 1200
            },
            "colors": {
                "theme": {
                    "base": {
                        "white": "#ffffff",
                        "primary": "#8950FC",
                        "secondary": "#E5EAEE",
                        "success": "#1BC5BD",
                        "info": "#6993FF",
                        "warning": "#FFA800",
                        "danger": "#F64E60",
                        "light": "#F3F6F9",
                        "dark": "#212121"
                    },
                    "light": {
                        "white": "#ffffff",
                        "primary": "#EEE5FF",
                        "secondary": "#ECF0F3",
                        "success": "#C9F7F5",
                        "info": "#E1E9FF",
                        "warning": "#FFF4DE",
                        "danger": "#FFE2E5",
                        "light": "#F3F6F9",
                        "dark": "#D6D6E0"
                    },
                    "inverse": {
                        "white": "#ffffff",
                        "primary": "#ffffff",
                        "secondary": "#212121",
                        "success": "#ffffff",
                        "info": "#ffffff",
                        "warning": "#ffffff",
                        "danger": "#ffffff",
                        "light": "#464E5F",
                        "dark": "#ffffff"
                    }
                },
                "gray": {
                    "gray-100": "#F3F6F9",
                    "gray-200": "#ECF0F3",
                    "gray-300": "#E5EAEE",
                    "gray-400": "#D6D6E0",
                    "gray-500": "#B5B5C3",
                    "gray-600": "#80808F",
                    "gray-700": "#464E5F",
                    "gray-800": "#1B283F",
                    "gray-900": "#212121"
                }
            },
            "font-family": "Poppins"
        };
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!--end::Global Config-->
    <!--begin::Global Theme Bundle(used by all pages)-->
    <script src="assets/plugins/global/plugins.bundle.js?v=7.0.4"></script>
    <script src="assets/plugins/custom/prismjs/prismjs.bundle.js?v=7.0.4"></script>
    <script src="assets/js/scripts.bundle.js?v=7.0.4"></script>
    <!--end::Global Theme Bundle-->
    <!--begin::Page Vendors(used by this page)-->
    <script src="assets/plugins/custom/fullcalendar/fullcalendar.bundle.js?v=7.0.4"></script>
    <!--end::Page Vendors-->
    <!--begin::Page Scripts(used by this page)-->
    <script src="assets/js/pages/widgets.js?v=7.0.4"></script>
    <!--end::Page Scripts-->
</body>
<!--end::Body-->

</html>