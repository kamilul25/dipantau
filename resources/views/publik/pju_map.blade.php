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

<div class="mb-2" id="btnKembaliContainer" style="display:none;">

    <button class="btn btn-secondary btn-sm" id="btnKembali">

        ← Kembali

    </button>

</div>

<div id="map" style="height:600px;"></div>

</div>
</div>

</div>

@endsection

@section('scripts')

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>

<link rel="stylesheet"
href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css"/>

<link rel="stylesheet"
href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css"/>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>

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

var markerCluster = L.markerClusterGroup({

    showCoverageOnHover:false,

    spiderfyOnMaxZoom:true,

    zoomToBoundsOnClick:true,

    removeOutsideVisibleBounds:true,

    chunkedLoading:true,

    chunkInterval:100,

    chunkDelay:50,

    disableClusteringAtZoom:18

});

map.addLayer(markerCluster);

var kecamatanLayer = null;
var maskLayer = null;
var jumlahKecamatan = {};
var jumlahDesa = {};
var desaGeojson = null;
var desaLayer = null;
var selectedKecamatan = null;

/* ===========================
   STYLE GIS
=========================== */

const STYLE = {

    kecamatan:{

        normal:{
            color:"#0d6efd",
            weight:2,
            fillColor:"#4da3ff",
            fillOpacity:0.30
        },

        hover:{
            color:"#0b5ed7",
            weight:3,
            fillColor:"#4da3ff",
            fillOpacity:0.55
        },

        selected:{
            color:"#0d6efd",
            weight:3,
            fillColor:"#4da3ff",
            fillOpacity:0.65
        },

        hidden:{
            opacity:0,
            fillOpacity:0,
            interactive:false
        }

    },

    desa:{

        normal:{
            color:"#198754",
            weight:1,
            fillColor:"#63c174",
            fillOpacity:0.35
        },

        hover:{
            color:"#146c43",
            weight:2,
            fillColor:"#63c174",
            fillOpacity:0.60
        },

        selected:{
            color:"#146c43",
            weight:2,
            fillColor:"#63c174",
            fillOpacity:0.75
        }

    }

};

fetch("{{ route('publik.jumlahKecamatan') }}")
.then(res=>res.json())
.then(function(data){

    data.forEach(function(item){

        jumlahKecamatan[item.kecamatan] = item.jumlah;

    });

    loadDesaGeojson();

    loadKecamatan();

});

fetch("{{ route('publik.jumlahDesa') }}")
.then(res => res.json())
.then(function(data){

    data.forEach(function(item){

        jumlahDesa[item.desa] = item.jumlah;

    });

});

function createMask(data){

    // Polygon dunia
    var world = [
        [
            [-90,-180],
            [-90,180],
            [90,180],
            [90,-180],
            [-90,-180]
        ]
    ];

    // Ambil seluruh polygon kecamatan
    var holes = [];

    data.features.forEach(function(feature){

        if(feature.geometry.type === "Polygon"){

            holes.push(
                feature.geometry.coordinates[0].map(function(coord){
                    return [coord[1], coord[0]];
                })
            );

        }

        if(feature.geometry.type === "MultiPolygon"){

            feature.geometry.coordinates.forEach(function(poly){

                holes.push(
                    poly[0].map(function(coord){
                        return [coord[1], coord[0]];
                    })
                );

            });

        }

    });

    maskLayer = L.polygon(
        world.concat(holes),
        {
            stroke:false,
            fillColor:"#000",
            fillOpacity:0.45,
            interactive:false
        }
    ).addTo(map);

}

function loadKecamatan(){

    fetch("/geojson/kecamatan.geojson")

    .then(res=>res.json())

    .then(data=>{

        createMask(data);

        kecamatanLayer = L.geoJSON(data,{

            style:STYLE.kecamatan.normal,

            onEachFeature:onEachKecamatan

        }).addTo(map);

        kecamatanLayer.bringToFront();

        map.fitBounds(kecamatanLayer.getBounds());

    });

}

function loadDesaGeojson(){

    fetch("/geojson/desa.geojson")

    .then(res => res.json())

    .then(data => {

        desaGeojson = data;

        console.log("Desa berhasil dimuat :", desaGeojson.features.length);

    });

}

function highlightFeature(e){

    if(selectedKecamatan === e.target){
        return;
    }

    e.target.setStyle(STYLE.kecamatan.hover);

}

function highlightDesa(e){

    e.target.setStyle(STYLE.desa.hover);

}

function resetHighlight(e){

    if(selectedKecamatan === e.target){
        return;
    }

    e.target.setStyle(STYLE.kecamatan.normal);

}

function resetDesa(e){

    e.target.setStyle(STYLE.desa.normal);

}

function updateKecamatanState(){

    kecamatanLayer.eachLayer(function(layer){

        // Tidak ada kecamatan yang dipilih
        if(selectedKecamatan === null){

            kecamatanLayer.resetStyle(layer);
            return;

        }

        // Kecamatan dipilih
        if(layer === selectedKecamatan){

            layer.setStyle(STYLE.kecamatan.selected);

        }else{

            layer.setStyle(STYLE.kecamatan.hidden);

        }

    });

}

function pilihKecamatan(id){

    kecamatanLayer.eachLayer(function(layer){

        if(parseInt(layer.feature.properties.kd_kecamatan) === parseInt(id)){

            selectedKecamatan = layer;

            map.fitBounds(layer.getBounds(),{

                padding:[30,30],

                animate:true,

                duration:0.8,

                easeLinearity:0.25

            });

            updateKecamatanState();

            setTimeout(function(){

                loadDesa(id);

            },200);

            document.getElementById("btnKembaliContainer").style.display="block";

        }

    });

}

function pilihDesa(id){

    if(!desaLayer){

        return;

    }

    desaLayer.eachLayer(function(layer){

        if(parseInt(layer.feature.properties.kd_kelurahan)===parseInt(id)){

            map.fitBounds(layer.getBounds(),{

                padding:[20,20],

                animate:true,

                duration:0.8

            });

            loadMarkerDesa(id);

        }

    });

}

function loadDesa(kecamatanId){

    if(!desaGeojson){

        return;

    }

    if(desaLayer){

        map.removeLayer(desaLayer);

    }

    let hasilFilter = {

        type: "FeatureCollection",

        features: desaGeojson.features.filter(function(feature){

            return parseInt(feature.properties.kd_kecamatan) === parseInt(kecamatanId);

        })

    };

desaLayer = L.geoJSON(hasilFilter,{

    style:STYLE.desa.normal,

    onEachFeature:onEachDesa

}).addTo(map);

desaLayer.eachLayer(function(layer){

    if(layer._path){

        layer._path.classList.add("fade-in");

    }

});

desaLayer.bringToFront();

}

function loadMarkerDesa(idDesa){

    markerCluster.clearLayers();

    fetch("{{ route('publik.markerDesa') }}",{

        method:"POST",

        headers:{

            "Content-Type":"application/json",

            "X-CSRF-TOKEN":"{{ csrf_token() }}"

        },

        body:JSON.stringify({

            desa:idDesa

        })

    })

    .then(res=>res.json())

    .then(function(data){

        data.forEach(function(p){

            var marker = L.marker([

                p.lat,

                p.lng

            ]);

    marker.bindPopup(

        "<b>Informasi PJU</b><br>" +

        "PJU : <b>" + p.pju + "</b><br>" +

        "PJUTS : <b>" + p.pjuts + "</b><br>" +

        "Tahun : <b>" + p.tahun + "</b>"

    );

            markerCluster.addLayer(marker);

        });

    });

}

function zoomToFeature(e){

    pilihKecamatan(

        e.target.feature.properties.kd_kecamatan

    );

}

function onEachKecamatan(feature, layer){

    let id = parseInt(feature.properties.kd_kecamatan);

    let nama = feature.properties.nm_kecamatan;

    layer.on({

        mouseover:function(e){

            highlightFeature(e);

            let jumlah = jumlahKecamatan[id] ?? 0;

            layer.bindTooltip(

                "<b>"+nama+"</b><br>" +
                "Jumlah PJU : <b>"+jumlah+"</b> Titik",

                {

                    sticky:true,
                    direction:'top',
                    className:'info-tooltip'

                }

            ).openTooltip();

        },

        mouseout:resetHighlight,

        click:zoomToFeature

    });

}

function onEachDesa(feature, layer){

    let id = parseInt(feature.properties.kd_kelurahan);

    let jumlah = jumlahDesa[id] ?? 0;

    layer.bindTooltip(

        "<b>" + feature.properties.nm_kelurahan + "</b><br>" +
        "Jumlah PJU : <b>" + jumlah + "</b> Titik",

        {
            sticky: true,
            direction: "top",
            className: "info-tooltip"
        }

    );

    layer.on({

        mouseover: highlightDesa,

        mouseout: resetDesa,

click:function(){

    pilihDesa(

        feature.properties.kd_kelurahan

    );

}

    });

}

function kembaliKeKecamatan(){

    markerCluster.clearLayers();

    if(desaLayer){

        map.removeLayer(desaLayer);

        desaLayer = null;

    }

    selectedKecamatan = null;

    kecamatanLayer.eachLayer(function(layer){
    layer.options.interactive = true;
    });

    updateKecamatanState();

    map.fitBounds(kecamatanLayer.getBounds());

    document.getElementById("btnKembaliContainer").style.display="none";

}

// function loadPJU(){

// fetch("{{ route('publik.getAllGpx') }}")
// .then(res=>res.json())
// .then(data=>{

// markers.forEach(m=>map.removeLayer(m));
// markers=[];

// var kec = document.getElementById("filter_kecamatan").value;
// var desa = document.getElementById("filter_desa").value;

// data.forEach(p=>{

// if(kec && p.kecamatan != kec) return;
// if(desa && p.desa != desa) return;

// var marker = L.marker([p.lat,p.lng])
// .addTo(map)
// .bindPopup("<b>"+p.nama+"</b>");

// markers.push(marker);
// map.addLayer(marker);

// });

// });

// }

// loadPJU();

// document.getElementById("filter_kecamatan").onchange = loadPJU;
// document.getElementById("filter_desa").onchange = loadPJU;


$('#filter_kecamatan').change(function(){

    let kecId = $(this).val();

    if(kecId==""){

        kembaliKeKecamatan();

        $('#filter_desa').html('<option value="">Semua Desa</option>');

        return;

    }

    $.post("{{ route('pju.getDesa') }}",{

        kecamatan_id:kecId,

        _token:"{{ csrf_token() }}"

    },function(response){

        $('#filter_desa').html(response);

    });

    pilihKecamatan(kecId);

});

$('#filter_desa').change(function(){

    let desa = $(this).val();

    if(desa=="") return;

    pilihDesa(desa);

});

document.getElementById("btnKembali").onclick=function(){

    kembaliKeKecamatan();

}
</script>

@endsection