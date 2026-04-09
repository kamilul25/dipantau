@extends('layout.app')

@section('title', 'KRB | DIPANTAU')

@section('content')

<!-- ================= HERO ================= -->
<section class="hero-modern">
    <div class="container">
        <h1>Kawasan Rantau Baru</h1>
        <p>
            Pusat wisata keluarga, ruang publik, dan ruang terbuka hijau utama
            Kabupaten Tapin, Kalimantan Selatan.
        </p>
        <a href="#peta" class="btn btn-success btn-cta mt-3">
            <i class="fa-solid fa-map-location-dot me-2"></i>Lihat Lokasi
        </a>
    </div>
</section>

<!-- ================= FASILITAS ================= -->
<section id="fasilitas" class="section bg-light">
    <div class="container">
        <div class="section-title section-divider">
            <h2>Fasilitas Unggulan</h2>
            <p>Mendukung aktivitas sehat, rekreasi, dan kegiatan masyarakat</p>
        </div>

        <div class="row g-4">
            <div class="col-md-3">
                <div class="card card-modern text-center h-100">
                    <div class="card-body">
                        <i class="fa-solid fa-person-running fa-2x text-success mb-3"></i>
                        <h5>Jogging Track</h5>
                        <p class="text-muted small">Lintasan nyaman untuk olahraga harian</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card card-modern text-center h-100">
                    <div class="card-body">
                        <i class="fa-solid fa-dumbbell fa-2x text-primary mb-3"></i>
                        <h5>Fitnes Outdoor</h5>
                        <p class="text-muted small">Peralatan olahraga luar ruang</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card card-modern text-center h-100">
                    <div class="card-body">
                        <i class="fa-solid fa-fish-fins fa-2x text-info mb-3"></i>
                        <h5>Kolam Ikan Koi</h5>
                        <p class="text-muted small">Rekreasi edukatif dan estetika</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card card-modern text-center h-100">
                    <div class="card-body">
                        <i class="fa-solid fa-children fa-2x text-warning mb-3"></i>
                        <h5>Permainan Edukasi</h5>
                        <p class="text-muted small">Ramah anak dan keluarga</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ================= INFORMASI ================= -->
<section class="section">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-md-6">
                <h3 class="fw-bold mb-3 text-success">Ruang Publik Terpadu</h3>
                <p class="text-muted">
                    RTH Rantau Baru dilengkapi area parkir luas, toilet umum termasuk
                    aksesibilitas disabilitas dan lansia, tempat ibadah, plaza penerima,
                    panggung terbuka, serta jalur evakuasi.
                </p>
                <p class="text-muted">
                    Berlokasi di depan <strong>Galeri Tamasa</strong> dan menjadi
                    pusat wisata buatan serta kegiatan pemerintahan Kabupaten Tapin.
                </p>
            </div>
            <div class="col-md-6 text-center">
                <i class="fa-solid fa-people-group fa-6x text-secondary opacity-75"></i>
            </div>
        </div>
    </div>
</section>

<!-- ================= PETA LOKASI ================= -->
<section id="peta" class="section bg-light">
    <div class="container">
        <div class="section-title section-divider">
            <h2>Lokasi RTH Rantau Baru</h2>
            <p>Lokasi ruang terbuka hijau Kabupaten Tapin</p>
        </div>

        <div class="map-wrapper">
            <iframe
                src="https://www.google.com/maps?q=-2.929633,115.164512&z=17&t=k&output=embed"
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    </div>
</section>

@endsection