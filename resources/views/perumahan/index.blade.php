@extends('layout.app')

@section('title', 'Pasum | DIPANTAU')

@section('content')

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

<!-- FORM TAMBAH TOGGLE -->
<div class="container mt-3">
    <a href="{{ route('perumahan.pdf') }}" target="_blank" class="btn btn-info mb-3" title="Cetak PDF">
    <i class="fa-solid fa-print"></i></a>
    <button class="btn btn-warning fw-semibold mb-3" id="btnToggleForm">+</button>

    <div id="formTambah" style="display:none;">
        <form method="post" action="{{ route('perumahan.store') }}">
            @csrf
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Perumahan</label>
                            <input name="nama_perumahan" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kecamatan</label>
                            <select name="kecamatan" id="kecamatan" class="form-control" required>
                                <option value="">== Pilih Kecamatan ==</option>
                                @foreach($kecamatans as $kec)
                                    <option value="{{ $kec->id }}">{{ $kec->nama_kecamatan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Desa/Kelurahan</label>
                            <select name="desa" id="desa" class="form-control" required>
                                <option value="">== Pilih Desa ==</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-control" required>
                                <option value="">-- Pilih Status --</option>
                                <option value="Sudah Serah Terima">Sudah Serah Terima</option>
                                <option value="Belum Serah Terima">Belum Serah Terima</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Jumlah Unit</label>
                            <input type="number" name="jumlah_unit" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Pengembang</label>
                            <input name="pengembang" class="form-control" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Alamat</label>
                            <textarea name="alamat" class="form-control" required></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Latitude</label>
                            <input type="text" id="latitude" name="latitude" class="form-control" placeholder="-3.1234567" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Longitude</label>
                            <input type="text" id="longitude" name="longitude" class="form-control" placeholder="114.1234567" required>
                        </div>
                        <div class="col-md-12 text-center mt-3">
                            <button type="button" class="btn btn-warning fw-semibold" onclick="cekLokasi()">
                                <i class="fa-solid fa-map-location-dot"></i> Cek Lokasi
                            </button>
                            <input type="submit" class="btn btn-warning fw-semibold" value="Simpan">
                            <input type="reset" class="btn btn-secondary fw-semibold" value="Reset">
                        </div>
                        <div class="col-md-12 mt-3" id="mapContainer" style="height:350px; display:none;">
                            <iframe id="googleMap" width="100%" height="100%" style="border:0"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </form>
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
                        <input type="text" id="filter_search" class="form-control" placeholder="Cari Nama Perumahan" />
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
            <th>Aksi</th> <!-- TAMBAHAN -->
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

            <!-- Tombol Lihat Map -->
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

            <!-- TOMBOL AKSI -->
            <td class="text-center">

                <!-- EDIT -->
                <a href="{{ route('perumahan.edit',$row->id) }}"
                   class="btn btn-warning btn-sm">
                    <i class="fa-solid fa-pen"></i>
                </a>

                <!-- HAPUS -->
            <form action="{{ route('perumahan.destroy',$row->id) }}"
                method="POST"
                style="display:inline;"
                class="delete-perumahan-form">

                @csrf
                @method('DELETE')

                <button type="button"
                        class="btn btn-danger btn-sm btn-delete-perumahan">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </form>

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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
$('#btnToggleForm').click(function(){

    let form = $('#formTambah');
    let btn  = $(this);

    form.slideToggle(200, function(){

        if(form.is(':visible')){
            btn.html('-');
        }else{
            btn.html('+');
        }

    });

});

// AJAX Desa
$('#kecamatan, #filter_kecamatan').change(function(){
    var kecamatan_id = $(this).val();
    var target = $(this).attr('id') == 'kecamatan' ? '#desa' : '#filter_desa';
    if(kecamatan_id){
        $.post('{{ route("get.desa") }}', { kecamatan_id: kecamatan_id, _token:'{{ csrf_token() }}'}, function(data){
            $(target).html(data);
        });
    } else { $(target).html('<option value="">== Pilih Desa ==</option>'); }
});

// DataTables
$(document).ready(function(){
    var table = $('#tabelPerumahan').DataTable({ pageLength:10 });
    $('#btnFilter').click(function(){ table.draw(); });
    $('#filter_kecamatan, #filter_desa').change(function(){ table.draw(); });
    $('#filter_search').keyup(function(e){ if(e.key==='Enter') table.draw(); });

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

// Map Modal
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

// Cek Lokasi di Form Tambah
function cekLokasi(){
    let lat=$('#latitude').val().trim(), lng=$('#longitude').val().trim();
    if(!lat||!lng){ alert('Latitude dan Longitude wajib diisi'); return; }
    lat=parseFloat(lat); lng=parseFloat(lng);
    if(isNaN(lat)||isNaN(lng)){ alert('Koordinat tidak valid'); return; }
    if(lat<-90||lat>90||lng<-180||lng>180){ alert('Koordinat di luar jangkauan'); return; }
    $('#googleMap').attr('src', `https://www.google.com/maps?q=${lat},${lng}&z=17&t=h&output=embed`);
    $('#mapContainer').show();
}

// Konfirmasi Hapus Perumahan dengan SweetAlert2
$('.btn-delete-perumahan').click(function(){
    const form = $(this).closest('form');

    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data perumahan akan dihapus secara permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if(result.isConfirmed){
            form.submit();
        }
    });
});
</script>
@endsection
