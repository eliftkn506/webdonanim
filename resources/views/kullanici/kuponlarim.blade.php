@extends('layouts.app')

@section('content')
<style>
:root {
    --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --success-gradient: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%);
    --warning-gradient: linear-gradient(135deg, #ffd93d 0%, #ff9500 100%);
}

.page-header {
    background: var(--primary-gradient);
    padding: 3rem 0 2rem;
    margin-bottom: 3rem;
    position: relative;
    overflow: hidden;
}

.page-title {
    color: white;
    font-size: 2.5rem;
    font-weight: 800;
    text-align: center;
    margin: 0;
}

.coupon-card {
    background: white;
    border-radius: 20px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
}

.coupon-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
}

.coupon-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 5px;
    background: var(--success-gradient);
}

.coupon-card.used::before {
    background: #ccc;
}

.coupon-header {
    display: flex;
    justify-content: space-between;
    align-items: start;
    margin-bottom: 1rem;
}

.coupon-title {
    font-size: 1.3rem;
    font-weight: 800;
    color: #2d3436;
    margin-bottom: 0.25rem;
}

.coupon-code {
    background: var(--primary-gradient);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-weight: 700;
    font-size: 0.9rem;
    display: inline-block;
}

.coupon-discount {
    font-size: 2rem;
    font-weight: 800;
    background: var(--success-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.coupon-description {
    color: #636e72;
    font-size: 0.9rem;
    margin-bottom: 1rem;
}

.coupon-details {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid #e9ecef;
}

.detail-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.85rem;
    color: #636e72;
}

.detail-item i {
    color: #667eea;
}

.coupon-actions {
    margin-top: 1rem;
    display: flex;
    gap: 0.5rem;
}

.btn-copy {
    background: var(--primary-gradient);
    color: white;
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 25px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-copy:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}

.used-badge {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: #6c757d;
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-weight: 700;
    font-size: 0.8rem;
}

.kural-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: var(--warning-gradient);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-weight: 600;
    font-size: 0.85rem;
    margin-top: 0.5rem;
}

.empty-state {
    text-align: center;
    padding: 4rem 1rem;
    color: #636e72;
}

.empty-state i {
    font-size: 5rem;
    color: #ddd;
    margin-bottom: 1rem;
}
</style>

<div class="page-header">
    <div class="container">
        <h1 class="page-title">
            <i class="fas fa-tags me-2"></i>
            Kuponlarım
        </h1>
    </div>
</div>

<div class="container py-5">
    @if($kuponlar->count() > 0)
        <div class="row">
            @foreach($kuponlar as $kuponPivot)
                @php
                    $kupon = $kuponPivot->kupon;
                    $kullanildi = $kuponPivot->kullanildi;
                @endphp
                
                <div class="col-md-6 col-lg-4">
                    <div class="coupon-card {{ $kullanildi ? 'used' : '' }}">
                        @if($kullanildi)
                            <span class="used-badge">
                                <i class="fas fa-check me-1"></i>
                                Kullanıldı
                            </span>
                        @endif
                        
                        <div class="coupon-header">
                            <div>
                                <div class="coupon-title">{{ $kupon->baslik }}</div>
                                <span class="coupon-code">{{ $kupon->kupon_kodu }}</span>
                            </div>
                            <div class="coupon-discount">
                                @if($kupon->indirim_tipi === 'yuzde')
                                    %{{ $kupon->indirim_miktari }}
                                @else
                                    ₺{{ number_format($kupon->indirim_miktari, 2) }}
                                @endif
                            </div>
                        </div>

                        @if($kupon->aciklama)
                            <div class="coupon-description">
                                {{ $kupon->aciklama }}
                            </div>
                        @endif

                        @if($kupon->kupon_turu === 'kural_bazli')
                            <div class="kural-badge">
                                <i class="fas fa-star"></i>
                                Özel Kupon
                            </div>
                        @endif

                        <div class="coupon-details">
                            @if($kupon->minimum_tutar)
                                <div class="detail-item">
                                    <i class="fas fa-shopping-cart"></i>
                                    Min: ₺{{ number_format($kupon->minimum_tutar, 2) }}
                                </div>
                            @endif
                            
                            <div class="detail-item">
                                <i class="fas fa-calendar"></i>
                                {{ $kupon->bitis_tarihi->format('d.m.Y') }}'e kadar
                            </div>

                            @if(!$kullanildi)
                                <div class="detail-item">
                                    <i class="fas fa-clock"></i>
                                    {{ $kupon->bitis_tarihi->diffForHumans() }}
                                </div>
                            @else
                                <div class="detail-item">
                                    <i class="fas fa-check-circle"></i>
                                    {{ $kuponPivot->kullanilma_tarihi->format('d.m.Y') }}
                                </div>
                            @endif
                        </div>

                        @if(!$kullanildi)
                            <div class="coupon-actions">
                                <button class="btn-copy" onclick="kopyala('{{ $kupon->kupon_kodu }}', this)">
                                    <i class="fas fa-copy me-2"></i>
                                    Kodu Kopyala
                                </button>
                                <a href="{{ route('home') }}" class="btn-copy" style="background: var(--success-gradient); text-decoration: none; display: inline-flex; align-items: center;">
                                    <i class="fas fa-shopping-bag me-2"></i>
                                    Kullan
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <i class="fas fa-ticket-alt"></i>
            <h4>Henüz kuponunuz yok</h4>
            <p>Alışveriş yaparak özel kuponlar kazanabilirsiniz!</p>
            <a href="{{ route('home') }}" class="btn-copy" style="background: var(--primary-gradient); text-decoration: none; display: inline-flex; align-items: center; margin-top: 1rem;">
                <i class="fas fa-shopping-bag me-2"></i>
                Alışverişe Başla
            </a>
        </div>
    @endif
</div>

<script>
function kopyala(kod, btn) {
    navigator.clipboard.writeText(kod).then(() => {
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check me-2"></i>Kopyalandı!';
        btn.style.background = 'var(--success-gradient)';
        
        setTimeout(() => {
            btn.innerHTML = originalText;
            btn.style.background = 'var(--primary-gradient)';
        }, 2000);
    }).catch(err => {
        alert('Kopyalama başarısız: ' + err);
    });
}

// Animasyon
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.coupon-card');
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