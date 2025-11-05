<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BayiBasvuru;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;


class BayiController extends Controller
{
    // Başvuruları listele (Bekleyen)
    public function basvurular()
    {
        $basvurular = BayiBasvuru::orderBy('created_at', 'desc')->get();
        return view('admin.bayiler.basvurular', compact('basvurular'));
    }

    // Onaylanan bayileri listele
    public function index()
    {
        $bayiler = BayiBasvuru::where('durum', 'onaylandi')
                              ->orderBy('created_at', 'desc')
                              ->get();
        return view('admin.bayiler.index', compact('bayiler'));
    }

    // Detay görüntüle
    public function show($id)
    {
        $basvuru = BayiBasvuru::findOrFail($id);
        return view('admin.bayiler.show', compact('basvuru'));
    }

   
public function approve(BayiBasvuru $basvuru)
{
    // Başvurunun durumunu güncelle
    $basvuru->durum = BayiBasvuru::DURUM_ONAYLANDI;
    $basvuru->save();

    // Eğer daha önce user oluşturulmamışsa yeni user kaydı aç
    if (!$basvuru->user_id) {
        $defaultPassword = '123456e.'; // Sabit şifre

        $user = \App\Models\User::create([
            'name'     => $basvuru->yetkili_ad . ' ' . $basvuru->yetkili_soyad,
            'email'    => $basvuru->email,
            'password' => Hash::make($defaultPassword), // Laravel hash
            'role'     => 'bayi',
        ]);

        // Başvuruyu user ile ilişkilendir
        $basvuru->user_id = $user->id;
        $basvuru->save();

        // Şifreyi test için ekrana yazdır
        session()->flash('password', $defaultPassword);
    }

    return redirect()->back()->with('success', 'Bayi başarıyla onaylandı, kullanıcı oluşturuldu.');
}




    // Reddet
    public function reject($id)
    {
        $basvuru = BayiBasvuru::findOrFail($id);
        $basvuru->durum = 'reddedildi';
        $basvuru->save();

        return redirect()->route('admin.bayiler.basvurular')
                         ->with('danger', 'Bayi başvurusu reddedildi.');
    }
}