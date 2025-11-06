@extends('layouts.app')
@section('title', 'Sipariş ')

@section('content')
<style>
:root {
    --success-gradient: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%);
    --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --text-primary: #2d3436;
    --text-secondary: #636e72;
    --glass-bg: rgba(255, 255, 255, 0.95);
    --shadow: 0 8px 32px rgba(31, 38, 135, 0.37);
}

body {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    min-height: 100vh;
}

.success-hero {
    background: var(--success-gradient);
    padding: 6rem 0 4rem;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.success-hero::before {
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

.success-icon {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: white;
    color: #4ecdc4;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
    margin: 0 auto 2rem;
    animation: bounce 2s ease-in-out infinite;
    position: relative;
    z-index: 2;
}

@keyframes bounce {
    0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
    40% { transform: translateY(-10px); }
    60% { transform: translateY(-5px); }
}

.success-title {
    color: white;
    font-size: 3rem;
    font-weight: 800;
    margin-bottom: 1rem;
    text-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    position: relative;
    z-index: 2;
}

.success-subtitle {
    color: rgba(255, 255, 255, 0.9);
    font-size: 1.2rem;
    margin-bottom: 2rem;
    position: relative;
    z-index: 2;
}

.order-number {
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    border-radius: 50px;
    padding: 1rem 2rem;
    display: inline-block;
    color: white;
    font-weight: 700;
    font-size: 1.1rem;
    margin-bottom: 2rem;
    position: relative;
    z-index: 2;
}

.glass-card {
    background: var(--glass-bg);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 20px;
    box-shadow: var(--shadow);
    transition: all 0.3s ease;
}

.glass-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(31, 38, 135, 0.3);
}

.section-title {
    font-size: 1.5rem;
    font-weight: 800;
    color: var(--text-primary);
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.order-info {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.info-card {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
    text-align: center;
}

.info-icon {
    width: 50px;
    height: 50px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    margin: 0 auto 1rem;
    color: white;
}

.info-icon.payment {
    background: var(--success-gradient);
}

.info-icon.shipping {
    background: var(--primary-gradient);
}

.info-icon.status {
    background: linear-gradient(135deg, #ffd93d 0%, #ff9500 100%);
}

.info-title {
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

.info-value {
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.order-items {
    background: white;
    border-radius: 15px;
    padding: 0;
    overflow: hidden;
}

.order-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.5rem;
    border-bottom: 1px solid #f0f0f0;
}

.order-item:last-child {
    border-bottom: none;
}

.item-image {
    width: 60px;
    height: 60px;
    border-radius: 10px;
    object-fit: cover;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-secondary);
    font-size: 1.5rem;
}

.item-info {
    flex: 1;
}

.item-name {
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.25rem;
}

.item-details {
    color: var(--text-secondary);
    font-size: 0.85rem;
}

.item-price {
    font-weight: 700;
    color: var(--text-primary);
    font-size: 1.1rem;
}

.order-summary {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    margin-top: 1rem;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
    border-bottom: 1px solid #f0f0f0;
}

.summary-row:last-child {
    border-bottom: none;
    font-size: 1.2rem;
    font-weight: 800;
    color: #4ecdc4;
    margin-top: 0.5rem;
    padding-top: 1rem;
    border-top: 2px solid #f0f0f0;
}

.action-buttons {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-top: 2rem;
}

.btn-modern {
    padding: 1rem 2rem;
    border: none;
    border-radius: 25px;
    font-weight: 700;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.btn-primary {
    background: var(--primary-gradient);
    color: white;
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}

.btn-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(102, 126, 234, 0.6);
    color: white;
    text-decoration: none;
}

.btn-success {
    background: var(--success-gradient);
    color: white;
    box-shadow: 0 5px 15px rgba(78, 205, 196, 0.4);
}

.btn-success:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(78, 205, 196, 0.6);
    color: white;
    text-decoration: none;
}

.btn-secondary {
    background: #6c757d;
    color: white;
    box-shadow: 0 5px 15px rgba(108, 117, 125, 0.4);
}

.btn-secondary:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(108, 117, 125, 0.6);
    color: white;
    text-decoration: none;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-weight: 600;
    font-size: 0.85rem;
}

.status-badge.success {
    background: linear-gradient(135deg, rgba(34, 197, 94, 0.1), rgba(34, 197, 94, 0.05));
    color: #059669;
    border: 1px solid rgba(34, 197, 94, 0.2);
}

.status-badge.pending {
    background: linear-gradient(135deg, rgba(251, 191, 36, 0.1), rgba(251, 191, 36, 0.05));
    color: #d97706;
    border: 1px solid rgba(251, 191, 36, 0.2);
}

.timeline {
    position: relative;
    padding-left: 2rem;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 1rem;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e9ecef;
}

.timeline-item {
    position: relative;
    margin-bottom: 2rem;
}

.timeline-item::before {
    content: '';
    position: absolute;
    left: -1.5rem;
    top: 0.5rem;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: #4ecdc4;
}

.timeline-item.active::before {
    background: #4ecdc4;
    box-shadow: 0 0 0 3px rgba(78, 205, 196, 0.3);
}

.timeline-item.pending::before {
    background: #e9ecef;
}

.timeline-content h6 {
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 0.25rem;
}

.timeline-content p {
    color: var(--text-secondary);
    font-size: 0.85rem;
    margin: 0;
}

@media (max-width: 768px) {
    .success-title {
        font-size: 2rem;
    }
    
    .order-info {
        grid-template-columns: 1fr;
    }
    
    .action-buttons {
        grid-template-columns: 1fr;
    }
    
    .order-item {
        padding: 1rem;
    }
}
</style>

<!-- Success Hero Section -->
<div class="success-hero">
    <div class="container">
        <div class="success-icon">
            <i class="fas fa-check"></i>
        </div>
        <h1 class="success-title">Siparişiniz Alındı!</h1>
        <p class="success-subtitle">Siparişiniz başarıyla oluşturuldu ve işleme alınmıştır.</p>
        <div class="order-number">
            <i class="fas fa-receipt me-2"></i>
            Sipariş No: {{ $siparis->siparis_no }}
        </div>
    </div>
</div>

<div class="container py-5">
    <!-- Sipariş Bilgi Kartları -->
    <div class="order-info">
        <div class="info-card">
            <div class="info-icon payment">
                <i class="fas fa-credit-card"></i>
            </div>
            <div class="info-title">Ödeme Durumu</div>
            <div class="info-value">
                @if($siparis->odeme_durumu == 'odendi')
                    <span class="status-badge success">
                        <i class="fas fa-check-circle"></i>
                        Ödeme Tamamlandı
                    </span>
                @else
                    <span class="status-badge pending">
                        <i class="fas fa-clock"></i>
                        {{ ucfirst($siparis->odeme_durumu) }}
                    </span>
                @endif
            </div>
        </div>
        
        <div class="info-card">
            <div class="info-icon shipping">
                <i class="fas fa-truck"></i>
            </div>
            <div class="info-title">Kargo Durumu</div>
            <div class="info-value">
                @switch($siparis->durum)
                    @case('onaylandi')
                        <span class="status-badge success">
                            <i class="fas fa-check-circle"></i>
                            Onaylandı
                        </span>
                        @break
                    @default
                        <span class="status-badge pending">
                            <i class="fas fa-clock"></i>
                            {{ ucfirst($siparis->durum) }}
                        </span>
                @endswitch
            </div>
        </div>
        
        <div class="info-card">
            <div class="info-icon status">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div class="info-title">Sipariş Tarihi</div>
            <div class="info-value">{{ $siparis->created_at->format('d.m.Y H:i') }}</div>
        </div>
    </div>

    <div class="row g-4 mt-4">
        <!-- Sol Taraf - Sipariş Detayları -->
        <div class="col-lg-8">
            <div class="glass-card p-4">
                <h4 class="section-title">
                    <i class="fas fa-box"></i>
                    Sipariş Detayları
                </h4>
                
                <!-- Sipariş Ürünleri -->
                <div class="order-items">
                    @foreach($siparis->urunler as $item)
                        <div class="order-item">
                            <div class="item-image">
                                @if($item->urun && $item->urun->resim)
                                    <img src="{{ asset($item->urun->resim) }}" alt="{{ $item->urun->isim }}" 
                                         style="width: 100%; height: 100%; object-fit: cover; border-radius: 10px;">
                                @else
                                    <i class="fas fa-box"></i>
                                @endif
                            </div>
                            <div class="item-info">
                                <div class="item-name">{{ $item->urun->isim ?? 'Ürün' }}</div>
                                <div class="item-details">
                                    {{ $item->adet }} adet × ₺{{ number_format($item->birim_fiyat, 2) }}
                                    @if($item->kdv_tutari > 0)
                                        <br><small class="text-muted">KDV: ₺{{ number_format($item->kdv_tutari, 2) }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="item-price">₺{{ number_format($item->toplam_fiyat + $item->kdv_tutari, 2) }}</div>
                        </div>
                    @endforeach
                </div>

                <!-- Sipariş Özeti -->
                <div class="order-summary">
                    <div class="summary-row">
                        <span>Ara Toplam:</span>
                        <span>₺{{ number_format($siparis->toplam_tutar, 2) }}</span>
                    </div>
                    @if($siparis->kdv_tutari > 0)
                        <div class="summary-row">
                            <span>KDV (%18):</span>
                            <span>₺{{ number_format($siparis->kdv_tutari, 2) }}</span>
                        </div>
                    @endif
                    @if($siparis->indirim_tutari > 0)
                        <div class="summary-row">
                            <span>İndirim:</span>
                            <span class="text-success">-₺{{ number_format($siparis->indirim_tutari, 2) }}</span>
                        </div>
                    @endif
                    @if($siparis->kupon_kodu)
                        <div class="summary-row">
                            <span>Kupon ({{ $siparis->kupon_kodu }}):</span>
                            <span class="text-success">-₺{{ number_format($siparis->indirim_tutari, 2) }}</span>
                        </div>
                    @endif
                    <div class="summary-row">
                        <span>Kargo:</span>
                        <span class="text-success">Ücretsiz</span>
                    </div>
                    <div class="summary-row">
                        <span>Genel Toplam:</span>
                        <span>₺{{ number_format($siparis->toplam_tutar + $siparis->kdv_tutari - $siparis->indirim_tutari, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sağ Taraf - Teslimat & İşlem Durumu -->
        <div class="col-lg-4">
            <!-- Teslimat Bilgileri -->
            <div class="glass-card p-4 mb-4">
                <h5 class="section-title">
                    <i class="fas fa-map-marker-alt"></i>
                    Teslimat Bilgileri
                </h5>
                <div class="mb-3">
                    <h6 class="mb-1">Teslimat Adresi:</h6>
                    <p class="text-muted mb-0">{{ $siparis->kargo_adresi }}</p>
                </div>
                
                @if($siparis->notlar)
                    <div class="mb-3">
                        <h6 class="mb-1">Notlar:</h6>
                        <p class="text-muted mb-0">{{ $siparis->notlar }}</p>
                    </div>
                @endif
            </div>

            <!-- Sipariş Durumu Timeline -->
            <div class="glass-card p-4 mb-4">
                <h5 class="section-title">
                    <i class="fas fa-tasks"></i>
                    Sipariş Durumu
                </h5>
                
                <div class="timeline">
                    <div class="timeline-item active">
                        <div class="timeline-content">
                            <h6>Sipariş Alındı</h6>
                            <p>{{ $siparis->created_at->format('d.m.Y H:i') }}</p>
                        </div>
                    </div>
                    
                    @if($siparis->odeme_durumu == 'odendi')
                        <div class="timeline-item active">
                            <div class="timeline-content">
                                <h6>Ödeme Onaylandı</h6>
                                <p>Ödemeniz başarıyla alınmıştır</p>
                            </div>
                        </div>
                    @else
                        <div class="timeline-item pending">
                            <div class="timeline-content">
                                <h6>Ödeme Bekleniyor</h6>
                                <p>{{ ucfirst($siparis->odeme_durumu) }}</p>
                            </div>
                        </div>
                    @endif
                    
                    @if($siparis->durum == 'onaylandi')
                        <div class="timeline-item active">
                            <div class="timeline-content">
                                <h6>Sipariş Onaylandı</h6>
                                <p>Hazırlık aşamasında</p>
                            </div>
                        </div>
                    @else
                        <div class="timeline-item pending">
                            <div class="timeline-content">
                                <h6>Sipariş İşleniyor</h6>
                                <p>{{ ucfirst($siparis->durum) }}</p>
                            </div>
                        </div>
                    @endif
                    
                    <div class="timeline-item pending">
                        <div class="timeline-content">
                            <h6>Kargoya Verildi</h6>
                            <p>Yakında güncellenir</p>
                        </div>
                    </div>
                    
                    <div class="timeline-item pending">
                        <div class="timeline-content">
                            <h6>Teslim Edildi</h6>
                            <p>Yakında güncellenir</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- İletişim Bilgileri -->
            <div class="glass-card p-4">
                <h5 class="section-title">
                    <i class="fas fa-headset"></i>
                    Yardım & Destek
                </h5>
                <p class="text-muted mb-3">Siparişinizle ilgili sorularınız için bizimle iletişime geçebilirsiniz.</p>
                
                <div class="mb-2">
                    <i class="fas fa-phone me-2 text-primary"></i>
                    <strong>0850 123 45 67</strong>
                </div>
                <div class="mb-2">
                    <i class="fas fa-envelope me-2 text-primary"></i>
                    <strong>destek@example.com</strong>
                </div>
                <div>
                    <i class="fas fa-clock me-2 text-primary"></i>
                    <strong>09:00 - 18:00 (Hafta içi)</strong>
                </div>
            </div>
        </div>
    </div>

    <!-- Aksiyon Butonları -->
    <div class="action-buttons">
        <a href="{{ route('siparis.detay', $siparis->id) }}" class="btn-modern btn-primary">
            <i class="fas fa-eye"></i>
            Sipariş Detayı
        </a>
        
        <a href="{{ route('fatura.goster', $siparis->id) }}" class="btn-modern btn-success">
            <i class="fas fa-file-invoice"></i>
            Faturayı Görüntüle
        </a>
        
        <a href="{{ route('siparislerim') }}" class="btn-modern btn-secondary">
            <i class="fas fa-list"></i>
            Tüm Siparişlerim
        </a>
        
        <a href="{{ route('home') }}" class="btn-modern btn-secondary">
            <i class="fas fa-home"></i>
            Ana Sayfaya Dön
        </a>
    </div>

    <!-- Ek Bilgilendirme -->
    <div class="glass-card p-4 mt-4">
        <div class="row">
            <div class="col-md-6">
                <h6><i class="fas fa-info-circle me-2 text-primary"></i>Önemli Bilgiler</h6>
                <ul class="list-unstyled text-muted">
                    <li class="mb-2"><i class="fas fa-check me-2 text-success"></i>Siparişinizin durumu e-posta ile bildirilecektir</li>
                    <li class="mb-2"><i class="fas fa-check me-2 text-success"></i>Kargo takip numarası SMS ile gönderilecektir</li>
                    <li class="mb-2"><i class="fas fa-check me-2 text-success"></i>14 gün içinde iade edebilirsiniz</li>
                </ul>
            </div>
            <div class="col-md-6">
                <h6><i class="fas fa-shield-alt me-2 text-primary"></i>Güvence</h6>
                <ul class="list-unstyled text-muted">
                    <li class="mb-2"><i class="fas fa-check me-2 text-success"></i>256-bit SSL güvenlik sertifikası</li>
                    <li class="mb-2"><i class="fas fa-check me-2 text-success"></i>Güvenli ödeme altyapısı</li>
                    <li class="mb-2"><i class="fas fa-check me-2 text-success"></i>Kişisel verileriniz korunmaktadır</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add entrance animations
    const elements = document.querySelectorAll('.glass-card, .info-card');
    elements.forEach((element, index) => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(30px)';
        
        setTimeout(() => {
            element.style.transition = 'all 0.6s ease';
            element.style.opacity = '1';
            element.style.transform = 'translateY(0)';
        }, index * 100);
    });
    
    // Confetti effect (optional)
    if (typeof confetti !== 'undefined') {
        confetti({
            particleCount: 100,
            spread: 70,
            origin: { y: 0.6 }
        });
    }
});
</script>

@endsection