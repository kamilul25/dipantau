@extends('layout.app')

@section('title', 'Beranda Pasum | DIPANTAU')

@section('content')

<div class="container mt-4">

    {{-- DASHBOARD --}}
<div class="container my-4">
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card card-modern text-center h-100">
                <div class="card-body">
                    <div class="icon-box bg-warning mb-2">
                        <i class="fa-solid fa-house"></i>
                    </div>
                    <h6 class="text-warning fw-bold">JUMLAH PERUMAHAN</h6>
                    <h2 class="fw-bold">{{ $total_perumahan }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-modern text-center h-100">
                <div class="card-body">
                    <div class="icon-box bg-success mb-2">
                        <i class="fa-solid fa-handshake"></i>
                    </div>
                    <h6 class="text-success fw-bold">SUDAH SERAH TERIMA PSU</h6>
                    <h2 class="fw-bold">{{ $sudah_psu }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-modern text-center h-100">
                <div class="card-body">
                    <div class="icon-box bg-danger mb-2">
                        <i class="fa-solid fa-handshake-slash"></i>
                    </div>
                    <h6 class="text-danger fw-bold">BELUM SERAH TERIMA PSU</h6>
                    <h2 class="fw-bold">{{ $belum_psu }}</h2>
                </div>
            </div>
        </div>
    </div>
</div>

    {{-- FILTER --}}
    <div class="card mb-4">
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
                        <input type="text" id="filter_search" class="form-control" placeholder="Cari Nama Perumahan">
                        <button class="btn btn-warning fw-semibold" id="btnFilter">Cari</button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- TABEL DATA --}}
    <div class="card">
        <div class="card-body">

            <table id="tabelPerumahan" class="table table-striped table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Perumahan</th>
                        <th>Kecamatan</th>
                        <th>Desa/Kelurahan</th>
                        <th>Status</th>
                        <th>Jumlah Unit</th>
                        <th>Lokasi</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no=1; @endphp
                    @foreach($perumahans as $row)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $row->nama_perumahan }}</td>
                        <td>{{ $row->kecamatan->nama_kecamatan ?? '-' }}</td>
                        <td>{{ $row->desa->nama_desa ?? '-' }}</td>
                        <td>
                            <span class="badge {{ $row->status=='Sudah Serah Terima' ? 'bg-success' : 'bg-danger' }}">
                                {{ $row->status }}
                            </span>
                        </td>
                        <td>{{ $row->jumlah_unit }}</td>

                        <td class="text-center">
<button class="btn btn-info btn-sm btn-map"
    data-lat="{{ $row->latitude }}"
    data-lng="{{ $row->longitude }}"
    data-nama="{{ $row->nama_perumahan }}"
    data-pengembang="{{ $row->pengembang }}"
    data-kecamatan="{{ $row->kecamatan->nama_kecamatan ?? '-' }}"
    data-desa="{{ $row->desa->nama_desa ?? '-' }}"
    data-alamat="{{ $row->alamat }}">
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

<!-- MODAL MAP -->
<div class="modal fade" id="mapModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <table class="table table-sm table-borderless mb-0" style="font-size: 1.0rem; line-height: 1.1;">
                    <tr>
                        <td class="text-muted py-0" style="width: 150px;">Lokasi Perumahan</td>
                        <td class="py-0" id="modalNama">: </td>
                    </tr>
                    <tr>
                        <td class="text-muted py-0">Pengembang</td>
                        <td class="py-0" id="modalPengembang">: </td>
                    </tr>
                    <tr>
                        <td class="text-muted py-0">Kecamatan</td>
                        <td class="py-0" id="modalKecamatan">: </td>
                    </tr>
                    <tr>
                        <td class="text-muted py-0">Desa</td>
                        <td class="py-0" id="modalDesa">: </td>
                    </tr>
                    <tr>
                        <td class="text-muted py-0">Alamat</td>
                        <td class="py-0" id="modalAlamat">: </td>
                    </tr>
                </table>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <iframe id="googleMapFrame" width="100%" height="450" style="border:0;"></iframe>
            </div>
        </div>
    </div>
</div>

@endsection


@section('scripts')

<script>
$(document).ready(function(){

// AJAX Desa
$('#kecamatan, #filter_kecamatan').change(function(){
    var kecamatan_id = $(this).val();
    var target = $(this).attr('id') == 'kecamatan' ? '#desa' : '#filter_desa';
    if(kecamatan_id){
        $.post('{{ route("pasum.getDesa") }}', { kecamatan_id: kecamatan_id, _token:'{{ csrf_token() }}'}, function(data){
            $(target).html(data);
        });
    } else { $(target).html('<option value="">== Pilih Desa ==</option>'); }
});

    var table = $('#tabelPerumahan').DataTable({
        pageLength: 10
    });

    // Filter custom
    $('#btnFilter').click(function(){
        table.draw();
    });

    $('#filter_kecamatan, #filter_desa').change(function(){
        table.draw();
    });

    $('#filter_search').keyup(function(e){
        if(e.key === 'Enter'){
            table.draw();
        }
    });

    $.fn.dataTable.ext.search.push(function(settings, data){

        var filterKec = $('#filter_kecamatan option:selected').text().toLowerCase();
        var filterDes = $('#filter_desa option:selected').text().toLowerCase();
        var filterSearch = $('#filter_search').val().toLowerCase();

        var nama = data[1].toLowerCase();
        var kec  = data[2].toLowerCase();
        var desa = data[3].toLowerCase();

        if($('#filter_kecamatan').val() && kec != filterKec) return false;
        if($('#filter_desa').val() && desa != filterDes) return false;
        if(filterSearch && !nama.includes(filterSearch)) return false;

        return true;
    });

});


// Modal Map
$('.btn-map').click(function(){
    let lat = $(this).data('lat');
    let lng = $(this).data('lng');
    let nama = $(this).data('nama');
    let pengembang = $(this).data('pengembang');
    let kecamatan = $(this).data('kecamatan');
    let desa = $(this).data('desa');
    let alamat = $(this).data('alamat');

    if(!lat || !lng){ alert('Koordinat belum tersedia'); return; }

    $('#googleMapFrame').attr('src', `https://www.google.com/maps?q=${lat},${lng}&z=17&t=h&output=embed`);

    $('#modalNama').text(': ' + nama);
    $('#modalPengembang').text(': ' + pengembang);
    $('#modalKecamatan').text(': ' + kecamatan);
    $('#modalDesa').text(': ' + desa);
    $('#modalAlamat').text(': ' + alamat);

    let modal = new bootstrap.Modal(document.getElementById('mapModal'));
    modal.show();
});

</script>

@endsection
