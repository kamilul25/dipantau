@extends('layout.app')

@section('title', 'Rekap Aduan | DIPANTAU')

@section('content')

@if(session('success'))
<script>
document.addEventListener('DOMContentLoaded', function () {
    Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: "{{ session('success') }}",
        showConfirmButton: false,
        timer: 2000
    });
});
</script>
@endif

<!-- FORM TAMBAH TOGGLE -->
<div class="container mt-3">
    <a href="{{ route('aduan.cetak') }}" target="_blank" class="btn btn-info mb-3" title="Cetak PDF">
    <i class="fa-solid fa-print"></i></a>
    <button class="btn btn-warning fw-semibold mb-3" id="btnToggleForm">+</button>

    <div id="formTambah" style="display:none;">
        <form method="POST" action="{{ route('aduan.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label>Nama</label>
                            <input type="text" name="nama" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label>Tanggal</label>
                            <input type="date" name="tanggal" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label>Kecamatan</label>
                            <select name="kecamatan" id="kecamatan" class="form-control" required>
                                <option value="">== Pilih Kecamatan ==</option>
                                @foreach($kecamatans as $kec)
                                    <option value="{{ $kec->id }}">{{ $kec->nama_kecamatan }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label>Desa/Kelurahan</label>
                            <select name="desa" id="desa" class="form-control" required>
                                <option value="">== Pilih Desa ==</option>
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label>Alamat/Lokasi</label>
                            <textarea name="alamat" class="form-control" required></textarea>
                        </div>

                        <div class="col-md-12">
                            <label>Isi Aduan</label>
                            <textarea name="isi_aduan" class="form-control" required></textarea>
                        </div>

                        <div class="col-md-6">
                            <label>Jumlah Titik</label>
                            <input type="number" name="titik" class="form-control" min="1" required>
                        </div>

                        <div class="col-md-6">
                            <label>Status</label>
                            <select name="keterangan" class="form-control" required>
                                <option value="">-- Pilih --</option>
                                <option value="Dalam Proses">Dalam Proses</option>
                                <option value="Sudah Tertangani">Sudah Tertangani</option>
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label>Foto (Maks 1MB)</label>
                            <input type="file" name="foto" id="fotoInput" class="form-control" accept="image/*">
                            <img id="fotoPreviewForm" class="img-fluid rounded mt-2 d-none" style="max-height:200px;">
                        </div>

                        <div class="col-md-12 text-center mt-3">
                            <button type="submit" class="btn btn-warning fw-semibold">Simpan</button>
                            <button type="reset" class="btn btn-secondary fw-semibold">Reset</button>
                        </div>

                    </div>
                </div>
            </div>
        </form>
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
                    <label class="form-label">Pencarian Nama</label>
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
                        <th>Foto</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($aduans as $row)
                        <tr data-kecamatan="{{ $row->kecamatan_id }}" data-desa="{{ $row->desa_id }}">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $row->nama }}</td>
                            <td>{{ \Carbon\Carbon::parse($row->tanggal)->format('d-m-Y') }}</td>
                            <td>{{ $row->kecamatan->nama_kecamatan ?? '-' }}</td>
                            <td>{{ $row->desa->nama_desa ?? '-' }}</td>
                            <td>{{ $row->titik }}</td>
                            <td class="text-center">
                                <div class="form-check form-switch d-flex justify-content-center">
                                    <input 
                                        class="form-check-input status-toggle" 
                                        type="checkbox" 
                                        data-id="{{ $row->id }}"
                                        {{ $row->keterangan == 'Sudah Tertangani' ? 'checked' : '' }}>
                                </div>
                                <small class="status-label text-muted">
                                    {{ $row->keterangan }}
                                </small>
                            </td>
                            <td class="text-center">
                                @if($row->foto)
                                <button class="btn btn-info btn-sm btn-foto" 
                                    data-foto="{{ asset('storage/'.$row->foto) }}" 
                                    data-nama="{{ $row->nama }}">
                                    <i class="fa-solid fa-image"></i>
                                </button>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('aduan.edit',$row->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <form action="{{ route('aduan.destroy',$row->id) }}" method="POST" class="d-inline delete-aduan-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-sm btn-delete-aduan">
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

<!-- MODAL FOTO -->
<div class="modal fade" id="fotoModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="fotoModalLabel">Foto Aduan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="fotoPreviewModal" class="img-fluid rounded">
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
$(document).ready(function(){
    // Toggle Form Tambah
    $('#btnToggleForm').click(function(){
        let form = $('#formTambah');
        form.slideToggle(200, function(){
            $('#btnToggleForm').text(form.is(':visible') ? '-' : '+');
        });
    });

    // AJAX Desa untuk tambah & filter
    $('#kecamatan, #filter_kecamatan').change(function(){
        let kecamatan_id = $(this).val();
        let target = $(this).attr('id')=='kecamatan' ? '#desa' : '#filter_desa';
        if(kecamatan_id){
            $.post('{{ route("getdesa") }}', { kecamatan_id: kecamatan_id, _token:'{{ csrf_token() }}' }, function(data){
                $(target).html(data);
            });
        } else { $(target).html('<option value="">== Pilih Desa ==</option>'); }
    });

    // DataTables + Filter
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

    // Modal Foto
    $('.btn-foto').click(function(){
        $('#fotoModalLabel').text('Foto Aduan: ' + $(this).data('nama'));
        $('#fotoPreviewModal').attr('src', $(this).data('foto'));
        new bootstrap.Modal(document.getElementById('fotoModal')).show();
    });

    // Preview Foto Form Tambah
    $('#fotoInput').on('change', function(){
        const file = this.files[0];
        if(!file) return;
        if(file.size>1048576){ alert('Maks 1MB'); this.value=''; return; }
        const reader = new FileReader();
        reader.onload = e => $('#fotoPreviewForm').attr('src', e.target.result).removeClass('d-none');
        reader.readAsDataURL(file);
    });

    // Reset form
    $('form').on('reset', function(){
        $('#fotoPreviewForm').addClass('d-none').attr('src','');
    });

});

// Konfirmasi hapus Aduan dengan SweetAlert2
$('.btn-delete-aduan').click(function(){
    const form = $(this).closest('form');

    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data aduan akan dihapus secara permanen!",
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

$(document).on('change', '.status-toggle', function(){
    let toggle = $(this);
    let id = toggle.data('id');
    let status = toggle.is(':checked') ? 'Sudah Tertangani' : 'Dalam Proses';

    $.ajax({
        url: '/aduan/update-status/' + id,
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            keterangan: status
        },
        beforeSend: function(){
            toggle.prop('disabled', true);
        },
        success: function(){
            toggle.closest('td').find('.status-label').text(status);

            Swal.fire({
                icon: 'success',
                title: 'Status diperbarui',
                timer: 1200,
                showConfirmButton: false
            });
        },
        error: function(){
            Swal.fire('Error', 'Gagal update status', 'error');
            toggle.prop('checked', !toggle.is(':checked')); // rollback
        },
        complete: function(){
            toggle.prop('disabled', false);
        }
    });
});
</script>
@endsection