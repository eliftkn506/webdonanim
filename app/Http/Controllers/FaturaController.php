<?php

namespace App\Http\Controllers;

use App\Models\Fatura;
use App\Models\Siparis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf; // Doğru import

class FaturaController extends Controller
{
    // Fatura görüntüleme
    public function goster($siparis_id)
    {
        $siparis = Siparis::with('urunler.urun')->findOrFail($siparis_id);
        
        
        
        $fatura = Fatura::where('siparis_id', $siparis->id)->first();

        if (!$fatura) {
            // Fatura yoksa otomatik oluştur
            $fatura = $this->otomatikFaturaOlustur($siparis);
        }

        return view('kullanici.fatura', compact('fatura', 'siparis'));
    }

    // PDF fatura indirme
    public function pdfIndir($siparis_id)
    {
        $siparis = Siparis::with('urunler.urun')->findOrFail($siparis_id);
        
        
        
        $fatura = Fatura::where('siparis_id', $siparis->id)->first();

        if (!$fatura) {
            $fatura = $this->otomatikFaturaOlustur($siparis);
        }

        try {
            $pdf = Pdf::loadView('pdf.fatura', compact('fatura', 'siparis'))
                ->setPaper('a4', 'portrait')
                ->setOptions([
                    'dpi' => 150,
                    'defaultFont' => 'DejaVu Sans',
                    'isRemoteEnabled' => true,
                    'isPhpEnabled' => false,
                    'isHtml5ParserEnabled' => true,
                    'fontHeightRatio' => 1.1,
                ]);

            $filename = 'Fatura_' . $fatura->fatura_no . '.pdf';
            
            return $pdf->download($filename);
            
        } catch (\Exception $e) {
            return back()->with('error', 'PDF oluşturulurken hata oluştu: ' . $e->getMessage());
        }
    }

    // PDF fatura önizleme (tarayıcıda açma)
    public function pdfOnizle($siparis_id)
    {
        $siparis = Siparis::with('urunler.urun')->findOrFail($siparis_id);
        
        // Kullanıcı kontrolü
       
        
        $fatura = Fatura::where('siparis_id', $siparis->id)->first();

        if (!$fatura) {
            $fatura = $this->otomatikFaturaOlustur($siparis);
        }

        try {
            $pdf = Pdf::loadView('pdf.fatura', compact('fatura', 'siparis'))
                ->setPaper('a4', 'portrait')
                ->setOptions([
                    'dpi' => 150,
                    'defaultFont' => 'DejaVu Sans',
                    'isRemoteEnabled' => true,
                ]);

            return $pdf->stream('Fatura_' . $fatura->fatura_no . '.pdf');
            
        } catch (\Exception $e) {
            return back()->with('error', 'PDF oluşturulurken hata oluştu: ' . $e->getMessage());
        }
    }

    // HTML fatura görüntüleme (debug için)
    public function htmlOnizle($siparis_id)
    {
        $siparis = Siparis::with('urunler.urun')->findOrFail($siparis_id);
        
        
        
        $fatura = Fatura::where('siparis_id', $siparis->id)->first();

        if (!$fatura) {
            $fatura = $this->otomatikFaturaOlustur($siparis);
        }

        // Debug için HTML olarak göster
        return view('pdf.fatura', compact('fatura', 'siparis'));
    }

    // Otomatik fatura oluşturma
    private function otomatikFaturaOlustur($siparis)
    {
        return Fatura::create([
            'siparis_id' => $siparis->id,
            'fatura_no' => 'FTR-' . date('Y') . '-' . str_pad($siparis->id, 6, '0', STR_PAD_LEFT),
            'unvan' => $siparis->ad_soyad ?? $siparis->user->name,
            'vergi_dairesi' => null,
            'vergi_no' => null,
            'tc_kimlik_no' => null,
            'fatura_adresi' => $siparis->fatura_adresi,
            'ara_toplam' => $siparis->toplam_tutar,
            'kdv_tutari' => $siparis->kdv_tutari,
            'genel_toplam' => $siparis->toplam_tutar + $siparis->kdv_tutari,
        ]);
    }

    // E-fatura entegrasyonu için (gelecekte eklenebilir)
    public function eFaturaGonder($siparis_id)
    {
        // E-fatura servis sağlayıcısı entegrasyonu
        // Örnek: Foriba, Logo, vs.
        
        return response()->json([
            'success' => true,
            'message' => 'E-fatura gönderildi'
        ]);
    }

    // Toplu fatura işlemleri
    public function topluPdfIndir(Request $request)
    {
        $siparis_ids = $request->input('siparis_ids', []);
        
        if (empty($siparis_ids)) {
            return back()->with('error', 'Hiçbir sipariş seçilmedi.');
        }

        $siparisler = Siparis::with('urunler.urun')
            ->where('user_id', Auth::id())
            ->whereIn('id', $siparis_ids)
            ->get();

        if ($siparisler->isEmpty()) {
            return back()->with('error', 'Seçilen siparişler bulunamadı.');
        }

        $zip = new \ZipArchive();
        $zipFileName = 'Faturalar_' . date('Y-m-d_H-i-s') . '.zip';
        $zipPath = storage_path('app/temp/' . $zipFileName);

        // Temp dizinini oluştur
        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }

        if ($zip->open($zipPath, \ZipArchive::CREATE) === TRUE) {
            foreach ($siparisler as $siparis) {
                $fatura = Fatura::where('siparis_id', $siparis->id)->first();
                
                if (!$fatura) {
                    $fatura = $this->otomatikFaturaOlustur($siparis);
                }

                $pdf = Pdf::loadView('pdf.fatura', compact('fatura', 'siparis'))
                    ->setPaper('a4', 'portrait');

                $pdfContent = $pdf->output();
                $zip->addFromString('Fatura_' . $fatura->fatura_no . '.pdf', $pdfContent);
            }
            $zip->close();

            return response()->download($zipPath)->deleteFileAfterSend();
        } else {
            return back()->with('error', 'ZIP dosyası oluşturulamadı.');
        }
    }
}