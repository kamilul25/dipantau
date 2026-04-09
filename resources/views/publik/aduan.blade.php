@extends('layout.app')

@section('title', 'Riwayat Aduan | DIPANTAU')

@section('content')

<div class="container mt-4">

<!-- ================= CARD ================= -->
<div class="container my-4">
<div class="row g-4">

        <div class="col-md-4">
            <div class="card card-modern text-center h-100">
                <div class="card-body">
                    <div class="icon-box bg-primary mb-2">
                        <i class="fa-solid fa-inbox"></i>
                    </div>
                    <h6 class="text-primary fw-bold">TOTAL ADUAN</h6>
                    <h2 class="fw-bold">{{ $total_aduan }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-modern text-center h-100">
                <div class="card-body">
                    <div class="icon-box bg-success mb-2">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <h6 class="text-success fw-bold">SUDAH TERTANGANI</h6>
                    <h2 class="fw-bold">{{ $sudah_tertangani }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-modern text-center h-100">
                <div class="card-body">
                    <div class="icon-box bg-danger mb-2">
                        <i class="fa-solid fa-clock"></i>
                    </div>
                    <h6 class="text-danger fw-bold">DALAM PROSES</h6>
                    <h2 class="fw-bold">{{ $dalam_proses }}</h2>
                </div>
            </div>
        </div>

</div>
</div>

<!-- FILTER -->
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
                        <input type="text" id="filter_search" class="form-control" placeholder="Cari Nama">
                        <button class="btn btn-warning" id="btnFilter">Cari</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- TABEL -->
<div class="container">
<div class="card">
<div class="card-body">

<table id="tabelAduan" class="table table-striped table-hover align-middle">
<thead class="table-light">
<tr>
<th>No</th>
<th>Nama</th>
<th>Tanggal</th>
<th>Kecamatan</th>
<th>Desa/Kelurahan</th>
<th>Titik</th>
<th>Status</th>
</tr>
</thead>
<tbody>
@foreach($aduans as $row)
<tr data-kecamatan="{{ $row->kecamatan_id }}" data-desa="{{ $row->desa_id }}">
<td>{{ $loop->iteration }}</td>
<td>{{ $row->nama }}</td>
<td>{{ $row->tanggal ? \Carbon\Carbon::parse($row->tanggal)->format('d-m-Y') : '-' }}</td>
<td>{{ $row->kecamatan->nama_kecamatan ?? '-' }}</td>
<td>{{ $row->desa->nama_desa ?? '-' }}</td>
<td>{{ $row->titik }}</td>
<td>
<span class="badge {{ $row->keterangan=='Sudah Tertangani'?'bg-success':'bg-danger' }}">
{{ $row->keterangan }}
</span>
</td>

</tr>
@endforeach
</tbody>
</table>

</div>
</div>
</div>
</div>
<!-- MODAL FOTO -->
<div class="modal fade" id="fotoModal" tabindex="-1">
<div class="modal-dialog modal-lg modal-dialog-centered">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title" id="fotoModalLabel">Foto Aduan</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>
<div class="modal-body text-center">
<img id="fotoPreviewModal" class="img-fluid rounded" src="" alt="Foto Aduan" style="display:none;">
</div>
</div>
</div>
</div>

@endsection

@section('scripts')
<script>

// AJAX Desa
$('#kecamatan, #filter_kecamatan').change(function(){
    let kecamatan_id = $(this).val();
    let target = $(this).attr('id')=='kecamatan' ? '#desa' : '#filter_desa';
    if(kecamatan_id){
        $.post('{{ route("aduan.getDesa") }}', { kecamatan_id: kecamatan_id, _token:'{{ csrf_token() }}' }, function(data){
            $(target).html(data);
        });
    } else { $(target).html('<option value="">== Pilih Desa ==</option>'); }
});

// DataTables + Filter
$(document).ready(function(){
    let table = $('#tabelAduan').DataTable({ pageLength:10 });

$.fn.dataTable.ext.search.push(function(settings, data, dataIndex){
    let filterKec = $('#filter_kecamatan').val();
    let filterDes = $('#filter_desa').val();
    let filterSearch = $('#filter_search').val().toLowerCase();

    let row = table.row(dataIndex).node();
    let rowKec = $(row).data('kecamatan');
    let rowDes = $(row).data('desa');
    let rowNama = data[1].toLowerCase();

    if(filterKec && rowKec != filterKec) return false;
    if(filterDes && rowDes != filterDes) return false;
    if(filterSearch && !rowNama.includes(filterSearch)) return false;

    return true;
});

    $('#btnFilter').click(function(){ table.draw(); });
    $('#filter_kecamatan, #filter_desa').change(function(){ table.draw(); });
    $('#filter_search').keyup(function(e){ if(e.key==='Enter') table.draw(); });
});

</script>
@endsection