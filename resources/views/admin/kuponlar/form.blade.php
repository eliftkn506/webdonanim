<style>
.rule-section {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 8px;
    margin-top: 1rem;
}
</style>

<div class="mb-3">
    <label>Kupon Kodu *</label>
    <input type="text" name="kupon_kodu" class="form-control" value="{{ $kupon->kupon_kodu ?? old('kupon_kodu') }}" required>
</div>

<div class="mb-3">
    <label>Başlık *</label>
    <input type="text" name="baslik" class="form-control" value="{{ $kupon->baslik ?? old('baslik') }}" required>
</div>

<div class="mb-3">
    <label>Açıklama</label>
    <textarea name="aciklama" class="form-control" rows="3">{{ $kupon->aciklama ?? old('aciklama') }}</textarea>
</div>

<!-- Kupon Türü -->
<div class="mb-3">
    <label>Kupon Türü *</label>
    <select name="kupon_turu" id="kuponTuru" class="form-control" required>
        <option value="genel" {{ (isset($kupon) && $kupon->kupon_turu=='genel') || old('kupon_turu')=='genel' ? 'selected' : '' }}>
            Genel (Herkes Kullanabilir)
        </option>
        <option value="kullanici_ozel" {{ (isset($kupon) && $kupon->kupon_turu=='kullanici_ozel') || old('kupon_turu')=='kullanici_ozel' ? 'selected' : '' }}>
            Kullanıcı Özel (Seçilen Kullanıcılar)
        </option>
        <option value="kural_bazli" {{ (isset($kupon) && $kupon->kupon_turu=='kural_bazli') || old('kupon_turu')=='kural_bazli' ? 'selected' : '' }}>
            Kural Bazlı (Koşullara Göre)
        </option>
    </select>
</div>

<!-- Kullanıcı Özel Seçenekleri -->
<div id="kullaniciOzelSection" class="rule-section" style="display: none;">
    <h6>Kupon Atanacak Kullanıcılar</h6>
    <div class="mb-3">
        <label>Kullanıcı Seç</label>
        <select name="secili_kullanicilar[]" id="kullaniciSelect" class="form-control" multiple size="10">
            @if(isset($atananKullanicilar))
                @foreach(\App\Models\User::whereIn('id', $atananKullanicilar)->get() as $user)
                    <option value="{{ $user->id }}" selected>{{ $user->name }} ({{ $user->email }})</option>
                @endforeach
            @endif
        </select>
        <small class="text-muted">Ctrl+Click ile birden fazla seçim yapabilirsiniz</small>
    </div>
    <div class="mb-3">
        <input type="text" id="kullaniciAra" class="form-control" placeholder="Kullanıcı ara...">
    </div>
</div>

<!-- Kural Bazlı Seçenekleri -->
<div id="kuralBazliSection" class="rule-section" style="display: none;">
    <h6>Kural Ayarları</h6>
    
    <div class="mb-3">
        <label>Kural Tipi *</label>
        <select name="kural_tipi" id="kuralTipi" class="form-control">
            <option value="">Seçiniz</option>
            <option value="toplam_alisveriş" {{ (isset($kupon) && $kupon->kural_tipi=='toplam_alisveriş') ? 'selected' : '' }}>
                Toplam Alışveriş Tutarı
            </option>
            <option value="siparis_adedi" {{ (isset($kupon) && $kupon->kural_tipi=='siparis_adedi') ? 'selected' : '' }}>
                Sipariş Adedi
            </option>
            <option value="tek_siparis_tutari" {{ (isset($kupon) && $kupon->kural_tipi=='tek_siparis_tutari') ? 'selected' : '' }}>
                Tek Sipariş Tutarı
            </option>
            <option value="belirli_kategori" {{ (isset($kupon) && $kupon->kural_tipi=='belirli_kategori') ? 'selected' : '' }}>
                Belirli Kategoriden Alışveriş
            </option>
            <option value="belirli_urun" {{ (isset($kupon) && $kupon->kural_tipi=='belirli_urun') ? 'selected' : '' }}>
                Belirli Ürün Satın Alma
            </option>
        </select>
    </div>

    <div class="mb-3">
        <label>Zaman Aralığı (Gün)</label>
        <input type="number" name="kural_gun_araligi" class="form-control" 
               value="{{ $kupon->kural_gun_araligi ?? old('kural_gun_araligi', 30) }}" 
               placeholder="Son X gün içinde">
        <small class="text-muted">Örn: 30 = Son 30 gün içinde</small>
    </div>

    <!-- Toplam Alışveriş / Tek Sipariş Tutarı -->
    <div id="minTutarField" style="display: none;">
        <div class="mb-3">
            <label>Minimum Tutar (₺) *</label>
            <input type="number" name="kural_min_tutar" class="form-control" step="0.01"
                   value="{{ $kupon->kural_min_tutar ?? old('kural_min_tutar') }}" 
                   placeholder="Örn: 50000">
        </div>
    </div>

    <!-- Sipariş Adedi -->
    <div id="minSiparisField" style="display: none;">
        <div class="mb-3">
            <label>Minimum Sipariş Adedi *</label>
            <input type="number" name="kural_min_siparis" class="form-control" 
                   value="{{ $kupon->kural_min_siparis ?? old('kural_min_siparis') }}" 
                   placeholder="Örn: 5">
        </div>
    </div>

    <!-- Kategori Seçimi -->
    <div id="kategoriField" style="display: none;">
        <div class="mb-3">
            <label>Hedef Kategoriler *</label>
            <select name="hedef_kategoriler[]" class="form-control" multiple size="8">
                @if(isset($kategoriler))
                    @foreach($kategoriler as $kategori)
                        <option value="{{ $kategori->id }}" 
                            {{ (isset($kupon) && $kupon->kural_hedefler && in_array($kategori->id, $kupon->kural_hedefler['kategoriler'] ?? [])) ? 'selected' : '' }}>
                            {{ $kategori->kategori_ad }}
                        </option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>

    <!-- Ürün Seçimi -->
    <div id="urunField" style="display: none;">
        <div class="mb-3">
            <label>Hedef Ürünler *</label>
            <select name="hedef_urunler[]" class="form-control" multiple size="8">
                @if(isset($urunler))
                    @foreach($urunler as $urun)
                        <option value="{{ $urun->id }}"
                            {{ (isset($kupon) && $kupon->kural_hedefler && in_array($urun->id, $kupon->kural_hedefler['urunler'] ?? [])) ? 'selected' : '' }}>
                            {{ $urun->urun_ad }}
                        </option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>

    <div class="mb-3 form-check">
        <input type="checkbox" name="otomatik_ata" class="form-check-input" value="1" 
               {{ (isset($kupon) && $kupon->otomatik_ata) ? 'checked' : '' }}>
        <label class="form-check-label">Otomatik Atama (Kurallara uyan kullanıcılara otomatik ata)</label>
    </div>
</div>

<div class="mb-3">
    <label>İndirim Tipi *</label>
    <select name="indirim_tipi" class="form-control" required>
        <option value="yuzde" {{ (isset($kupon) && $kupon->indirim_tipi=='yuzde') ? 'selected' : '' }}>Yüzde (%)</option>
        <option value="tutar" {{ (isset($kupon) && $kupon->indirim_tipi=='tutar') ? 'selected' : '' }}>Sabit Tutar (₺)</option>
    </select>
</div>

<div class="mb-3">
    <label>İndirim Miktarı *</label>
    <input type="number" name="indirim_miktari" class="form-control" step="0.01"
           value="{{ $kupon->indirim_miktari ?? old('indirim_miktari') }}" required>
</div>

<div class="mb-3">
    <label>Minimum Sipariş Tutarı (₺)</label>
    <input type="number" name="minimum_tutar" class="form-control" step="0.01"
           value="{{ $kupon->minimum_tutar ?? old('minimum_tutar') }}">
    <small class="text-muted">Kuponun kullanılabilmesi için gereken minimum sepet tutarı</small>
</div>

<div class="mb-3">
    <label>Kullanım Limiti</label>
    <input type="number" name="kullanim_limiti" class="form-control" 
           value="{{ $kupon->kullanim_limiti ?? old('kullanim_limiti') }}">
    <small class="text-muted">Boş bırakırsanız limitsiz olur</small>
</div>

<div class="mb-3">
    <label>Başlangıç Tarihi *</label>
    <input type="datetime-local" name="baslangic_tarihi" class="form-control" 
           value="{{ isset($kupon) ? $kupon->baslangic_tarihi->format('Y-m-d\TH:i') : old('baslangic_tarihi') }}" required>
</div>

<div class="mb-3">
    <label>Bitiş Tarihi *</label>
    <input type="datetime-local" name="bitis_tarihi" class="form-control" 
           value="{{ isset($kupon) ? $kupon->bitis_tarihi->format('Y-m-d\TH:i') : old('bitis_tarihi') }}" required>
</div>

<div class="mb-3 form-check">
    <input type="checkbox" name="aktif" class="form-check-input" value="1" 
           {{ (isset($kupon) && $kupon->aktif) ? 'checked' : '' }}>
    <label class="form-check-label">Aktif</label>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const kuponTuru = document.getElementById('kuponTuru');
    const kuralTipi = document.getElementById('kuralTipi');
    const kullaniciOzelSection = document.getElementById('kullaniciOzelSection');
    const kuralBazliSection = document.getElementById('kuralBazliSection');
    
    // Kupon türü değişimi
    kuponTuru.addEventListener('change', function() {
        kullaniciOzelSection.style.display = 'none';
        kuralBazliSection.style.display = 'none';
        
        if (this.value === 'kullanici_ozel') {
            kullaniciOzelSection.style.display = 'block';
        } else if (this.value === 'kural_bazli') {
            kuralBazliSection.style.display = 'block';
        }
    });
    
    // Kural tipi değişimi
    kuralTipi.addEventListener('change', function() {
        document.getElementById('minTutarField').style.display = 'none';
        document.getElementById('minSiparisField').style.display = 'none';
        document.getElementById('kategoriField').style.display = 'none';
        document.getElementById('urunField').style.display = 'none';
        
        if (this.value === 'toplam_alisveriş' || this.value === 'tek_siparis_tutari') {
            document.getElementById('minTutarField').style.display = 'block';
        } else if (this.value === 'siparis_adedi') {
            document.getElementById('minSiparisField').style.display = 'block';
        } else if (this.value === 'belirli_kategori') {
            document.getElementById('kategoriField').style.display = 'block';
        } else if (this.value === 'belirli_urun') {
            document.getElementById('urunField').style.display = 'block';
        }
    });
    
    // Sayfa yüklendiğinde mevcut değerlere göre göster
    kuponTuru.dispatchEvent(new Event('change'));
    if (kuralTipi.value) {
        kuralTipi.dispatchEvent(new Event('change'));
    }
    
    // Kullanıcı arama
    const kullaniciAra = document.getElementById('kullaniciAra');
    if (kullaniciAra) {
        let timeout = null;
        kullaniciAra.addEventListener('input', function() {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                araKullanici(this.value);
            }, 300);
        });
    }
});

function araKullanici(query) {
    if (query.length < 2) return;
    
    fetch(`/admin/kuponlar/kullanici-ara?q=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => {
            const select = document.getElementById('kullaniciSelect');
            const mevcutSecimler = Array.from(select.selectedOptions).map(opt => opt.value);
            
            // Mevcut seçimleri koru
            select.innerHTML = '';
            data.forEach(kullanici => {
                const option = document.createElement('option');
                option.value = kullanici.id;
                option.textContent = `${kullanici.name} (${kullanici.email})`;
                if (mevcutSecimler.includes(kullanici.id.toString())) {
                    option.selected = true;
                }
                select.appendChild(option);
            });
        })
        .catch(error => console.error('Arama hatası:', error));
}
</script>