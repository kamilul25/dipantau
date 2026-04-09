<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Perumahan;
use App\Models\Kecamatan;
use App\Models\Desa;
use Barryvdh\DomPDF\Facade\Pdf;

class PerumahanController extends Controller
{
    public function index()
    {
        $perumahans = Perumahan::with(['kecamatan','desa'])
                        ->orderBy('id')
                        ->get();

        $kecamatans = Kecamatan::orderBy('nama_kecamatan')->get();

        return view('perumahan.index', compact('perumahans','kecamatans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_perumahan' => 'required',
            'kecamatan' => 'required',
            'desa' => 'required',
            'alamat' => 'required',
            'status' => 'required',
            'jumlah_unit' => 'required|integer',
            'pengembang' => 'required',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        Perumahan::create([
            'nama_perumahan' => $request->nama_perumahan,
            'kecamatan_id' => $request->kecamatan,
            'desa_id' => $request->desa,
            'alamat' => $request->alamat,
            'status' => $request->status,
            'jumlah_unit' => $request->jumlah_unit,
            'pengembang' => $request->pengembang,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return redirect()->route('perumahan.index')
                ->with('success','Data berhasil ditambahkan');
    }

    public function edit($id)
    {
        $perumahan = Perumahan::findOrFail($id);
        $kecamatans = Kecamatan::orderBy('nama_kecamatan')->get();
        $desas = Desa::where('kecamatan_id',$perumahan->kecamatan_id)
                    ->orderBy('nama_desa')
                    ->get();

        return view('perumahan.edit', compact('perumahan','kecamatans','desas'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_perumahan' => 'required',
            'kecamatan' => 'required',
            'desa' => 'required',
            'alamat' => 'required',
            'status' => 'required',
            'jumlah_unit' => 'required|integer',
            'pengembang' => 'required',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $perumahan = Perumahan::findOrFail($id);

        $perumahan->update([
            'nama_perumahan' => $request->nama_perumahan,
            'kecamatan_id' => $request->kecamatan,
            'desa_id' => $request->desa,
            'alamat' => $request->alamat,
            'status' => $request->status,
            'jumlah_unit' => $request->jumlah_unit,
            'pengembang' => $request->pengembang,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return redirect()->route('perumahan.index')
                ->with('success','Data berhasil diupdate');
    }

    public function destroy($id)
    {
        Perumahan::findOrFail($id)->delete();

        return redirect()->route('perumahan.index')
                ->with('success','Data berhasil dihapus');
    }

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

    public function cetak()
    {
        $perumahans = Perumahan::with(['kecamatan','desa'])
            ->orderBy('nama_perumahan','asc')
            ->get();

        return view('perumahan.cetak', compact('perumahans'));
    }

    public function pdf()
    {
        $perumahans = Perumahan::with(['kecamatan','desa'])
            ->orderBy('nama_perumahan','asc')
            ->get();

        $pdf = Pdf::loadView('perumahan.cetak', compact('perumahans'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream('Laporan_Data_Pasum_Perumahan_Tapin.pdf');
    }
}
