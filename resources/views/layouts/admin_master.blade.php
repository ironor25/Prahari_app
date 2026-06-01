<!DOCTYPE html>
<html lang="en" dir="ltr" data-nav-layout="vertical" data-theme-mode="light" data-header-styles="light"
    data-menu-styles="light" data-toggled="close">

<head>

    <!-- Meta Data -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title> Prahari Admin Panel </title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="Description" content="Bootstrap Responsive Admin Web Dashboard HTML5 Template">
    <meta name="Author" content="Spruko Technologies Private Limited">
    <meta name="keywords"
        content="bootstrap template, dashboard, bootstrap admin dashboard, admin template, bootstrap 5 templates, admin, admin dashboard template, bootstrap dashboard, bootstrap admin template, template bootstrap 5, dashboard template bootstrap, admin dashboard ui, template admin, dashboard admin, dashboard template, bootstrap admin panel">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('assets/images/brand-logos/favicon.ico') }}" type="image/x-icon">

    <!-- Choices JS -->
    <script src="{{ asset('assets/libs/choices.js/public/assets/scripts/choices.min.js') }}"></script>

    <!-- Main Theme Js -->
    <script src="{{ asset('assets/js/main.js') }}"></script>

    <!-- Bootstrap Css -->
    <link id="style" href="{{ asset('assets/libs/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Style Css -->
    <link href="{{ asset('assets/css/styles.css') }}" rel="stylesheet">

    <!-- Icons Css -->
    <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet">

    
    <!-- Node Waves Css -->
    <link href="{{ asset('assets/libs/node-waves/waves.min.css') }}" rel="stylesheet">

    <!-- Simplebar Css -->
    <link href="{{ asset('assets/libs/simplebar/simplebar.min.css') }}" rel="stylesheet">

    <!-- Color Picker Css -->
    <link rel="stylesheet" href="{{ asset('assets/libs/flatpickr/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/@simonwep/pickr/themes/nano.min.css') }}">

    <!-- Choices Css -->
    <link rel="stylesheet" href="{{ asset('assets/libs/choices.js/public/assets/styles/choices.min.css') }}">

    <!-- FlatPickr CSS -->
    <link rel="stylesheet" href="{{ asset('assets/libs/flatpickr/flatpickr.min.css') }}">

    <!-- Auto Complete CSS -->
    <link rel="stylesheet" href="{{ asset('assets/libs/@tarekraafat/autocomplete.js/css/autoComplete.css') }}">

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
    <!-- DataTables Buttons CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
    <style>
        /* Custom CSV Export Button */
        .dt-buttons {
            margin-bottom: 10px;
        }
        .dt-button.csv-export-btn {
            background-color: #2c3e50 !important;
            color: #ffffff !important;
            border: none !important;
            border-radius: 5px !important;
            padding: 6px 15px !important;
            font-size: 13px !important;
            font-weight: 500 !important;
            transition: all 0.3s ease;
        }
        .dt-button.csv-export-btn:hover {
            background-color: #1a252f !important;
            opacity: 0.9;
        }
        /* Tertiary Background Customization */
        .bg-body-tertiary {
            background-color: #2c3e50 !important;
            color: #ffffff !important;
        }
        .bg-body-tertiary * {
            color: #ffffff !important;
        }
        .bg-body-tertiary .header-link-icon, 
        .bg-body-tertiary i,
        .bg-body-tertiary svg {
            color: #ffffff !important;
            fill: #ffffff !important;
        }

        /* Table Beautification */
        .table {
            border-collapse: collapse !important;
        }
        .table-bordered {
            border: 1px solid #222 !important;
        }
        .table-bordered th, .table-bordered td {
            border: 1px solid #eee !important;
        }

        /* Custom DataTable Search & Filter Bar */
        .custom-table-controls {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 24px;
        }
        .custom-search-container {
            position: relative;
            flex-grow: 1;
        }
        .custom-search-container i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #888;
            font-size: 16px;
        }
        .custom-search-input {
            width: 100%;
            padding: 12px 15px 12px 45px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            background-color: #ffffff;
            font-size: 14px;
            transition: all 0.3s ease;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }
        .custom-search-input:focus {
            outline: none;
            border-color: #2c3e50;
            box-shadow: 0 0 0 3px rgba(44, 62, 80, 0.1);
        }
        .filter-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            background-color: #ffffff;
            color: #333;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }
        .filter-btn:hover {
            background-color: #f9f9f9;
            border-color: #d0d0d0;
        }
        .filter-btn i {
            font-size: 16px;
        }

        /* Hide Default DataTable Search */
        .dataTables_filter {
            display: none !important;
        }

        .table-bordered th, .table-bordered td {
            font-weight: 500 !important;
            vertical-align: middle;
            color: #000 !important; /* Force black text */
        }
        .table thead th {
            font-weight: 700 !important;
            background-color: #f8f9fa !important;
            color: #333 !important;
            border-bottom: 2px solid #222 !important;
        }

        /* Full Width Header Overrides */
        .app-header {
            width: 100% !important;
            left: 0 !important;
            margin-left: 0 !important;
            padding-left: 0 !important;
            inset-inline-start: 0 !important;
            margin-inline-start: 0 !important;
            z-index: 1030 !important;
        }
        .app-sidebar{
            top: 60px !important; 
            height: calc(100vh - 60px) !important;
            z-index: 1020 !important;
        }
        .main-content.app-content {
            margin-top: 60px !important; 
        }
        .main-sidebar {
            padding-top: 0 !important;
        }

        /* Modern Notification System Styles */
        #custom-toast-container {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .custom-toast {
            background: #2c3e50;
            color: white;
            padding: 14px 24px;
            border-radius: 10px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.15);
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 280px;
            font-weight: 500;
            border-left: 5px solid transparent;
            animation: slideInRight 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        .custom-toast.success { border-left-color: #27ae60; background: #ffffff; color: #2c3e50; }
        .custom-toast.success i { color: #27ae60; font-size: 1.2rem; }
        .custom-toast.error { border-left-color: #e74c3c; background: #ffffff; color: #2c3e50; }
        .custom-toast.error i { color: #e74c3c; font-size: 1.2rem; }

        .confirm-card {
            border-radius: 16px;
            border: none;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(0,0,0,0.2);
        }
        .confirm-card .modal-header {
            background: #f8f9fa;
            border-bottom: 1px solid #eee;
            padding: 20px 24px;
        }
        .confirm-card .modal-title {
            font-weight: 700;
            color: #2c3e50;
        }
        .confirm-card .modal-body {
            padding: 30px 24px;
            font-size: 16px;
            color: #555;
        }
        .confirm-card .modal-footer {
            border-top: 1px solid #eee;
            padding: 16px 24px;
            background: #f8f9fa;
        }
        .btn-confirm {
            background: #e74c3c;
            color: white;
            border: none;
            padding: 10px 25px;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.2s;
        }
        .btn-confirm:hover {
            background: #c0392b;
            transform: translateY(-1px);
        }
        .btn-cancel {
            background: #ecf0f1;
            color: #7f8c8d;
            border: none;
            padding: 10px 25px;
            font-weight: 600;
            border-radius: 8px;
        }
    </style>
    @stack('page-style')
</head>

<body>


    <!-- Loader -->
    <div id="loader">
        <img src="{{ asset('assets/images/media/loader.svg') }}" alt="">
    </div>
    <!-- Loader -->

    <div class="page">
        @include('layouts.header')
        @include('layouts.admin_sidebar')

        <!-- Start::app-content -->
        <div class="main-content app-content">
            <div class="container-fluid">
                @yield('page-content')
            </div>
        </div>
        <!-- End::app-content -->

        

        </div>

        <!-- Custom Confirmation Modal -->
        <div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
                <div class="modal-content confirm-card">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmModalTitle">Confirm Action</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <i class="bi bi-exclamation-circle text-warning mb-3" style="font-size: 3rem;"></i>
                        <p id="confirmModalMessage" class="mb-0">Are you sure you want to proceed?</p>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-cancel px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-confirm px-4" id="confirmModalBtn">Confirm</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Toast Container -->
        <div id="custom-toast-container"></div>

    </div>


    <!-- Scroll To Top -->
    <div class="scrollToTop">
        <span class="arrow lh-1"><i class="ri-rocket-line align-middle fs-18"></i></span>
    </div>
    <div id="responsive-overlay"></div>
    <!-- Scroll To Top -->

    <!-- Popper JS -->
    <script src="{{ asset('assets/libs/@popperjs/core/umd/popper.min.js') }}"></script>

    <!-- Bootstrap JS -->
    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Defaultmenu JS -->
    <script src="{{ asset('assets/js/defaultmenu.min.js') }}"></script>

    <!-- Node Waves JS-->
    <script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>

    <!-- Sticky JS -->
    <script src="{{ asset('assets/js/sticky.js') }}"></script>

    <!-- Simplebar JS -->
    <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/js/simplebar.js') }}"></script>

    <!-- Auto Complete JS -->
    <script src="{{ asset('assets/libs/@tarekraafat/autocomplete.js/autoComplete.min.js') }}"></script>

    <!-- Color Picker JS -->
    <script src="{{ asset('assets/libs/@simonwep/pickr/pickr.es5.min.js') }}"></script>

    <!-- Date & Time Picker JS -->
    <script src="{{ asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>


    <!-- Apex Charts JS -->
    <script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>

    <!-- Sales Dashboard -->
    <script src="{{ asset('assets/js/sales-dashboard.js') }}"></script>

    <!-- Custom JS -->
    <script src="{{ asset('assets/js/custom.js') }}"></script>


    <!-- Custom-Switcher JS -->
    <script src="{{ asset('assets/js/custom-switcher.min.js') }}"></script>

    <script src="https://code.jquery.com/jquery-4.0.0.js" integrity="sha256-9fsHeVnKBvqh3FB2HYu7g2xseAZ5MlN6Kz/qnkASV8U="
        crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <!-- DataTables Buttons JS -->
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.22.1/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @stack('page-script')

    <script>
        // Global Notification Functions
        window.showToast = function(message, type = 'success') {
            const toastId = 'toast-' + Date.now();
            const icon = type === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill';
            const toastHtml = `
                <div id="${toastId}" class="custom-toast ${type}">
                    <i class="bi ${icon}"></i>
                    <span>${message}</span>
                </div>
            `;
            $('#custom-toast-container').append(toastHtml);
            
            // Auto remove
            setTimeout(() => {
                $(`#${toastId}`).animate({
                    marginRight: '-100%',
                    opacity: 0
                }, 400, function() {
                    $(this).remove();
                });
            }, 2000);
        };

        window.showConfirm = function(title, message, callback) {
            $('#confirmModalTitle').text(title);
            $('#confirmModalMessage').text(message);
            $('#confirmModalBtn').off('click').on('click', function() {
                callback();
                bootstrap.Modal.getInstance(document.getElementById('confirmModal')).hide();
            });
            new bootstrap.Modal(document.getElementById('confirmModal')).show();
        };
    </script>
</body>

</html>

