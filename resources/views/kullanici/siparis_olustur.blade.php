@extends('layouts.app')
@section('title', 'Siparişi Tamamla - Avantaj Bilişim')

@section('content')
<style>
/* === TEMA RENKLERİ === */
:root {
    --primary-turq: #00d4aa;
    --primary-dark: #00a896;
    --secondary-navy: #1e293b;
    --bg-soft: #f8fafc;
    --border-color: #e2e8f0;
    --card-shadow: 0 10px 30px rgba(0,0,0,0.03);
}

body {
    background-color: var(--bg-soft);
    font-family: 'Inter', sans-serif;
}

/* === HEADER === */
.checkout-header {
    background: var(--secondary-navy);
    padding: 50px 0 80px; 
    color: white;
    text-align: center;
    position: relative;
    margin-bottom: -50px;
}

.checkout-header::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image: radial-gradient(var(--primary-turq) 1px, transparent 1px);
    background-size: 30px 30px;
    opacity: 0.1;
}

/* === STEPPER === */
.stepper {
    display: inline-flex;
    background: rgba(255,255,255,0.1);
    border-radius: 50px;
    padding: 5px;
    margin-top: 20px;
    backdrop-filter: blur(5px);
    border: 1px solid rgba(255,255,255,0.1);
}

.step-item {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 20px;
    border-radius: 40px;
    color: #94a3b8;
    font-size: 0.9rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.step-item.active {
    background: var(--primary-turq);
    color: var(--secondary-navy);
    box-shadow: 0 4px 12px rgba(0, 212, 170, 0.3);
}

.step-item.completed {
    color: var(--primary-turq);
}

.step-icon {
    width: 20px;
    height: 20px;
    background: rgba(255,255,255,0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.7rem;
}

.step-item.active .step-icon {
    background: var(--secondary-navy);
    color: white;
}

/* === KARTLAR === */
.checkout-card {
    background: white;
    border-radius: 20px;
    border: 1px solid var(--border-color);
    box-shadow: var(--card-shadow);
    padding: 25px;
    margin-bottom: 20px;
}

.section-title {
    font-size: 1.1rem;
    font-weight: 800;
    color: var(--secondary-navy);
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
    padding-bottom: 15px;
    border-bottom: 1px solid var(--border-color);
}
.section-title i { color: var(--primary-turq); }

/* === FORM ELEMANLARI === */
.form-label {
    font-weight: 700;
    font-size: 0.85rem;
    color: #475569;
    margin-bottom: 6px;
}

.custom-input {
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    padding: 12px 15px;
    font-size: 0.95rem;
    transition: all 0.3s;
}

.custom-input:focus {
    border-color: var(--primary-turq);
    box-shadow: 0 0 0 4px rgba(0, 212, 170, 0.1);
    outline: none;
}

/* === ÖDEME SEKMELERİ === */
.payment-tabs {
    display: flex;
    gap: 15px;
    margin-bottom: 20px;
}

.payment-tab {
    flex: 1;
    border: 2px solid var(--border-color);
    border-radius: 15px;
    padding: 15px;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    gap: 10px;
    background: #fff;
}

.payment-tab:hover {
    border-color: #cbd5e1;
    background: #f8fafc;
}

.payment-tab.active {
    border-color: var(--primary-turq);
    background: #f0fdfa;
}

.payment-tab i {
    font-size: 1.5rem;
    color: #64748b;
}

.payment-tab.active i {
    color: var(--primary-dark);
}

.payment-title {
    font-weight: 700;
    color: var(--secondary-navy);
    display: block;
}

.payment-desc {
    font-size: 0.75rem;
    color: #64748b;
}

/* === KUPON BİLETİ === */
.coupon-ticket {
    display: flex;
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    overflow: hidden;
    margin-bottom: 10px;
    cursor: pointer;
    transition: all 0.3s;
    position: relative;
}

.coupon-ticket:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    border-color: var(--primary-turq);
}

.ticket-left {
    background: var(--secondary-navy);
    width: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    border-right: 2px dashed rgba(255,255,255,0.3);
}

.ticket-left::before, .ticket-left::after {
    content: '';
    position: absolute;
    width: 16px;
    height: 16px;
    background: white;
    border-radius: 50%;
    right: -8px;
}
.ticket-left::before { top: -8px; }
.ticket-left::after { bottom: -8px; }

.ticket-text-vertical {
    writing-mode: vertical-rl;
    transform: rotate(180deg);
    color: var(--primary-turq);
    font-weight: 800;
    font-size: 0.7rem;
    letter-spacing: 1px;
}

.ticket-right { padding: 12px 15px; flex: 1; }
.coupon-code-text { font-family: monospace; font-weight: 800; font-size: 1rem; color: var(--secondary-navy); }
.coupon-info { font-size: 0.75rem; color: #64748b; margin-top: 2px; }
.use-btn { font-size: 0.7rem; font-weight: 800; color: var(--primary-dark); text-transform: uppercase; margin-top: 5px; display: block; }

/* === ÖZET ALANI === */
.summary-row { display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 0.95rem; color: #64748b; }
.total-row { border-top: 2px dashed var(--border-color); padding-top: 15px; margin-top: 15px; display: flex; justify-content: space-between; align-items: center; }
.total-label { font-size: 1.1rem; font-weight: 800; color: var(--secondary-navy); }
.total-price { font-size: 1.5rem; font-weight: 900; color: var(--primary-dark); }

.checkout-btn {
    width: 100%; padding: 16px; background: var(--secondary-navy); color: white; border: none; border-radius: 12px;
    font-weight: 700; font-size: 1rem; margin-top: 20px; display: flex; align-items: center; justify-content: center;
    gap: 10px; transition: all 0.3s;
}
.checkout-btn:hover { background: var(--primary-dark); transform: translateY(-2px); box-shadow: 0 10px 20px rgba(0, 168, 150, 0.2); }

@media (max-width: 768px) {
    .payment-tabs { flex-direction: column; }
    .checkout-header { text-align: center; }
}
</style>

<div class="checkout-header">
    <div class="container">
        <h2 class="fw-bold mb-2">Siparişi Tamamla</h2>
        <div class="stepper">
            <div class="step-item completed">
                <div class="step-icon"><i class="fas fa-check"></i></div>
                Sepet
            </div>
            <div class="step-item active">
                <div class="step-icon">2</div>
                Teslimat & Ödeme
            </div>
            <div class="step-item">
                <div class="step-icon">3</div>
                Onay
            </div>
        </div>
    </div>
</div>

<div class="container pb-5" style="position: relative; z-index: 10;">
    
    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4 d-flex align-items-center gap-3">
            <i class="fas fa-exclamation-circle fa-2x"></i>
            <div>{{ session('error') }}</div>
        </div>
    @endif

    <form action="{{ route('siparis.tamamla') }}" method="POST" id="checkoutForm">
        @csrf
        <div class="row g-4">
            
            <div class="col-lg-8">
                
                <div class="checkout-card">
                    <div class="section-title">
                        <i class="fas fa-map-marker-alt"></i> Teslimat Bilgileri
                    </div>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Ad Soyad</label>
                            <input type="text" name="ad_soyad" class="custom-input w-100" placeholder="Örn: Ahmet Yılmaz" value="{{ Auth::user()->name ?? old('ad_soyad') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Telefon</label>
                            <input type="tel" name="telefon" class="custom-input w-100" placeholder="05XX XXX XX XX" value="{{ old('telefon') }}" required>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label">Açık Adres (İl, İlçe, Mahalle dahil)</label>
                            <textarea name="kargo_adresi" class="custom-input w-100" rows="3" placeholder="Mahalle, Cadde, Sokak, No, İlçe/Şehir..." required>{{ old('kargo_adresi') }}</textarea>
                        </div>

                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="faturaAdresiAyni" checked onchange="toggleFaturaAdresi()">
                                <label class="form-check-label small fw-bold text-muted" for="faturaAdresiAyni">
                                    Fatura adresim teslimat adresimle aynı
                                </label>
                            </div>
                        </div>

                        <div class="col-12 d-none" id="faturaAdresiDiv">
                            <label class="form-label">Fatura Adresi</label>
                            <textarea name="fatura_adresi" class="custom-input w-100" rows="2" placeholder="Fatura adresinizi giriniz...">{{ old('fatura_adresi') }}</textarea>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Sipariş Notu (İsteğe Bağlı)</label>
                            <textarea name="siparis_notu" class="custom-input w-100" rows="1" placeholder="Kurye için notunuz...">{{ old('siparis_notu') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="checkout-card">
                    <div class="section-title">
                        <i class="fas fa-wallet"></i> Ödeme Yöntemi
                    </div>

                    <input type="hidden" name="odeme_yontemi" id="selectedPaymentMethod" value="kredi_karti">

                    <div class="payment-tabs">
                        <div class="payment-tab active" id="tab-cc" onclick="switchPayment('kredi_karti')">
                            <i class="fas fa-credit-card"></i>
                            <div>
                                <span class="payment-title">Kredi / Banka Kartı</span>
                                <span class="payment-desc">Güvenli ve hızlı ödeme</span>
                            </div>
                        </div>
                        <div class="payment-tab" id="tab-kapida" onclick="switchPayment('kapida_odeme')">
                            <i class="fas fa-truck"></i> <div>
                                <span class="payment-title">Kapıda Ödeme</span>
                                <span class="payment-desc">Nakit veya Kart ile</span>
                            </div>
                        </div>
                    </div>

                    <div id="cc-form" class="p-4 rounded-4 bg-light border">
                        <div class="mb-3">
                            <label class="form-label">Kart Üzerindeki İsim</label>
                            <input type="text" name="kart_isim" class="custom-input w-100 bg-white">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kart Numarası</label>
                            <div class="position-relative">
                                <input type="text" name="kart_no" class="custom-input w-100 bg-white" placeholder="0000 0000 0000 0000" maxlength="19">
                                <i class="fab fa-cc-mastercard position-absolute top-50 end-0 translate-middle-y me-3 fs-4 text-muted"></i>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-6">
                                <label class="form-label">Son Kullanma (Ay/Yıl)</label>
                                <input type="text" name="kart_tarih" class="custom-input w-100 bg-white" placeholder="AA/YY" maxlength="5">
                            </div>
                            <div class="col-6">
                                <label class="form-label">CVV</label>
                                <input type="text" name="kart_cvv" class="custom-input w-100 bg-white" placeholder="***" maxlength="4">
                            </div>
                        </div>
                        <div class="mt-3 small text-muted text-center">
                            <i class="fas fa-lock text-success me-1"></i> Ödemeniz 256-bit SSL sertifikası ile korunmaktadır.
                        </div>
                    </div>

                    <div id="kapida-info" class="d-none">
                        <div class="alert alert-info border-0 d-flex align-items-center gap-3" style="background-color: #e0f2f1; color: #0f766e;">
                            <i class="fas fa-shipping-fast fa-2x"></i>
                            <div>
                                <strong>Kapıda Ödeme Seçildi:</strong> 
                                Sipariş tutarını kargo görevlisine ürün teslimatı sırasında <b>Nakit</b> veya <b>Kredi Kartı</b> ile ödeyebilirsiniz.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="checkout-card position-sticky" style="top: 20px;">
                    <div class="section-title justify-content-between">
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
                        
                        <div class="summary-row d-none" id="indirimSatiri">
                            <span class="text-danger fw-bold">Kupon İndirimi</span>
                            <span class="text-danger fw-bold" id="indirimTutari">-0.00 ₺</span>
                        </div>

                        <div class="mt-4 mb-4">
                            <label class="form-label">İndirim Kuponu</label>
                            <div class="input-group">
                                <input type="text" name="kupon_kodu" id="couponInput" class="form-control custom-input" placeholder="Kod giriniz" style="border-top-right-radius: 0; border-bottom-right-radius: 0;">
                                <button class="btn btn-dark" type="button" onclick="checkCoupon()" style="border-top-right-radius: 12px; border-bottom-right-radius: 12px; background: var(--secondary-navy); border: none;">
                                    Uygula
                                </button>
                            </div>
                            <div id="couponMessage" class="small mt-1"></div>
                        </div>

                        @if(count($kuponlar) > 0)
                            <div class="mb-3">
                                <small class="text-uppercase fw-bold text-muted mb-2 d-block" style="font-size: 0.7rem;">Sizin İçin Fırsatlar</small>
                                
                                @foreach($kuponlar as $kupon)
                                    <div class="coupon-ticket" onclick="applyCoupon('{{ $kupon->kupon_kodu }}')">
                                        <div class="ticket-left">
                                            <span class="ticket-text-vertical">KUPON</span>
                                        </div>
                                        <div class="ticket-right">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="coupon-code-text">{{ $kupon->kupon_kodu }}</span>
                                            </div>
                                            <div class="coupon-info">
                                                @if($kupon->indirim_tipi == 'yuzde')
                                                    %{{ intval($kupon->indirim_miktari) }} İndirim
                                                @else
                                                    {{ intval($kupon->indirim_miktari) }} TL İndirim
                                                @endif
                                                @if($kupon->minimum_tutar > 0)
                                                    ({{ intval($kupon->minimum_tutar) }}₺ üzeri)
                                                @endif
                                            </div>
                                            <span class="use-btn"><i class="fas fa-plus-circle"></i> KULLAN</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <div class="total-row">
                            <span class="total-label">GENEL TOPLAM</span>
                            <span class="total-price" id="genelToplam">{{ number_format($toplam, 2) }} ₺</span>
                        </div>

                        <button type="submit" class="checkout-btn">
                            Siparişi Onayla <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>

<script>
    // 1. Ödeme Yöntemi Değiştirme (Tab Mantığı - Kapıda Ödeme Güncellendi)
    function switchPayment(type) {
        // Tab Sınıflarını Yönet
        document.querySelectorAll('.payment-tab').forEach(el => el.classList.remove('active'));
        
        const activeTab = type === 'kredi_karti' ? document.getElementById('tab-cc') : document.getElementById('tab-kapida');
        activeTab.classList.add('active');

        // Form Göster/Gizle
        document.getElementById('selectedPaymentMethod').value = type;
        
        if (type === 'kredi_karti') {
            document.getElementById('cc-form').classList.remove('d-none');
            document.getElementById('kapida-info').classList.add('d-none');
            // Kredi kartı inputlarını validate edebilirsin
        } else {
            document.getElementById('cc-form').classList.add('d-none');
            document.getElementById('kapida-info').classList.remove('d-none');
        }
    }

    // 2. Fatura Adresi Göster/Gizle
    function toggleFaturaAdresi() {
        const chk = document.getElementById('faturaAdresiAyni');
        const div = document.getElementById('faturaAdresiDiv');
        div.classList.toggle('d-none', chk.checked);
    }

    // 3. Kupon Kodunu Inputa Taşı
    function applyCoupon(code) {
        const input = document.getElementById('couponInput');
        input.value = code;
        input.focus();
        // Hafif bir flash efekti
        input.style.backgroundColor = "#e0f2f1";
        setTimeout(() => input.style.backgroundColor = "white", 300);
    }

    // 4. Kupon Kontrolü (AJAX)
    function checkCoupon() {
        const code = document.getElementById('couponInput').value;
        const msgDiv = document.getElementById('couponMessage');
        
        // Eğer boşsa uyarı ver
        if(!code.trim()) {
            msgDiv.innerHTML = '<span class="text-danger fw-bold">Lütfen bir kod giriniz.</span>';
            return;
        }

        // Blade'den gelen toplam tutar
        const currentTotal = {{ $toplam }}; 

        fetch('{{ route("siparis.kupon.kontrol") }}', { 
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ kupon_kodu: code, sepet_toplami: currentTotal })
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                msgDiv.innerHTML = `<span class="text-success fw-bold"><i class="fas fa-check-circle"></i> ${data.message}</span>`;
                
                // İndirim satırını göster
                document.getElementById('indirimSatiri').classList.remove('d-none');
                document.getElementById('indirimTutari').innerText = '-' + data.indirim + ' ₺';
                
                // Yeni toplamı güncelle
                document.getElementById('genelToplam').innerText = data.yeni_toplam + ' ₺';
            } else {
                msgDiv.innerHTML = `<span class="text-danger fw-bold"><i class="fas fa-times-circle"></i> ${data.message}</span>`;
                // Hata durumunda indirimi gizle ve eski fiyata dön
                document.getElementById('indirimSatiri').classList.add('d-none');
                document.getElementById('genelToplam').innerText = '{{ number_format($toplam, 2) }} ₺';
            }
        })
        .catch(err => {
            console.error(err);
            msgDiv.innerHTML = '<span class="text-danger">Bir hata oluştu.</span>';
        });
    }
</script>
@endsection