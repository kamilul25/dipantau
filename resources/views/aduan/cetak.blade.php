<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan Rekap Aduan</title>

<style>
body {
    font-family: "Times New Roman", serif;
    font-size: 11px;
    color: #000;
}
.kop {
    text-align: center;
    margin-bottom: 10px;
}
.kop h3 {
    margin: 0;
    font-size: 16px;
    text-transform: uppercase;
}
.kop p {
    margin: 2px 0;
    font-size: 12px;
}
hr {
    border: 1px solid #000;
    margin: 8px 0 12px;
}
table {
    width: 100%;
    border-collapse: collapse;
}
th, td {
    border: 1px solid #000;
    padding: 5px;
    vertical-align: top;
}
th {
    background-color: #f0f0f0;
    text-align: center;
    font-weight: bold;
}
.text-center {
    text-align: center;
}
</style>
</head>
<body>

<div class="kop">
    <h3>Laporan Rekap Aduan</h3>
    <p>Kabupaten Tapin</p>
    <p>Tahun {{ date('Y') }}</p>
</div>

<hr>

<table>
<thead>
<tr>
    <th width="4%">No</th>
    <th width="14%">Nama</th>
    <th width="8%">Tanggal</th>
    <th width="12%">Kecamatan</th>
    <th width="14%">Desa/Kelurahan</th>
    <th width="17%">Alamat/Lokasi</th>
    <th width="17%">Isi Aduan</th>
    <th width="4%">Jumlah Titik</th>
    <th width="10%">Keterangan</th>
</tr>
</thead>
<tbody>

@foreach($aduans as $row)
<tr>
    <td class="text-center">{{ $loop->iteration }}</td>
    <td>{{ $row->nama }}</td>
    <td class="text-center">{{ \Carbon\Carbon::parse($row->tanggal)->format('d/m/Y') }}</td>
    <td>{{ $row->kecamatan->nama_kecamatan ?? '-' }}</td>
    <td>{{ $row->desa->nama_desa ?? '-' }}</td>
    <td>{{ $row->alamat }}</td>
    <td>{{ $row->isi_aduan }}</td>
    <td class="text-center">{{ $row->titik }}</td>
    <td class="text-center">{{ $row->keterangan }}</td>
</tr>
@endforeach

</tbody>

<tfoot>
<tr>
    <th colspan="7" class="text-center">TOTAL TITIK</th>
    <th class="text-center">{{ $total_titik }}</th>
    <th></th>
</tr>
</tfoot>
</table>

</body>
</html>