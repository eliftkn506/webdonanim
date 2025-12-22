<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Degerlendirme;

class DegerlendirmeController extends Controller
{
    /**
     * Tüm değerlendirmeleri listele
     */
    public function index()
    {
        $degerlendirmeler = Degerlendirme::with(['user', 'urun'])
            ->latest()
            ->paginate(15);

        return view('admin.degerlendirmeler.index', compact('degerlendirmeler'));
    }

    /**
     * Değerlendirme Onay Durumunu Değiştir
     */
    public function onayla($id)
    {
        $degerlendirme = Degerlendirme::findOrFail($id);
        $degerlendirme->onay = !$degerlendirme->onay;
        $degerlendirme->save();

        $mesaj = $degerlendirme->onay ? 'Yorum onaylandı.' : 'Yorum gizlendi.';
        return redirect()->back()->with('success', $mesaj);
    }

    /**
     * Değerlendirmeyi Sil
     */
    public function sil($id)
    {
        $degerlendirme = Degerlendirme::findOrFail($id);
        $degerlendirme->delete();

        return redirect()->back()->with('success', 'Yorum başarıyla silindi.');
    }

    /**
     * YENİ: Yorum Cevapla
     */
    public function cevapla(Request $request, $id)
    {
        $request->validate([
            'cevap' => 'required|string|max:1000'
        ]);

        $degerlendirme = Degerlendirme::findOrFail($id);
        $degerlendirme->cevap = $request->cevap;
        $degerlendirme->onay = 1; // Cevap verilince otomatik onaylansın (isteğe bağlı)
        $degerlendirme->save();

        return redirect()->back()->with('success', 'Yoruma cevap verildi.');
    }
    
    /**
     * YENİ: Cevabı Sil (Opsiyonel)
     */
    public function cevapSil($id)
    {
        $degerlendirme = Degerlendirme::findOrFail($id);
        $degerlendirme->cevap = null;
        $degerlendirme->save();
        
        return redirect()->back()->with('success', 'Cevap kaldırıldı.');
    }
}