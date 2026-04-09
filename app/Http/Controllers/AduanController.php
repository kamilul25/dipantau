<?php

namespace App\Http\Controllers;

use App\Models\Aduan;
use App\Models\Kecamatan;
use App\Models\Desa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class AduanController extends Controller
{
    // ===============================
    // List Semua Aduan
    // ===============================
    public function index()
    {
        $aduans = Aduan::with(['kecamatan','desa'])
            ->orderBy('created_at', 'desc')
            ->get();

        $kecamatans = Kecamatan::orderBy('nama_kecamatan')->get();

        return view('aduan.index', compact('aduans', 'kecamatans'));
    }

    // ===============================
    // Simpan Aduan Baru
    // ===============================
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'kecamatan' => 'required|exists:kecamatans,id',
            'desa' => 'required|exists:desas,id',
            'alamat' => 'required|string',
            'isi_aduan' => 'required|string',
            'titik' => 'required|integer|min:1',
            'keterangan' => 'required|in:Dalam Proses,Sudah Tertangani',
            'foto' => 'nullable|image|max:1024', // Maks 1MB
        ]);

        // Upload foto jika ada
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('foto_aduan','public');
        }

        // Simpan data
        Aduan::create([
            'nama' => $validated['nama'],
            'tanggal' => $validated['tanggal'],
            'kecamatan_id' => $validated['kecamatan'],
            'desa_id' => $validated['desa'],
            'alamat' => $validated['alamat'],
            'isi_aduan' => $validated['isi_aduan'],
            'titik' => $validated['titik'],
            'keterangan' => $validated['keterangan'],
            'foto' => $fotoPath,
        ]);

        return redirect()->route('aduan.index')
            ->with('success','Data aduan berhasil disimpan');
    }

    // ===============================
    // Hapus Aduan
    // ===============================
    public function destroy($id)
    {
        $aduan = Aduan::findOrFail($id);

        // Hapus foto lama jika ada
        if ($aduan->foto && Storage::exists('public/' . $aduan->foto)) {
            Storage::delete('public/' . $aduan->foto);
        }

        $aduan->delete();

        return redirect()->route('aduan.index')
                        ->with('success', 'Data berhasil dihapus');
    }

    // ===============================
    // Ambil Desa Berdasarkan Kecamatan
    // ===============================
    public function getDesa(Request $request)
    {
        $desas = Desa::where('kecamatan_id', $request->kecamatan_id)
                ->orderBy('nama_desa')
                ->get();

        $output = '<option value="">== Pilih Desa ==</option>';
        foreach($desas as $desa){
            $output .= '<option value="'.$desa->id.'">'.$desa->nama_desa.'</option>';
        }

        return $output;
    }    

    // ===============================
    // Edit Aduan
    // ===============================
    public function edit($id)
    {
        $aduan = Aduan::findOrFail($id);
        $kecamatans = Kecamatan::orderBy('nama_kecamatan')->get();
        $desas = Desa::where('kecamatan_id', $aduan->kecamatan_id)->orderBy('nama_desa')->get();

        return view('aduan.edit', compact('aduan','kecamatans','desas'));
    }

    // ===============================
    // Update Aduan
    // ===============================
    public function update(Request $request, $id)
    {
        $aduan = Aduan::findOrFail($id);

        // Validasi input
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'kecamatan' => 'required|exists:kecamatans,id',
            'desa' => 'required|exists:desas,id',
            'alamat' => 'required|string',
            'isi_aduan' => 'required|string',
            'titik' => 'required|integer|min:1',
            'keterangan' => 'required|in:Dalam Proses,Sudah Tertangani',
            'foto' => 'nullable|image|max:1024',
        ]);

        // Siapkan array update
        $data = [
            'nama' => $validated['nama'],
            'tanggal' => $validated['tanggal'],
            'kecamatan_id' => $validated['kecamatan'],
            'desa_id' => $validated['desa'],
            'alamat' => $validated['alamat'],
            'isi_aduan' => $validated['isi_aduan'],
            'titik' => $validated['titik'],
            'keterangan' => $validated['keterangan'],
        ];

        // Upload foto baru jika ada
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($aduan->foto && Storage::exists('public/' . $aduan->foto)) {
                Storage::delete('public/' . $aduan->foto);
            }

            $data['foto'] = $request->file('foto')->store('foto_aduan','public');
        }

        // Update data
        $aduan->update($data);

        return redirect()->route('aduan.index')
            ->with('success', 'Data aduan berhasil diperbarui');
    }

    // ===============================
    // Cetak PDF Aduan
    // ===============================
    public function cetak()
    {
        $aduans = Aduan::with(['kecamatan','desa'])
            ->orderBy('tanggal','desc')
            ->get();

        $total_titik = $aduans->sum('titik');

        $pdf = Pdf::loadView('aduan.cetak', compact('aduans','total_titik'))
            ->setPaper('A4', 'landscape');

        return $pdf->stream('Laporan_Data_Aduan.pdf');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'keterangan' => 'required|in:Dalam Proses,Sudah Tertangani'
        ]);

        $aduan = Aduan::findOrFail($id);
        $aduan->keterangan = $request->keterangan;
        $aduan->save();

        return response()->json(['success' => true]);
    }
}