<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Kupon;
use App\Models\User;
use App\Models\KullaniciKupon;
use App\Models\Siparis;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OtomatikKuponAta extends Command
{
    protected $signature = 'kupon:otomatik-ata';
    protected $description = 'Kural bazlı kuponları uygun kullanıcılara otomatik atar';

    public function handle()
    {
        $this->info('Otomatik kupon atama başlatılıyor...');

        $kuponlar = Kupon::where('kupon_turu', 'kural_bazli')
            ->where('otomatik_ata', true)
            ->where('aktif', true)
            ->where('baslangic_tarihi', '<=', now())
            ->where('bitis_tarihi', '>=', now())
            ->get();

        if ($kuponlar->isEmpty()) {
            $this->info('Atanacak kural bazlı kupon bulunamadı.');
            return 0;
        }

        $toplamAtanan = 0;

        foreach ($kuponlar as $kupon) {
            $this->line("İşleniyor: {$kupon->baslik} ({$kupon->kupon_kodu})");
            
            $uygunKullanicilar = $this->uygunKullanicilariGetir($kupon);
            $atananSayisi = 0;

            foreach ($uygunKullanicilar as $userId) {
                // Daha önce atanmamışsa ata
                $atanmisMi = KullaniciKupon::where('user_id', $userId)
                    ->where('kupon_id', $kupon->id)
                    ->exists();

                if (!$atanmisMi) {
                    KullaniciKupon::create([
                        'user_id' => $userId,
                        'kupon_id' => $kupon->id,
                        'kullanildi' => false,
                        'atanma_tarihi' => now(),
                    ]);
                    $atananSayisi++;
                    $toplamAtanan++;
                }
            }

            $this->info("  ✓ {$atananSayisi} kullanıcıya atandı");
        }

        $this->info("Toplam {$toplamAtanan} kupon atandı.");
        return 0;
    }

    private function uygunKullanicilariGetir(Kupon $kupon)
    {
        $gunAraligi = $kupon->kural_gun_araligi ?? 30;
        $baslangicTarihi = Carbon::now()->subDays($gunAraligi);

        switch ($kupon->kural_tipi) {
            case 'toplam_alisveriş':
                // Toplam alışveriş tutarı kontrolü
                return User::select('users.id')
                    ->join('siparisler', 'users.id', '=', 'siparisler.user_id')
                    ->where('siparisler.odeme_durumu', 'odendi')
                    ->where('siparisler.created_at', '>=', $baslangicTarihi)
                    ->groupBy('users.id')
                    ->havingRaw('SUM(siparisler.toplam_tutar + siparisler.kdv_tutari - siparisler.indirim_tutari) >= ?', [$kupon->kural_min_tutar])
                    ->pluck('users.id')
                    ->toArray();

            case 'siparis_adedi':
                // Sipariş adedi kontrolü
                return User::select('users.id')
                    ->join('siparisler', 'users.id', '=', 'siparisler.user_id')
                    ->where('siparisler.odeme_durumu', 'odendi')
                    ->where('siparisler.created_at', '>=', $baslangicTarihi)
                    ->groupBy('users.id')
                    ->havingRaw('COUNT(siparisler.id) >= ?', [$kupon->kural_min_siparis])
                    ->pluck('users.id')
                    ->toArray();

            case 'tek_siparis_tutari':
                // En az bir siparişi belirtilen tutarı aşan kullanıcılar
                return User::select('users.id')
                    ->join('siparisler', 'users.id', '=', 'siparisler.user_id')
                    ->where('siparisler.odeme_durumu', 'odendi')
                    ->where('siparisler.created_at', '>=', $baslangicTarihi)
                    ->whereRaw('(siparisler.toplam_tutar + siparisler.kdv_tutari - siparisler.indirim_tutari) >= ?', [$kupon->kural_min_tutar])
                    ->distinct()
                    ->pluck('users.id')
                    ->toArray();

            default:
                return [];
        }
    }
}