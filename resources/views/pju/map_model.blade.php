<script>
// MODAL GPX INTERAKTIF DENGAN SWITCH LAYER
$('.btn-map').click(function(){

    var id = $(this).data('id');
    var nama = $(this).data('nama');

    $('#mapModalLabel').text('Lokasi PJU: ' + nama);

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
        var gpxUrlTemplate = "{{ route('pju.viewGpx', ['id' => ':id']) }}";
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
</script>