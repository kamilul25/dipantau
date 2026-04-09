<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'DIPANTAU')</title>

    <link rel="icon" href="{{ asset('images/dinas.png') }}">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="d-flex flex-column min-vh-100">

@php
    $waSetting = \App\Models\Setting::first();
@endphp

@include("layout.navbar")

<main class="flex-fill">
@yield('content')
</main>

@include("layout.footer")

<!-- FLOATING WHATSAPP BUTTON -->
<a href="https://api.whatsapp.com/send?phone={{ $waSetting->whatsapp_number }}"
   target="_blank"
   class="floating-wa">
    <i class="fa-brands fa-whatsapp"></i>
</a>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

@yield('scripts')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
@if(auth()->check())

    let idleTime = 0;
    const idleLimit = 15 * 60; // 15 menit dalam detik
    let idleInterval;

    function resetIdleTime() {
        idleTime = 0;
    }

    function startIdleTimer() {

        idleInterval = setInterval(function() {
            idleTime++;

            if (idleTime >= idleLimit) {

                clearInterval(idleInterval);

                Swal.fire({
                    icon: 'warning',
                    title: 'Sesi Berakhir',
                    text: 'Anda tidak aktif selama 15 menit.',
                    confirmButtonText: 'Login Kembali',
                    allowOutsideClick: false
                }).then(() => {

                    fetch("{{ route('auto.logout') }}", {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Content-Type": "application/json"
                        }
                    }).then(() => {
                        window.location.href = "{{ route('login') }}";
                    });

                });

            }

        }, 1000);
    }

    // Reset timer saat ada aktivitas
    document.addEventListener('mousemove', resetIdleTime);
    document.addEventListener('keypress', resetIdleTime);
    document.addEventListener('click', resetIdleTime);
    document.addEventListener('scroll', resetIdleTime);

    startIdleTimer();

@endif
</script>

<script>
document.getElementById('btnLogout').addEventListener('click', function () {

    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Anda akan keluar dari sistem!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Keluar!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {

            Swal.fire({
                title: 'Logout...',
                text: 'Sedang keluar dari sistem',
                icon: 'success',
                timer: 1000,
                showConfirmButton: false
            });

            document.getElementById('logout-form').submit();
        }
    });

});
</script>
</body>
</html>
