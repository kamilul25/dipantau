@extends('layout.app')

@section('title', 'Edit Pasum | DIPANTAU')

@section('content')

<div class="container my-4">
    <h2 class="mb-4">Edit Data Perumahan</h2>

    <div class="card shadow-sm">
        <div class="card-body">

            <form method="POST" action="{{ route('perumahan.update',$perumahan->id) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label>Nama Perumahan</label>
                    <input name="nama_perumahan" class="form-control"
                        value="{{ $perumahan->nama_perumahan }}" required>
                </div>

                <div class="mb-3">
                    <label>Kecamatan</label>
                    <select name="kecamatan" id="kecamatan" class="form-select" required>
                        @foreach($kecamatans as $kec)
                            <option value="{{ $kec->id }}"
                                {{ $perumahan->kecamatan_id == $kec->id ? 'selected':'' }}>
                                {{ $kec->nama_kecamatan }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>Desa</label>
                    <select name="desa" id="desa" class="form-select" required>
                        @foreach($desas as $des)
                            <option value="{{ $des->id }}"
                                {{ $perumahan->desa_id == $des->id ? 'selected':'' }}>
                                {{ $des->nama_desa }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>Alamat</label>
                    <textarea name="alamat" class="form-control" required>{{ $perumahan->alamat }}</textarea>
                </div>

                <div class="mb-3">
                    <label>Status</label>
                    <select name="status" class="form-select" required>
                        <option value="Sudah Serah Terima"
                            {{ $perumahan->status=='Sudah Serah Terima'?'selected':'' }}>
                            Sudah Serah Terima
                        </option>
                        <option value="Belum Serah Terima"
                            {{ $perumahan->status=='Belum Serah Terima'?'selected':'' }}>
                            Belum Serah Terima
                        </option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Jumlah Unit</label>
                    <input type="number" name="jumlah_unit" class="form-control"
                        value="{{ $perumahan->jumlah_unit }}" required>
                </div>

                <div class="mb-3">
                    <label>Pengembang</label>
                    <input name="pengembang" class="form-control"
                        value="{{ $perumahan->pengembang }}" required>
                </div>

                <div class="mb-3">
                    <label>Latitude</label>
                    <input type="text" name="latitude" id="latitude"
                        class="form-control"
                        value="{{ $perumahan->latitude }}">
                </div>

                <div class="mb-3">
                    <label>Longitude</label>
                    <input type="text" name="longitude" id="longitude"
                        class="form-control"
                        value="{{ $perumahan->longitude }}">
                </div>

                <div class="mb-3 text-center">
                    <button type="button" class="btn btn-warning" onclick="cekLokasiEdit()">
                        Cek Lokasi
                    </button>
                </div>

                <div class="mb-3">
                    <div id="mapContainer"
                        style="height:350px; display:none;">
                        <iframe id="googleMap"
                                width="100%"
                                height="100%"
                                style="border:0">
                        </iframe>
                    </div>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary">
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('perumahan.index') }}" class="btn btn-secondary ms-2">
                        Kembali
                    </a>
                </div>

            </form>

        </div>
    </div>
</div>

@endsection


@section('scripts')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
// AJAX Desa
$('#kecamatan').on('change', function(){
    let kecamatan_id = $(this).val();
    if(kecamatan_id){
        $.post('{{ route("get.desa") }}',
        { kecamatan_id: kecamatan_id, _token:'{{ csrf_token() }}' },
        function(data){
            $('#desa').html(data);
        });
    }
});

// Cek Lokasi
function cekLokasiEdit(){
    let lat = $('#latitude').val().trim();
    let lng = $('#longitude').val().trim();

    if(!lat || !lng){
        alert('Latitude dan Longitude wajib diisi');
        return;
    }

    let mapUrl = `https://www.google.com/maps?q=${lat},${lng}&z=17&t=h&output=embed`;
    $('#googleMap').attr('src', mapUrl);
    $('#mapContainer').show();
}

// Auto tampilkan map jika sudah ada koordinat
$(document).ready(function(){
    let lat = $('#latitude').val();
    let lng = $('#longitude').val();

    if(lat && lng){
        let mapUrl = `https://www.google.com/maps?q=${lat},${lng}&z=17&t=h&output=embed`;
        $('#googleMap').attr('src', mapUrl);
        $('#mapContainer').show();
    }
});
</script>

@endsection
