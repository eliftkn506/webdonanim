@extends('layouts.app')
@section('title', 'Profil ')

@section('content')
<style>
:root {
    --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --success-gradient: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%);
    --text-primary: #2d3436;
    --text-secondary: #636e72;
    --glass-bg: rgba(255, 255, 255, 0.95);
    --shadow: 0 8px 32px rgba(31, 38, 135, 0.37);
}

body {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    min-height: 100vh;
}

.profile-header {
    background: var(--primary-gradient);
    padding: 3rem 0;
    color: white;
    position: relative;
    overflow: hidden;
}

.profile-header::before {
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

.nav-tabs-custom {
    border: none;
    background: white;
    border-radius: 15px;
    padding: 0.5rem;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
}

.nav-tabs-custom .nav-link {
    border: none;
    border-radius: 10px;
    color: var(--text-secondary);
    font-weight: 600;
    padding: 0.75rem 1.5rem;
    margin: 0 0.25rem;
    transition: all 0.3s ease;
}

.nav-tabs-custom .nav-link:hover {
    background: rgba(102, 126, 234, 0.1);
    color: #667eea;
}

.nav-tabs-custom .nav-link.active {
    background: var(--primary-gradient);
    color: white;
}

.order-card {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    margin-bottom: 1rem;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
}

.order-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
}

.order-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #f0f0f0;
}

.order-number {
    font-weight: 700;
    color: var(--text-primary);
    font-size: 1.1rem;
}

.order-date {
    color: var(--text-secondary);
    font-size: 0.9rem;
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

.status-badge.beklemede {
    background: linear-gradient(135deg, rgba(251, 191, 36, 0.1), rgba(251, 191, 36, 0.05));
    color: #d97706;
    border: 1px solid rgba(251, 191, 36, 0.2);
}

.status-badge.onaylandi {
    background: linear-gradient(135deg, rgba(34, 197, 94, 0.1), rgba(34, 197, 94, 0.05));
    color: #059669;
    border: 1px solid rgba(34, 197, 94, 0.2);
}

.status-badge.kargoda {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(59, 130, 246, 0.05));
    color: #2563eb;
    border: 1px solid rgba(59, 130, 246, 0.2);
}

.status-badge.teslim-edildi {
    background: linear-gradient(135deg, rgba(34, 197, 94, 0.1), rgba(34, 197, 94, 0.05));
    color: #059669;
    border: 1px solid rgba(34, 197, 94, 0.2);
}

.status-badge.iptal {
    background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(239, 68, 68, 0.05));
    color: #dc2626;
    border: 1px solid rgba(239, 68, 68, 0.2);
}

.order-items {
    margin: 1rem 0;
}

.order-item-mini {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.5rem 0;
}

.item-image-mini {
    width: 50px;
    height: 50px;
    border-radius: 8px;
    object-fit: cover;
    background: #f8f9fa;
}

.order-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 2px solid #f0f0f0;
}

.order-total {
    font-weight: 800;
    color: #4ecdc4;
    font-size: 1.2rem;
}

.btn-modern {
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 25px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
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
}

.empty-state {
    text-align: center;
    padding: 3rem 1rem;
    color: var(--text-secondary);
}

.empty-state i {
    font-size: 4rem;
    color: #ddd;
    margin-bottom: 1rem;
}

@media (max-width: 768px) {
    .order-header, .order-footer {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }
}
</style>

<div class="profile-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-auto">
                <div style="width: 80px; height: 80px; border-radius: 50%; background: white; color: #667eea; display: flex; align-items: center; justify-content: center; font-size: 2rem; font-weight: 800;">
                    {{ substr($kullanici->name, 0, 1) }}
                </div>
            </div>
            <div class="col">
                <h2 style="margin: 0; font-weight: 800;">{{ $kullanici->name }}</h2>
                <p style="margin: 0; opacity: 0.9;">{{ $kullanici->email }}</p>
                @if($kullanici->isBayi())
                    <span class="badge bg-warning text-dark mt-2">
                        <i class="fas fa-star"></i> Bayi Üyesi
                    </span>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <ul class="nav nav-tabs nav-tabs-custom mb-4" id="profileTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="siparisler-tab" data-bs-toggle="tab" data-bs-target="#siparisler" type="button">
                <i class="fas fa-shopping-bag me-2"></i>Siparişlerim
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="konfigurasyonlar-tab" data-bs-toggle="tab" data-bs-target="#konfigurasyonlar" type="button">
                <i class="fas fa-cog me-2"></i>Konfigürasyonlarım
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="favoriler-tab" data-bs-toggle="tab" data-bs-target="#favoriler" type="button">
                <i class="fas fa-heart me-2"></i>Favorilerim
            </button>
        </li>
    </ul>

    <div class="tab-content" id="profileTabContent">
        <!-- SİPARİŞLERİM -->
        <div class="tab-pane fade show active" id="siparisler" role="tabpanel">
            @if($siparisler->count() > 0)
                @foreach($siparisler as $siparis)
                    <div class="order-card">
                        <div class="order-header">
                            <div>
                                <div class="order-number">
                                    <i class="fas fa-receipt me-2"></i>
                                    Sipariş No: {{ $siparis->siparis_no }}
                                </div>
                                <div class="order-date">
                                    <i class="far fa-calendar me-1"></i>
                                    {{ $siparis->created_at->format('d.m.Y H:i') }}
                                </div>
                            </div>
                            <div>
                                @php
                                    $statusClass = match($siparis->durum) {
                                        'beklemede' => 'beklemede',
                                        'onaylandi' => 'onaylandi',
                                        'kargoda' => 'kargoda',
                                        'teslim-edildi' => 'teslim-edildi',
                                        'iptal' => 'iptal',
                                        default => 'beklemede'
                                    };
                                    $statusIcon = match($siparis->durum) {
                                        'beklemede' => 'fa-clock',
                                        'onaylandi' => 'fa-check-circle',
                                        'kargoda' => 'fa-truck',
                                        'teslim-edildi' => 'fa-check-double',
                                        'iptal' => 'fa-times-circle',
                                        default => 'fa-clock'
                                    };
                                @endphp
                                <span class="status-badge {{ $statusClass }}">
                                    <i class="fas {{ $statusIcon }}"></i>
                                    {{ ucfirst($siparis->durum) }}
                                </span>
                            </div>
                        </div>

                        <div class="order-items">
                            @foreach($siparis->urunler->take(3) as $item)
                                <div class="order-item-mini">
                                    @if($item->urun && $item->urun->resim_url)
                                        <img src="{{ asset($item->urun->resim_url) }}" 
                                             alt="{{ $item->urun->urun_ad }}" 
                                             class="item-image-mini">
                                    @else
                                        <div class="item-image-mini">
                                            <i class="fas fa-box"></i>
                                        </div>
                                    @endif
                                    <div class="flex-grow-1">
                                        <div class="fw-600">{{ $item->urun->urun_ad ?? 'Ürün' }}</div>
                                        <small class="text-muted">{{ $item->adet }} adet × ₺{{ number_format($item->birim_fiyat, 2) }}</small>
                                    </div>
                                </div>
                            @endforeach
                            @if($siparis->urunler->count() > 3)
                                <small class="text-muted">+{{ $siparis->urunler->count() - 3 }} ürün daha...</small>
                            @endif
                        </div>

                        <div class="order-footer">
                            <div class="order-total">
                                Toplam: ₺{{ number_format($siparis->toplam_tutar + $siparis->kdv_tutari - $siparis->indirim_tutari, 2) }}
                            </div>
                            <div>
                                <a href="{{ route('siparis.detay', $siparis->id) }}" class="btn-modern btn-primary">
                                    <i class="fas fa-eye"></i>
                                    Detay
                                </a>
                                @if($siparis->odeme_durumu == 'odendi')
                                    <a href="{{ route('fatura.goster', $siparis->id) }}" class="btn-modern btn-success">
                                        <i class="fas fa-file-invoice"></i>
                                        Fatura
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="glass-card p-5">
                    <div class="empty-state">
                        <i class="fas fa-shopping-bag"></i>
                        <h4>Henüz siparişiniz yok</h4>
                        <p>Alışverişe başlamak için ürünleri keşfedin!</p>
                        <a href="{{ route('home') }}" class="btn-modern btn-primary mt-3">
                            <i class="fas fa-shopping-cart"></i>
                            Alışverişe Başla
                        </a>
                    </div>
                </div>
            @endif
        </div>

        <!-- KONFİGÜRASYONLARIM -->
        <div class="tab-pane fade" id="konfigurasyonlar" role="tabpanel">
            @if($konfiglar->count() > 0)
                @foreach($konfiglar as $konfig)
                    <div class="glass-card p-4 mb-3">
                        <h5>{{ $konfig->isim }}</h5>
                        <p class="text-muted">{{ $konfig->urunler->count() }} ürün</p>
                        <div class="d-flex gap-2">
                            <a href="{{ route('konfigurasyon.sepet', $konfig->id) }}" class="btn-modern btn-success">
                                <i class="fas fa-cart-plus"></i> Sepete Ekle
                            </a>
                            <form action="{{ route('konfigurasyon.sil', $konfig->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-modern btn-danger" onclick="return confirm('Emin misiniz?')">
                                    <i class="fas fa-trash"></i> Sil
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="glass-card p-5">
                    <div class="empty-state">
                        <i class="fas fa-cog"></i>
                        <h4>Henüz konfigürasyonunuz yok</h4>
                    </div>
                </div>
            @endif
        </div>

        <!-- FAVORİLERİM -->
        <div class="tab-pane fade" id="favoriler" role="tabpanel">
            @if($favoriUrunler->count() > 0)
                <div class="row g-4">
                    @foreach($favoriUrunler as $favori)
                        <div class="col-md-6 col-lg-4">
                            <div class="glass-card p-3">
                                @if($favori->urun->resim_url)
                                    <img src="{{ asset($favori->urun->resim_url) }}" 
                                         class="img-fluid rounded mb-3" 
                                         alt="{{ $favori->urun->urun_ad }}">
                                @endif
                                <h6>{{ $favori->urun->urun_ad }}</h6>
                                <p class="text-muted mb-2">₺{{ number_format($favori->urun->getFiyatForUser(), 2) }}</p>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('urun.incele', $favori->urun->id) }}" class="btn-modern btn-primary btn-sm">
                                        <i class="fas fa-eye"></i> İncele
                                    </a>
                                    <form action="{{ route('favori.sil', $favori->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-modern btn-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="glass-card p-5">
                    <div class="empty-state">
                        <i class="fas fa-heart"></i>
                        <h4>Henüz favori ürününüz yok</h4>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.order-card, .glass-card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        
        setTimeout(() => {
            card.style.transition = 'all 0.6s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
});
</script>

@endsection