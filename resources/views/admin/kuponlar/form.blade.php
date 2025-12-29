<div class="row">
    <div class="col-xl-8 col-lg-7">
        <div class="card mb-4">
            <div class="card-header border-bottom d-flex align-items-center">
                <i class='bx bx-info-circle me-2'></i>
                <h5 class="card-title mb-0">Temel Bilgiler</h5>
            </div>
            <div class="card-body pt-4">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Kupon Kodu <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class='bx bx-barcode'></i></span>
                            <input type="text" name="kupon_kodu" id="kuponKoduInput" class="form-control text-uppercase" 
                                   value="{{ $kupon->kupon_kodu ?? old('kupon_kodu') }}" 
                                   placeholder="YENI2025" required>
                            <button type="button" class="btn btn-outline-secondary" onclick="rastgeleKodOlustur()">
                                <i class='bx bx-refresh'></i>
                            </button>
                        </div>
                        <small class="text-muted">Benzersiz kupon kodu giriniz</small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Başlık <span class="text-danger">*</span></label>
                        <input type="text" name="baslik" class="form-control" 
                               value="{{ $kupon->baslik ?? old('baslik') }}" 
                               placeholder="Örn: Yeni Müşteri İndirimi" required>
                        <small class="text-muted">Müşterilere gösterilecek başlık</small>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Açıklama</label>
                    <textarea name="aciklama" class="form-control" rows="2" 
                              placeholder="Kupon detaylarını buraya yazabilirsiniz...">{{ $kupon->aciklama ?? old('aciklama') }}</textarea>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label fw-bold">İndirim Tipi <span class="text-danger">*</span></label>
                        <select name="indirim_tipi" id="indirimTipi" class="form-select" required>
                            <option value="yuzde" {{ (isset($kupon) && $kupon->indirim_tipi=='yuzde') || old('indirim_tipi')=='yuzde' ? 'selected' : '' }}>
                                Yüzde (%)
                            </option>
                            <option value="tutar" {{ (isset($kupon) && $kupon->indirim_tipi=='tutar') || old('indirim_tipi')=='tutar' ? 'selected' : '' }}>
                                Sabit Tutar (₺)
                            </option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">İndirim Miktarı <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" name="indirim_miktari" class="form-control" step="0.01" min="0"
                                   value="{{ $kupon->indirim_miktari ?? old('indirim_miktari') }}" 
                                   placeholder="10" required>
                            <span class="input-group-text" id="indirimSembol">%</span>
                        </div>
                    </div>
                    <div class="col-md-4" id="maksimumIndirimDiv">
                        <label class="form-label fw-bold">Maksimum İndirim</label>
                        <div class="input-group">
                            <span class="input-group-text">₺</span>
                            <input type="number" name="maksimum_indirim" class="form-control" step="0.01" min="0"
                                   value="{{ $kupon->maksimum_indirim ?? old('maksimum_indirim') }}" 
                                   placeholder="100">
                        </div>
                        <small class="text-muted">Yüzdelik indirimlerde max tutar</small>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Minimum Sepet Tutarı</label>
                        <div class="input-group">
                            <span class="input-group-text">₺</span>
                            <input type="number" name="minimum_tutar" class="form-control" step="0.01" min="0"
                                   value="{{ $kupon->minimum_tutar ?? old('minimum_tutar', 0) }}" 
                                   placeholder="0">
                        </div>
                        <small class="text-muted">Kuponu kullanmak için gereken min. tutar</small>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Toplam Kullanım</label>
                        <input type="number" name="kullanim_limiti" class="form-control" min="1"
                               value="{{ $kupon->kullanim_limiti ?? old('kullanim_limiti') }}" 
                               placeholder="Sınırsız">
                        <small class="text-muted">Tüm kullanımlar</small>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Kişi Başı <span class="text-danger">*</span></label>
                        <input type="number" name="kullanici_basina_limit" class="form-control" min="1"
                               value="{{ $kupon->kullanici_basina_limit ?? old('kullanici_basina_limit', 1) }}" 
                               placeholder="1" required>
                        <small class="text-muted">Her kişi kaç kez</small>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Başlangıç Tarihi <span class="text-danger">*</span></label>
                        <input type="datetime-local" name="baslangic_tarihi" class="form-control" 
                               value="{{ isset($kupon) ? $kupon->baslangic_tarihi->format('Y-m-d\TH:i') : old('baslangic_tarihi', now()->format('Y-m-d\TH:i')) }}" 
                               required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Bitiş Tarihi <span class="text-danger">*</span></label>
                        <input type="datetime-local" name="bitis_tarihi" class="form-control" 
                               value="{{ isset($kupon) ? $kupon->bitis_tarihi->format('Y-m-d\TH:i') : old('bitis_tarihi', now()->addMonth()->format('Y-m-d\TH:i')) }}" 
                               required>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header border-bottom d-flex align-items-center">
                <i class='bx bx-target-lock me-2'></i>
                <h5 class="card-title mb-0">Hedefleme (Opsiyonel)</h5>
            </div>
            <div class="card-body pt-4">
                <div class="alert alert-info mb-3">
                    <i class='bx bx-info-circle me-1'></i>
                    Bu kupon sadece seçili kategori/ürünler için geçerli olsun mu? Boş bırakırsanız tüm ürünler için geçerli olur.
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Hedef Kategoriler</label>
                        <select name="hedef_kategoriler[]" class="form-select" multiple size="5">
                            <option value="">Tüm Kategoriler</option>
                            @foreach($kategoriler as $kategori)
                                <option value="{{ $kategori->id }}" 
                                    {{ (isset($kupon) && in_array($kategori->id, $kupon->hedef_kategoriler ?? [])) ? 'selected' : '' }}>
                                    {{ $kategori->kategori_ad }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Ctrl ile çoklu seçim</small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Hedef Ürünler</label>
                        <select name="hedef_urunler[]" class="form-select" multiple size="5">
                            <option value="">Tüm Ürünler</option>
                            @foreach($urunler as $urun)
                                <option value="{{ $urun->id }}"
                                    {{ (isset($kupon) && in_array($urun->id, $kupon->hedef_urunler ?? [])) ? 'selected' : '' }}>
                                    {{ $urun->urun_ad }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Ctrl ile çoklu seçim</small>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Hariç Kategoriler</label>
                        <select name="haric_kategoriler[]" class="form-select" multiple size="5">
                            @foreach($kategoriler as $kategori)
                                <option value="{{ $kategori->id }}"
                                    {{ (isset($kupon) && in_array($kategori->id, $kupon->hariç_kategoriler ?? [])) ? 'selected' : '' }}>
                                    {{ $kategori->kategori_ad }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Bu kategoriler hariç</small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Hariç Ürünler</label>
                        <select name="haric_urunler[]" class="form-select" multiple size="5">
                            @foreach($urunler as $urun)
                                <option value="{{ $urun->id }}"
                                    {{ (isset($kupon) && in_array($urun->id, $kupon->hariç_urunler ?? [])) ? 'selected' : '' }}>
                                    {{ $urun->urun_ad }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Bu ürünler hariç</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-lg-5">
        <div class="card mb-4">
            <div class="card-header border-bottom bg-label-primary">
                <h5 class="card-title mb-0">
                    <i class='bx bx-group me-2'></i>Kupon Türü & Kurallar
                </h5>
            </div>
            <div class="card-body pt-4">
                
                <div class="mb-4">
                    <label class="form-label fw-bold">Kupon Türü <span class="text-danger">*</span></label>
                    <select name="kupon_turu" id="kuponTuru" class="form-select form-select-lg" required>
                        <option value="genel" {{ (isset($kupon) && $kupon->kupon_turu=='genel') || old('kupon_turu')=='genel' ? 'selected' : '' }}>
                            🌍 Genel - Herkes Kullanabilir
                        </option>
                        <option value="kullanici_ozel" {{ (isset($kupon) && $kupon->kupon_turu=='kullanici_ozel') || old('kupon_turu')=='kullanici_ozel' ? 'selected' : '' }}>
                            👤 Özel - Seçili Kullanıcılar
                        </option>
                        <option value="kural_bazli" {{ (isset($kupon) && $kupon->kupon_turu=='kural_bazli') || old('kupon_turu')=='kural_bazli' ? 'selected' : '' }}>
                            🤖 Kural Bazlı - Otomatik
                        </option>
                    </select>
                </div>

                <div id="kullaniciOzelSection" class="p-3 border rounded bg-light mb-3" style="display: none;">
                    <h6 class="fw-bold mb-3">
                        <i class='bx bx-user-check me-1'></i> Kullanıcı Seçimi
                    </h6>
                    
                    <div class="mb-3">
                        <input type="text" id="kullaniciAra" class="form-control" 
                               placeholder="🔍 İsim veya e-posta ara...">
                        <small class="text-muted">En az 2 karakter girin</small>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-bold">Seçili Kullanıcılar</label>
                        <div id="selectedUsers" class="mb-2" style="max-height: 200px; overflow-y: auto;">
                            @if(isset($atananKullanicilar) && $atananKullanicilar->count() > 0)
                                @foreach($atananKullanicilar as $user)
                                    <div class="badge bg-primary me-1 mb-1" data-user-id="{{ $user->id }}">
                                        {{ $user->name }}
                                        <i class='bx bx-x' onclick="kullaniciKaldir({{ $user->id }})" style="cursor: pointer;"></i>
                                        <input type="hidden" name="secili_kullanicilar[]" value="{{ $user->id }}">
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    <select id="kullaniciSelect" class="form-select" size="8" style="display: none;">
                        </select>
                </div>

                <div id="kuralBazliSection" class="p-3 border rounded bg-light mb-3" style="display: none;">
                    <h6 class="fw-bold mb-3">
                        <i class='bx bx-brain me-1'></i> Kural Şablonları
                    </h6>

                    <div class="mb-3">
                        <label class="form-label">Hazır Şablonlar</label>
                        <select id="kuralSablonu" class="form-select" onchange="sabloUygula(this.value)">
                            <option value="">-- Şablon Seçin --</option>
                            <option value="ilk_alisveris">🎉 İlk Alışveriş Yapanlar</option>
                            <option value="sadik_musteri">⭐ Sadık Müşteriler (1000₺+)</option>
                            <option value="cok_alisveris">🛒 Sık Alışveriş Yapanlar (5+)</option>
                            <option value="dogum_gunu">🎂 Doğum Günü Olanlar</option>
                            <option value="inaktif">😴 İnaktif Müşteriler (60+ gün)</option>
                        </select>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class='bx bx-calendar'></i> Zaman Aralığı
                        </label>
                        <div class="input-group">
                            <input type="number" name="kural_tarih_araligi" class="form-control" min="1"
                                   value="{{ $kupon->kural_kosullari['tarih_araligi'] ?? old('kural_tarih_araligi', 30) }}" 
                                   placeholder="30">
                            <span class="input-group-text">gün</span>
                        </div>
                        <small class="text-muted">Son X gündeki veriler</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class='bx bx-money'></i> Min. Toplam Alışveriş
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">₺</span>
                            <input type="number" name="kural_min_siparis_tutari" class="form-control" step="0.01" min="0"
                                   value="{{ $kupon->kural_kosullari['min_siparis_tutari'] ?? old('kural_min_siparis_tutari') }}" 
                                   placeholder="Opsiyonel">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class='bx bx-cart'></i> Min. Sipariş Adedi
                        </label>
                        <input type="number" name="kural_min_siparis_adedi" class="form-control" min="1"
                               value="{{ $kupon->kural_kosullari['min_siparis_adedi'] ?? old('kural_min_siparis_adedi') }}" 
                               placeholder="Opsiyonel">
                    </div>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="kural_ilk_alisveris" value="1" 
                               id="kuralIlkAlisveris"
                               {{ (isset($kupon) && isset($kupon->kural_kosullari['ilk_alisveris']) && $kupon->kural_kosullari['ilk_alisveris']) ? 'checked' : '' }}>
                        <label class="form-check-label" for="kuralIlkAlisveris">
                            <i class='bx bx-party'></i> İlk Alışveriş Yapacaklar
                        </label>
                    </div>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="kural_dogum_gunu" value="1" 
                               id="kuralDogumGunu"
                               {{ (isset($kupon) && isset($kupon->kural_kosullari['dogum_gunu']) && $kupon->kural_kosullari['dogum_gunu']) ? 'checked' : '' }}>
                        <label class="form-check-label" for="kuralDogumGunu">
                            <i class='bx bx-cake'></i> Doğum Günü Yaklaşanlar
                        </label>
                    </div>

                    <div id="dogumGunuAyarlari" class="ms-4 mb-3" style="display: none;">
                        <label class="form-label">Kaç gün öncesinden?</label>
                        <input type="number" name="kural_dogum_gunu_aralik" class="form-control" min="1" max="30"
                               value="{{ $kupon->kural_kosullari['dogum_gunu_aralik'] ?? old('kural_dogum_gunu_aralik', 7) }}" 
                               placeholder="7">
                    </div>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="kural_inaktif_musteri" value="1" 
                               id="kuralInaktif"
                               {{ (isset($kupon) && isset($kupon->kural_kosullari['inaktif_musteri']) && $kupon->kural_kosullari['inaktif_musteri']) ? 'checked' : '' }}>
                        <label class="form-check-label" for="kuralInaktif">
                            <i class='bx bx-time'></i> İnaktif Müşteriler
                        </label>
                    </div>

                    <div id="inaktifAyarlari" class="ms-4 mb-3" style="display: none;">
                        <label class="form-label">Kaç gündür alışveriş yapmayan?</label>
                        <input type="number" name="kural_inaktif_gun" class="form-control" min="1"
                               value="{{ $kupon->kural_kosullari['inaktif_gun'] ?? old('kural_inaktif_gun', 60) }}" 
                               placeholder="60">
                    </div>

                    <hr>

                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="otomatik_ata" value="1" 
                               id="otomatikAta" 
                               {{ (isset($kupon) && $kupon->otomatik_ata) || old('otomatik_ata') ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold" for="otomatikAta">
                            <i class='bx bx-run'></i> Otomatik Ata
                        </label>
                        <div><small class="text-muted">Kaydettiğinizde koşulları sağlayan kullanıcılara otomatik atanır</small></div>
                    </div>
                </div>

                <hr>

                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="aktif" value="1" id="statusSwitch" 
                           {{ (isset($kupon) && $kupon->aktif) || !isset($kupon) || old('aktif') ? 'checked' : '' }}>
                    <label class="form-check-label fw-bold" for="statusSwitch">
                        Kupon Aktif
                    </label>
                </div>

            </div>
        </div>

        @if(isset($kupon))
        <div class="card mb-4">
            <div class="card-header border-bottom bg-label-info">
                <h6 class="card-title mb-0">
                    <i class='bx bx-bar-chart me-1'></i> İstatistikler
                </h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Kullanılan:</span>
                    <strong>{{ $kupon->kullanilan_adet }} / {{ $kupon->kullanim_limiti ?? '∞' }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Toplam İndirim:</span>
                    <strong class="text-success">₺{{ number_format($kupon->toplam_indirim_tutari, 2) }}</strong>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Kullanan Kişi:</span>
                    <strong>{{ $kupon->toplam_kullanan_kisi }}</strong>
                </div>
                
                @if($kupon->son_atama_tarihi)
                <hr>
                <small class="text-muted">
                    Son Atama: {{ $kupon->son_atama_tarihi->diffForHumans() }}
                </small>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // İndirim tipi değişimi
    const indirimTipi = document.getElementById('indirimTipi');
    const indirimSembol = document.getElementById('indirimSembol');
    const maksimumIndirimDiv = document.getElementById('maksimumIndirimDiv');
    
    function indirimTipiDegisti() {
        if (indirimTipi.value === 'yuzde') {
            indirimSembol.textContent = '%';
            maksimumIndirimDiv.style.display = 'block';
        } else {
            indirimSembol.textContent = '₺';
            maksimumIndirimDiv.style.display = 'none';
        }
    }
    
    indirimTipi.addEventListener('change', indirimTipiDegisti);
    indirimTipiDegisti();

    // Kupon türü değişimi
    const kuponTuru = document.getElementById('kuponTuru');
    const userSection = document.getElementById('kullaniciOzelSection');
    const ruleSection = document.getElementById('kuralBazliSection');
    
    function kuponTuruDegisti() {
        const tur = kuponTuru.value;
        userSection.style.display = tur === 'kullanici_ozel' ? 'block' : 'none';
        ruleSection.style.display = tur === 'kural_bazli' ? 'block' : 'none';
    }
    
    kuponTuru.addEventListener('change', kuponTuruDegisti);
    kuponTuruDegisti();

    // Doğum günü ayarları
    const dogumGunuCheck = document.getElementById('kuralDogumGunu');
    const dogumGunuAyarlari = document.getElementById('dogumGunuAyarlari');
    
    if (dogumGunuCheck) {
        dogumGunuCheck.addEventListener('change', function() {
            dogumGunuAyarlari.style.display = this.checked ? 'block' : 'none';
        });
        if (dogumGunuCheck.checked) {
            dogumGunuAyarlari.style.display = 'block';
        }
    }

    // İnaktif müşteri ayarları
    const inaktifCheck = document.getElementById('kuralInaktif');
    const inaktifAyarlari = document.getElementById('inaktifAyarlari');
    
    if (inaktifCheck) {
        inaktifCheck.addEventListener('change', function() {
            inaktifAyarlari.style.display = this.checked ? 'block' : 'none';
        });
        if (inaktifCheck.checked) {
            inaktifAyarlari.style.display = 'block';
        }
    }

    // Kullanıcı arama (debounce)
    let aramaTimeout = null;
    const searchInput = document.getElementById('kullaniciAra');
    const selectBox = document.getElementById('kullaniciSelect');
    
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            clearTimeout(aramaTimeout);
            const query = e.target.value.trim();
            
            if (query.length < 2) {
                selectBox.style.display = 'none';
                return;
            }

            aramaTimeout = setTimeout(() => {
                // Not: Route ismini kendi sistemine göre güncellemelisin
                fetch(`{{ route('admin.kuponlar.kullanici-ara') }}?q=${encodeURIComponent(query)}`)
                    .then(res => res.json())
                    .then(data => {
                        selectBox.innerHTML = '';
                        
                        if (data.length === 0) {
                            selectBox.innerHTML = '<option disabled>Kullanıcı bulunamadı</option>';
                        } else {
                            data.forEach(user => {
                                // Zaten seçili mi kontrol et
                                const alreadySelected = document.querySelector(`[data-user-id="${user.id}"]`);
                                if (!alreadySelected) {
                                    const option = document.createElement('option');
                                    option.value = user.id;
                                    option.textContent = `${user.name} (${user.email})`;
                                    option.onclick = function() {
                                        kullaniciEkle(user.id, user.name);
                                    };
                                    selectBox.appendChild(option);
                                }
                            });
                        }
                        
                        selectBox.style.display = 'block';
                    })
                    .catch(err => {
                        console.error('Arama hatası:', err);
                    });
            }, 300);
        });
    }
});

// Kullanıcı ekle
function kullaniciEkle(userId, userName) {
    const selectedUsersDiv = document.getElementById('selectedUsers');
    
    // Zaten var mı kontrol et
    if (document.querySelector(`[data-user-id="${userId}"]`)) {
        return;
    }
    
    const badge = document.createElement('div');
    badge.className = 'badge bg-primary me-1 mb-1';
    badge.setAttribute('data-user-id', userId);
    badge.innerHTML = `
        ${userName}
        <i class='bx bx-x' onclick="kullaniciKaldir(${userId})" style="cursor: pointer;"></i>
        <input type="hidden" name="secili_kullanicilar[]" value="${userId}">
    `;
    
    selectedUsersDiv.appendChild(badge);
    document.getElementById('kullaniciAra').value = '';
    document.getElementById('kullaniciSelect').style.display = 'none';
}

// Kullanıcı kaldır
function kullaniciKaldir(userId) {
    const badge = document.querySelector(`[data-user-id="${userId}"]`);
    if (badge) {
        badge.remove();
    }
}

// Rastgele kod oluştur
function rastgeleKodOlustur() {
    const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
    let kod = '';
    for (let i = 0; i < 8; i++) {
        kod += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    document.getElementById('kuponKoduInput').value = kod;
}

// Şablon uygula
function sabloUygula(sablon) {
    if (!sablon) return;

    // Tüm inputları temizle
    document.querySelector('[name="kural_min_siparis_tutari"]').value = '';
    document.querySelector('[name="kural_min_siparis_adedi"]').value = '';
    document.getElementById('kuralIlkAlisveris').checked = false;
    document.getElementById('kuralDogumGunu').checked = false;
    document.getElementById('kuralInaktif').checked = false;
    
    document.getElementById('dogumGunuAyarlari').style.display = 'none';
    document.getElementById('inaktifAyarlari').style.display = 'none';

    switch(sablon) {
        case 'ilk_alisveris':
            document.getElementById('kuralIlkAlisveris').checked = true;
            document.querySelector('[name="kural_tarih_araligi"]').value = 365;
            break;
            
        case 'sadik_musteri':
            document.querySelector('[name="kural_min_siparis_tutari"]').value = 1000;
            document.querySelector('[name="kural_tarih_araligi"]').value = 90;
            break;
            
        case 'cok_alisveris':
            document.querySelector('[name="kural_min_siparis_adedi"]').value = 5;
            document.querySelector('[name="kural_tarih_araligi"]').value = 60;
            break;
            
        case 'dogum_gunu':
            document.getElementById('kuralDogumGunu').checked = true;
            document.getElementById('dogumGunuAyarlari').style.display = 'block';
            document.querySelector('[name="kural_dogum_gunu_aralik"]').value = 7;
            document.querySelector('[name="kural_tarih_araligi"]').value = 365;
            break;
            
        case 'inaktif':
            document.getElementById('kuralInaktif').checked = true;
            document.getElementById('inaktifAyarlari').style.display = 'block';
            document.querySelector('[name="kural_inaktif_gun"]').value = 60;
            document.querySelector('[name="kural_tarih_araligi"]').value = 365;
            break;
    }
}
</script>