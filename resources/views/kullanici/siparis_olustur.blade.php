@extends('layouts.app')

@section('content')
<style>
:root {
    --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --success-gradient: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%);
    --danger-gradient: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
    --warning-gradient: linear-gradient(135deg, #ffd93d 0%, #ff9500 100%);
    --glass-bg: rgba(255, 255, 255, 0.1);
    --glass-border: rgba(255, 255, 255, 0.2);
    --shadow: 0 8px 32px rgba(31, 38, 135, 0.37);
    --text-primary: #2d3436;
    --text-secondary: #636e72;
}

body {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    min-height: 100vh;
}

.page-header {
    background: var(--primary-gradient);
    padding: 4rem 0 2rem;
    margin-bottom: 3rem;
    position: relative;
    overflow: hidden;
}

.page-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" fill="rgba(255,255,255,0.1)"><path d="M0,0v46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1047.97,8.57,1130.87,27.39,1200.94,44,1266.87,85.69,1341.17,91.49,1427.93,98.19,1513.25,63.17,1597.94,35.28c82.44-27.24,165.39-48.76,251.38-35.88C1912.08-4.87,1959.63,22.33,2004.54,57.9V0Z"/></svg>') repeat-x;
    background-size: 2000px 100px;
    animation: wave 10s ease-in-out infinite;
}

@keyframes wave {
    0%, 100% { transform: translateX(0); }
    50% { transform: translateX(-50px); }
}

.page-title {
    color: white;
    font-size: 3rem;
    font-weight: 800;
    text-align: center;
    margin: 0;
    text-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    position: relative;
    z-index: 2;
}

.checkout-steps {
    display: flex;
    justify-content: center;
    margin: 2rem 0;
    position: relative;
    z-index: 2;
}

.step {
    display: flex;
    align-items: center;
    color: rgba(255, 255, 255, 0.7);
    margin: 0 1rem;
}

.step.active {
    color: white;
    font-weight: 700;
}

.step-number {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 0.5rem;
    font-weight: 700;
}

.step.active .step-number {
    background: white;
    color: #667eea;
}

.glass-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 20px;
    box-shadow: var(--shadow);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.glass-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 15px 35px rgba(31, 38, 135, 0.3);
}

.form-section {
    margin-bottom: 2rem;
}

.section-title {
    font-size: 1.4rem;
    font-weight: 800;
    color: var(--text-primary);
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
    display: block;
}

.form-control {
    width: 100%;
    padding: 1rem;
    border: 2px solid #e9ecef;
    border-radius: 15px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: white;
}

.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    outline: none;
}

.payment-methods {
    display: grid;
    gap: 1rem;
    margin-bottom: 2rem;
}

.payment-option {
    border: 2px solid #e9ecef;
    border-radius: 15px;
    padding: 1.5rem;
    cursor: pointer;
    transition: all 0.3s ease;
    background: white;
}

.payment-option:hover {
    border-color: #667eea;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.2);
}

.payment-option.selected {
    border-color: #667eea;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
}

.payment-option input[type="radio"] {
    display: none;
}

.payment-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 0.5rem;
}

.payment-icon {
    width: 50px;
    height: 50px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
}

.payment-icon.credit-card {
    background: var(--primary-gradient);
}

.payment-icon.cash {
    background: var(--warning-gradient);
}

.payment-icon.transfer {
    background: var(--success-gradient);
}

.payment-title {
    font-weight: 700;
    color: var(--text-primary);
    font-size: 1.1rem;
}

.payment-desc {
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.credit-card-fields {
    display: none;
    margin-top: 1.5rem;
    padding-top: 1.5rem;
    border-top: 1px solid #e9ecef;
}

.credit-card-fields.show {
    display: block;
}

.card-row {
    display: flex;
    gap: 1rem;
}

.card-row .form-group {
    flex: 1;
}

.order-summary {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    position: sticky;
    top: 2rem;
}

.summary-title {
    font-size: 1.4rem;
    font-weight: 800;
    color: var(--text-primary);
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.order-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem 0;
    border-bottom: 1px solid #f0f0f0;
}

.order-item:last-child {
    border-bottom: none;
}

.item-image {
    width: 60px;
    height: 60px;
    border-radius: 10px;
    object-fit: contain;
    background: #f8f9fa;
    padding: 5px;
}

.item-info {
    flex: 1;
}

.item-name {
    font-weight: 600;
    color: var(--text-primary);
    font-size: 0.9rem;
    margin-bottom: 0.25rem;
}

.item-quantity {
    color: var(--text-secondary);
    font-size: 0.8rem;
}

.item-price {
    font-weight: 700;
    color: var(--text-primary);
}

.summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 0;
    border-bottom: 1px solid #f0f0f0;
}

.summary-row:last-child {
    border-bottom: none;
    font-size: 1.2rem;
    font-weight: 800;
}

.btn-modern {
    padding: 1rem 2rem;
    border: none;
    border-radius: 25px;
    font-weight: 700;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    text-decoration: none;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    position: relative;
    overflow: hidden;
    width: 100%;
}

.btn-success {
    background: var(--success-gradient);
    color: white;
    box-shadow: 0 5px 15px rgba(78, 205, 196, 0.4);
}

.btn-success:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(78, 205, 196, 0.6);
}

.btn-secondary {
    background: #6c757d;
    color: white;
    box-shadow: 0 5px 15px rgba(108, 117, 125, 0.4);
}

.btn-secondary:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(108, 117, 125, 0.6);
}

.coupon-section {
    border: 2px dashed #e9ecef;
    border-radius: 15px;
    padding: 1rem;
    margin: 1rem 0;
    background: rgba(249, 250, 251, 0.5);
}

.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
}

.loading-overlay.show {
    opacity: 1;
    visibility: visible;
}

.loading-spinner {
    width: 60px;
    height: 60px;
    border: 4px solid rgba(255, 255, 255, 0.1);
    border-top: 4px solid white;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin-bottom: 1rem;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.loading-text {
    color: white;
    font-size: 1.1rem;
    font-weight: 600;
}

.alert {
    padding: 1rem;
    margin-bottom: 1rem;
    border-radius: 10px;
    border: none;
}

.alert-success {
    background: linear-gradient(135deg, rgba(34, 197, 94, 0.1), rgba(34, 197, 94, 0.05));
    color: #059669;
    border-left: 4px solid #10b981;
}

.alert-danger {
    background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(239, 68, 68, 0.05));
    color: #dc2626;
    border-left: 4px solid #ef4444;
}

@media (max-width: 768px) {
    .page-title {
        font-size: 2rem;
    }
    
    .checkout-steps {
        flex-direction: column;
        align-items: center;
        gap: 1rem;
    }
    
    .card-row {
        flex-direction: column;
    }
    
    .order-summary {
        position: static;
        margin-top: 2rem;
    }
}
</style>

<!-- Loading Overlay -->
<div class="loading-overlay" id="loadingOverlay">
    <div class="loading-spinner"></div>
    <div class="loading-text">Siparişiniz hazırlanıyor...</div>
</div>

<!-- Page Header -->
<div class="page-header">
    <div class="container">
        <h1 class="page-title">Sipariş Oluştur</h1>
        <div class="checkout-steps">
            <div class="step">
                <div class="step-number">1</div>
                <span>Sepet</span>
            </div>
            <div class="step active">
                <div class="step-number">2</div>
                <span>Sipariş</span>
            </div>
            <div class="step">
                <div class="step-number">3</div>
                <span>Ödeme</span>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <!-- Hata/Başarı Mesajları -->
    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Bayi Bilgilendirmesi -->
@if(auth()->check() && auth()->user()->isBayi())
    <div class="container mb-3">
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>
            <strong>Bayi Hesabı:</strong> Sipariş özeti bayi fiyatlarını göstermektedir.
        </div>
    </div>
@endif

@if($kuponlar->count())
<div class="mb-3">
    <label for="kupon_kodu">Kupon Kodu Kullan:</label>
    <select name="kupon_kodu" id="kupon_kodu" class="form-control">
        <option value="">Seçiniz</option>
        @foreach($kuponlar as $kupon)
            <option value="{{ $kupon->kupon_kodu }}">
                {{ $kupon->kupon_kodu }} - {{ $kupon->baslik }} 
                @if($kupon->indirim_tipi=='yuzde')
                    ({{ $kupon->indirim_miktari }}%)
                @else
                    (₺{{ $kupon->indirim_miktari }})
                @endif
            </option>
        @endforeach
    </select>
</div>
@endif


    <form id="checkoutForm" action="{{ route('siparis.tamamla') }}" method="POST">
        @csrf
        <div class="row g-4">
            <!-- Sipariş Formu -->
            <div class="col-lg-7">
                <div class="glass-card p-4">
                    <!-- Kişisel Bilgiler -->
                    <div class="form-section">
                        <h4 class="section-title">
                            <i class="fas fa-user"></i>
                            Kişisel Bilgiler
                        </h4>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Ad Soyad *</label>
                                    <input type="text" name="ad_soyad" class="form-control" required 
                                           value="{{ old('ad_soyad', Auth::check() ? Auth::user()->name : '') }}" 
                                           placeholder="Adınızı ve soyadınızı girin">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Telefon *</label>
                                    <input type="tel" name="telefon" class="form-control" required 
                                           value="{{ old('telefon') }}"
                                           placeholder="0555 123 4567">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">E-posta</label>
                            <input type="email" name="email" class="form-control" 
                                   value="{{ old('email', Auth::check() ? Auth::user()->email : '') }}" 
                                   placeholder="ornek@email.com">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Teslimat Adresi *</label>
                            <textarea name="kargo_adresi" class="form-control" rows="3" required 
                                      placeholder="Tam adresinizi yazın">{{ old('kargo_adresi') }}</textarea>
                        </div>
                    </div>

                    <!-- Fatura Bilgileri -->
                    <div class="form-section">
                        <h4 class="section-title">
                            <i class="fas fa-file-invoice"></i>
                            Fatura Bilgileri
                        </h4>
                        
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="sameAddress" 
                                   {{ old('same_address', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="sameAddress">
                                Fatura adresi teslimat adresi ile aynı
                            </label>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Fatura Türü</label>
                                <select name="fatura_tipi" class="form-control" id="faturaTipi">
                                    <option value="bireysel" {{ old('fatura_tipi', 'bireysel') == 'bireysel' ? 'selected' : '' }}>Bireysel</option>
                                    <option value="kurumsal" {{ old('fatura_tipi') == 'kurumsal' ? 'selected' : '' }}>Kurumsal</option>
                                </select>
                            </div>
                        </div>
                        
                        <div id="faturaFields" style="{{ old('same_address', true) ? 'display: none;' : '' }}">
                            <div class="row">
                                <div class="col-md-6" id="tckn_field" style="{{ old('fatura_tipi', 'bireysel') == 'kurumsal' ? 'display: none;' : '' }}">
                                    <div class="form-group">
                                        <label class="form-label">TC Kimlik No</label>
                                        <input type="text" name="tc_kimlik_no" class="form-control" 
                                               value="{{ old('tc_kimlik_no') }}"
                                               maxlength="11" placeholder="12345678901">
                                    </div>
                                </div>
                                <div class="col-md-6" id="vergi_dairesi_field" style="{{ old('fatura_tipi', 'bireysel') == 'bireysel' ? 'display: none;' : '' }}">
                                    <div class="form-group">
                                        <label class="form-label">Vergi Dairesi</label>
                                        <input type="text" name="vergi_dairesi" class="form-control" 
                                               value="{{ old('vergi_dairesi') }}"
                                               placeholder="Vergi dairesi adı">
                                    </div>
                                </div>
                                <div class="col-md-6" id="vergi_no_field" style="{{ old('fatura_tipi', 'bireysel') == 'bireysel' ? 'display: none;' : '' }}">
                                    <div class="form-group">
                                        <label class="form-label">Vergi No</label>
                                        <input type="text" name="vergi_no" class="form-control" 
                                               value="{{ old('vergi_no') }}"
                                               maxlength="10" placeholder="1234567890">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Fatura Adresi</label>
                                <textarea name="fatura_adresi" class="form-control" rows="3" 
                                          placeholder="Fatura adresinizi yazın">{{ old('fatura_adresi') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Ödeme Yöntemi -->
                    <div class="form-section">
                        <h4 class="section-title">
                            <i class="fas fa-credit-card"></i>
                            Ödeme Yöntemi
                        </h4>
                        
                        <div class="payment-methods">
                            <!-- Kredi Kartı -->
                            <div class="payment-option {{ old('odeme_yontemi', 'kredi_karti') == 'kredi_karti' ? 'selected' : '' }}" onclick="selectPayment('kredi_karti')">
                                <input type="radio" name="odeme_yontemi" value="kredi_karti" id="kredi_karti" 
                                       {{ old('odeme_yontemi', 'kredi_karti') == 'kredi_karti' ? 'checked' : '' }}>
                                <div class="payment-header">
                                    <div class="payment-icon credit-card">
                                        <i class="fas fa-credit-card"></i>
                                    </div>
                                    <div>
                                        <div class="payment-title">Kredi Kartı</div>
                                        <div class="payment-desc">Güvenli ödeme, anında onay</div>
                                    </div>
                                </div>
                                
                                <div class="credit-card-fields {{ old('odeme_yontemi', 'kredi_karti') == 'kredi_karti' ? 'show' : '' }}" id="creditCardFields">
                                    <div class="form-group">
                                        <label class="form-label">Kart Üzerindeki İsim *</label>
                                        <input type="text" name="kart_isim" class="form-control" 
                                               value="{{ old('kart_isim') }}"
                                               placeholder="AHMET MEHMET">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label">Kart Numarası *</label>
                                        <input type="text" name="kart_no" class="form-control" 
                                               value="{{ old('kart_no') }}"
                                               placeholder="1234 5678 9012 3456" 
                                               maxlength="19" 
                                               oninput="formatCardNumber(this)">
                                    </div>
                                    
                                    <div class="card-row">
                                        <div class="form-group">
                                            <label class="form-label">Son Kullanma Tarihi *</label>
                                            <input type="text" name="kart_tarih" class="form-control" 
                                                   value="{{ old('kart_tarih') }}"
                                                   placeholder="MM/YY" 
                                                   maxlength="5" 
                                                   oninput="formatExpiryDate(this)">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">CVV *</label>
                                            <input type="text" name="kart_cvv" class="form-control" 
                                                   value="{{ old('kart_cvv') }}"
                                                   placeholder="123" 
                                                   maxlength="4">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Kapıda Ödeme -->
                            <div class="payment-option {{ old('odeme_yontemi') == 'kapida_odeme' ? 'selected' : '' }}" onclick="selectPayment('kapida_odeme')">
                                <input type="radio" name="odeme_yontemi" value="kapida_odeme" id="kapida_odeme" 
                                       {{ old('odeme_yontemi') == 'kapida_odeme' ? 'checked' : '' }}>
                                <div class="payment-header">
                                    <div class="payment-icon cash">
                                        <i class="fas fa-money-bill-wave"></i>
                                    </div>
                                    <div>
                                        <div class="payment-title">Kapıda Ödeme</div>
                                        <div class="payment-desc">Nakit veya kartla kapıda öde</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Havale/EFT -->
                            <div class="payment-option {{ old('odeme_yontemi') == 'havale' ? 'selected' : '' }}" onclick="selectPayment('havale')">
                                <input type="radio" name="odeme_yontemi" value="havale" id="havale" 
                                       {{ old('odeme_yontemi') == 'havale' ? 'checked' : '' }}>
                                <div class="payment-header">
                                    <div class="payment-icon transfer">
                                        <i class="fas fa-university"></i>
                                    </div>
                                    <div>
                                        <div class="payment-title">Havale/EFT</div>
                                        <div class="payment-desc">Banka hesabına havale yapın</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sipariş Notu -->
                    <div class="form-section">
                        <h4 class="section-title">
                            <i class="fas fa-sticky-note"></i>
                            Sipariş Notu (İsteğe Bağlı)
                        </h4>
                        <div class="form-group">
                            <textarea name="siparis_notu" class="form-control" rows="3" 
                                      placeholder="Siparişinizle ilgili özel bir notunuz varsa yazabilirsiniz">{{ old('siparis_notu') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sipariş Özeti -->
            <div class="col-lg-5">
                <div class="order-summary">
                    <h4 class="summary-title">
                        <i class="fas fa-shopping-bag"></i>
                        Sipariş Özeti
                    </h4>
                    
                    <div class="order-items">
                        @foreach($sepet as $item)
                        <div class="order-item">
                            <div class="item-image">
                                @if(isset($item['resim']) && $item['resim'])
                                    <img src="{{ asset($item['resim']) }}" alt="{{ $item['isim'] }}" 
                                         style="width: 100%; height: 100%; object-fit: cover; border-radius: 10px;">
                                @else
                                    <i class="fas fa-box"></i>
                                @endif
                            </div>
                            <div class="item-info">
                                <div class="item-name">{{ $item['isim'] }}</div>
                                <div class="item-quantity">{{ $item['adet'] }} adet x {{ number_format($item['fiyat'], 2) }} ₺</div>
                            </div>
                            <div class="item-price">₺{{ number_format($item['fiyat'] * $item['adet'], 2) }}</div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Kupon Bölümü -->
                    <div class="coupon-section">
                        <div class="input-group">
                            <input type="text" name="kupon_kodu" id="kuponKodu" class="form-control" 
                                   value="{{ old('kupon_kodu') }}"
                                   placeholder="Kupon kodunuz">
                            <button class="btn btn-outline-primary" type="button" onclick="kuponKontrol()">
                                <i class="fas fa-tags"></i> Uygula
                            </button>
                        </div>
                        <div id="kuponSonuc" class="mt-2"></div>
                    </div>

                    <div class="summary-totals">
                        <div class="summary-row">
                            <span>Ara Toplam:</span>
                            <span id="araToplam">₺{{ number_format($toplam, 2) }}</span>
                        </div>
                        <div class="summary-row">
                            <span>Kargo:</span>
                            <span class="text-success">Ücretsiz</span>
                        </div>
                        <div class="summary-row">
                            <span>KDV (%18):</span>
                            <span id="kdvTutari">₺{{ number_format($kdvToplam, 2) }}</span>
                        </div>
                        <div class="summary-row" id="kuponIndirimiRow" style="display: none;">
                            <span>Kupon İndirimi:</span>
                            <span id="kuponIndirimi" class="text-success">-₺0.00</span>
                        </div>
                        <div class="summary-row">
                            <span>Toplam:</span>
                            <span id="genelToplam">₺{{ number_format($toplam + $kdvToplam, 2) }}</span>
                        </div>
                    </div>

                    <div class="checkout-actions mt-4">
                        <button type="submit" class="btn-modern btn-success mb-3">
                            <i class="fas fa-shopping-cart me-2"></i>
                            Siparişi Oluştur
                        </button>
                        <a href="{{ route('sepet.index') }}" class="btn-modern btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>
                            Sepete Geri Dön
                        </a>
                    </div>

                    <!-- Güvenlik Bilgileri -->
                    <div class="security-info mt-4 p-3 bg-light rounded">
                        <h6 class="mb-2">
                            <i class="fas fa-shield-alt text-success me-2"></i>
                            Güvenli Alışveriş
                        </h6>
                        <small class="text-muted">
                            Tüm bilgileriniz 256-bit SSL şifreleme ile korunmaktadır.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
// Global değişkenler
let araToplam = {{ $toplam }};
let kdvToplam = {{ $kdvToplam }};
let kuponIndirim = 0;

// Payment method selection
function selectPayment(method) {
    // Remove all selected classes
    document.querySelectorAll('.payment-option').forEach(option => {
        option.classList.remove('selected');
    });
    
    // Hide all credit card fields
    document.querySelectorAll('.credit-card-fields').forEach(field => {
        field.classList.remove('show');
    });
    
    // Select current option
    const selectedOption = document.querySelector(`input[value="${method}"]`).closest('.payment-option');
    selectedOption.classList.add('selected');
    
    // Check radio button
    document.querySelector(`input[value="${method}"]`).checked = true;
    
    // Show credit card fields if credit card is selected
    if (method === 'kredi_karti') {
        document.getElementById('creditCardFields').classList.add('show');
    }
}

// Format card number
function formatCardNumber(input) {
    let value = input.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
    let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
    input.value = formattedValue;
}

// Format expiry date
function formatExpiryDate(input) {
    let value = input.value.replace(/\D/g, '');
    if (value.length >= 2) {
        value = value.substring(0, 2) + '/' + value.substring(2, 4);
    }
    input.value = value;
}

// Kupon kontrol
function kuponKontrol() {
    const kuponKodu = document.getElementById('kuponKodu').value;
    const sonucDiv = document.getElementById('kuponSonuc');
    
    if (!kuponKodu) {
        sonucDiv.innerHTML = '<small class="text-danger">Lütfen kupon kodunu girin.</small>';
        return;
    }
    
    // AJAX ile kupon kontrolü
    fetch('{{ route("siparis.kupon.kontrol") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            kupon_kodu: kuponKodu,
            sepet_toplami: araToplam
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            kuponIndirim = data.indirim;
            sonucDiv.innerHTML = `<small class="text-success"><i class="fas fa-check"></i> ${data.message}</small>`;
            
            // Kupon indirimi satırını göster
            document.getElementById('kuponIndirimiRow').style.display = 'flex';
            document.getElementById('kuponIndirimi').textContent = '-₺' + kuponIndirim.toFixed(2);
            
            // Genel toplamı güncelle
            const yeniToplam = araToplam + kdvToplam - kuponIndirim;
            document.getElementById('genelToplam').textContent = '₺' + yeniToplam.toFixed(2);
        } else {
            sonucDiv.innerHTML = `<small class="text-danger"><i class="fas fa-times"></i> ${data.message}</small>`;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        sonucDiv.innerHTML = '<small class="text-danger">Bir hata oluştu.</small>';
    });
}

// Fatura adresi toggle
document.getElementById('sameAddress').addEventListener('change', function() {
    document.getElementById('faturaFields').style.display = this.checked ? 'none' : 'block';
});

// Fatura türü değişimi
document.getElementById('faturaTipi').addEventListener('change', function() {
    const tcknField = document.getElementById('tckn_field');
    const vergiDairesiField = document.getElementById('vergi_dairesi_field');
    const vergiNoField = document.getElementById('vergi_no_field');
    
    if (this.value === 'kurumsal') {
        tcknField.style.display = 'none';
        vergiDairesiField.style.display = 'block';
        vergiNoField.style.display = 'block';
    } else {
        tcknField.style.display = 'block';
        vergiDairesiField.style.display = 'none';
        vergiNoField.style.display = 'none';
    }
});

// Form submission
document.getElementById('checkoutForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Validate form
    if (!validateForm()) {
        return;
    }
    
    // Show loading
    document.getElementById('loadingOverlay').classList.add('show');
    
    // Submit form after delay
    setTimeout(() => {
        this.submit();
    }, 1000);
});

// Form validation
function validateForm() {
    const requiredFields = document.querySelectorAll('input[required], textarea[required]');
    let isValid = true;
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            field.style.borderColor = '#ff6b6b';
            isValid = false;
        } else {
            field.style.borderColor = '#e9ecef';
        }
    });
    
    // Check payment method
    const paymentMethod = document.querySelector('input[name="odeme_yontemi"]:checked');
    if (!paymentMethod) {
        alert('Lütfen bir ödeme yöntemi seçin.');
        isValid = false;
    }
    
    // If credit card is selected, validate credit card fields
    if (paymentMethod && paymentMethod.value === 'kredi_karti') {
        const cardFields = document.querySelectorAll('#creditCardFields input');
        cardFields.forEach(field => {
            if (field.hasAttribute('required') || field.name === 'kart_isim' || field.name === 'kart_no' || field.name === 'kart_cvv' || field.name === 'kart_tarih') {
                if (!field.value.trim()) {
                    field.style.borderColor = '#ff6b6b';
                    isValid = false;
                } else {
                    field.style.borderColor = '#e9ecef';
                }
            }
        });
        
        // Kart numarası uzunluk kontrolü
        const kartNo = document.querySelector('input[name="kart_no"]').value.replace(/\s/g, '');
        if (kartNo.length !== 16) {
            alert('Kart numarası 16 haneli olmalıdır.');
            isValid = false;
        }
    }
    
    if (!isValid) {
        alert('Lütfen tüm gerekli alanları doldurun.');
    }
    
    return isValid;
}

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    // Set default payment method if not set
    const selectedPayment = document.querySelector('input[name="odeme_yontemi"]:checked');
    if (selectedPayment) {
        selectPayment(selectedPayment.value);
    }
    
    // Add animations to elements
    const elements = document.querySelectorAll('.glass-card, .order-summary');
    elements.forEach((element, index) => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(30px)';
        
        setTimeout(() => {
            element.style.transition = 'all 0.5s ease';
            element.style.opacity = '1';
            element.style.transform = 'translateY(0)';
        }, index * 200);
    });
});
</script>

@endsection