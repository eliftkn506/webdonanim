<div class="row">
    <div class="col-xl-7 col-lg-6">
        <div class="card mb-4">
            <div class="card-header border-bottom">
                <h5 class="card-title mb-0">Temel Bilgiler</h5>
            </div>
            <div class="card-body pt-4">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Kupon Kodu <span class="text-danger">*</span></label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="bx bx-barcode"></i></span>
                            <input type="text" name="kupon_kodu" class="form-control text-uppercase" 
                                   value="{{ $kupon->kupon_kodu ?? old('kupon_kodu') }}" placeholder="YAZ2025" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Başlık <span class="text-danger">*</span></label>
                        <input type="text" name="baslik" class="form-control" 
                               value="{{ $kupon->baslik ?? old('baslik') }}" placeholder="Yaz İndirimi Fırsatı" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Açıklama</label>
                    <textarea name="aciklama" class="form-control" rows="2" placeholder="Kupon detayları...">{{ $kupon->aciklama ?? old('aciklama') }}</textarea>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">İndirim Tipi <span class="text-danger">*</span></label>
                        <select name="indirim_tipi" class="form-select">
                            <option value="yuzde" {{ (isset($kupon) && $kupon->indirim_tipi=='yuzde') ? 'selected' : '' }}>Yüzde (%)</option>
                            <option value="tutar" {{ (isset($kupon) && $kupon->indirim_tipi=='tutar') ? 'selected' : '' }}>Sabit Tutar (₺)</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">İndirim Miktarı <span class="text-danger">*</span></label>
                        <input type="number" name="indirim_miktari" class="form-control" step="0.01" 
                               value="{{ $kupon->indirim_miktari ?? old('indirim_miktari') }}" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Min. Sepet Tutarı</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text">₺</span>
                            <input type="number" name="minimum_tutar" class="form-control" step="0.01" 
                                   value="{{ $kupon->minimum_tutar ?? old('minimum_tutar') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Kullanım Limiti</label>
                        <input type="number" name="kullanim_limiti" class="form-control" 
                               value="{{ $kupon->kullanim_limiti ?? old('kullanim_limiti') }}" placeholder="Sınırsız için boş bırakın">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Başlangıç Tarihi</label>
                        <input type="datetime-local" name="baslangic_tarihi" class="form-control" 
                               value="{{ isset($kupon) ? $kupon->baslangic_tarihi->format('Y-m-d\TH:i') : old('baslangic_tarihi') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Bitiş Tarihi</label>
                        <input type="datetime-local" name="bitis_tarihi" class="form-control" 
                               value="{{ isset($kupon) ? $kupon->bitis_tarihi->format('Y-m-d\TH:i') : old('bitis_tarihi') }}" required>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-5 col-lg-6">
        <div class="card mb-4">
            <div class="card-header border-bottom bg-label-secondary">
                <h5 class="card-title mb-0">Hedefleme & Kurallar</h5>
            </div>
            <div class="card-body pt-4">
                
                <div class="mb-3">
                    <label class="form-label">Kupon Türü <span class="text-danger">*</span></label>
                    <select name="kupon_turu" id="kuponTuru" class="form-select">
                        <option value="genel" {{ (isset($kupon) && $kupon->kupon_turu=='genel') ? 'selected' : '' }}>Genel (Herkese Açık)</option>
                        <option value="kullanici_ozel" {{ (isset($kupon) && $kupon->kupon_turu=='kullanici_ozel') ? 'selected' : '' }}>Özel (Seçili Kişiler)</option>
                        <option value="kural_bazli" {{ (isset($kupon) && $kupon->kupon_turu=='kural_bazli') ? 'selected' : '' }}>Kural Bazlı (Otomatik)</option>
                    </select>
                </div>

                <div id="kullaniciOzelSection" class="p-3 border rounded bg-light mb-3" style="display: none;">
                    <label class="form-label fw-bold">Kullanıcı Seçimi</label>
                    <input type="text" id="kullaniciAra" class="form-control mb-2" placeholder="İsim veya e-posta ara...">
                    <select name="secili_kullanicilar[]" id="kullaniciSelect" class="form-select" multiple size="6">
                        @if(isset($atananKullanicilar))
                            @foreach(\App\Models\User::whereIn('id', $atananKullanicilar)->get() as $user)
                                <option value="{{ $user->id }}" selected>{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        @endif
                    </select>
                    <small class="text-muted d-block mt-1">Birden fazla seçim için Ctrl tuşuna basılı tutun.</small>
                </div>

                <div id="kuralBazliSection" class="p-3 border rounded bg-light mb-3" style="display: none;">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Kural Tipi</label>
                        <select name="kural_tipi" id="kuralTipi" class="form-select">
                            <option value="">Kural Seçiniz...</option>
                            <option value="toplam_alisveriş" {{ (isset($kupon) && $kupon->kural_tipi=='toplam_alisveriş') ? 'selected' : '' }}>Toplam Alışveriş Tutarı</option>
                            <option value="siparis_adedi" {{ (isset($kupon) && $kupon->kural_tipi=='siparis_adedi') ? 'selected' : '' }}>Sipariş Adedi</option>
                            <option value="tek_siparis_tutari" {{ (isset($kupon) && $kupon->kural_tipi=='tek_siparis_tutari') ? 'selected' : '' }}>Tek Seferde Harcama</option>
                            <option value="belirli_kategori" {{ (isset($kupon) && $kupon->kural_tipi=='belirli_kategori') ? 'selected' : '' }}>Kategori Alışverişi</option>
                            <option value="belirli_urun" {{ (isset($kupon) && $kupon->kural_tipi=='belirli_urun') ? 'selected' : '' }}>Ürün Satın Alma</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Zaman Aralığı (Gün)</label>
                        <input type="number" name="kural_gun_araligi" class="form-control" 
                               value="{{ $kupon->kural_gun_araligi ?? 30 }}" placeholder="30">
                        <small class="text-muted">Son kaç gündeki veriler baz alınsın?</small>
                    </div>

                    <div id="dynamicRuleFields">
                        <div class="mb-3 rule-field" data-type="amount">
                            <label class="form-label">Min. Tutar (₺)</label>
                            <input type="number" name="kural_min_tutar" class="form-control" step="0.01" value="{{ $kupon->kural_min_tutar ?? '' }}">
                        </div>
                        <div class="mb-3 rule-field" data-type="count" style="display:none;">
                            <label class="form-label">Min. Adet</label>
                            <input type="number" name="kural_min_siparis" class="form-control" value="{{ $kupon->kural_min_siparis ?? '' }}">
                        </div>
                        <div class="mb-3 rule-field" data-type="category" style="display:none;">
                            <label class="form-label">Kategoriler</label>
                            <select name="hedef_kategoriler[]" class="form-select" multiple size="4">
                                @foreach($kategoriler as $kategori)
                                    <option value="{{ $kategori->id }}" 
                                        {{ (isset($kupon) && isset($kupon->kural_hedefler['kategoriler']) && in_array($kategori->id, $kupon->kural_hedefler['kategoriler'])) ? 'selected' : '' }}>
                                        {{ $kategori->kategori_ad }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3 rule-field" data-type="product" style="display:none;">
                            <label class="form-label">Ürünler</label>
                            <select name="hedef_urunler[]" class="form-select" multiple size="4">
                                @foreach($urunler as $urun)
                                    <option value="{{ $urun->id }}"
                                        {{ (isset($kupon) && isset($kupon->kural_hedefler['urunler']) && in_array($urun->id, $kupon->kural_hedefler['urunler'])) ? 'selected' : '' }}>
                                        {{ $urun->urun_ad }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-check mt-3">
                        <input class="form-check-input" type="checkbox" name="otomatik_ata" value="1" id="autoAssign" 
                               {{ (isset($kupon) && $kupon->otomatik_ata) ? 'checked' : '' }}>
                        <label class="form-check-label" for="autoAssign">
                            Uygun kullanıcılara otomatik tanımla
                        </label>
                    </div>
                </div>

                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="aktif" value="1" id="statusSwitch" 
                           {{ (isset($kupon) && $kupon->aktif) || !isset($kupon) ? 'checked' : '' }}>
                    <label class="form-check-label" for="statusSwitch">Kupon Aktif</label>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const kuponTuru = document.getElementById('kuponTuru');
    const kuralTipi = document.getElementById('kuralTipi');
    const userSection = document.getElementById('kullaniciOzelSection');
    const ruleSection = document.getElementById('kuralBazliSection');
    
    // Kupon Türü Değişimi
    function toggleSections() {
        const val = kuponTuru.value;
        userSection.style.display = val === 'kullanici_ozel' ? 'block' : 'none';
        ruleSection.style.display = val === 'kural_bazli' ? 'block' : 'none';
    }
    kuponTuru.addEventListener('change', toggleSections);
    toggleSections(); // Init

    // Kural Tipi Değişimi
    function toggleRuleFields() {
        const type = kuralTipi.value;
        const fields = document.querySelectorAll('.rule-field');
        fields.forEach(f => f.style.display = 'none');

        if(['toplam_alisveriş', 'tek_siparis_tutari'].includes(type)) {
            document.querySelector('[data-type="amount"]').style.display = 'block';
        } else if (type === 'siparis_adedi') {
            document.querySelector('[data-type="count"]').style.display = 'block';
        } else if (type === 'belirli_kategori') {
            document.querySelector('[data-type="category"]').style.display = 'block';
        } else if (type === 'belirli_urun') {
            document.querySelector('[data-type="product"]').style.display = 'block';
        }
    }
    kuralTipi.addEventListener('change', toggleRuleFields);
    toggleRuleFields(); // Init

    // Kullanıcı Arama (Debounce)
    let timeout = null;
    const searchInput = document.getElementById('kullaniciAra');
    const selectBox = document.getElementById('kullaniciSelect');

    if(searchInput) {
        searchInput.addEventListener('input', function(e) {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                const query = e.target.value;
                if(query.length < 2) return;

                fetch(`/admin/kuponlar/kullanici-ara?q=${encodeURIComponent(query)}`)
                    .then(res => res.json())
                    .then(data => {
                        // Mevcut seçili olanları sakla
                        const selectedValues = Array.from(selectBox.selectedOptions).map(opt => ({
                            val: opt.value, 
                            text: opt.text
                        }));
                        
                        selectBox.innerHTML = '';
                        
                        // Seçilileri geri ekle
                        selectedValues.forEach(item => {
                            const opt = new Option(item.text, item.val, true, true);
                            selectBox.add(opt);
                        });

                        // Yeni sonuçları ekle (zaten ekli değilse)
                        data.forEach(user => {
                            if(!selectedValues.some(sv => sv.val == user.id)) {
                                const text = `${user.name} (${user.email})`;
                                const opt = new Option(text, user.id);
                                selectBox.add(opt);
                            }
                        });
                    });
            }, 300);
        });
    }
});
</script>