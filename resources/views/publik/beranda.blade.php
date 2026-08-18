@extends('layout.app')

@section('title', 'Beranda | DIPANTAU')

@section('content')

{{-- ================= HEADER CAROUSEL ================= --}}
<div id="headerCarousel" class="carousel slide carousel-fade mb-4" data-bs-ride="carousel" data-bs-interval="5000">

    {{-- Indicators --}}
    <div class="carousel-indicators">
        @foreach($slides as $key => $slide)
            <button type="button" 
                    data-bs-target="#headerCarousel" 
                    data-bs-slide-to="{{ $key }}" 
                    class="{{ $key == 0 ? 'active' : '' }}">
            </button>
        @endforeach
    </div>

    {{-- Slides --}}
<div class="carousel-inner">
    @foreach($slides as $key => $slide)
        <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
            <img src="{{ asset('storage/' . $slide->image) }}" 
                 class="d-block w-100 header-img" 
                 alt="Slide">
            <div class="carousel-caption d-none d-md-block">
                <h5>{{ $slide->title }}</h5>
            </div>
        </div>
    @endforeach
</div>

</div>

{{-- ================= DASHBOARD PERUMAHAN ================= --}}
<div class="container my-4">
    <h5 class="fw-semibold border-bottom pb-2 mb-3">PASUM PERUMAHAN</h5>

    <div class="row g-3">

        {{-- JUMLAH PERUMAHAN --}}
        <div class="col-md-4">
            <div class="card gooey-card gooey-warning text-center h-100"
                 onclick="window.location.href='{{ route('publik.pasum') }}'">

                <div class="gooey-bg"></div>

                <div class="card-body">
                    <div class="icon-box mb-3">
                        <i class="fa-solid fa-house"></i>
                    </div>

                    <h6 class="fw-bold card-title-gooey">
                        JUMLAH PERUMAHAN
                    </h6>

                    <h2 class="fw-bold card-number-gooey">
                        {{ $total_perumahan }}
                    </h2>
                </div>
            </div>
        </div>


        {{-- SUDAH SERAH TERIMA PSU --}}
        <div class="col-md-4">
            <div class="card gooey-card gooey-success text-center h-100"
                 onclick="window.location.href='{{ route('publik.pasum') }}'">

                <div class="gooey-bg"></div>

                <div class="card-body">
                    <div class="icon-box mb-3">
                        <i class="fa-solid fa-handshake"></i>
                    </div>

                    <h6 class="fw-bold card-title-gooey">
                        SUDAH SERAH TERIMA PSU
                    </h6>

                    <h2 class="fw-bold card-number-gooey">
                        {{ $sudah_psu }}
                    </h2>
                </div>
            </div>
        </div>


        {{-- BELUM SERAH TERIMA PSU --}}
        <div class="col-md-4">
            <div class="card gooey-card gooey-danger text-center h-100"
                 onclick="window.location.href='{{ route('publik.pasum') }}'">

                <div class="gooey-bg"></div>

                <div class="card-body">
                    <div class="icon-box mb-3">
                        <i class="fa-solid fa-handshake-slash"></i>
                    </div>

                    <h6 class="fw-bold card-title-gooey">
                        BELUM SERAH TERIMA PSU
                    </h6>

                    <h2 class="fw-bold card-number-gooey">
                        {{ $belum_psu }}
                    </h2>
                </div>
            </div>
        </div>

    </div>
</div>


{{-- ================= DASHBOARD PJU & PJUTS ================= --}}
<div class="container my-4">
    <h5 class="fw-semibold border-bottom pb-2 mb-3">PJU DAN PJUTS</h5>

    <div class="row g-3">

        {{-- JUMLAH PJU --}}
        <div class="col-md-4">
            <div class="card gooey-card gooey-warning text-center h-100"
                 onclick="window.location.href='{{ route('publik.pju') }}'">

                <div class="gooey-bg"></div>

                <div class="card-body">
                    <div class="icon-box mb-3">
                        <i class="fa-solid fa-lightbulb"></i>
                    </div>

                    <h6 class="fw-bold card-title-gooey">
                        JUMLAH PJU
                    </h6>

                    <h2 class="fw-bold card-number-gooey">
                        {{ $jumlah_pju }}
                    </h2>
                </div>
            </div>
        </div>


        {{-- JUMLAH PJUTS --}}
        <div class="col-md-4">
            <div class="card gooey-card gooey-success text-center h-100"
                 onclick="window.location.href='{{ route('publik.pju') }}'">

                <div class="gooey-bg"></div>

                <div class="card-body">
                    <div class="icon-box mb-3">
                        <i class="fa-solid fa-solar-panel"></i>
                    </div>

                    <h6 class="fw-bold card-title-gooey">
                        JUMLAH PJUTS
                    </h6>

                    <h2 class="fw-bold card-number-gooey">
                        {{ $jumlah_pjuts }}
                    </h2>
                </div>
            </div>
        </div>


        {{-- TOTAL TITIK --}}
        <div class="col-md-4">
            <div class="card gooey-card gooey-primary text-center h-100"
                 onclick="window.location.href='{{ route('publik.pju') }}'">

                <div class="gooey-bg"></div>

                <div class="card-body">
                    <div class="icon-box mb-3">
                        <i class="fa-solid fa-chart-pie"></i>
                    </div>

                    <h6 class="fw-bold card-title-gooey">
                        TOTAL TITIK
                    </h6>

                    <h2 class="fw-bold card-number-gooey">
                        {{ $total_titik }}
                    </h2>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- ================= INFORMASI ================= -->
<section class="section">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-md-6 text-center">
                <img src="{{ asset('images/krb.png') }}"
                    class="img-fluid krb-img"
                    alt="Tentang Bidang PSU">
            </div>
            <div class="col-md-6">
                <h3 class="fw-bold mb-3 text-success">Tentang Bidang PSU</h3>
                <p class="text-muted">
                Bidang Prasarana, Sarana, dan Utilitas (PSU) pada Dinas Perumahan, 
                Kawasan Permukiman dan Pertanahan Kabupaten Tapin memiliki peran 
                penting dalam pengelolaan dan pengawasan prasarana, sarana, serta 
                utilitas umum di Kabupaten Tapin. 
                </p>
                <p class="text-muted">
                Bidang ini bertanggung jawab dalam memastikan ketersediaan dan 
                kualitas infrastruktur pendukung seperti jalan lingkungan, 
                penerangan jalan umum (PJU), serta fasilitas umum lainnya agar sesuai 
                dengan standar yang telah ditetapkan.
                </p>
                <p class="text-muted">
                Selain itu, Bidang PSU juga menangani proses serah terima prasarana, 
                sarana, dan utilitas dari pengembang kepada pemerintah daerah guna 
                menjamin keberlanjutan pemeliharaan dan pelayanan kepada masyarakat.
                </p>                
            </div>
        </div>
    </div>
</section>

<!-- ================= PETA LOKASI ================= -->
<section id="peta" class="section bg-light">
    <div class="container">
        <div class="section-title section-divider">
            <h2>Lokasi Disperkimtan Kab. Tapin</h2>
            <p>Lokasi Dinas Perumahan, Kawasan Permukiman dan Pertanahan Kabupaten Tapin</p>
        </div>

        <div class="map-wrapper">
            <iframe
                src="https://www.google.com/maps?q=-2.92796,115.16989&z=17&t=k&output=embed"
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    </div>
</section>
@endsection
