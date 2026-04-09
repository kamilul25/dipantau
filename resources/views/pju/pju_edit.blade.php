@extends('layout.app')

@section('title', 'Edit PJU | DIPANTAU')

@section('content')

<div class="container my-4">
    <h2 class="mb-4">Edit Data PJU</h2>

    <div class="card shadow-sm">
        <div class="card-body">

            <form method="POST" action="{{ route('pju.update',$pju->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label>Kecamatan</label>
                    <select name="kecamatan" id="kecamatan" class="form-select" required>
                        <option value="">== Pilih Kecamatan ==</option>
                        @foreach($kecamatans as $kec)
                            <option value="{{ $kec->id }}" {{ $pju->kecamatan == $kec->id ? 'selected' : '' }}>
                                {{ $kec->nama_kecamatan }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>Desa/Kelurahan</label>
                    <select name="desa" id="desa" class="form-select" required>
                        <option value="">== Pilih Desa ==</option>
                        @foreach($desas as $des)
                            <option value="{{ $des->id }}" {{ $pju->desa == $des->id ? 'selected' : '' }}>
                                {{ $des->nama_desa }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="row g-3">
                    <div class="col-md-3">
                        <label>RT</label>
                        <input type="number" name="rt" class="form-control" value="{{ $pju->rt }}" required>
                    </div>
                    <div class="col-md-3">
                        <label>RW</label>
                        <input type="number" name="rw" class="form-control" value="{{ $pju->rw }}" required>
                    </div>
                    <div class="col-md-3">
                        <label>PJU</label>
                        <input type="number" name="pju" class="form-control" value="{{ $pju->pju }}" required>
                    </div>
                    <div class="col-md-3">
                        <label>PJUTS</label>
                        <input type="number" name="pjuts" class="form-control" value="{{ $pju->pjuts }}" required>
                    </div>
                </div>

                <div class="row g-3 mt-3">
                    <div class="col-md-6">
                        <label>Tahun</label>
                        <input type="number" name="tahun" class="form-control" value="{{ $pju->tahun }}" required>
                    </div>
                    <div class="col-md-6">
                        <label>File GPX</label>
                        <input type="file" name="file_gpx" class="form-control" accept=".gpx">
                        @if($pju->file_gpx)
                            <small>File saat ini: 
                                <a href="{{ asset('storage/gpx/'.$pju->file_gpx) }}" target="_blank">{{ $pju->file_gpx }}</a>
                            </small>
                        @endif
                    </div>
                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="{{ route('pju.index') }}" class="btn btn-secondary ms-2">Kembali</a>
                </div>

            </form>

        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
// AJAX update desa saat kecamatan berubah
$('#kecamatan').on('change', function(){
    let kecamatan_id = $(this).val();
    if(kecamatan_id){
        $.post('{{ route("pju.getDesa") }}', 
            { kecamatan_id: kecamatan_id, _token:'{{ csrf_token() }}' },
            function(data){
                $('#desa').html(data.options);
            });
    } else { 
        $('#desa').html('<option value="">== Pilih Desa ==</option>'); 
    }
});
</script>
@endsection