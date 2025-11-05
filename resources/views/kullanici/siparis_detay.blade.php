@extends('layouts.app')

@section('content')
<style>
:root {
    --primary: #667eea;
    --success: #4ecdc4;
    --warning: #fbbf24;
    --danger: #ef4444;
}

.page-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 3rem 0;
    color: white;
    margin-bottom: 2rem;
}

.status-timeline {
    position: relative;
    padding: 2rem 0;
}

.timeline-line {
    position: absolute;
    left: 30px;
    top: 0;
    bottom: 0;
    width: 3px;
    background: #e9ecef;
}

.timeline-step {
    position: relative;
    padding-left: 80px;
    margin-bottom: 2rem;
}

.timeline-icon {
    position: absolute;
    left: 10px;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: white;
    border: 3px solid #e9ecef;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    color: #6c757d;
    z-index: 2;
}

.timeline-step.active .timeline-icon {
    border-color: var(--success);
    background: var(--success);
    color: white;
    box-shadow: 0 0 0 4px rgba(78, 205, 196, 0.2);
}

.timeline-step.current .timeline-icon {
    border-color: var(--primary);
    background: var(--primary);
    color: white;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { box-shadow: 0 0 0 0 rgba(102, 126, 234, 0.4); }
    50% { box-shadow: 0 0 0 10px rgba(102, 126, 234, 0); }
}

.info-card {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    margin-bottom: 1.5rem;
}

.product-list-item {
    display: flex;
    gap: 1rem;
    padding: 1rem;
    border-bottom: 1px solid #f0f0f0;
    align-items: center;
}

.product-list-item:last-child {
    border-bottom: none;
}

.product-image {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 10px;
}

.status-badge {
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-weight: 600;
    font-size: 0.9rem;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.badge-beklemede, .badge-odemebekliyor { background: #fef3c7; color: #d97706; }
.badge-onaylandi { background: #d1fae5; color: #059669; }
.badge-hazirlaniyor { background: #dbeafe; color: #2563eb; }
.badge-kargoda { background: #e0e7ff; color: #6366f1; }
.badge-teslimedildi { background: #dcfce7; color: #16a34a; }
.badge-iptaledildi { background: #fee2e2; color: #dc2626; }

.action-button {
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-block;
}

.action-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

.btn-primary-custom { background: var(--primary); color: white; }
.btn-success-custom { background: var(--success); color: white; }
.btn-secondary-custom { background: #6c757d; color: white; }
</style>

<!-- Page Header -->
<div class="page-header">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-2">Sipariş Detayı</h2>
                <p class="mb-0 opacity-75">Sipariş No: {{ $siparis->siparis_no }}</p>
            </div>
            <div class="text-end">
                <div class="fs-4 mb-1">₺{{ number_format($siparis->toplam_tutar + $siparis->kdv_tutari - $siparis->indirim_tutari, 2) }}</div>
                <small>{{ $siparis->created_at->format('d.m.Y H:i') }}</small>
            </div>
        </div>
    </div>
</div>

<div class="container py-4">
    <div class="row">
        <!-- Sol Taraf - Sipariş Takibi -->
        <div class="col-lg-8">
            <!-- Sipariş Durumu Timeline -->
            <div class="info-card">
                <h5 class="mb-4"><i class="fas fa-route me-2 text-primary"></i>Sipariş Takibi</h5>
                
                <div class="status-timeline">
                    <div class="timeline-line"></div>
                    
                    <div class="timeline-step {{ $siparis->durum != 'iptal_edildi' ? 'active' : '' }}">
                        <div class="timeline-icon">
                            <i class="fas fa-check"></i>
                        </div>
                        <div>
                            <strong>Sipariş Alındı</strong>
                            <div class="text-muted small">{{ $siparis->created_at->format('d.m.Y H:i') }}</div>
                            <p class="text-muted small mb-0">Siparişiniz başarıyla oluşturuldu</p>
                        </div>
                    </div>
                    
                    <div class="timeline-step {{ in_array($siparis->durum, ['onaylandi', 'hazirlaniyor', 'kargoda', 'teslim_edildi']) ? 'active' : ($siparis->durum == 'beklemede' ? 'current' : '') }}">
                        <div class="timeline-icon">
                            <i class="fas fa-clipboard-check"></i>
                        </div>
                        <div>
                            <strong>Sipariş Onaylandı</strong>
                            @if(in_array($siparis->durum, ['onaylandi', 'hazirlaniyor', 'kargoda', 'teslim_edildi']))
                                <div class="text-muted small">Onaylandı</div>
                                <p class="text-muted small mb-0">Siparişiniz onaylandı ve işleme alındı</p>
                            @else
                                <div class="text-warning small">Onay bekleniyor...</div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="timeline-step {{ in_array($siparis->durum, ['hazirlaniyor', 'kargoda', 'teslim_edildi']) ? 'active' : ($siparis->durum == 'onaylandi' ? 'current' : '') }}">
                        <div class="timeline-icon">
                            <i class="fas fa-box"></i>
                        </div>
                        <div>
                            <strong>Hazırlanıyor</strong>
                            @if(in_array($siparis->durum, ['hazirlaniyor', 'kargoda', 'teslim_edildi']))
                                <div class="text-muted small">Hazırlanıyor</div>
                                <p class="text-muted small mb-0">Siparişiniz paketleniyor</p>
                            @else
                                <div class="text-muted small">Bekliyor...</div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="timeline-step {{ in_array($siparis->durum, ['kargoda', 'teslim_edildi']) ? 'active' : ($siparis->durum == 'hazirlaniyor' ? 'current' : '') }}">
                        <div class="timeline-icon">
                            <i class="fas fa-truck"></i>
                        </div>
                        <div>
                            <strong>Kargoya Verildi</strong>
                            @if(in_array($siparis->durum, ['kargoda', 'teslim_edildi']))
                                <div class="text-muted small">Kargoda</div>
                                <p class="text-muted small mb-0">Siparişiniz kargoya teslim edildi</p>
                            @else
                                <div class="text-muted small">Bekliyor...</div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="timeline-step {{ $siparis->durum == 'teslim_edildi' ? 'active' : ($siparis->durum == 'kargoda' ? 'current' : '') }}">
                        <div class="timeline-icon">
                            <i class="fas fa-home"></i>
                        </div>
                        <div>
                            <strong>Teslim Edildi</strong>
                            @if($siparis->durum == 'teslim_edildi')
                                <div class="text-success small">{{ $siparis->updated_at->format('d.m.Y H:i') }}</div>
                                <p class="text-muted small mb-0">Siparişiniz teslim edildi.  🎉</p>
                            @else
                                <div class="text-muted small">Bekliyor...</div>
                            @endif
                        </div>
                    </div>
                    
                    @if($siparis->durum == 'iptal_edildi')
                    <div class="timeline-step active">
                        <div class="timeline-icon" style="background: var(--danger); border-color: var(--danger);">
                            <i class="fas fa-times"></i>
                        </div>
                        <div>
                            <strong class="text-danger">Sipariş İptal Edildi</strong>
                            <div class="text-muted small">{{ $siparis->updated_at->format('d.m.Y H:i') }}</div>
                            <p class="text-muted small mb-0">Siparişiniz iptal edildi</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Sipariş Ürünleri -->
            <div class="info-card">
                <h5 class="mb-4"><i class="fas fa-shopping-bag me-2 text-primary"></i>Sipariş Ürünleri</h5>
                
                @foreach($siparis->urunler as $item)
                <div class="product-list-item">
                    @if($item->urun && $item->urun->resim)
                        <img src="{{ asset($item->urun->resim) }}" alt="{{ $item->urun->isim }}" class="product-image">
                    @else
                        <div class="product-image bg-light d-flex align-items-center justify-content-center">
                            <i class="fas fa-image fa-2x text-muted"></i>
                        </div>
                    @endif
                    
                    <div class="flex-grow-1">
                        <h6 class="mb-1">{{ $item->urun->isim ?? 'Ürün' }}</h6>
                        <div class="text-muted small">
                            {{ $item->adet }} adet × ₺{{ number_format($item->birim_fiyat, 2) }}
                        </div>
                    </div>
                    
                    <div class="text-end">
                        <strong class="d-block">₺{{ number_format($item->toplam_fiyat + $item->kdv_tutari, 2) }}</strong>
                        @if($item->kdv_tutari > 0)
                            <small class="text-muted">KDV: ₺{{ number_format($item->kdv_tutari, 2) }}</small>
                        @endif
                    </div>
                </div>
                @endforeach

                <!-- Özet -->
                <div class="mt-4 pt-3 border-top">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Ara Toplam:</span>
                        <span>₺{{ number_format($siparis->toplam_tutar, 2) }}</span>
                    </div>
                    @if($siparis->kdv_tutari > 0)
                    <div class="d-flex justify-content-between mb-2">
                        <span>KDV (%18):</span>
                        <span>₺{{ number_format($siparis->kdv_tutari, 2) }}</span>
                    </div>
                    @endif
                    @if($siparis->indirim_tutari > 0)
                    <div class="d-flex justify-content-between mb-2">
                        <span>İndirim:</span>
                        <span class="text-success">-₺{{ number_format($siparis->indirim_tutari, 2) }}</span>
                    </div>
                    @endif
                    <div class="d-flex justify-content-between mb-2">
                        <span>Kargo:</span>
                        <span class="text-success">Ücretsiz</span>
                    </div>
                    <div class="d-flex justify-content-between fw-bold fs-5 text-primary pt-3 border-top">
                        <span>Genel Toplam:</span>
                        <span>₺{{ number_format($siparis->toplam_tutar + $siparis->kdv_tutari - $siparis->indirim_tutari, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Admin Notları (Eğer varsa) -->
            @if($siparis->notlar && trim($siparis->notlar) != '')
            <div class="info-card">
                <h5 class="mb-3"><i class="fas fa-comment-alt me-2 text-primary"></i>Sipariş Notları</h5>
                <div class="alert alert-info mb-0">
                    {!! nl2br(e($siparis->notlar)) !!}
                </div>
            </div>
            @endif
        </div>

        <!-- Sağ Taraf - Bilgiler -->
        <div class="col-lg-4">
            <!-- Durum Kartı -->
            <div class="info-card">
                <h6 class="mb-3"><i class="fas fa-info-circle me-2"></i>Sipariş Durumu</h6>
                
                <div class="mb-3">
                    <span class="status-badge badge-{{ str_replace('_', '', $siparis->durum) }}">
                        <i class="fas fa-circle"></i>
                        {{ ucfirst(str_replace('_', ' ', $siparis->durum)) }}
                    </span>
                </div>
                
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Ödeme Durumu:</span>
                    <strong class="{{ $siparis->odeme_durumu == 'odendi' ? 'text-success' : 'text-warning' }}">
                        {{ ucfirst($siparis->odeme_durumu) }}
                    </strong>
                </div>
                
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Ödeme Tipi:</span>
                    <strong>{{ ucfirst(str_replace('_', ' ', $siparis->odeme_tipi)) }}</strong>
                </div>
            </div>

            <!-- Teslimat Bilgileri -->
            <div class="info-card">
                <h6 class="mb-3"><i class="fas fa-map-marker-alt me-2"></i>Teslimat Adresi</h6>
                <p class="text-muted mb-0">{{ $siparis->kargo_adresi }}</p>
            </div>

            <!-- Fatura Bilgileri -->
            @if($fatura)
            <div class="info-card">
                <h6 class="mb-3"><i class="fas fa-file-invoice me-2"></i>Fatura Bilgileri</h6>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Fatura No:</span>
                    <strong>{{ $fatura->fatura_no }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Ünvan:</span>
                    <strong>{{ $fatura->unvan }}</strong>
                </div>
                <div class="mt-3">
                    <a href="{{ route('fatura.goster', $siparis->id) }}" target="_blank" class="action-button btn-primary-custom w-100 text-center">
                        <i class="fas fa-download me-2"></i>Faturayı Görüntüle
                    </a>
                </div>
            </div>
            @endif

            <!-- İletişim -->
            <div class="info-card">
                <h6 class="mb-3"><i class="fas fa-headset me-2"></i>Yardım mı Gerekiyor?</h6>
                <p class="text-muted small mb-3">Siparişinizle ilgili sorularınız için destek ekibimizle iletişime geçebilirsiniz.</p>
                
                <div class="mb-2">
                    <i class="fas fa-phone me-2 text-primary"></i>
                    <strong>0850 123 45 67</strong>
                </div>
                <div class="mb-2">
                    <i class="fas fa-envelope me-2 text-primary"></i>
                    <strong>destek@example.com</strong>
                </div>
            </div>

            <!-- Aksiyonlar -->
            <div class="d-grid gap-2">
                <a href="{{ route('siparislerim') }}" class="action-button btn-secondary-custom text-center">
                    <i class="fas fa-list me-2"></i>Tüm Siparişlerim
                </a>
                
                <a href="{{ route('home') }}" class="action-button btn-success-custom text-center">
                    <i class="fas fa-home me-2"></i>Ana Sayfaya Dön
                </a>
            </div>
        </div>
    </div>
</div>

<script>
// Sayfa yüklendiğinde animasyon
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.info-card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            card.style.transition = 'all 0.5s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
    
    // Otomatik yenileme (30 saniyede bir)
    setInterval(() => {
        if(!['teslim_edildi', 'iptal_edildi'].includes('{{ $siparis->durum }}')) {
            location.reload();
        }
    }, 30000);
});
</script>
@endsection