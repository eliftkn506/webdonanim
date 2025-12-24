@extends('layouts.app')

@section('title', 'Sipariş Oluştur - Avantaj Bilişim')

@section('content')

<style>
    /* --- SAYFAYA ÖZEL STİLLER --- */
    
    /* 1. Hero & Stepper (Üst Kısım) */
    .checkout-hero {
        background: var(--secondary-color); /* Fallback */
        background: linear-gradient(135deg, var(--secondary-color) 0%, #0f172a 100%);
        padding: 40px 0 80px 0; /* Alttan boşluk grid'in üzerine binmesi için */
        color: white;
        position: relative;
        margin-bottom: -50px; /* Kartları yukarı çekmek için negatif margin */
    }

    .stepper-container {
        display: flex;
        justify-content: center;
        gap: 15px;
        margin-top: 25px;
    }

    .step-badge {
        display: flex;
        align-items: center;
        gap: 10px;
        background: rgba(255,255,255,0.08);
        padding: 8px 20px;
        border-radius: 50px;
        color: #94a3b8;
        font-weight: 600;
        font-size: 0.9rem;
        backdrop-filter: blur(5px);
        border: 1px solid rgba(255,255,255,0.05);
        transition: all 0.3s ease;
    }

    .step-badge.active {
        background: var(--primary-color);
        color: var(--secondary-color);
        box-shadow: 0 0 15px rgba(0, 212, 170, 0.4);
        border-color: var(--primary-color);
    }
    
    .step-badge.completed {
        background: rgba(0, 212, 170, 0.2);
        color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .step-number {
        width: 24px;
        height: 24px;
        background: white;
        color: var(--secondary-color);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 0.8rem;
    }
    
    .step-badge.active .step-number {
        background: var(--secondary-color);
        color: white;
    }

    /* 2. Kart Tasarımı (Beyaz Kutular) */
    .checkout-card {
        background: white;
        border-radius: 1.25rem; /* radius-xl */
        border: 1px solid var(--border-color);
        padding: 25px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.03);
        margin-bottom: 24px;
    }

    .card-header-custom {
        border-bottom: 1px solid var(--border-color);
        padding-bottom: 15px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--secondary-color);
    }
    
    .card-header-custom i { color: var(--primary-color); }

    /* 3. Form Elemanları */
    .form-control-custom {
        padding: 12px 15px;
        border-radius: 10px;
        border: 1px solid #cbd5e1;
        font-size: 0.95rem;
        transition: all 0.3s;
    }
    .form-control-custom:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(0, 212, 170, 0.1);
        outline: none;
    }
    .form-label { font-weight: 600; font-size: 0.9rem; color: var(--secondary-color); }

    /* 4. Ödeme Sekmeleri */
    .payment-tab-btn {
        flex: 1;
        padding: 15px;
        border: 1px solid var(--border-color);
        border-radius: 12px;
        background: #f8fafc;
        color: var(--text-muted);
        font-weight: 600;
        transition: all 0.3s;
        text-align: left;
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
    }
    
    .payment-tab-btn.active {
        border-color: var(--primary-color);
        background: rgba(0, 212, 170, 0.05);
        color: #0f766e; /* primary-dark */
    }

    /* 5. KUPON BİLET TASARIMI (Ticket Style) */
    .coupon-ticket {
        position: relative;
        display: flex;
        background: linear-gradient(to right, #ffffff, #f8fafc);
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        margin-bottom: 12px;
        overflow: hidden;
        cursor: pointer;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .coupon-ticket:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        border-color: var(--primary-color);
    }

    .ticket-stub {
        background: var(--secondary-color);
        color: white;
        width: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-right: 2px dashed rgba(255,255,255,0.4);
        position: relative;
        padding: 5px;
    }
    
    /* Tırtıklı Kenar Efekti */
    .ticket-stub::after {
        content: '';
        position: absolute;
        right: -6px;
        top: 50%;
        transform: translateY(-50%);
        width: 12px;
        height: 12px;
        background: white;
        border-radius: 50%;
        box-shadow: -2px 0 0 0 rgba(0,0,0,0.05); 
    }

    .stub-text {
        writing-mode: vertical-rl;
        transform: rotate(180deg);
        font-weight: 800;
        font-size: 0.75rem;
        letter-spacing: 1px;
        text-align: center;
        white-space: nowrap;
    }

    .ticket-body {
        padding: 12px 15px;
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
        overflow: hidden;
    }

    .coupon-code {
        font-family: 'Monaco', 'Consolas', monospace;
        font-weight: 800;
        color: var(--secondary-color);
        font-size: 0.95rem;
        display: block;
    }

    .coupon-desc {
        font-size: 0.75rem;
        color: #64748b;
        line-height: 1.3;
        margin-top: 4px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .apply-text {
        font-size: 0.7rem;
        text-transform: uppercase;
        font-weight: 800;
        margin-top: 8px;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    /* 6. Özet Alanı */
    .summary-row {
        display: flex;
        justify-content: space-between;
        font-size: 0.95rem;
        margin-bottom: 10px;
        color: var(--text-muted);
    }
    
    .total-row {
        border-top: 2px dashed var(--border-color);
        padding-top: 15px;
        margin-top: 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .total-amount {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--secondary-color);
    }

    .btn-checkout-primary {
        background: var(--secondary-color);
        color: white;
        width: 100%;
        padding: 16px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 1rem;
        border: none;
        transition: all 0.3s;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 10px;
    }
    
    .btn-checkout-primary:hover {
        background: var(--primary-color);
        color: white;
        transform: translateY(-2px);
    }

</style>

<div class="checkout-hero">
    <div class="container text-center">
        <h2 class="fw-800 m-0">Siparişini Tamamla</h2>
        <div class="stepper-container">
            <div class="step-badge completed">
                <div class="step-number"><i class="fas fa-check"></i></div>
                <span>Sepetim</span>
            </div>
            <div class="step-badge active">
                <div class="step-number">2</div>
                <span>Teslimat & Ödeme</span>
            </div>
            <div class="step-badge">
                <div class="step-number">3</div>
                <span>Onay</span>
            </div>
        </div>
    </div>
</div>

<div class="container pb-5" style="position: relative; z-index: 10;">
    @if(session('error'))
        <div class="alert alert-danger shadow-sm rounded-3 border-0 mb-4">
            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('siparis.tamamla') }}" method="POST" id="checkoutForm">
        @csrf
        <div class="row g-4">
            
            <div class="col-lg-8">
                
                <div class="checkout-card">
                    <div class="card-header-custom">
                        <i class="fas fa-map-marker-alt"></i> Teslimat Adresi
                    </div>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Adınız</label>
                            <input type="text" name="ad_soyad" class="form-control form-control-custom" placeholder="Örn: Ahmet Yılmaz" value="{{ Auth::user()->name ?? old('ad_soyad') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Telefon</label>
                            <input type="tel" name="telefon" class="form-control form-control-custom" placeholder="05XX XXX XX XX" value="{{ old('telefon') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Şehir</label>
                            <select name="sehir" class="form-select form-control-custom">
                                <option selected disabled>Seçiniz</option>
                                <option value="Istanbul">İstanbul</option>
                                <option value="Ankara">Ankara</option>
                                <option value="Izmir">İzmir</option>
                                </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Teslimat Adresi</label>
                            <textarea name="kargo_adresi" class="form-control form-control-custom" rows="3" placeholder="Mahalle, sokak, kapı no, daire no..." required>{{ old('kargo_adresi') }}</textarea>
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="faturaAdresiAyni" checked onchange="toggleFaturaAdresi()">
                                <label class="form-check-label small" for="faturaAdresiAyni">
                                    Fatura adresim teslimat adresimle aynı
                                </label>
                            </div>
                        </div>
                        <div class="col-12 d-none" id="faturaAdresiDiv">
                            <label class="form-label">Fatura Adresi</label>
                            <textarea name="fatura_adresi" class="form-control form-control-custom" rows="2" placeholder="Fatura adresinizi giriniz...">{{ old('fatura_adresi') }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Sipariş Notu (İsteğe Bağlı)</label>
                            <textarea name="siparis_notu" class="form-control form-control-custom" rows="2" placeholder="Kurye için notunuz...">{{ old('siparis_notu') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="checkout-card">
                    <div class="card-header-custom">
                        <i class="fas fa-wallet"></i> Ödeme Seçenekleri
                    </div>

                    <input type="hidden" name="odeme_yontemi" id="selectedPaymentMethod" value="kredi_karti">

                    <div class="d-flex gap-3 mb-4 flex-wrap">
                        <div class="payment-tab-btn active" id="btn-cc" onclick="switchPayment('kredi_karti')">
                            <i class="fas fa-credit-card fs-5"></i>
                            <div>
                                <div>Kredi / Banka Kartı</div>
                                <div style="font-size: 0.75rem; font-weight: 400;">Güvenli Ödeme</div>
                            </div>
                        </div>
                        <div class="payment-tab-btn" id="btn-havale" onclick="switchPayment('havale')">
                            <i class="fas fa-university fs-5"></i>
                            <div>
                                <div>Havale / EFT</div>
                                <div style="font-size: 0.75rem; font-weight: 400;">Banka Transferi</div>
                            </div>
                        </div>
                    </div>

                    <div id="cc-form">
                        <div class="p-4 rounded-3" style="background: #f8fafc; border: 1px solid var(--border-color);">
                            <div class="mb-3">
                                <label class="form-label">Kart Üzerindeki İsim</label>
                                <input type="text" name="kart_isim" class="form-control form-control-custom">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Kart Numarası</label>
                                <div class="position-relative">
                                    <input type="text" name="kart_no" class="form-control form-control-custom" placeholder="0000 0000 0000 0000" maxlength="19">
                                    <i class="fab fa-cc-mastercard position-absolute top-50 end-0 translate-middle-y me-3 fs-4 text-muted"></i>
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="form-label">Son Kullanma (Ay/Yıl)</label>
                                    <input type="text" name="kart_tarih" class="form-control form-control-custom" placeholder="MM/YY" maxlength="5">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">CVV</label>
                                    <input type="text" name="kart_cvv" class="form-control form-control-custom" placeholder="***" maxlength="4">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="havale-info" class="d-none">
                        <div class="alert alert-info border-0 text-dark d-flex align-items-center" style="background-color: #e0f2f1;">
                            <i class="fas fa-info-circle fa-2x me-3 text-info"></i>
                            <div>
                                <strong>Bilgilendirme:</strong> Siparişinizi onayladıktan sonra size verilecek sipariş numarası ile birlikte aşağıdaki hesaplarımıza ödeme yapabilirsiniz.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                
                <div class="checkout-card position-sticky" style="top: 100px;">
                    <div class="card-header-custom justify-content-between">
                        <span>Sipariş Özeti</span>
                        <span class="badge bg-light text-dark border">{{ count($sepet) }} Ürün</span>
                    </div>

                    <div class="summary-content">
                        <div class="summary-row">
                            <span>Ara Toplam</span>
                            <span class="fw-bold">{{ number_format($toplam, 2) }} ₺</span>
                        </div>
                        <div class="summary-row">
                            <span>Kargo</span>
                            <span class="text-success fw-bold">Ücretsiz</span>
                        </div>
                        <div class="summary-row">
                            <span>KDV Dahil</span>
                            <span>Evet</span>
                        </div>
                        
                        <div class="summary-row d-none" id="indirimSatiri">
                            <span class="text-danger">Kupon İndirimi</span>
                            <span class="text-danger fw-bold" id="indirimTutari">-0.00 ₺</span>
                        </div>
                        
                        <div class="mt-4 mb-3">
                            <label class="form-label small">İndirim Kuponu</label>
                            <div class="input-group">
                                <input type="text" name="kupon_kodu" id="couponInput" class="form-control form-control-custom" placeholder="Kupon kodunu giriniz" style="border-top-right-radius: 0; border-bottom-right-radius: 0;">
                                <button class="btn btn-dark" type="button" onclick="checkCoupon()" style="border-top-right-radius: 10px; border-bottom-right-radius: 10px; background: var(--secondary-color);">Uygula</button>
                            </div>
                            <div id="couponMessage" class="small mt-1"></div>
                        </div>

                        <div class="mt-4">
                            <h6 class="fw-800 small text-uppercase text-muted mb-3 ps-1">
                                <i class="fas fa-tags text-warning me-1"></i> Senin İçin Fırsatlar
                            </h6>

                            @php
                                // Tasarımdaki renk paleti (Sırayla dönecek)
                                $colors = ['var(--primary-color)', '#1e293b', '#e11d48', '#f59e0b', '#7c3aed'];
                            @endphp

                            @forelse($kuponlar as $index => $kupon)
                                @php
                                    // 1. Renk Seçimi
                                    $currentColor = $colors[$index % count($colors)];

                                    // 2. İkon ve Metin Mantığı (Controller verisine göre)
                                    $stubText = '';
                                    $descText = '';

                                    // İndirim Tipi Kontrolü
                                    if ($kupon->indirim_tipi == 'yuzde') {
                                        $stubText = '%' . intval($kupon->indirim_miktari);
                                    } elseif ($kupon->indirim_tipi == 'tutar') {
                                        $stubText = intval($kupon->indirim_miktari) . 'TL';
                                    } else {
                                        $stubText = 'FREE';
                                    }

                                    // Özel Durum: Kargo kuponu
                                    if (str_contains(strtoupper($kupon->kupon_kodu), 'KARGO')) {
                                        $stubText = 'KARGO';
                                    }

                                    // Açıklama Mantığı
                                    if (!empty($kupon->aciklama)) {
                                        $descText = $kupon->aciklama;
                                    } else {
                                        if ($kupon->minimum_tutar > 0) {
                                            $descText = number_format($kupon->minimum_tutar, 0) . ' TL ve üzeri alışverişlerde geçerli.';
                                        } else {
                                            $descText = 'Tüm ürünlerde geçerli özel fırsat!';
                                        }
                                    }
                                @endphp

                                <div class="coupon-ticket" onclick="applyCoupon('{{ $kupon->kupon_kodu }}')" title="Kuponu Kullan">
                                    <div class="ticket-stub" style="background: {{ $currentColor }};">
                                        <span class="stub-text" style="font-size: {{ strlen($stubText) > 4 ? '0.65rem' : '0.75rem' }}">
                                            {{ $stubText }}
                                        </span>
                                    </div>
                                    <div class="ticket-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="coupon-code">{{ $kupon->kupon_kodu }}</span>
                                            @if($kupon->bitis_tarihi)
                                                <small class="text-muted" style="font-size:0.65rem;">
                                                    <i class="far fa-clock"></i> {{ \Carbon\Carbon::parse($kupon->bitis_tarihi)->diffInDays() }} gün
                                                </small>
                                            @endif
                                        </div>
                                        <span class="coupon-desc">{{ $descText }}</span>
                                        <span class="apply-text" style="color: {{ $currentColor }}">
                                            <i class="fas fa-plus-circle"></i> KULLAN
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <div class="p-3 text-center border rounded-3 bg-light text-muted small">
                                    <i class="fas fa-ticket-alt opacity-50 mb-1"></i><br>
                                    Şu an kullanılabilir kupon yok.
                                </div>
                            @endforelse
                        </div>
                        <div class="total-row">
                            <span>GENEL TOPLAM</span>
                            <span class="total-amount text-primary" id="genelToplam">{{ number_format($toplam, 2) }} ₺</span>
                        </div>

                        <button type="submit" class="btn-checkout-primary mt-4">
                            Siparişi Onayla <i class="fas fa-arrow-right"></i>
                        </button>

                        <div class="text-center mt-3 small text-muted">
                            <i class="fas fa-lock"></i> 256-bit SSL ile güvenli ödeme
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    // 1. Ödeme Yöntemi Değiştirme
    function switchPayment(type) {
        // Buton stillerini sıfırla
        document.getElementById('btn-cc').classList.remove('active');
        document.getElementById('btn-cc').style.borderColor = 'var(--border-color)';
        document.getElementById('btn-cc').style.background = '#f8fafc';
        document.getElementById('btn-cc').style.color = 'var(--text-muted)';
        
        document.getElementById('btn-havale').classList.remove('active');
        document.getElementById('btn-havale').style.borderColor = 'var(--border-color)';
        document.getElementById('btn-havale').style.background = '#f8fafc';
        document.getElementById('btn-havale').style.color = 'var(--text-muted)';

        // Seçilen butonu aktif yap
        const activeBtn = type === 'kredi_karti' ? document.getElementById('btn-cc') : document.getElementById('btn-havale');
        activeBtn.classList.add('active');
        activeBtn.style.borderColor = 'var(--primary-color)';
        activeBtn.style.background = 'rgba(0, 212, 170, 0.05)';
        activeBtn.style.color = '#0f766e'; // Primary dark

        // Form alanlarını göster/gizle
        document.getElementById('selectedPaymentMethod').value = type;
        if (type === 'kredi_karti') {
            document.getElementById('cc-form').classList.remove('d-none');
            document.getElementById('havale-info').classList.add('d-none');
        } else {
            document.getElementById('cc-form').classList.add('d-none');
            document.getElementById('havale-info').classList.remove('d-none');
        }
    }

    // 2. Fatura Adresi Toggle
    function toggleFaturaAdresi() {
        const checkbox = document.getElementById('faturaAdresiAyni');
        const div = document.getElementById('faturaAdresiDiv');
        if (checkbox.checked) {
            div.classList.add('d-none');
        } else {
            div.classList.remove('d-none');
        }
    }

    // 3. Kuponu Input'a Kopyalama
    function applyCoupon(code) {
        const input = document.getElementById('couponInput');
        input.value = code;
        
        // Görsel efekt
        input.style.transition = "all 0.3s";
        input.style.backgroundColor = "#e0f2f1"; // Açık yeşil flash
        input.style.borderColor = "var(--primary-color)";
        
        setTimeout(() => {
            input.style.backgroundColor = "white";
        }, 500);
    }

    // 4. Kupon Kontrolü (AJAX) - Controller'daki 'kuponKontrol' metoduna istek atar
    function checkCoupon() {
        const code = document.getElementById('couponInput').value;
        const total = {{ $toplam }}; // Blade değişkeni
        const msgDiv = document.getElementById('couponMessage');
        const indirimSatiri = document.getElementById('indirimSatiri');
        const indirimTutari = document.getElementById('indirimTutari');
        const genelToplam = document.getElementById('genelToplam');

        if(!code) return;

        fetch('{{ route("siparis.kupon.kontrol") }}', { // Rotayı web.php'de tanımladığından emin ol
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ kupon_kodu: code, sepet_toplami: total })
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                msgDiv.innerHTML = `<span class="text-success"><i class="fas fa-check"></i> ${data.message}</span>`;
                indirimSatiri.classList.remove('d-none');
                indirimTutari.innerText = '-' + data.indirim + ' ₺';
                genelToplam.innerText = data.yeni_toplam + ' ₺';
            } else {
                msgDiv.innerHTML = `<span class="text-danger"><i class="fas fa-times"></i> ${data.message}</span>`;
                indirimSatiri.classList.add('d-none');
                // Toplamı sıfırla gerekirse
                genelToplam.innerText = '{{ number_format($toplam, 2) }} ₺';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            msgDiv.innerHTML = '<span class="text-danger">Bir hata oluştu.</span>';
        });
    }
</script>

@endsection