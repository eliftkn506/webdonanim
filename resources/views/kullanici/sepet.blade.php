@extends('layouts.app')
@section('title', 'Sepet ')

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
    margin: 0;
}

/* Page Header */
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
    top: 0; left: 0; right: 0; bottom: 0;
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

.breadcrumb-nav {
    text-align: center;
    margin-top: 1rem;
    position: relative;
    z-index: 2;
}

.breadcrumb-nav a {
    color: rgba(255, 255, 255, 0.8);
    text-decoration: none;
    transition: color 0.3s ease;
}

.breadcrumb-nav a:hover {
    color: white;
}

/* Sepet Item Card */
.sepet-item {
    background: white;
    border-radius: 20px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 1rem;
    position: relative;
}

.product-image {
    width: 120px;
    height: 120px;
    border-radius: 15px;
    object-fit: contain;
    background: #f8f9fa;
    padding: 10px;
    border: 2px solid #e9ecef;
    transition: transform 0.3s ease;
}

.sepet-item:hover .product-image {
    transform: scale(1.05) rotate(2deg);
}

.product-info {
    flex: 1;
}

.product-title {
    font-size: 1.4rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

.product-meta {
    color: var(--text-secondary);
    font-size: 0.95rem;
    margin-bottom: 1rem;
    display: flex;
    gap: 0.5rem;
}

.price-info {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
}

.unit-price {
    font-size: 1.1rem;
    color: var(--text-secondary);
    font-weight: 600;
}

.total-price {
    font-size: 1.4rem;
    font-weight: 800;
    background: var(--success-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.quantity-controls {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.qty-btn {
    width: 40px;
    height: 40px;
    border: none;
    border-radius: 50%;
    background: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s ease;
}

.qty-btn.decrease { color: #e74c3c; }
.qty-btn.increase { color: #27ae60; }
.qty-btn:disabled { opacity: 0.5; cursor: not-allowed; }

.qty-display {
    min-width: 60px;
    text-align: center;
    font-weight: 700;
}

.item-actions {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.remove-btn {
    background: var(--danger-gradient);
    color: white;
    border: none;
    border-radius: 15px;
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.remove-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255, 107, 107, 0.4);
}

/* Sepet Summary */
.sepet-summary {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    position: sticky;
    top: 2rem;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    padding: 0.5rem 0;
    border-bottom: 1px solid #f0f0f0;
}

.summary-row:last-child {
    border-bottom: none;
}

.summary-label { color: var(--text-secondary); font-weight: 600; }
.summary-value { font-weight: 700; color: var(--text-primary); }

/* Buttons */
.btn-modern {
    padding: 1rem 2rem;
    border: none;
    border-radius: 25px;
    font-weight: 700;
    text-align: center;
    display: inline-block;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-success { background: var(--success-gradient); color: white; }
.btn-outline { background: transparent; border: 2px solid var(--glass-border); color: var(--text-primary); }

@media (max-width: 768px) {
    .sepet-item { flex-direction: column; align-items: center; }
    .item-actions { flex-direction: row; justify-content: center; }
    .quantity-controls { margin: 0 auto; }
}
</style>


<div class="page-header">
    <div class="container">
        <h1 class="page-title">Sepetim</h1>
        <nav class="breadcrumb-nav">
            <a href="/">Ana Sayfa</a> / <span style="color: white;">Sepet</span>
        </nav>
    </div>
</div>

<div class="container py-5">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if(count($sepetler) > 0)
        <div class="row g-4">
            <div class="col-lg-8">
                <!-- Bayi Bilgisi Göster -->
                @if(auth()->check() && auth()->user()->isBayi())
                    <div class="alert alert-info mb-3">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Bayi Fiyatları</strong> - Sepetinizdeki ürünler bayi fiyatlarıyla gösterilmektedir.
                    </div>
                @endif

                <div id="sepetItems">
                    @foreach($sepetler as $item)
                        @php
                            $urunId = $item['id'] ?? 0;
                            $urunAd = $item['urun_ad'] ?? 'Bilinmeyen Ürün';
                            $fiyat = $item['fiyat'] ?? 0;
                            $adet = $item['adet'] ?? 1;
                            $resim = $item['resim_url'] ?? '';
                            $marka = $item['marka'] ?? '';
                            $model = $item['model'] ?? '';
                        @endphp

                        <div class="sepet-item" data-urun-id="{{ $urunId }}" data-fiyat="{{ $fiyat }}">
                            <img src="{{ $resim ? asset($resim) : 'https://via.placeholder.com/120' }}" 
                                 alt="{{ $urunAd }}" class="product-image"
                                 onerror="this.src='https://via.placeholder.com/120'">

                            <div class="product-info">
                                <h4 class="product-title">{{ $urunAd }}</h4>
                                <div class="product-meta">
                                    @if($marka) <span><i class="fas fa-building me-1"></i>{{ $marka }}</span> @endif
                                    @if($model) <span><i class="fas fa-tag me-1"></i>{{ $model }}</span> @endif
                                </div>
                                <div class="price-info">
                                    <div class="unit-price">
                                        <i class="fas fa-lira-sign me-1"></i>
                                        {{ number_format($fiyat, 2, ',', '.') }} TL / adet
                                    </div>
                                    <div class="total-price">
                                        <i class="fas fa-lira-sign me-1"></i>
                                        <span class="item-total">{{ number_format($fiyat * $adet, 2, ',', '.') }}</span> TL
                                    </div>
                                </div>
                                <div class="quantity-controls">
                                    <button class="qty-btn decrease" onclick="updateQuantity({{ $urunId }}, -1)" {{ $adet <= 1 ? 'disabled' : '' }}>
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <div class="qty-display">{{ $adet }}</div>
                                    <button class="qty-btn increase" onclick="updateQuantity({{ $urunId }}, 1)">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="item-actions">
                                <button class="remove-btn" onclick="removeItem({{ $urunId }})">
                                    <i class="fas fa-trash-alt me-1"></i> Kaldır
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="col-lg-4">
                <div class="sepet-summary">
                    <h4><i class="fas fa-receipt me-2"></i>Sipariş Özeti</h4>
                    <div class="summary-row">
                        <span class="summary-label">Toplam Ürün:</span> 
                        <span class="summary-value" id="totalItems">{{ $sepetCount }}</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">Ara Toplam:</span> 
                        <span class="summary-value">
                            <i class="fas fa-lira-sign"></i>
                            <span id="subtotal">{{ number_format($toplam, 2, ',', '.') }}</span> TL
                        </span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">Kargo:</span> 
                        <span class="summary-value text-success">
                            <i class="fas fa-shipping-fast me-1"></i>Ücretsiz
                        </span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label"><strong>Toplam:</strong></span> 
                        <span class="summary-value">
                            <strong>
                                <i class="fas fa-lira-sign"></i>
                                <span id="totalPrice">{{ number_format($toplam, 2, ',', '.') }}</span> TL
                            </strong>
                        </span>
                    </div>
                    
                    @if(auth()->check() && auth()->user()->isBayi())
                        <div class="alert alert-success mt-3 mb-0">
                            <small><i class="fas fa-check-circle me-1"></i>Bayi indirimi uygulandı!</small>
                        </div>
                    @endif

                    <div class="action-buttons mt-3">
                        <a href="{{ route('siparis.olustur') }}" class="btn-modern btn-success w-100 mb-2">
                            <i class="fas fa-check-circle me-2"></i>Sepeti Onayla
                        </a>
                        
                        <form action="{{ route('sepet.temizle') }}" method="POST" class="mb-0" onsubmit="return confirm('Sepeti temizlemek istediğinize emin misiniz?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-modern btn-outline w-100 text-center">
                                <i class="fas fa-trash me-2"></i>Sepeti Temizle
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-5">
            <div style="font-size: 5rem; color: #ccc;">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <h3 class="mt-3">Sepetiniz Boş</h3>
            <p class="text-muted">Henüz sepete ürün eklemediniz.</p>
            <a href="{{ route('urun.index') }}" class="btn-modern btn-success mt-3">
                <i class="fas fa-shopping-bag me-2"></i>Alışverişe Başla
            </a>
        </div>
    @endif
</div>

<script>
function updateQuantity(urunId, change) {
    const item = document.querySelector(`.sepet-item[data-urun-id="${urunId}"]`);
    const qtyDisplay = item.querySelector('.qty-display');
    const decreaseBtn = item.querySelector('.qty-btn.decrease');
    
    let qty = parseInt(qtyDisplay.textContent) + change;
    if(qty < 1) return;

    // Disable/enable decrease button
    decreaseBtn.disabled = qty <= 1;

    fetch(`/sepet/guncelle/${urunId}`, {
        method: 'POST',
        headers: { 
            'X-CSRF-TOKEN': '{{ csrf_token() }}', 
            'Content-Type': 'application/json' 
        },
        body: JSON.stringify({adet: qty})
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            qtyDisplay.textContent = qty;
            updateTotals();
            
            // Sepet badge güncelle
            if(document.getElementById('navbarCartCount')) {
                document.getElementById('navbarCartCount').textContent = data.sepetCount;
            }
        }
    })
    .catch(err => {
        console.error('Hata:', err);
        alert('Bir hata oluştu!');
    });
}

function removeItem(urunId) {
    if(!confirm('Bu ürünü sepetten kaldırmak istiyor musunuz?')) return;

    fetch(`/sepet/sil/${urunId}`, {
        method: 'DELETE',
        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            const item = document.querySelector(`.sepet-item[data-urun-id="${urunId}"]`);
            item.style.transition = 'all 0.3s ease';
            item.style.opacity = '0';
            item.style.transform = 'translateX(-20px)';
            
            setTimeout(() => {
                item.remove();
                updateTotals();
                
                // Sepet boşsa sayfayı yenile
                if(document.querySelectorAll('.sepet-item').length === 0) {
                    location.reload();
                }
            }, 300);
        }
    })
    .catch(err => {
        console.error('Hata:', err);
        alert('Bir hata oluştu!');
    });
}

function updateTotals() {
    let totalItems = 0, subtotal = 0;
    
    document.querySelectorAll('.sepet-item').forEach(item => {
        const qty = parseInt(item.querySelector('.qty-display').textContent);
        const price = parseFloat(item.getAttribute('data-fiyat'));
        
        totalItems += qty;
        subtotal += qty * price;
        
        // Item total güncelle
        item.querySelector('.item-total').textContent = (qty * price).toFixed(2).replace('.', ',');
    });
    
    document.getElementById('totalItems').textContent = totalItems;
    document.getElementById('subtotal').textContent = subtotal.toFixed(2).replace('.', ',');
    document.getElementById('totalPrice').textContent = subtotal.toFixed(2).replace('.', ',');
}
</script>
@endsection
