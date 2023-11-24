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

// Fetch user's name from the database based on user_id
$query = "SELECT name FROM user_login WHERE id_user = $user_id";
$result = $db->query($query);

if ($result && $result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $user_name = $user['name'];
} else {
    $user_name = "Unknown"; // Default value if name is not found
}


$query_data = "SELECT id, nama_kategori FROM kategori_barang";
$result_data = $db->query($query_data);

// Insert data if form is submitted
// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     $NamaKategori = $_POST['nama_kategori'];

//     $insert_query = "INSERT INTO kategori_barang (nama_kategori) VALUES ('$NamaKategori')";

//     if ($conn->query($insert_query) === TRUE) {
//         echo "Data inserted successfully.";
//     } else {
//         echo "Error: " . $insert_query . "<br>" . $conn->error;
//     }
// }

// Include database connection and session start here

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Delete query
    $delete_query = "DELETE FROM kategori_barang WHERE id='$id'";

    if ($db->query($delete_query) === TRUE) {
        header("Location: data-kategoribarang.php");
        exit();
    } else {
        echo "Error deleting record: " . $db->error;
    }
}

// if (isset($_SESSION['success_message'])) {
//     echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
//         ' . $_SESSION['success_message'] . '
//         <button type="button" class="close" data-dismiss="alert" aria-label="Close">
//             <span aria-hidden="true">&times;</span>
//         </button>
//     </div>';
//     // Hapus variabel sesi
//     unset($_SESSION['success_message']);
// }

// $_SESSION['success_message'] = 'Data telah diperbarui dengan sukses.';
// header("Location: data-kategoribarang.php");
// exit();
?>

<!DOCTYPE html>

<html lang="en">
<!--begin::Head-->

<head>
    <base href="">
    <meta charset="utf-8" />
    <title>Stock Barang | Kategori Barang</title>
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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
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
                        <img alt="Logo" src="..." class="max-h-30px" />
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
                                <img alt="Logo" src=".." class="logo-default" style="max-height: 80px;" />
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
                                        <div class="text-dark m-0 flex-grow-1 mr-3 font-size-h5">
                                            <?php echo $user_name; ?></div>
                                        <!--end::Text-->
                                    </div>
                                    <!--end::Header-->
                                    <!--begin::Nav-->
                                    <div class="navi navi-spacer-x-0 pt-5">
                                        <!--begin::Footer-->
                                        <div class="navi-separator mt-3"></div>
                                        <div class="navi-footer px-8 py-5">
                                            <a href="login.php" class="btn btn-light-primary font-weight-bold">Sign
                                                Out</a>
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
                                    <a href="data-kategoribarang.php" class="menu-link">
                                        <span class="menu-text">Data Kategori Barang</span>
                                    </a>
                                </li>
                                <li class="menu-item" data-menu-toggle="click" aria-haspopup="true">
                                    <a href="data-namabarang.php" class="menu-link">
                                        <span class="menu-text">Data Nama Barang</span>
                                    </a>
                                </li>
                                <li class="menu-item" data-menu-toggle="click" aria-haspopup="true">
                                    <a href="data-supplier.php" class="menu-link">
                                        <span class="menu-text">Data Supplier</span>
                                    </a>
                                </li>
                                <li class="menu-item" data-menu-toggle="click" aria-haspopup="true">
                                    <a href="data-stockmasuk.php" class="menu-link">
                                        <span class="menu-text">Stock Masuk</span>
                                    </a>
                                </li>
                                <li class="menu-item" data-menu-toggle="click" aria-haspopup="true">
                                    <a href="data-stockkeluar.php" class="menu-link">
                                        <span class="menu-text">Stock Keluar</span>
                                    </a>
                                </li>
                            </ul>
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
                                    <h3 class="card-label">Data Kategori Barang
                                </div>
                                <div class="card-toolbar">
                                    <!--begin::Dropdown-->
                                    <div class="dropdown dropdown-inline mr-2">
                                        <button type="button" class="btn btn-light-primary font-weight-bolder dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <span class="svg-icon svg-icon-md">
                                                <!--begin::Svg Icon | path:assets/media/svg/icons/Design/PenAndRuller.svg-->
                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                        <rect x="0" y="0" width="24" height="24" />
                                                        <path d="M3,16 L5,16 C5.55228475,16 6,15.5522847 6,15 C6,14.4477153 5.55228475,14 5,14 L3,14 L3,12 L5,12 C5.55228475,12 6,11.5522847 6,11 C6,10.4477153 5.55228475,10 5,10 L3,10 L3,8 L5,8 C5.55228475,8 6,7.55228475 6,7 C6,6.44771525 5.55228475,6 5,6 L3,6 L3,4 C3,3.44771525 3.44771525,3 4,3 L10,3 C10.5522847,3 11,3.44771525 11,4 L11,19 C11,19.5522847 10.5522847,20 10,20 L4,20 C3.44771525,20 3,19.5522847 3,19 L3,16 Z" fill="#000000" opacity="0.3" />
                                                        <path d="M16,3 L19,3 C20.1045695,3 21,3.8954305 21,5 L21,15.2485298 C21,15.7329761 20.8241635,16.200956 20.5051534,16.565539 L17.8762883,19.5699562 C17.6944473,19.7777745 17.378566,19.7988332 17.1707477,19.6169922 C17.1540423,19.602375 17.1383289,19.5866616 17.1237117,19.5699562 L14.4948466,16.565539 C14.1758365,16.200956 14,15.7329761 14,15.2485298 L14,5 C14,3.8954305 14.8954305,3 16,3 Z" fill="#000000" />
                                                    </g>
                                                </svg>
                                                <!--end::Svg Icon-->
                                            </span>Export</button>
                                        <!--begin::Dropdown Menu-->
                                        
                                        <!--end::Dropdown Menu-->
                                    </div>
                                    <!--end::Dropdown-->
                                    <!--begin::Button-->
                                    <a href="add-kategoribarang.php" class="btn btn-primary font-weight-bolder">
                                        <span class="svg-icon svg-icon-md">
                                            <!--begin::Svg Icon | path:assets/media/svg/icons/Design/Flatten.svg-->
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24" />
                                                    <circle fill="#000000" cx="9" cy="15" r="6" />
                                                    <path d="M8.8012943,7.00241953 C9.83837775,5.20768121 11.7781543,4 14,4 C17.3137085,4 20,6.6862915 20,10 C20,12.2218457 18.7923188,14.1616223 16.9975805,15.1987057 C16.9991904,15.1326658 17,15.0664274 17,15 C17,10.581722 13.418278,7 9,7 C8.93357256,7 8.86733422,7.00080962 8.8012943,7.00241953 Z" fill="#000000" opacity="0.3" />
                                                </g>
                                            </svg>
                                            <!--end::Svg Icon-->
                                        </span>Add Data</a>
                                    <!--end::Button-->
                                </div>
                            </div>
                            <div class="card-body">
                                <!--begin: Datatable-->
                                <table class="table table-bordered table-checkable table-responsive-sm">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>NAMA KATEGORI</th>
                                            <th width="15%">ACTIONS</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        while ($data = $result_data->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td>" . $data['id'] . "</td>";
                                            echo "<td>" . $data['nama_kategori'] . "</td>";
                                            echo "<td>";
                                            echo '<a href="edit-kategoribarang.php?id=' . $data['id'] . '" class="btn btn-sm btn-clean btn-icon" title="Edit details">';
                                            echo '<i class="la la-edit"></i>';
                                            echo '</a>';
                                            echo '<a href="data-kategoribarang.php?id=' . $data['id'] . '" class="btn btn-sm btn-clean btn-icon" title="Delete">';
                                            echo '<i class="la la-trash"></i>';
                                            echo '</a>';
                                            echo "</td>";
                                            echo "</tr>";
                                        }
                                        ?>
                                        <!-- <td>
                                                <a href="edit-pakaian.php" class="btn btn-sm btn-clean btn-icon"
                                                    title="Edit details">
                                                    <i class="la la-edit"></i>
                                                </a>
                                                <a href="javascript:;" class="btn btn-sm btn-clean btn-icon"
                                                    title="Delete">
                                                    <i class="la la-trash"></i>
                                                </a>
                                            </td> -->
                                    </tbody>
                                </table>
                                <!--end: Datatable-->
                            </div>
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

    <script>
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