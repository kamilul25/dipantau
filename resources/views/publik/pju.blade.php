@extends('layout.app')

@section('title', 'Beranda PJU | DIPANTAU')

@section('content')

@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

@if(session('success'))
<script>
Swal.fire({
    icon: 'success',
    title: 'Berhasil',
    text: "{{ session('success') }}",
    timer: 2000,
    showConfirmButton: false
});
</script>
@endif

<!-- ================= DASHBOARD SUMMARY ================= -->
<div class="container my-4">
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card card-modern text-center h-100">
                <div class="card-body">
                    <div class="icon-box bg-warning mb-2">
                        <i class="fa-solid fa-lightbulb"></i>
                    </div>
                    <h6 class="text-warning fw-bold">JUMLAH PJU</h6>
                    <h2 class="fw-bold">{{ $jumlah_pju }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-modern text-center h-100">
                <div class="card-body">
                    <div class="icon-box bg-success mb-2">
                        <i class="fa-solid fa-solar-panel"></i>
                    </div>
                    <h6 class="text-success fw-bold">JUMLAH PJUTS</h6>
                    <h2 class="fw-bold">{{ $jumlah_pjuts }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-modern text-center h-100">
                <div class="card-body">
                    <div class="icon-box bg-primary mb-2">
                        <i class="fa-solid fa-chart-pie"></i>
                    </div>
                    <h6 class="text-primary fw-bold">TOTAL TITIK</h6>
                    <h2 class="fw-bold">{{ $total_titik }}</h2>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- FILTER & SEARCH -->
<div class="container mb-4">
    <div class="card">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Kecamatan</label>
                    <select class="form-select" id="filter_kecamatan">
                        <option value="">== Pilih Kecamatan ==</option>
                        @foreach($kecamatans as $kec)
                        <option value="{{ $kec->id }}">{{ $kec->nama_kecamatan }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Desa/Kelurahan</label>
                    <select class="form-select" id="filter_desa">
                        <option value="">== Pilih Desa ==</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Pencarian</label>
                    <div class="input-group">
                        <input type="text" id="filter_search" class="form-control" placeholder="Cari">
                        <button class="btn btn-warning fw-semibold" id="btnFilter">Cari</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- TABEL DATA -->
<div class="container">
    <div class="card">
        <div class="card-body">
            <table id="tabelPJU" class="table table-striped table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Kecamatan</th>
                        <th>Desa/Kelurahan</th>
                        <th>RT / RW</th>
                        <th>PJU</th>
                        <th>PJUTS</th>
                        <th>Jumlah Titik</th>
                        <th>Tahun</th>
                        <th>Lokasi</th>
                        
                    </tr>
                </thead>
                <tbody>
                    @php $no=1; @endphp
                    @foreach($pjus as $row)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ \App\Models\Kecamatan::find($row->kecamatan)->nama_kecamatan ?? '-' }}</td>
                        <td>{{ \App\Models\Desa::find($row->desa)->nama_desa ?? '-' }}</td>
                        <td>
                            <span class="badge bg-secondary">
                                RT {{ $row->rt }} / RW {{ $row->rw }}
                            </span>
                        </td>
                        <td class="text-center">{{ $row->pju }}</td>
                        <td class="text-center">{{ $row->pjuts }}</td>
                        <td class="text-center fw-semibold">{{ $row->pju + $row->pjuts }}</td>
                        <td class="text-center">{{ $row->tahun }}</td>
                        <td class="text-center">
                            <button class="btn btn-info btn-sm btn-map" 
                                    data-id="{{ $row->id }}" 
                                    data-nama="{{ $row->desa->nama_desa ?? '-' }}">
                                <i class="fa-solid fa-map-location-dot"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL GPX -->
<div class="modal fade" id="mapModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mapModalLabel">Lokasi PJU</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="height: 450px; padding:0;">
                <div id="mapModalMap" style="width:100%; height:100%;"></div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-gpx/1.7.0/gpx.min.js"></script>

<script>
var map = null;

$(document).ready(function(){

    // AJAX Desa
    $('#kecamatan, #filter_kecamatan').change(function(){
        var kecamatan_id = $(this).val();
        var target = $(this).attr('id') == 'kecamatan' ? '#desa' : '#filter_desa';
        if(kecamatan_id){
            $.post('{{ route("getdesa") }}', { kecamatan_id: kecamatan_id, _token:'{{ csrf_token() }}'}, function(data){
                $(target).html(data);
            });
        } else { $(target).html('<option value="">== Pilih Desa ==</option>'); }
    });

    // DATATABLES
    var table = $('#tabelPJU').DataTable({ pageLength:10 });
    $('#btnFilter').click(function(){ table.draw(); });
    $('#filter_kecamatan, #filter_desa').change(function(){ table.draw(); });
    $('#filter_search').keyup(function(e){ if(e.key==='Enter') table.draw(); });

    $.fn.dataTable.ext.search.push(function(settings, data){
        var filterKec = $('#filter_kecamatan option:selected').text().toLowerCase();
        var filterDes = $('#filter_desa option:selected').text().toLowerCase();
        var filterSearch = $('#filter_search').val().toLowerCase();

        var kec = data[1].toLowerCase();
        var desa = data[2].toLowerCase();
        var namaGabung = data.join(' ').toLowerCase();

        if($('#filter_kecamatan').val() && kec !== filterKec) return false;
        if($('#filter_desa').val() && desa !== filterDes) return false;
        if(filterSearch && !namaGabung.includes(filterSearch)) return false;

        return true;
    });

// MODAL GPX INTERAKTIF DENGAN SWITCH LAYER
$('.btn-map').click(function(){

    var id = $(this).data('id');
    // var nama = $(this).data('nama');

    // $('#mapModalLabel').text('Lokasi PJU: ' + nama);

    var modalEl = document.getElementById('mapModal');
    var modal = new bootstrap.Modal(modalEl);
    modal.show();

    modalEl.addEventListener('shown.bs.modal', function () {

        // 🔥 HAPUS MAP LAMA JIKA ADA
        if (map !== null) {
            map.remove();
            map = null;
        }

        // 🔥 BUAT MAP BARU
        map = L.map('mapModalMap').setView([-3.3, 114.6], 13);

        // 🔥 BAGIAN YANG DIRUBAH / TAMBAH LAYER PETA
        var street = L.tileLayer(
            'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
            { attribution: '&copy; OpenStreetMap' }
        ).addTo(map); // default peta

        var satellite = L.tileLayer(
            'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}',
            { attribution: '© Esri' }
        );

        var labels = L.tileLayer(
            'https://{s}.basemaps.cartocdn.com/light_only_labels/{z}/{x}/{y}.png',
            { attribution: '© CartoDB', pane: 'overlayPane' }
        );

        var satelliteLabels = L.layerGroup([satellite, labels]);

        var baseMaps = {
            "Street Map": street,
            "Satelit": satelliteLabels
        };

        L.control.layers(baseMaps).addTo(map);
        L.control.scale().addTo(map); // skala peta

        // 🔥 LOAD GPX
        var gpxUrlTemplate = "{{ route('pjuviewGpx', ['id' => ':id']) }}";
        var gpxUrl = gpxUrlTemplate.replace(':id', id);

        new L.GPX(gpxUrl, {
            async: true,
            marker_options: {
                startIconUrl: null,
                endIconUrl: null,
                shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
                wptIconUrls: { '': 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png' }
            }
        })
        .on('loaded', function(e){
            map.fitBounds(e.target.getBounds());
        })
        .on('addpoint', function(e){

            var lat = e.point.getLatLng().lat;
            var lng = e.point.getLatLng().lng;

            var googleMapsUrl = "https://www.google.com/maps/dir/?api=1&destination=" 
                                + lat + "," + lng + "&travelmode=driving";

            var popupContent = `
                <div style="text-align:center;">
                    <b>Lokasi PJU</b><br><br>
                    <a href="${googleMapsUrl}" target="_blank" 
                    style="
                        background:#28a745;
                        color:white;
                        padding:6px 10px;
                        text-decoration:none;
                        border-radius:4px;
                    ">
                    🧭 Navigasi ke Lokasi
                    </a>
                </div>
            `;

            e.point.bindPopup(popupContent);
        })
        .addTo(map);

        setTimeout(function(){
            map.invalidateSize();
        }, 200);

    }, { once: true });

});

// 🔥 HAPUS MAP SAAT MODAL TERTUTUP
$('#mapModal').on('hidden.bs.modal', function () {
    if (map !== null) {
        map.remove();
        map = null;
    }
    $('#mapModalMap').html('');
});

});
</script>
@endsection