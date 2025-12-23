@extends('layouts.app')
@section('title', 'Alışveriş Sepetim - Avantaj Bilişim')

@section('content')
<style>
:root {
    /* Proje ile Uyumlu Renk Paleti */
    --primary-turq: #00d4aa;
    --primary-dark: #00a896;
    --secondary-navy: #1e293b;
    --accent-orange: #f59e0b;
    --bg-soft: #f8fafc;
    --border-color: #e2e8f0;
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

body {
    background-color: var(--bg-soft);
}

/* Page Header */
.cart-header-section {
    background: var(--secondary-navy);
    padding: 60px 0;
    color: white;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.cart-header-section::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image: radial-gradient(var(--primary-turq) 1px, transparent 1px);
    background-size: 30px 30px;
    opacity: 0.1;
}

.cart-title {
    font-size: 3rem;
    font-weight: 800;
    margin-bottom: 10px;
    letter-spacing: -1px;
}

/* Sepet Kartları */
.sepet-container {
    margin-top: -30px;
    position: relative;
    z-index: 10;
}

.sepet-card {
    background: white;
    border-radius: 20px;
    border: 1px solid var(--border-color);
    box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    overflow: hidden;
    margin-bottom: 1.5rem;
    transition: var(--transition);
}

.sepet-item {
    display: grid;
    grid-template-columns: 140px 1fr 180px 100px;
    align-items: center;
    padding: 2rem;
    gap: 2rem;
    border-bottom: 1px solid var(--border-color);
}

.sepet-item:last-child {
    border-bottom: none;
}

.product-image-box {
    width: 140px;
    height: 140px;
    background: #f1f5f9;
    border-radius: 15px;
    padding: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}

.product-image-box img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
    mix-blend-mode: multiply;
}

.product-info h4 {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--secondary-navy);
    margin-bottom: 0.5rem;
}

.brand-badge {
    display: inline-block;
    padding: 4px 12px;
    background: #f0fdfa;
    color: var(--primary-dark);
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    margin-bottom: 1rem;
}

/* Miktar Kontrolleri */
.qty-wrapper {
    display: flex;
    align-items: center;
    background: #f1f5f9;
    border-radius: 12px;
    padding: 5px;
    width: fit-content;
}

.qty-btn {
    width: 35px;
    height: 35px;
    border: none;
    background: white;
    color: var(--secondary-navy);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 800;
    cursor: pointer;
    transition: var(--transition);
}

.qty-btn:hover:not(:disabled) {
    background: var(--primary-turq);
    color: white;
}

.qty-input-val {
    width: 50px;
    text-align: center;
    font-weight: 800;
    color: var(--secondary-navy);
    font-size: 1rem;
}

/* Fiyat Alanı */
.price-display {
    text-align: right;
}

.item-total-price {
    font-size: 1.5rem;
    font-weight: 900;
    color: var(--secondary-navy);
    display: block;
}

.item-unit-price {
    font-size: 0.85rem;
    color: var(--text-muted);
    font-weight: 600;
}

.remove-action-btn {
    color: #ef4444;
    background: #fef2f2;
    border: none;
    width: 45px;
    height: 45px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: var(--transition);
    cursor: pointer;
}

.remove-action-btn:hover {
    background: #ef4444;
    color: white;
    transform: rotate(90deg);
}

/* Sipariş Özeti */
.summary-card {
    background: white;
    border-radius: 24px;
    padding: 2.5rem;
    border: 1px solid var(--border-color);
    box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    position: sticky;
    top: 100px;
}

.summary-title {
    font-weight: 800;
    color: var(--secondary-navy);
    margin-bottom: 2rem;
    display: flex;
    align-items: center;
    gap: 10px;
}

.summary-line {
    display: flex;
    justify-content: space-between;
    margin-bottom: 1.25rem;
    font-weight: 600;
    color: var(--text-muted);
}

.summary-line.grand-total {
    border-top: 2px dashed var(--border-color);
    padding-top: 1.5rem;
    margin-top: 1.5rem;
    color: var(--secondary-navy);
    font-size: 1.5rem;
    font-weight: 900;
}

.checkout-btn {
    background: var(--primary-turq);
    color: var(--secondary-navy);
    width: 100%;
    padding: 1.25rem;
    border-radius: 14px;
    border: none;
    font-weight: 800;
    font-size: 1.1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    transition: var(--transition);
    text-decoration: none;
    margin-top: 1.5rem;
}

.checkout-btn:hover {
    background: var(--primary-dark);
    color: white;
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(0, 212, 170, 0.3);
}

.clear-cart-link {
    display: block;
    text-align: center;
    margin-top: 1.5rem;
    color: #ef4444;
    font-weight: 700;
    font-size: 0.9rem;
    text-decoration: none;
    transition: var(--transition);
}

.clear-cart-link:hover {
    opacity: 0.7;
}

/* Boş Sepet */
.empty-cart-state {
    text-align: center;
    padding: 100px 0;
}

.empty-icon {
    width: 120px;
    height: 120px;
    background: #f1f5f9;
    color: #cbd5e1;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
    margin: 0 auto 2rem;
}

@media (max-width: 991px) {
    .sepet-item {
        grid-template-columns: 100px 1fr 1fr;
    }
    .remove-cell { grid-column: 3; justify-self: end; }
}

@media (max-width: 768px) {
    .sepet-item {
        grid-template-columns: 1fr;
        text-align: center;
        gap: 1rem;
    }
    .product-image-box, .qty-wrapper, .price-display { margin: 0 auto; }
}
</style>

<div class="cart-header-section">
    <div class="container">
        <h1 class="cart-title">Alışveriş Sepetim</h1>
        <p class="opacity-75">Seçtiğiniz ürünleri kontrol edin ve güvenle satın alın.</p>
    </div>
</div>

<div class="container sepet-container py-5">
    @if(count($sepetler) > 0)
        <div class="row g-4">
            <div class="col-lg-8">
                @if(auth()->check() && auth()->user()->isBayi())
                    <div class="alert bg-white border-primary-turq border-2 rounded-4 mb-4 d-flex align-items-center gap-3">
                        <div class="bg-primary-turq p-2 rounded-circle text-white">
                            <i class="fas fa-handshake"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0 text-navy">Bayi Girişi Yapıldı</h6>
                            <small class="text-muted">Tüm fiyatlar firmanıza özel iskonto ile güncellenmiştir.</small>
                        </div>
                    </div>
                @endif

                <div class="sepet-card shadow-sm">
                    <div id="sepetItems">
                        @foreach($sepetler as $item)
                            <div class="sepet-item" data-urun-id="{{ $item['id'] }}" data-fiyat="{{ $item['fiyat'] }}">
                                <div class="product-image-box">
                                    <img src="{{ $item['resim_url'] ? asset($item['resim_url']) : 'https://via.placeholder.com/150' }}" alt="{{ $item['urun_ad'] }}">
                                </div>

                                <div class="product-info">
                                    <span class="brand-badge">{{ $item['marka'] ?? 'Teknoloji' }}</span>
                                    <h4>{{ $item['urun_ad'] }}</h4>
                                    <p class="text-muted small">Model: {{ $item['model'] ?? 'N/A' }}</p>
                                </div>

                                <div class="qty-wrapper">
                                    <button class="qty-btn" onclick="updateQuantity({{ $item['id'] }}, -1)" {{ $item['adet'] <= 1 ? 'disabled' : '' }}>-</button>
                                    <div class="qty-input-val">{{ $item['adet'] }}</div>
                                    <button class="qty-btn" onclick="updateQuantity({{ $item['id'] }}, 1)">+</button>
                                </div>

                                <div class="d-flex align-items-center gap-4 remove-cell">
                                    <div class="price-display">
                                        <span class="item-total-price">₺<span class="item-total">{{ number_format($item['fiyat'] * $item['adet'], 2, ',', '.') }}</span></span>
                                        <span class="item-unit-price">₺{{ number_format($item['fiyat'], 2, ',', '.') }} / adet</span>
                                    </div>
                                    <button class="remove-action-btn" onclick="removeItem({{ $item['id'] }})" title="Kaldır">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <a href="{{ route('urun.index') }}" class="btn fw-bold text-navy mt-2">
                    <i class="fas fa-arrow-left me-2"></i> Alışverişe Devam Et
                </a>
            </div>

            <div class="col-lg-4">
                <div class="summary-card">
                    <h4 class="summary-title">
                        <i class="fas fa-file-invoice-dollar text-primary-turq"></i>
                        Sipariş Özeti
                    </h4>

                    <div class="summary-line">
                        <span>Toplam Ürün</span>
                        <span id="totalItems">{{ $sepetCount }}</span>
                    </div>

                    <div class="summary-line">
                        <span>Ara Toplam</span>
                        <span>₺<span id="subtotal">{{ number_format($toplam, 2, ',', '.') }}</span></span>
                    </div>

                    <div class="summary-line">
                        <span>Kargo</span>
                        <span class="text-success fw-bold">ÜCRETSİZ</span>
                    </div>

                    <div class="summary-line grand-total">
                        <span>Toplam</span>
                        <span>₺<span id="totalPrice">{{ number_format($toplam, 2, ',', '.') }}</span></span>
                    </div>

                    <a href="{{ route('siparis.olustur') }}" class="checkout-btn shadow-lg">
                        SEPETİ ONAYLA <i class="fas fa-chevron-right ms-2"></i>
                    </a>

                    <form action="{{ route('sepet.temizle') }}" method="POST" id="clearCartForm">
                        @csrf @method('DELETE')
                        <a href="javascript:void(0)" onclick="confirmClearCart()" class="clear-cart-link">
                            <i class="fas fa-trash-alt me-1"></i> Sepeti Temizle
                        </a>
                    </form>
                </div>
            </div>
        </div>
    @else
        <div class="empty-cart-state bg-white rounded-5 shadow-sm p-5 border">
            <div class="empty-icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <h2 class="fw-bold text-navy">Sepetiniz Şu An Boş</h2>
            <p class="text-muted mb-5">Görünüşe göre henüz bir ürün eklemediniz. Teknolojinin avantajlı dünyasını keşfetmeye ne dersiniz?</p>
            <a href="{{ route('urun.index') }}" class="checkout-btn d-inline-flex px-5 w-auto">
                <i class="fas fa-rocket me-2"></i> ALIŞVERİŞE BAŞLA
            </a>
        </div>
    @endif
</div>

<script>
function updateQuantity(urunId, change) {
    const item = document.querySelector(`.sepet-item[data-urun-id="${urunId}"]`);
    const qtyDisplay = item.querySelector('.qty-input-val');
    
    let qty = parseInt(qtyDisplay.textContent) + change;
    if(qty < 1) return;

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
            item.querySelector('.qty-btn').disabled = (qty <= 1);
            updateTotals();
            if(document.getElementById('cartCount')) {
                document.getElementById('cartCount').textContent = data.sepetCount;
            }
        }
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
            item.style.transform = 'scale(0.95)';
            item.style.opacity = '0';
            setTimeout(() => {
                item.remove();
                updateTotals();
                if(document.querySelectorAll('.sepet-item').length === 0) location.reload();
            }, 300);
        }
    });
}

function updateTotals() {
    let totalItems = 0, subtotal = 0;
    document.querySelectorAll('.sepet-item').forEach(item => {
        const qty = parseInt(item.querySelector('.qty-input-val').textContent);
        const price = parseFloat(item.getAttribute('data-fiyat'));
        totalItems += qty;
        subtotal += qty * price;
        item.querySelector('.item-total').textContent = (qty * price).toLocaleString('tr-TR', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    });
    
    document.getElementById('totalItems').textContent = totalItems;
    const formatted = subtotal.toLocaleString('tr-TR', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    document.getElementById('subtotal').textContent = formatted;
    document.getElementById('totalPrice').textContent = formatted;
}

function confirmClearCart() {
    if(confirm('Sepetinizdeki tüm ürünler silinecek. Emin misiniz?')) {
        document.getElementById('clearCartForm').submit();
    }
}
</script>
@endsection