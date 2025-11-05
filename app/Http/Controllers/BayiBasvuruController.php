<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BayiBasvuru;

class BayiBasvuruController extends Controller
{
    /**
     * Formu göster
     */
    public function showForm()
    {
        return view('bayi.basvuru'); // senin blade dosyan resources/views/bayi/basvuru.blade.php olmalı
    }

    /**
     * Başvuru kaydet
     */
    public function submit(Request $request)
    {
        $validated = $request->validate([
            'firma_adi'      => 'required|string|max:255',
            'yetkili_ad'     => 'required|string|max:100',
            'yetkili_soyad'  => 'required|string|max:100',
            'email'          => 'required|email|unique:bayi_basvurular,email',
            'telefon'        => 'nullable|string|max:20',
            'adres'          => 'nullable|string',
            'vergi_no'       => 'nullable|string|max:50',
        ]);

        BayiBasvuru::create([
            'firma_adi'     => $validated['firma_adi'],
            'yetkili_ad'    => $validated['yetkili_ad'],
            'yetkili_soyad' => $validated['yetkili_soyad'],
            'email'         => $validated['email'],
            'telefon'       => $request->telefon,
            'adres'         => $request->adres,
            'vergi_no'      => $request->vergi_no,
            'durum'         => 'beklemede',
        ]);

        return redirect()->route('bayi.basvuru.form')->with('success', 'Başvurunuz alınmıştır. Onay bekleniyor.');
    }
}
