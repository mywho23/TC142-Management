<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Application TC 142</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="{{ asset('template/assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/assets/vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('template/assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('template/assets/vendors/font-awesome/css/font-awesome.min.css') }}">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="{{ asset('template/assets/vendors/jquery-bar-rating/css-stars.css') }}">
    <link rel="stylesheet" href="{{ asset('template/assets/vendors/font-awesome/css/font-awesome.min.css') }}">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="{{ asset('template/assets/css/style.css') }}">
    <!-- End layout styles -->
    <link rel="shortcut icon" href="{{ asset('template/assets/images/weem.png') }}" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css">
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/js/app.js'])
</head>

<body>
    <div class="container-scroller">
        @include('layouts.sidebar') {{-- Sidebar --}}

        <div class="container-fluid page-body-wrapper">
            @include('layouts.navbar') {{-- Navbar --}}

            <div class="main-panel">
                <div class="content-wrapper">
                    @include('partials.breadcrumb')
                    @yield('content')
                </div>
            </div>
        </div>
    </div>


    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="{{ asset('template/assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="{{ asset('template/assets/vendors/jquery-bar-rating/jquery.barrating.min.js') }}"></script>
    <script src="{{ asset('template/assets/vendors/chart.js/chart.umd.js') }}"></script>
    <script src="{{ asset('template/assets/vendors/flot/jquery.flot.js') }}"></script>
    <script src="{{ asset('template/assets/vendors/flot/jquery.flot.resize.js') }}"></script>
    <script src="{{ asset('template/assets/vendors/flot/jquery.flot.categories.js') }}"></script>
    <script src="{{ asset('template/assets/vendors/flot/jquery.flot.fillbetween.js') }}"></script>
    <script src="{{ asset('template/assets/vendors/flot/jquery.flot.stack.js') }}"></script>
    <script src="{{ asset('template/assets/js/jquery.cookie.js') }}" type="text/javascript"></script>
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="{{ asset('template/assets/js/off-canvas.js') }}"></script>
    <script src="{{ asset('template/assets/js/misc.js') }}"></script>
    <script src="{{ asset('template/assets/js/settings.js') }}"></script>
    <script src="{{ asset('template/assets/js/todolist.js') }}"></script>
    <script src="{{ asset('template/assets/js/hoverable-collapse.js') }}"></script>
    <!-- endinject -->
    <!-- Custom js for this page -->
    <script src="{{ asset('template/assets/js/dashboard.js') }}"></script>
    <!-- End custom js for this page -->
    @yield('script') {{-- script tambahan per halaman (optional) --}}
    <script>
        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById('preview');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#usersTable').DataTable({
                language: {
                    sEmptyTable: "Tidak ada data untuk ditampilkan",

                    "autoWidth": false,
                    responsive: true
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            var table = $('#tableCeklis').DataTable({
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50],
                ordering: false,
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data per halaman",
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                    paginate: {
                        previous: "Sebelumnya",
                        next: "Berikutnya"
                    },
                    zeroRecords: "Tidak ada data yang ditemukan"
                }
            });

            $('#formCeklis').on('submit', function(e) {
                Swal.fire({
                    title: 'Menyimpan Checklist...',
                    text: 'Mohon tunggu, seluruh halaman sedang diproses.',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => Swal.showLoading()
                });

                // Ambil semua input dari seluruh halaman (termasuk yg tersembunyi)
                table.$('input, select, textarea').each(function() {
                    const value = $(this).val();

                    // Skip kalau input kosong atau sudah ada di DOM utama
                    if ($.contains(document, this) || value === null || value === '') {
                        return; // lewati
                    }

                    // Clone input dari halaman lain ke form
                    const clone = $(this).clone();
                    clone.val(value);
                    $('#formCeklis').append(clone);
                });

                setTimeout(() => Swal.close(), 2000);
            });
        });
    </script>

    <script>
        let historyTable;

        $(document).ready(function() {
            historyTable = $('#historyTable').DataTable({
                responsive: true,
                lengthMenu: [5, 10, 15, 20, 50],
                pageLength: 10,

                order: [
                    [1, 'desc']
                ],
                columnDefs: [{
                    orderable: false,
                    searchable: false,
                    targets: [0, 4]
                }],
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data", // 🔥 label
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                    zeroRecords: "Data tidak ditemukan",
                    paginate: {
                        next: "Next",
                        previous: "Prev"
                    }
                }
            });
        });
    </script>
    <!---timer otomatis logbook ----->
</body>

</html>