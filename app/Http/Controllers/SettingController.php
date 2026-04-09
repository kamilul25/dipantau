<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::first();
        return view('management.settings', compact('setting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'whatsapp_number' => 'required|numeric'
        ]);

        $setting = Setting::first();
        $setting->update([
            'whatsapp_number' => $request->whatsapp_number
        ]);

        return back()->with('success', 'Nomor WhatsApp berhasil diperbarui');
    }
}