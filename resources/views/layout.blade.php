<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <title>KTHRM- Kridentia</title>
    <link href="{{ asset('css/search.css') }}" rel="stylesheet">
    <!-- Custom fonts for this template-->
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('css/search.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Custom styles for this template-->
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">
    <style>
        .sidebar {
            background: linear-gradient(to bottom, #00aeef, #002244 );
        }

        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            color: #ffffff;
            font-size: 18px;
            perspective: 1000px;
            /* Perspective to create 3D effect */
        }

        .loading-logo {
            width: 300px;
            height: 300px;
            background-image: url("{{ asset('favicon.ico') }}");
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            transform-style: preserve-3d;
            /* Preserve 3D transformations */
            animation: rotateSideways 5s linear infinite;
            /* Infinite 3D sideways rotation */
        }

        @keyframes rotateSideways {
            0% {
                transform: rotateY(0deg);
                /* Start at 0 degrees */
            }

            100% {
                transform: rotateY(360deg);
                /* Rotate 360 degrees on Y-axis */
            }
        }
    </style>
    @yield('custom_css')
</head>

<body id="page-top">

    <!-- Loading Overlay -->
    <div id="loading-overlay" class="loading-overlay">
        <div class="loading-logo"></div>
    </div>

    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        @include('partials.sidebar')

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                @include('partials.topbar')

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    @yield('content')
                </div>
                <!-- /.container-fluid -->

                <!-- Footer -->
                @include('partials.footer')

            </div>
            <!-- End of Main Content -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->


    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    @include('partials.logout-modal')

    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>

    <!-- Page level plugins -->
    <script src="{{ asset('vendor/chart.js/Chart.min.js') }}"></script>

    <!-- Page level custom scripts -->
    <script src="{{ asset('js/demo/chart-pie-demo.js') }}"></script>

    <!-- Include jQuery (already present in most Laravel apps) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Select2 CSS & JS -->

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    @yield('custom_js')

    <!-- Script to handle the loading overlay -->
    <script>
        window.addEventListener('load', () => {
            document.getElementById('loading-overlay').style.display = 'none';
            document.getElementById('content').style.display = 'block';
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Show the loading overlay when the form is submitted
            document.getElementById('employee-upload-form').addEventListener('submit', () => {
                document.getElementById('loading-overlay').style.display = 'flex';
            });

            document.getElementById('family-upload-form').addEventListener('submit', () => {
                document.getElementById('loading-overlay').style.display = 'flex';
            });

            // Hide the loading overlay once the page has loaded
            window.addEventListener('load', () => {
                document.getElementById('loading-overlay').style.display = 'none';
                document.getElementById('content').style.display = 'block';
            });
        });
    </script>
    @stack('scripts')
</body>

</html>

<!-- 