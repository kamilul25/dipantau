<?php

namespace App\Http\Controllers;

use App\Models\Perumahan;
use App\Models\Kecamatan;
use App\Models\Desa;
use App\Models\Pju;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Aduan;
use App\Models\Slide;

class PublikController extends Controller
{
    public function index()
    {
        $total_perumahan = Perumahan::count();
        $sudah_psu = Perumahan::where('status','Sudah Serah Terima')->count();
        $belum_psu = Perumahan::where('status','!=','Sudah Serah Terima')->count();

        $perumahans = Perumahan::with(['kecamatan','desa'])
            ->orderBy('id','asc')
            ->get();

        $kecamatans = Kecamatan::orderBy('nama_kecamatan','asc')->get();

        return view('publik.pasum', compact(
            'total_perumahan',
            'sudah_psu',
            'belum_psu',
            'perumahans',
            'kecamatans'
        ));
    }

    public function pju()
    {
        // Ambil data PJU
        $pjus = Pju::with(['kecamatan','desa'])
                    ->orderBy('kecamatan')
                    ->orderBy('desa')
                    ->get();

        $kecamatans = Kecamatan::orderBy('nama_kecamatan')->get();

        // ===================== HITUNG TOTAL =====================
        $jumlah_pju = $pjus->sum('pju');       // Total PJU
        $jumlah_pjuts = $pjus->sum('pjuts');   // Total PJUTS
        $total_titik = $pjus->sum(function($item){
            return $item->pju + $item->pjuts;  // Total titik = PJU + PJUTS
        });
        // =========================================================

        return view('publik.pju', compact('pjus','kecamatans','jumlah_pju','jumlah_pjuts','total_titik'));
    }

public function beranda()
{
    // ===== PERUMAHAN =====
    $stats = Perumahan::selectRaw("
        COUNT(*) as total,
        SUM(CASE WHEN status = 'Sudah Serah Terima' THEN 1 ELSE 0 END) as sudah,
        SUM(CASE WHEN status != 'Sudah Serah Terima' THEN 1 ELSE 0 END) as belum
    ")->first();

    $total_perumahan = $stats->total ?? 0;
    $sudah_psu = $stats->sudah ?? 0;
    $belum_psu = $stats->belum ?? 0;

    // ===== PJU & PJUTS =====
    $pjus = Pju::all();
    $jumlah_pju = $pjus->sum('pju');
    $jumlah_pjuts = $pjus->sum('pjuts');
    $total_titik = $pjus->sum(function($item){
        return $item->pju + $item->pjuts;
    });

    // ===== SLIDES =====
    $slides = Slide::orderBy('order','asc')->take(5)->get();

    return view('publik.beranda', compact(
        'slides',
        'total_perumahan',
        'sudah_psu',
        'belum_psu',
        'jumlah_pju',
        'jumlah_pjuts',
        'total_titik'
    ));
}

        public function aduan()
        {
            $kecamatans = Kecamatan::orderBy('nama_kecamatan')->get();

            $aduans = Aduan::with(['kecamatan','desa'])
                        ->orderBy('tanggal','desc')
                        ->get();

            // ===== HITUNG TOTAL =====
        $stats = Aduan::selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN keterangan='Sudah Tertangani' THEN 1 ELSE 0 END) as sudah,
            SUM(CASE WHEN keterangan!='Sudah Tertangani' THEN 1 ELSE 0 END) as proses
        ")->first();

        $total_aduan = $stats->total;
        $sudah_tertangani = $stats->sudah;
        $dalam_proses = $stats->proses;

            return view('publik.aduan', compact(
                'aduans',
                'kecamatans',
                'total_aduan',
                'sudah_tertangani',
                'dalam_proses'
            ));
        }

    /**
     * AJAX request untuk mendapatkan desa berdasarkan kecamatan
     */
    public function getDesas(Request $request)
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

public function mapAll()
{
    $kecamatans = Kecamatan::all();
    return view('publik.pju_map', compact('kecamatans'));
}

public function getAllGpx()
{
    $pjus = Pju::whereNotNull('file_gpx')->get();

    $data = [];

    foreach($pjus as $pju){

        $path = storage_path('app/public/gpx/'.$pju->file_gpx);

        if(file_exists($path)){

            $xml = simplexml_load_file($path);

            foreach($xml->wpt as $point){

                $data[] = [
                    'lat' => (float)$point['lat'],
                    'lng' => (float)$point['lon'],
                    'nama' => "RT ".$pju->rt." RW ".$pju->rw,
                    'desa' => $pju->desa,
                    'kecamatan' => $pju->kecamatan
                ];
            }

        }
    }

    return response()->json($data);
}
}
