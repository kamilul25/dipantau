@extends('layout.app')

@section('title', 'Edit Rekap Aduan | DIPANTAU')

@section('content')

<div class="container mt-3">

    <h4>Edit Aduan: {{ $aduan->nama }}</h4>

    <form method="POST" action="{{ route('aduan.update', $aduan->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="card mb-4">
            <div class="card-body">
                <div class="row g-3">

                    <div class="col-md-6">
                        <label>Nama</label>
                        <input type="text" name="nama" value="{{ old('nama', $aduan->nama) }}" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label>Tanggal</label>
                        <input type="date" name="tanggal" 
                            value="{{ old('tanggal', \Carbon\Carbon::parse($aduan->tanggal)->format('Y-m-d')) }}" 
                            class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label>Kecamatan</label>
                        <select name="kecamatan" id="kecamatan" class="form-control" required>
                            <option value="">== Pilih Kecamatan ==</option>
                            @foreach($kecamatans as $kec)
                                <option value="{{ $kec->id }}" {{ $aduan->kecamatan_id==$kec->id ? 'selected' : '' }}>{{ $kec->nama_kecamatan }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label>Desa/Kelurahan</label>
                        <select name="desa" id="desa" class="form-control" required>
                            <option value="">== Pilih Desa ==</option>
                            @foreach($desas as $desa)
                                <option value="{{ $desa->id }}" {{ $aduan->desa_id==$desa->id ? 'selected' : '' }}>{{ $desa->nama_desa }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label>Alamat/Lokasi</label>
                        <textarea name="alamat" class="form-control" required>{{ old('alamat', $aduan->alamat) }}</textarea>
                    </div>

                    <div class="col-md-12">
                        <label>Isi Aduan</label>
                        <textarea name="isi_aduan" class="form-control" required>{{ old('isi_aduan', $aduan->isi_aduan) }}</textarea>
                    </div>

                    <div class="col-md-6">
                        <label>Jumlah Titik</label>
                        <input type="number" name="titik" value="{{ old('titik', $aduan->titik) }}" class="form-control" min="1" required>
                    </div>

                    <div class="col-md-6">
                        <label>Status</label>
                        <select name="keterangan" class="form-control" required>
                            <option value="">-- Pilih --</option>
                            <option value="Dalam Proses" {{ $aduan->keterangan=='Dalam Proses'?'selected':'' }}>Dalam Proses</option>
                            <option value="Sudah Tertangani" {{ $aduan->keterangan=='Sudah Tertangani'?'selected':'' }}>Sudah Tertangani</option>
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label>Foto (Maks 1MB)</label>
                        <input type="file" name="foto" id="fotoInput" class="form-control" accept="image/*">
                        @if($aduan->foto)
                            <img id="fotoPreviewForm" src="{{ asset('storage/'.$aduan->foto) }}" class="img-fluid rounded mt-2" style="max-height:200px;">
                        @else
                            <img id="fotoPreviewForm" class="img-fluid rounded mt-2 d-none" style="max-height:200px;">
                        @endif
                    </div>

                    <div class="col-md-12 text-center mt-3">
                        <button type="submit" class="btn btn-warning fw-semibold">Update</button>
                        <a href="{{ route('aduan.index') }}" class="btn btn-secondary fw-semibold">Kembali</a>
                    </div>

                </div>
            </div>
        </div>
    </form>
</div>

@endsection

@section('scripts')
<script>
$(document).ready(function(){

    // AJAX Desa
    $('#kecamatan').change(function(){
        let kecamatan_id = $(this).val();
        if(kecamatan_id){
            $.post('{{ route("getdesa") }}', { kecamatan_id: kecamatan_id, _token:'{{ csrf_token() }}' }, function(data){
                $('#desa').html(data);
            });
        } else { $('#desa').html('<option value="">== Pilih Desa ==</option>'); }
    });

    // Preview Foto
    $('#fotoInput').on('change', function(){
        const file = this.files[0];
        if(!file) return;
        if(file.size>1048576){ alert('Maks 1MB'); this.value=''; return; }
        const reader = new FileReader();
        reader.onload = e => $('#fotoPreviewForm').attr('src', e.target.result).removeClass('d-none');
        reader.readAsDataURL(file);
    });

});
</script>
@endsection