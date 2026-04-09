<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use App\Models\Desa;
use App\Models\Pju;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Dompdf\Dompdf;
use Dompdf\Options;

class PjuController extends Controller
{
    /**
     * Tampilkan halaman PJU beserta daftar kecamatan dan PJU
     */
    public function index()
    {
        // Ambil semua data PJU beserta relasi kecamatan dan desa
        $pjus = Pju::with(['kecamatan','desa'])->get();

        // Ambil daftar kecamatan untuk dropdown
        $kecamatans = Kecamatan::all();

        return view('pju.pju', compact('pjus', 'kecamatans'));
    }

    /**
     * AJAX request untuk mendapatkan desa berdasarkan kecamatan
     */
    public function getDesa(Request $request)
    {
        $desas = Desa::where('kecamatan_id',$request->kecamatan_id)
                ->orderBy('nama_desa')
                ->get();

        $output = '<option value="">-- Pilih Desa --</option>';

        foreach($desas as $desa){
            $output .= '<option value="'.$desa->id.'">'.$desa->nama_desa.'</option>';
        }

        return $output;
    }

    /**
     * Proses simpan data PJU
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'kecamatan' => 'required|numeric',
            'desa' => 'required|numeric',
            'rt' => 'required|numeric',
            'rw' => 'required|numeric',
            'pju' => 'required|numeric',
            'pjuts' => 'required|numeric',
            'tahun' => 'required|numeric',
            'file_gpx' => 'nullable|mimes:gpx,xml|max:10240', // Maksimal 10MB
        ]);

$filename = null;

if ($request->hasFile('file_gpx') && $request->file('file_gpx')->isValid()) {
    $file = $request->file('file_gpx');
    $filename = 'pju_' . time() . '.' . $file->getClientOriginalExtension();
    $file->storeAs('gpx', $filename, 'public');
}


        // Simpan ke database
        Pju::create([
            'kecamatan' => $request->kecamatan,
            'desa' => $request->desa,
            'rt' => $request->rt,
            'rw' => $request->rw,
            'pju' => $request->pju,
            'pjuts' => $request->pjuts,
            'tahun' => $request->tahun,
            'file_gpx' => $filename,
        ]);

        return redirect()->route('pju.index')->with('success', 'Data PJU berhasil disimpan.');
    }

    /**
     * Lihat file GPX PJU di modal
     */
    public function viewGpx($id)
    {
        $pju = Pju::findOrFail($id);

        if (!$pju->file_gpx) {
            abort(404, 'File GPX belum diunggah');
        }

        if (!Storage::disk('public')->exists('gpx/' . $pju->file_gpx)) {
            abort(404, 'File GPX tidak ditemukan');
        }

        return Storage::disk('public')->response(
            'gpx/' . $pju->file_gpx,
            null,
            ['Content-Type' => 'application/gpx+xml']
        );
    }

    /**
     * Optional: Hapus data PJU
     */
    public function destroy($id)
    {
        $pju = Pju::findOrFail($id);

        // Hapus file GPX jika ada
        if ($pju->file_gpx && Storage::disk('public')->exists('gpx/' . $pju->file_gpx)) {
            Storage::disk('public')->delete('gpx/' . $pju->file_gpx);
        }


        $pju->delete();

        return redirect()->route('pju.index')->with('success', 'Data PJU berhasil dihapus.');
    }

/**
 * Tampilkan form edit data PJU
 */
public function edit($id)
{
    $pju = Pju::findOrFail($id);
    $kecamatans = Kecamatan::all();
    $desas = Desa::where('kecamatan_id', $pju->kecamatan)->get();

    return view('pju.pju_edit', compact('pju', 'kecamatans', 'desas'));
}

/**
 * Update data PJU
 */
public function update(Request $request, $id)
{
    $pju = Pju::findOrFail($id);

    // Validasi input
    $request->validate([
        'kecamatan' => 'required|numeric',
        'desa' => 'required|numeric',
        'rt' => 'required|numeric',
        'rw' => 'required|numeric',
        'pju' => 'required|numeric',
        'pjuts' => 'required|numeric',
        'tahun' => 'required|numeric',
        'file_gpx' => 'nullable|mimes:gpx,xml|max:10240',
    ]);

    $filename = $pju->file_gpx;

    // Upload file baru jika ada
    if ($request->hasFile('file_gpx') && $request->file('file_gpx')->isValid()) {
        // Hapus file lama
        if ($filename && Storage::disk('public')->exists('gpx/' . $filename)) {
           Storage::disk('public')->delete('gpx/' . $filename);
        }

        $file = $request->file('file_gpx');
        $filename = 'pju_' . time() . '.' . $file->getClientOriginalExtension();
        $file->storeAs('gpx', $filename, 'public');
    }

    // Update database
    $pju->update([
        'kecamatan' => $request->kecamatan,
        'desa' => $request->desa,
        'rt' => $request->rt,
        'rw' => $request->rw,
        'pju' => $request->pju,
        'pjuts' => $request->pjuts,
        'tahun' => $request->tahun,
        'file_gpx' => $filename,
    ]);

    return redirect()->route('pju.index')->with('success', 'Data PJU berhasil diupdate.');
}

public function cetak()
{
    $pjus = Pju::with(['kecamatan','desa'])->orderBy('kecamatan')->orderBy('desa')->orderBy('rt')->orderBy('rw')->get();
    return view('pju.pju_cetak', compact('pjus'));
}

public function pdf()
{
    $pjus = Pju::with(['kecamatan','desa'])->orderBy('kecamatan')->orderBy('desa')->orderBy('rt')->orderBy('rw')->get();

    $options = new Options();
    $options->set('defaultFont', 'Times New Roman');
    $options->set('isRemoteEnabled', true);

    $dompdf = new Dompdf($options);
    $html = view('pju.pju_cetak', compact('pjus'))->render();

    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'landscape');
    $dompdf->render();

    return $dompdf->stream('Laporan_Data_PJU.pdf', ['Attachment' => false]);
}

}
