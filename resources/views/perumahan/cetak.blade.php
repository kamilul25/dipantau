<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Data Perumahan</title>

    <style>
        body {
            font-family: "Times New Roman", serif;
            font-size: 12px;
            color: #000;
        }

        .kop {
            text-align: center;
            margin-bottom: 15px;
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
            margin: 10px 0 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px;
            vertical-align: top;
        }

        th {
            background-color: #f2f2f2;
            text-align: center;
            font-weight: bold;
        }

        td {
            text-align: left;
        }

        .text-center {
            text-align: center;
        }

        .footer {
            margin-top: 30px;
            width: 100%;
        }

        .footer .ttd {
            width: 40%;
            float: right;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="kop">
        <h3>Data Pasum Perumahan</h3>
        <p>Kabupaten Tapin</p>
        <p>Tahun {{ date('Y') }}</p>
    </div>

    <hr>

    <table>
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="15%">Nama Perumahan</th>
                <th width="12%">Kecamatan</th>
                <th width="12%">Desa / Kelurahan</th>
                <th width="25%">Alamat</th>
                <th width="10%">Status</th>
                <th width="8%">Jumlah Unit</th>
                <th width="14%">Pengembang</th>
            </tr>
        </thead>
        <tbody>
            @foreach($perumahans as $no => $row)
            <tr>
                <td class="text-center">{{ $no + 1 }}</td>
                <td>{{ $row->nama_perumahan }}</td>
                <td>{{ $row->kecamatan->nama_kecamatan ?? '-' }}</td>
                <td>{{ $row->desa->nama_desa ?? '-' }}</td>
                <td>{{ $row->alamat }}</td>
                <td class="text-center">{{ $row->status }}</td>
                <td class="text-center">{{ $row->jumlah_unit }}</td>
                <td>{{ $row->pengembang }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
