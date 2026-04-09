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
        <div class="col-md-4">
            <div class="card text-center shadow-sm border-0 h-100" style="cursor:pointer;" onclick="window.location.href='{{ route('publik.pasum') }}'">
                <div class="card-body">
                    <div class="icon-box bg-warning mb-3">
                        <i class="fa-solid fa-house"></i>
                    </div>
                    <h6 class="text-warning fw-bold">JUMLAH PERUMAHAN</h6>
                    <h2 class="fw-bold">{{ $total_perumahan }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center shadow-sm border-0 h-100" style="cursor:pointer;" onclick="window.location.href='{{ route('publik.pasum') }}'">
                <div class="card-body">
                    <div class="icon-box bg-success mb-3">
                        <i class="fa-solid fa-handshake"></i>
                    </div>
                    <h6 class="text-success fw-bold">SUDAH SERAH TERIMA PSU</h6>
                    <h2 class="fw-bold">{{ $sudah_psu }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center shadow-sm border-0 h-100" style="cursor:pointer;" onclick="window.location.href='{{ route('publik.pasum') }}'">
                <div class="card-body">
                    <div class="icon-box bg-danger mb-3">
                        <i class="fa-solid fa-handshake-slash"></i>
                    </div>
                    <h6 class="text-danger fw-bold">BELUM SERAH TERIMA PSU</h6>
                    <h2 class="fw-bold">{{ $belum_psu }}</h2>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ================= DASHBOARD PJU & PJUTS ================= --}}
<div class="container my-4">
    <h5 class="fw-semibold border-bottom pb-2 mb-3">PJU DAN PJUTS</h5>
    <div class="row g-3">
        <div class="col-md-4">
            <div class="card text-center shadow-sm border-0 h-100" style="cursor:pointer;" onclick="window.location.href='{{ route('publik.pju') }}'">
                <div class="card-body">
                    <div class="icon-box bg-warning mb-3">
                        <i class="fa-solid fa-lightbulb"></i>
                    </div>
                    <h6 class="text-warning fw-bold">JUMLAH PJU</h6>
                    <h2 class="fw-bold">{{ $jumlah_pju }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center shadow-sm border-0 h-100" style="cursor:pointer;" onclick="window.location.href='{{ route('publik.pju') }}'">
                <div class="card-body">
                    <div class="icon-box bg-success mb-3">
                        <i class="fa-solid fa-solar-panel"></i>
                    </div>
                    <h6 class="text-success fw-bold">JUMLAH PJUTS</h6>
                    <h2 class="fw-bold">{{ $jumlah_pjuts }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center shadow-sm border-0 h-100" style="cursor:pointer;" onclick="window.location.href='{{ route('publik.pju') }}'">
                <div class="card-body">
                    <div class="icon-box bg-primary mb-3">
                        <i class="fa-solid fa-chart-pie"></i>
                    </div>
                    <h6 class="text-primary fw-bold">TOTAL TITIK</h6>
                    <h2 class="fw-bold">{{ $total_titik }}</h2>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ================= DESKRIPSI INSTANSI ================= --}}
<div class="container my-5">
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <h5 class="fw-bold mb-3 text-primary">
                <i class="fa-solid fa-building me-2"></i>
                Tentang Bidang PSU
            </h5>
            <p class="text-muted mb-0" style="text-align: justify;">
                Bidang Prasarana, Sarana, dan Utilitas (PSU) pada Dinas Perumahan, 
                Kawasan Permukiman dan Pertanahan Kabupaten Tapin memiliki peran 
                penting dalam pengelolaan dan pengawasan prasarana, sarana, serta 
                utilitas umum di Kabupaten Tapin. 
                <br><br>
                Bidang ini bertanggung jawab dalam memastikan ketersediaan dan 
                kualitas infrastruktur pendukung seperti jalan lingkungan, 
                penerangan jalan umum (PJU), serta fasilitas umum lainnya agar sesuai 
                dengan standar yang telah ditetapkan.
                <br><br>
                Selain itu, Bidang PSU juga menangani proses serah terima prasarana, 
                sarana, dan utilitas dari pengembang kepada pemerintah daerah guna 
                menjamin keberlanjutan pemeliharaan dan pelayanan kepada masyarakat.
            </p>
        </div>
    </div>
</div>
@endsection
