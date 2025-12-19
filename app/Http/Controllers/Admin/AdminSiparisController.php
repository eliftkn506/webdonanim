<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siparis;
use App\Models\OdemeBilgisi;
use App\Models\Fatura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminSiparisController extends Controller
{
    /**
     * Sipariş listesi
     */
    public function index(Request $request)
{
    $query = Siparis::with(['user']);

    // Durum Filtresi
    if ($request->filled('durum')) {
        $query->where('durum', $request->durum);
    }

    // Arama Filtresi
    if ($request->filled('q')) {
        $term = $request->q;
        $query->where(function($q) use ($term) {
            $q->where('siparis_no', 'like', '%' . $term . '%')
              ->orWhereHas('user', function($userQuery) use ($term) {
                  $userQuery->where('name', 'like', '%' . $term . '%')
                            ->orWhere('email', 'like', '%' . $term . '%');
              });
        });
    }

    $siparisler = $query->latest()->paginate(15)->withQueryString();

    // İstatistikler (Aynen kalabilir)
    $istatistikler = [ /* ... */ ]; // Mevcut kodunuzdaki gibi kalsın

    return view('admin.siparisler.index', compact('siparisler')); // İstatistikleri de gönderiyorsanız ekleyin
}

    /**
     * Sipariş detayı
     */
    public function show($id)
    {
        $siparis = Siparis::with(['user', 'urunler.urun'])->findOrFail($id);
        $odemeBilgisi = OdemeBilgisi::where('siparis_id', $siparis->id)->first();
        $fatura = Fatura::where('siparis_id', $siparis->id)->first();

        return view('admin.siparisler.show', compact('siparis', 'odemeBilgisi', 'fatura'));
    }
public function durumGuncelle(Request $request, $id)
{
    $request->validate([
        'durum' => 'required|string|in:beklemede,onaylandi,hazirlaniyor,kargoda,teslim_edildi,iptal_edildi',
        'not' => 'nullable|string|max:500',
    ]);

    $siparis = Siparis::findOrFail($id);

    $siparis->durum = $request->durum;

    // Eğer DB’de not alanı varsa ekle, yoksa kaldır
    if (in_array('not', $siparis->getFillable())) {
        $siparis->not = $request->not;
    }

    $siparis->save();

    return response()->json([
        'success' => true,
        'message' => 'Sipariş durumu başarıyla güncellendi!'
    ]);
}




    /**
     * Ödeme durumu güncelleme
     */
    public function odemeDurumuGuncelle(Request $request, $id)
    {
        $request->validate([
            'odeme_durumu' => 'required|in:beklemede,isleniyor,odendi,iptal_edildi,iade_edildi'
        ]);

        $siparis = Siparis::findOrFail($id);
        $siparis->update(['odeme_durumu' => $request->odeme_durumu]);

        // Ödeme onaylandıysa sipariş durumunu da güncelle
        if ($request->odeme_durumu === 'odendi' && $siparis->durum === 'beklemede') {
            $siparis->update(['durum' => 'onaylandi']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Ödeme durumu güncellendi'
        ]);
    }

    /**
     * Sipariş iptali
     */
    public function iptal(Request $request, $id)
    {
        $request->validate([
            'iptal_nedeni' => 'required|string|max:500'
        ]);

        $siparis = Siparis::findOrFail($id);
        
        $siparis->update([
            'durum' => 'iptal_edildi',
            'odeme_durumu' => 'iptal_edildi',
            'notlar' => $siparis->notlar . "\n[" . now()->format('d.m.Y H:i') . "] İptal Nedeni: " . $request->iptal_nedeni
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Sipariş iptal edildi'
        ]);
    }

    /**
     * Kargo takip numarası ekleme
     */
    public function kargoEkle(Request $request, $id)
    {
        $request->validate([
            'kargo_firmasi' => 'required|string|max:100',
            'takip_no' => 'required|string|max:100'
        ]);

        $siparis = Siparis::findOrFail($id);
        
        $siparis->update([
            'durum' => 'kargoda',
            'notlar' => $siparis->notlar . "\n[" . now()->format('d.m.Y H:i') . "] Kargo Bilgisi: " . $request->kargo_firmasi . " - " . $request->takip_no
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kargo bilgisi eklendi'
        ]);
    }

    /**
     * Fatura oluşturma/güncelleme
     */
    public function faturaGuncelle(Request $request, $id)
    {
        $siparis = Siparis::findOrFail($id);
        $fatura = Fatura::where('siparis_id', $siparis->id)->first();

        if ($fatura) {
            $fatura->update([
                'e_fatura_gonderildi' => true,
                'e_fatura_tarih' => now()
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'E-Fatura gönderildi'
        ]);
    }

    /**
     * Bekleyen siparişler (bildirim için)
     */
    public function bekleyenSiparisler()
    {
        $bekleyenSiparisler = Siparis::with('user')
            ->where('durum', 'beklemede')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return response()->json([
            'count' => $bekleyenSiparisler->count(),
            'siparisler' => $bekleyenSiparisler
        ]);
    }
}