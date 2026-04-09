<?php

namespace App\Http\Controllers;

use App\Models\Slide;
use Illuminate\Http\Request;

class SlideController extends Controller
{
    public function index()
    {
        // Ambil slide berdasarkan urutan
        $slides = Slide::orderBy('order','asc')->get();
        return view('slide.slide', compact('slides'));
    }

    public function store(Request $request)
    {
        if (Slide::count() >= 4) {
            return back()->with('error', 'Maksimal hanya 4 slide!');
        }

        $request->validate([
            'title' => 'nullable|string|max:255',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $image = $request->file('image')->store('slides', 'public');

        // Urutan terakhir
        $order = Slide::max('order') + 1;

        Slide::create([
            'title' => $request->title,
            'image' => $image,
            'order' => $order
        ]);

        return back()->with('success', 'Slide berhasil ditambahkan');
    }

    public function destroy($id)
    {
        $slide = Slide::findOrFail($id);
        \Storage::disk('public')->delete($slide->image);
        $slide->delete();

        return back()->with('success', 'Slide berhasil dihapus');
    }

    // AJAX untuk update urutan slide
    public function updateOrderAjax(Request $request)
    {
        $orderData = $request->input('order', []);
        foreach($orderData as $item){
            Slide::where('id', $item['id'])->update(['order' => $item['order']]);
        }
        return response()->json(['success' => true]);
    }
}