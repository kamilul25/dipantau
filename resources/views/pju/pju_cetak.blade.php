<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Data PJU</title>
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
        td {
            text-align: left;
        }
        .text-center {
            text-align: center;
        }
        tfoot th {
            text-align: center;
        }
    </style>
</head>
<body>

<div class="kop">
    <h3>Laporan Data Penerangan Jalan Umum (PJU)</h3>
    <p>Kabupaten Tapin</p>
    <p>Tahun {{ date('Y') }}</p>
</div>

<hr>

<table>
    <thead>
        <tr>
            <th width="4%">No</th>
            <th width="14%">Kecamatan</th>
            <th width="14%">Desa / Kelurahan</th>
            <th width="8%">RT</th>
            <th width="8%">RW</th>
            <th width="8%">PJU</th>
            <th width="8%">PJUTS</th>
            <th width="10%">Jumlah Titik</th>
            <th width="10%">Tahun</th>
        </tr>
    </thead>
    <tbody>
        @php
            $no = 1;
            $total_pju = 0;
            $total_pjuts = 0;
            $total_titik = 0;
        @endphp

        @foreach($pjus as $row)
            @php
                $jumlah_titik = $row->pju + $row->pjuts;
                $total_pju += $row->pju;
                $total_pjuts += $row->pjuts;
                $total_titik += $jumlah_titik;
            @endphp
            <tr>
                <td class="text-center">{{ $no++ }}</td>
                <td>{{ \App\Models\Kecamatan::find($row->kecamatan)->nama_kecamatan ?? '-' }}</td>
                <td>{{ \App\Models\Desa::find($row->desa)->nama_desa ?? '-' }}</td>
                <td class="text-center">{{ $row->rt }}</td>
                <td class="text-center">{{ $row->rw }}</td>
                <td class="text-center">{{ $row->pju }}</td>
                <td class="text-center">{{ $row->pjuts }}</td>
                <td class="text-center"><strong>{{ $jumlah_titik }}</strong></td>
                <td class="text-center">{{ $row->tahun }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="5" class="text-center">TOTAL</th>
            <th class="text-center">{{ $total_pju }}</th>
            <th class="text-center">{{ $total_pjuts }}</th>
            <th class="text-center">{{ $total_titik }}</th>
            <th></th>
        </tr>
    </tfoot>
</table>

</body>
</html>