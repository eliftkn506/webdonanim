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
        $query = Siparis::with(['user', 'urunler.urun']);

        // Durum filtresi
        if ($request->filled('durum')) {
            $query->where('durum', $request->durum);
        }

        // Ödeme durumu filtresi
        if ($request->filled('odeme_durumu')) {
            $query->where('odeme_durumu', $request->odeme_durumu);
        }

        // Tarih filtresi
        if ($request->filled('tarih_baslangic')) {
            $query->whereDate('created_at', '>=', $request->tarih_baslangic);
        }
        if ($request->filled('tarih_bitis')) {
            $query->whereDate('created_at', '<=', $request->tarih_bitis);
        }

        // Arama
        if ($request->filled('arama')) {
            $query->where(function($q) use ($request) {
                $q->where('siparis_no', 'like', '%' . $request->arama . '%')
                  ->orWhereHas('user', function($qu) use ($request) {
                      $qu->where('name', 'like', '%' . $request->arama . '%')
                         ->orWhere('email', 'like', '%' . $request->arama . '%');
                  });
            });
        }

        $siparisler = $query->orderBy('created_at', 'desc')->paginate(15);

        // İstatistikler
        $istatistikler = [
            'bekleyen' => Siparis::where('durum', 'beklemede')->count(),
            'onaylanan' => Siparis::where('durum', 'onaylandi')->count(),
            'kargoda' => Siparis::where('durum', 'kargoda')->count(),
            'teslim_edilen' => Siparis::where('durum', 'teslim_edildi')->count(),
            'iptal_edilen' => Siparis::where('durum', 'iptal_edildi')->count(),
            'bugun_toplam' => Siparis::whereDate('created_at', today())->sum(DB::raw('toplam_tutar + kdv_tutari - indirim_tutari')),
            'bugun_adet' => Siparis::whereDate('created_at', today())->count(),
        ];

        return view('admin.siparisler.index', compact('siparisler', 'istatistikler'));
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