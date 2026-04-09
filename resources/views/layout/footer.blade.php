<!-- footer.php -->
<footer class="bg-dark text-white pt-5 pb-3">
    <div class="container">
        <div class="row">

            <!-- Tentang -->
            <div class="col-md-4 mb-4">
                <h5 class="fw-bold mb-3">Tentang DIPANTAU</h5>
                <p style="font-size: 0.9rem;">
                    DIPANTAU (Data Informasi Prasarana, Sarana, dan Utilitas Umum) adalah sistem informasi untuk pendataan dan pemantauan Pasum Perumahan, Penerangan Jalan Umum (PJU), dan Kawasan Rantau Baru (KRB) secara terintegrasi.
                </p>
            </div>

            <!-- Kontak -->
            <div class="col-md-4 mb-4">
                <h5 class="fw-bold mb-3">Kontak</h5>
                <ul class="list-unstyled" style="font-size: 0.9rem;">
                    <li><i class="fa-solid fa-location-dot me-2"></i>Jl. Jend. Sudirman (By Pass) Rantau - Kab. Tapin</li>
                    <li><i class="fa-solid fa-envelope me-2"></i>disperkimtan@tapinkab.go.id</li>
                    <li><i class="fa-solid fa-phone me-2"></i>(0511) 31291</li>
                </ul>
            </div>

            <!-- Sosial Media -->
            <div class="col-md-4 mb-4">
                <h5 class="fw-bold mb-3">Ikuti Kami</h5>
                <div class="d-flex gap-3">
                    <a href="https://www.instagram.com/disperkimtan_tapin" target="_blank" class="text-white fs-5"><i class="fab fa-instagram"></i></a>
                    <a href="{{ url('/') }}" class="text-white fs-5"><i class="fab fa-facebook"></i></a>
                    <a href="{{ url('/') }}" class="text-white fs-5"><i class="fab fa-x-twitter"></i></a>
                    <a href="{{ url('/') }}" class="text-white fs-5"><i class="fab fa-youtube"></i></a>
                </div>
            </div>

        </div>

        <hr class="bg-light">

        <div class="row">
            <div class="col-md-6">
                <p class="mb-0" style="font-size: 0.85rem;">&copy; {{ date('Y') }} Kabupaten Tapin. Semua hak dilindungi.</p>
            </div>
            <div class="col-md-6 text-md-end">
                <p class="mb-0" style="font-size: 0.85rem;">Website by <a class="text-white text-decoration-underline">Milul</a></p>
            </div>
        </div>
    </div>
</footer>
