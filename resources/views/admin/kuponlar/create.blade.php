@extends('layouts.admin')
@section('title', 'Yeni Kupon Ekle')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">
            <span class="text-muted fw-light">Kuponlar /</span> Yeni Kupon
        </h4>
        <a href="{{ route('admin.kuponlar.index') }}" class="btn btn-outline-secondary">
            <i class='bx bx-arrow-back me-1'></i> İptal
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('admin.kuponlar.store') }}" method="POST">
        @csrf
        
        <div class="row">
            <div class="col-xl-8 col-lg-7">
                
                <div class="card mb-4">
                    <div class="card-header border-bottom d-flex align-items-center">
                        <i class='bx bx-info-circle me-2 fs-4 text-primary'></i>
                        <h5 class="card-title mb-0">Temel Bilgiler</h5>
                    </div>
                    <div class="card-body pt-4">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Kupon Kodu <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class='bx bx-barcode'></i></span>
                                    <input type="text" name="kupon_kodu" id="kuponKoduInput" class="form-control text-uppercase" 
                                           value="{{ old('kupon_kodu') }}" placeholder="YENI2025" required>
                                    <button type="button" class="btn btn-outline-secondary" onclick="rastgeleKodOlustur()">
                                        <i class='bx bx-refresh'></i>
                                    </button>
                                </div>
                                <div class="form-text">Benzersiz bir kod giriniz veya oluşturunuz.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Başlık <span class="text-danger">*</span></label>
                                <input type="text" name="baslik" class="form-control" 
                                       value="{{ old('baslik') }}" placeholder="Örn: Yeni Müşteri İndirimi" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Açıklama</label>
                            <textarea name="aciklama" class="form-control" rows="2" 
                                      placeholder="Kupon detaylarını buraya yazabilirsiniz...">{{ old('aciklama') }}</textarea>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">İndirim Tipi <span class="text-danger">*</span></label>
                                <select name="indirim_tipi" id="indirimTipi" class="form-select" required>
                                    <option value="yuzde" {{ old('indirim_tipi')=='yuzde' ? 'selected' : '' }}>Yüzde (%)</option>
                                    <option value="tutar" {{ old('indirim_tipi')=='tutar' ? 'selected' : '' }}>Sabit Tutar (₺)</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">İndirim Miktarı <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" name="indirim_miktari" class="form-control" step="0.01" min="0"
                                           value="{{ old('indirim_miktari') }}" placeholder="10" required>
                                    <span class="input-group-text" id="indirimSembol">%</span>
                                </div>
                            </div>
                            <div class="col-md-4" id="maksimumIndirimDiv">
                                <label class="form-label fw-bold">Maksimum İndirim Tutarı</label>
                                <div class="input-group">
                                    <span class="input-group-text">₺</span>
                                    <input type="number" name="maksimum_indirim" class="form-control" step="0.01" min="0"
                                           value="{{ old('maksimum_indirim') }}" placeholder="100">
                                </div>
                                <div class="form-text">Sadece yüzdelik indirimler için.</div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Minimum Sepet Tutarı</label>
                                <div class="input-group">
                                    <span class="input-group-text">₺</span>
                                    <input type="number" name="minimum_tutar" class="form-control" step="0.01" min="0"
                                           value="{{ old('minimum_tutar', 0) }}" placeholder="0">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Toplam Limit</label>
                                <input type="number" name="kullanim_limiti" class="form-control" min="1"
                                       value="{{ old('kullanim_limiti') }}" placeholder="Sınırsız">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Kişi Başı Limit <span class="text-danger">*</span></label>
                                <input type="number" name="kullanici_basina_limit" class="form-control" min="1"
                                       value="{{ old('kullanici_basina_limit', 1) }}" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Başlangıç Tarihi <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="baslangic_tarihi" class="form-control" 
                                       value="{{ old('baslangic_tarihi', now()->format('Y-m-d\TH:i')) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Bitiş Tarihi <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="bitis_tarihi" class="form-control" 
                                       value="{{ old('bitis_tarihi', now()->addMonth()->format('Y-m-d\TH:i')) }}" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header border-bottom d-flex align-items-center">
                        <i class='bx bx-target-lock me-2 fs-4 text-warning'></i>
                        <h5 class="card-title mb-0">Hedefleme (Opsiyonel)</h5>
                    </div>
                    <div class="card-body pt-4">
                        <div class="alert alert-info d-flex align-items-center" role="alert">
                            <i class='bx bx-info-circle me-2'></i>
                            <div>
                                Bu alanları boş bırakırsanız kupon <strong>tüm ürünlerde</strong> geçerli olur.
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Geçerli Kategoriler</label>
                                <select name="hedef_kategoriler[]" class="form-select" multiple size="5">
                                    <option value="" disabled>-- Seçiniz --</option>
                                    @foreach($kategoriler as $kategori)
                                        <option value="{{ $kategori->id }}">{{ $kategori->kategori_ad }}</option>
                                    @endforeach
                                </select>
                                <div class="form-text">Çoklu seçim için CTRL tuşuna basılı tutun.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Geçerli Ürünler</label>
                                <select name="hedef_urunler[]" class="form-select" multiple size="5">
                                    <option value="" disabled>-- Seçiniz --</option>
                                    @foreach($urunler as $urun)
                                        <option value="{{ $urun->id }}">{{ $urun->urun_ad }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Hariç Tutulan Kategoriler</label>
                                <select name="haric_kategoriler[]" class="form-select" multiple size="5">
                                    @foreach($kategoriler as $kategori)
                                        <option value="{{ $kategori->id }}">{{ $kategori->kategori_ad }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Hariç Tutulan Ürünler</label>
                                <select name="haric_urunler[]" class="form-select" multiple size="5">
                                    @foreach($urunler as $urun)
                                        <option value="{{ $urun->id }}">{{ $urun->urun_ad }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-lg-5">
                <div class="card mb-4">
                    <div class="card-header border-bottom bg-label-primary">
                        <h5 class="card-title mb-0">
                            <i class='bx bx-slider-alt me-2'></i>Kupon Ayarları
                        </h5>
                    </div>
                    <div class="card-body pt-4">
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">Kupon Türü <span class="text-danger">*</span></label>
                            <select name="kupon_turu" id="kuponTuru" class="form-select form-select-lg" required>
                                <option value="genel" {{ old('kupon_turu')=='genel' ? 'selected' : '' }}>
                                    🌍 Genel - Herkes Kullanabilir
                                </option>
                                <option value="kullanici_ozel" {{ old('kupon_turu')=='kullanici_ozel' ? 'selected' : '' }}>
                                    👤 Özel - Seçili Kullanıcılar
                                </option>
                                <option value="kural_bazli" {{ old('kupon_turu')=='kural_bazli' ? 'selected' : '' }}>
                                    🤖 Kural Bazlı - Otomatik
                                </option>
                            </select>
                        </div>

                        <div id="kullaniciOzelSection" class="p-3 border rounded bg-light mb-3" style="display: none;">
                            <h6 class="fw-bold mb-3 text-warning"><i class='bx bx-user-check me-1'></i> Kullanıcı Seçimi</h6>
                            <div class="mb-3">
                                <input type="text" id="kullaniciAra" class="form-control" placeholder="🔍 İsim veya e-posta ara...">
                                <div class="form-text text-muted">Aramak için en az 2 harf girin.</div>
                            </div>
                            <div class="mb-2">
                                <label class="form-label fw-bold small text-uppercase">Seçili Kullanıcılar:</label>
                                <div id="selectedUsers" class="d-flex flex-wrap gap-2 p-2 border rounded bg-white" style="min-height: 50px; max-height: 200px; overflow-y: auto;">
                                    <span class="text-muted small fst-italic w-100 text-center mt-2" id="noUserSelected">Henüz kullanıcı seçilmedi.</span>
                                </div>
                            </div>
                            <select id="kullaniciSelect" class="form-select" size="5" style="display: none;"></select>
                        </div>

                        <div id="kuralBazliSection" style="display: none;">
                            <div class="divider text-start">
                                <div class="divider-text fw-bold text-primary"><i class='bx bx-brain'></i> Kural Şablonları</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted small fw-bold">HAZIR ŞABLONLAR</label>
                                <select id="kuralSablonu" class="form-select" onchange="sabloUygula(this.value)">
                                    <option value="">-- Şablon Seçin --</option>
                                    <option value="ilk_alisveris">🎉 İlk Alışveriş Yapanlar</option>
                                    <option value="sadik_musteri">⭐ Sadık Müşteriler (1000₺+)</option>
                                    <option value="cok_alisveris">🛒 Sık Alışveriş Yapanlar (5+)</option>
                                    <option value="dogum_gunu">🎂 Doğum Günü Olanlar</option>
                                    <option value="inaktif">😴 İnaktif Müşteriler (60+ gün)</option>
                                </select>
                            </div>

                            <hr class="my-3">

                            <div class="mb-3">
                                <label class="form-label fw-bold text-dark"><i class='bx bx-calendar'></i> ZAMAN ARALIĞI</label>
                                <div class="input-group">
                                    <input type="number" name="kural_tarih_araligi" class="form-control" 
                                           value="{{ old('kural_tarih_araligi', 30) }}" placeholder="30">
                                    <span class="input-group-text">gün</span>
                                </div>
                                <div class="form-text" style="font-size: 0.75rem">Son X gündeki veriler taranır</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold text-dark"><i class='bx bx-money'></i> MİN. TOPLAM ALIŞVERİŞ</label>
                                <div class="input-group">
                                    <span class="input-group-text">₺</span>
                                    <input type="number" name="kural_min_siparis_tutari" class="form-control" 
                                           value="{{ old('kural_min_siparis_tutari') }}" placeholder="Opsiyonel">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold text-dark"><i class='bx bx-cart'></i> MİN. SİPARİŞ ADEDİ</label>
                                <input type="number" name="kural_min_siparis_adedi" class="form-control" 
                                       value="{{ old('kural_min_siparis_adedi') }}" placeholder="Opsiyonel">
                            </div>

                            <div class="d-flex flex-column gap-2 mt-3 p-2 bg-light rounded">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="kural_ilk_alisveris" value="1" id="kuralIlkAlisveris" {{ old('kural_ilk_alisveris') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="kuralIlkAlisveris">🎉 İlk Alışveriş Yapacaklar</label>
                                </div>

                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="kural_dogum_gunu" value="1" id="kuralDogumGunu" {{ old('kural_dogum_gunu') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="kuralDogumGunu">🎂 Doğum Günü Yaklaşanlar</label>
                                </div>
                                <div id="dogumGunuAyarlari" class="ms-4 mb-2" style="display: none;">
                                    <div class="input-group input-group-sm">
                                        <input type="number" name="kural_dogum_gunu_aralik" class="form-control" 
                                            value="7" placeholder="7">
                                        <span class="input-group-text">gün önce</span>
                                    </div>
                                </div>

                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="kural_inaktif_musteri" value="1" id="kuralInaktif" {{ old('kural_inaktif_musteri') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="kuralInaktif">🕒 İnaktif Müşteriler</label>
                                </div>
                                <div id="inaktifAyarlari" class="ms-4 mb-2" style="display: none;">
                                    <div class="input-group input-group-sm">
                                        <input type="number" name="kural_inaktif_gun" class="form-control" 
                                            value="60" placeholder="60">
                                        <span class="input-group-text">gündür</span>
                                    </div>
                                </div>
                            </div>

                            <div class="alert alert-secondary mt-3 p-3 mb-0 border-primary border-1">
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" name="otomatik_ata" value="1" id="otomatikAta" {{ old('otomatik_ata') ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold text-primary" for="otomatikAta">
                                        <i class='bx bx-run'></i> Otomatik Ata
                                    </label>
                                </div>
                                <small class="d-block mt-2 text-muted" style="font-size: 0.75rem; line-height: 1.3;">
                                    Bu seçenek işaretlenirse, kaydettiğinizde koşulları sağlayan kullanıcılara kupon <strong>otomatik olarak</strong> tanımlanır.
                                </small>
                            </div>

                        </div> <hr class="my-4">

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="aktif" value="1" id="statusSwitch" checked>
                            <label class="form-check-label fw-bold" for="statusSwitch">Kupon Aktif</label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 btn-lg">
                            <i class="bx bx-check-circle me-1"></i> Kuponu Oluştur
                        </button>

                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. İndirim Tipi Değişimi
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

    // 2. Kupon Türü Değişimi ve Bölümlerin Açılması
    const kuponTuru = document.getElementById('kuponTuru');
    const userSection = document.getElementById('kullaniciOzelSection');
    const ruleSection = document.getElementById('kuralBazliSection');
    
    function kuponTuruDegisti() {
        const tur = kuponTuru.value;
        // Kullanıcı özel ise göster
        userSection.style.display = tur === 'kullanici_ozel' ? 'block' : 'none';
        // Kural bazlı ise göster
        ruleSection.style.display = tur === 'kural_bazli' ? 'block' : 'none';
    }
    
    kuponTuru.addEventListener('change', kuponTuruDegisti);
    kuponTuruDegisti(); // Sayfa yüklendiğinde çalıştır

    // 3. Doğum Günü Ayarı Görünürlüğü
    const dogumGunuCheck = document.getElementById('kuralDogumGunu');
    const dogumGunuDiv = document.getElementById('dogumGunuAyarlari');
    dogumGunuCheck.addEventListener('change', function() {
        dogumGunuDiv.style.display = this.checked ? 'block' : 'none';
    });
    // Başlangıç kontrolü (old input için)
    if(dogumGunuCheck.checked) dogumGunuDiv.style.display = 'block';

    // 4. İnaktif Müşteri Ayarı Görünürlüğü
    const inaktifCheck = document.getElementById('kuralInaktif');
    const inaktifDiv = document.getElementById('inaktifAyarlari');
    inaktifCheck.addEventListener('change', function() {
        inaktifDiv.style.display = this.checked ? 'block' : 'none';
    });
    // Başlangıç kontrolü
    if(inaktifCheck.checked) inaktifDiv.style.display = 'block';

    // 5. Kullanıcı Arama (AJAX)
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
                // Route ismini kontrol edin, admin.kuponlar.kullanici-ara olmalı
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

// Rastgele kod oluştur
function rastgeleKodOlustur() {
    const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
    let kod = '';
    for (let i = 0; i < 8; i++) {
        kod += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    document.getElementById('kuponKoduInput').value = kod;
}

// Kullanıcı ekle
function kullaniciEkle(userId, userName) {
    const selectedUsersDiv = document.getElementById('selectedUsers');
    const noUserSpan = document.getElementById('noUserSelected');
    
    // Zaten var mı kontrol et
    if (document.querySelector(`[data-user-id="${userId}"]`)) {
        return;
    }
    
    if(noUserSpan) noUserSpan.style.display = 'none';

    const badge = document.createElement('div');
    badge.className = 'badge bg-primary d-flex align-items-center gap-2';
    badge.setAttribute('data-user-id', userId);
    badge.innerHTML = `
        ${userName}
        <i class='bx bx-x fs-6' onclick="kullaniciKaldir(${userId})" style="cursor: pointer;"></i>
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
    const selectedUsersDiv = document.getElementById('selectedUsers');
    if(selectedUsersDiv.children.length <= 1) { // 1 because the span is hidden
         const noUserSpan = document.getElementById('noUserSelected');
         if(noUserSpan) noUserSpan.style.display = 'block';
    }
}

// Şablon Uygula Fonksiyonu
function sabloUygula(sablon) {
    if (!sablon) return;

    // Formu temizle
    document.querySelector('[name="kural_min_siparis_tutari"]').value = '';
    document.querySelector('[name="kural_min_siparis_adedi"]').value = '';
    
    const kIlk = document.getElementById('kuralIlkAlisveris');
    const kDogum = document.getElementById('kuralDogumGunu');
    const kInaktif = document.getElementById('kuralInaktif');
    
    if(kIlk) kIlk.checked = false;
    if(kDogum) kDogum.checked = false;
    if(kInaktif) kInaktif.checked = false;
    
    // Gizli alanları kapat
    document.getElementById('dogumGunuAyarlari').style.display = 'none';
    document.getElementById('inaktifAyarlari').style.display = 'none';

    // Şablona göre doldur
    switch(sablon) {
        case 'ilk_alisveris':
            if(kIlk) kIlk.checked = true;
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
            if(kDogum) {
                kDogum.checked = true;
                document.getElementById('dogumGunuAyarlari').style.display = 'block';
            }
            document.querySelector('[name="kural_dogum_gunu_aralik"]').value = 7;
            document.querySelector('[name="kural_tarih_araligi"]').value = 365;
            break;
            
        case 'inaktif':
            if(kInaktif) {
                kInaktif.checked = true;
                document.getElementById('inaktifAyarlari').style.display = 'block';
            }
            document.querySelector('[name="kural_inaktif_gun"]').value = 60;
            document.querySelector('[name="kural_tarih_araligi"]').value = 365;
            break;
    }
}
</script>
@endsection