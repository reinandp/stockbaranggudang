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


function checkKodeBarangExist($db, $kodeBarang)
{
    $kodeBarang = mysqli_real_escape_string($db, $kodeBarang);

    $query = "SELECT COUNT(*) AS count FROM nama_barang WHERE kode_barang = '$kodeBarang'";
    $result = $db->query($query);

    if ($result) {
        $row = $result->fetch_assoc();
        $count = $row['count'];
        return $count > 0;
    } else {
        return false;
    }
}

// Fetch data from 'kategori_barang' table
$query_kategori = "SELECT id, nama_kategori FROM kategori_barang";
$result_kategori = $db->query($query_kategori);



// Fetch user's name from the database based on user_id
$query = "SELECT name FROM user_login WHERE id_user = $user_id";
$result = $db->query($query);

if ($result && $result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $user_name = $user['name'];
} else {
    $user_name = "Unknown"; // Default value if name is not found
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['id_kategori'])) {
        $id = $_GET['id'];
        $IdKategori = $_POST['id_kategori'];
        $KodeBarang = $_POST['kode_barang'];
        $NamaBarang = $_POST['nama_barang'];
        $HargaSatuan = $_POST['harga_satuan'];

        // Prevent SQL injection by using prepared statement
        $update_query = "UPDATE nama_barang SET  id_kategori=?, kode_barang=?, nama_barang=?, harga_satuan=? WHERE id=?";
        $update = $db->prepare($update_query);
        $update->bind_param("ssssi", $IdKategori, $KodeBarang, $NamaBarang, $HargaSatuan, $id); // Menggunakan id dari tabel nama_barang

        if ($update->execute()) {
            header("Location: data-namabarang.php");
            exit();
        } else {
            echo "Error: " . $update->error;
        }
    }
}

// Fetch data for the selected ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $select_query = "SELECT * FROM nama_barang WHERE id=?";
    $select = $db->prepare($select_query);
    $select->bind_param("i", $id);
    $select->execute();
    $result_update = $select->get_result();
    $data = $result_update->fetch_assoc();
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
    <title>Babycom | Edit Pakaian</title>
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
                    <a href="index.html">
                        <img alt="Logo" src="assets/media/logos/logo-default.png" class="max-h-30px" />
                    </a>
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
                                <img alt="Logo" src=".../" class="logo-default" style="max-height: 80px;" />
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
                                    <a href="data-namabarang.php" class="menu-link">
                                        <span class="menu-text">Data Nama Barang</span>
                                    </a>
                                </li>

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
                                    <h3 class="card-label">Edit Nama Barang</h3>
                                    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
                                        <li class="breadcrumb-item">
                                            <a href="data-namabarang.php" class="text-muted">Data Nama Barang</a>
                                        </li>
                                        <li class="breadcrumb-item">
                                            <a href="edit-namabarang.php" class="text-muted">Edit Data</a>
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
                                            <form method="post" onsubmit="return validateForm()">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="form-group col-6">
                                                            <label for="exampleSelect1">Kategori Barang</label>
                                                            <select class="form-control" name="id_kategori" id="exampleSelect1">
                                                                <?php
                                                                $selected_kategori_id = $data['id_kategori']; // Ambil id_kategori dari data Anda

                                                                $query_kategori = "SELECT id, nama_kategori FROM kategori_barang";
                                                                $result_kategori = $db->query($query_kategori);

                                                                while ($row_kategori = $result_kategori->fetch_assoc()) {
                                                                    $id_kategori = $row_kategori['id'];
                                                                    $nama_kategori = $row_kategori['nama_kategori'];

                                                                    $selected = ($id_kategori == $selected_kategori_id) ? 'selected' : '';

                                                                    echo "<option value=\"$id_kategori\" $selected>$nama_kategori</option>";
                                                                }
                                                                ?>
                                                            </select>

                                                        </div>
                                                        <div class="form-group col-6">
                                                            <label>Kode Barang</label>
                                                            <input type="text" class="form-control" name="kode_barang" id="kode_barang" value="<?php echo $data['kode_barang']; ?>" />
                                                            <span class="form-text text-muted">Pastikan Kode Barang tidak sama</span>
                                                            <!-- <span id="duplicate_message" style="color: red; display: none;">Kode barang sudah terdata</span> -->
                                                        </div>
                                                        <div class="form-group col-6">
                                                            <label>Nama Barang</label>
                                                            <input type="text" class="form-control" name="nama_barang" id="nama_barang" value="<?php echo $data['nama_barang']; ?>" />
                                                            <span class="form-text text-muted">Pastikan Nama Kategori diisi dengan huruf, bukan angka</span>
                                                            <!-- <span id="error_message" style="color: red; display: none;">Harus diisi dengan huruf, bukan angka</span> -->
                                                        </div>
                                                        <div class="form-group col-6">
                                                            <label>Harga Satuan</label>
                                                            <input type="text" class="form-control" placeholder="Masukkan Harga Satuan" name="harga_satuan" id="harga_satuan" value="<?php echo $data['harga_satuan']; ?>" />
                                                            <span class="form-text text-muted">Harga Satuan / Harga per KG</span>
                                                            <span id="harga_error_message" style="color: red; display: none;">Harus diisi dengan nominal angka</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer">
                                                    <button type="submit" class="btn btn-primary mr-2">Submit</button>
                                                </div>
                                            </form>
                                            <!--end::Form-->
                                            <div class="alert alert-danger" id="empty_data_message" style="display: none;">
                                                Data harus diisi.
                                            </div>
                                            <div class="alert alert-danger" id="duplicate_message" style="display: none;">
                                                Kode Barang sudah terdata
                                            </div>
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
                            <a href="#" class="text-white text-hover-primary">Babycom</a>
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
    <script>
        function validateForm() {
            var kodeBarang = document.getElementById('kode_barang').value;
            var namaBarang = document.getElementById('nama_barang').value;
            var hargaSatuan = document.getElementById('harga_satuan').value;
            var hasNumber = /\d/.test(namaBarang);

            if (isNaN(hargaSatuan)) {
                document.getElementById('empty_data_message').style.display = 'none';
                document.getElementById('error_message').style.display = 'none';
                document.getElementById('duplicate_message').style.display = 'none';
                document.getElementById('harga_error_message').style.display = 'block';
                return false;
            }

            if (namaBarang.trim() === '') {
                document.getElementById('empty_data_message').style.display = 'block';
                document.getElementById('error_message').style.display = 'none';
                document.getElementById('duplicate_message').style.display = 'none';
                return false;
            } else if (hasNumber) {
                document.getElementById('empty_data_message').style.display = 'none';
                document.getElementById('error_message').style.display = 'block';
                document.getElementById('duplicate_message').style.display = 'none';
                return false;
            } else {
                // Get the current kode_barang from the database
                var currentKodeBarang = '<?php echo $data['kode_barang']; ?>';

                // If the kode_barang is not changed, then skip the duplicate check
                if (kodeBarang === currentKodeBarang) {
                    document.getElementById('empty_data_message').style.display = 'none';
                    document.getElementById('error_message').style.display = 'none';
                    document.getElementById('duplicate_message').style.display = 'none';
                    return true;
                } else {
                    // Validate if kode_barang already exists
                    var kodeBarangExists = checkKodeBarangExists(kodeBarang);
                    if (kodeBarangExists) {
                        document.getElementById('empty_data_message').style.display = 'none';
                        document.getElementById('error_message').style.display = 'none';
                        document.getElementById('duplicate_message').style.display = 'block';
                        return false;
                    } else {
                        document.getElementById('empty_data_message').style.display = 'none';
                        document.getElementById('error_message').style.display = 'none';
                        document.getElementById('duplicate_message').style.display = 'none';
                        return true;
                    }
                }
            }
        }

        // Function to check if kode_barang exists in the database
        function checkKodeBarangExists(kodeBarang) {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'check-kode-barang.php', false); // Synchronous request
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.send('kode_barang=' + encodeURIComponent(kodeBarang));

            return xhr.responseText === 'Kode Barang sudah terdata';
        }
        var HOST_URL = "https://keenthemes.com/metronic/tools/preview";
    </script>
    <!--begin::Global Config(global config for global JS scripts)-->
    <script>
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