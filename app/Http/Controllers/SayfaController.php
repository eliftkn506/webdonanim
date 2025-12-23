<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\Page;

class SayfaController extends Controller
{
    public function hakkimizda()
    {
        $page = Page::where('slug', 'hakkimizda')->firstOrFail();
        return view('sayfalar.hakkimizda', compact('page'));
    }
    
    public function iletisim()
    {
        $page = Page::where('slug', 'iletisim')->firstOrFail();
        return view('sayfalar.iletisim', compact('page'));
    }
    
    public function iletisimGonder(Request $request)
    {
        $request->validate([
            'ad' => 'required|string|max:255',
            'email' => 'required|email',
            'telefon' => 'nullable|string|max:20',
            'konu' => 'required|string',
            'mesaj' => 'required|string|min:10'
        ]);
        
        // E-posta gönderme işlemi burada yapılacak
        // Mail::to('info@webdonanim.com')->send(new IletisimMail($request->all()));
        
        return back()->with('success', 'Mesajınız başarıyla gönderildi! 24 saat içinde size dönüş yapacağız.');
    }
}