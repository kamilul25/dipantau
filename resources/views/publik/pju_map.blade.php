@extends('layout.app')

@section('title','Peta PJU')

@section('content')

<div class="container mt-3">

<div class="card">
<div class="card-body">

<div class="row mb-3">

<div class="col-md-4">
<label>Kecamatan</label>
<select id="filter_kecamatan" class="form-select">
<option value="">Semua Kecamatan</option>
@foreach($kecamatans as $kec)
<option value="{{ $kec->id }}">{{ $kec->nama_kecamatan }}</option>
@endforeach
</select>
</div>

<div class="col-md-4">
<label>Desa</label>
<select id="filter_desa" class="form-select">
<option value="">Semua Desa/Kelurahan</option>
</select>
</div>

</div>

<div id="map" style="height:600px;"></div>

</div>
</div>

</div>

@endsection

@section('scripts')

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>

var map = L.map('map').setView([-2.935,115.152],10);

// Street Map
var street = L.tileLayer(
    'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
    { attribution: '&copy; OpenStreetMap' }
).addTo(map);

// Satellite
var satellite = L.tileLayer(
    'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}',
    { attribution: '© Esri' }
);

// Label satelit
var labels = L.tileLayer(
    'https://{s}.basemaps.cartocdn.com/light_only_labels/{z}/{x}/{y}.png',
    { attribution: '© CartoDB' }
);

var satelliteLabels = L.layerGroup([satellite, labels]);

// Layer Switch
var baseMaps = {
    "Street Map": street,
    "Satelit": satelliteLabels
};

L.control.layers(baseMaps).addTo(map);

// Skala peta
L.control.scale().addTo(map);

var markers = [];

function loadPJU(){

fetch("{{ route('publik.getAllGpx') }}")
.then(res=>res.json())
.then(data=>{

markers.forEach(m=>map.removeLayer(m));
markers=[];

var kec = document.getElementById("filter_kecamatan").value;
var desa = document.getElementById("filter_desa").value;

data.forEach(p=>{

if(kec && p.kecamatan != kec) return;
if(desa && p.desa != desa) return;

var marker = L.marker([p.lat,p.lng])
.addTo(map)
.bindPopup("<b>"+p.nama+"</b>");

markers.push(marker);
map.addLayer(marker);

});

});

}

loadPJU();

document.getElementById("filter_kecamatan").onchange = loadPJU;
document.getElementById("filter_desa").onchange = loadPJU;


$('#filter_kecamatan').change(function(){

    var kecId = $(this).val();

    if(kecId){

        $.post("{{ route('pju.getDesa') }}", {
            kecamatan_id: kecId,
            _token: '{{ csrf_token() }}'
        }, function(response){

            $('#filter_desa').html(response);

        });

    } else {

        $('#filter_desa').html('<option value="">Semua Desa</option>');

    }

    loadPJU(); // reload map setelah pilih kecamatan
});

$('#filter_desa').change(function(){
    loadPJU();
});
</script>

@endsection