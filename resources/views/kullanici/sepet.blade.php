@extends('layouts.app')
@section('title', 'Sepetim - Avantaj Bilişim')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

    :root {
        --primary: #00897B;
        --primary-hover: #00695C;
        --primary-light: #E0F2F1;
        --dark: #0F172A;
        --text-main: #334155;
        --text-muted: #64748B;
        --bg-body: #F1F5F9;
        --card-bg: #FFFFFF;
        --border: #E2E8F0;
        --danger: #EF4444;
        --radius: 12px;
        --shadow-card: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
    }

    body {
        background-color: var(--bg-body);
        font-family: 'Inter', sans-serif;
        color: var(--text-main);
    }

    /* === HEADER === */
    .cart-header {
        background: white;
        padding: 2rem 0;
        border-bottom: 1px solid var(--border);
        margin-bottom: 2rem;
    }
    .cart-title {
        font-size: 1.75rem;
        font-weight: 800;
        color: var(--dark);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .cart-count-badge {
        background: var(--primary-light);
        color: var(--primary);
        font-size: 0.9rem;
        padding: 0.2rem 0.8rem;
        border-radius: 20px;
    }

    /* === CART LAYOUT === */
    .cart-grid {
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 2rem;
        align-items: start;
    }

    /* === CART ITEMS LIST === */
    .cart-items-wrapper {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .cart-item-card {
        background: var(--card-bg);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 1.5rem;
        display: grid;
        grid-template-columns: 100px 1fr auto auto;
        gap: 1.5rem;
        align-items: center;
        transition: all 0.2s ease;
        position: relative;
        overflow: hidden;
    }

    .cart-item-card:hover {
        border-color: var(--primary);
        box-shadow: var(--shadow-card);
        transform: translateY(-2px);
    }

    /* Resim */
    .item-img-box {
        width: 100px;
        height: 100px;
        background: #F8FAFC;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0.5rem;
        border: 1px solid var(--border);
    }
    .item-img-box img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        mix-blend-mode: multiply;
    }

    /* Bilgi */
    .item-info {
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .item-brand {
        font-size: 0.75rem;
        color: var(--primary);
        font-weight: 700;
        text-transform: uppercase;
        margin-bottom: 0.25rem;
    }
    .item-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 0.25rem;
        line-height: 1.3;
        text-decoration: none;
    }
    .item-title:hover { color: var(--primary); }
    .item-model {
        font-size: 0.85rem;
        color: var(--text-muted);
    }

    /* Miktar Arttır/Azalt */
    .qty-control {
        display: flex;
        align-items: center;
        background: #F1F5F9;
        border-radius: 8px;
        padding: 4px;
        border: 1px solid var(--border);
    }
    .qty-btn {
        width: 32px;
        height: 32px;
        border: none;
        background: white;
        border-radius: 6px;
        color: var(--dark);
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }
    .qty-btn:hover:not(:disabled) {
        background: var(--primary);
        color: white;
    }
    .qty-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    .qty-display {
        width: 40px;
        text-align: center;
        font-weight: 700;
        color: var(--dark);
        font-size: 1rem;
    }

    /* Fiyat ve Silme */
    .item-actions {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 0.5rem;
    }
    .total-price {
        font-size: 1.25rem;
        font-weight: 800;
        color: var(--primary);
    }
    .unit-price {
        font-size: 0.8rem;
        color: var(--text-muted);
    }
    .btn-remove {
        color: var(--text-muted);
        background: transparent;
        border: none;
        cursor: pointer;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 0.3rem;
        transition: color 0.2s;
        margin-top: 0.5rem;
    }
    .btn-remove:hover { color: var(--danger); }

    /* === ORDER SUMMARY (SIDEBAR) === */
    .summary-card {
        background: white;
        border-radius: var(--radius);
        border: 1px solid var(--border);
        box-shadow: var(--shadow-card);
        padding: 1.5rem;
        position: sticky;
        top: 2rem;
    }
    .summary-header {
        font-size: 1.2rem;
        font-weight: 800;
        color: var(--dark);
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--border);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 1rem;
        color: var(--text-muted);
        font-size: 0.95rem;
    }
    .summary-row span:last-child {
        font-weight: 600;
        color: var(--dark);
    }
    .summary-total {
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 2px dashed var(--border);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .total-label { font-size: 1.1rem; font-weight: 700; color: var(--dark); }
    .total-amount { font-size: 1.75rem; font-weight: 900; color: var(--primary); }

    .btn-checkout {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        background: linear-gradient(135deg, var(--primary), var(--primary-hover));
        color: white;
        padding: 1rem;
        border-radius: var(--radius);
        text-decoration: none;
        font-weight: 700;
        font-size: 1.1rem;
        margin-top: 1.5rem;
        transition: transform 0.2s, box-shadow 0.2s;
        box-shadow: 0 4px 12px var(--primary-light);
    }
    .btn-checkout:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 137, 123, 0.3);
        color: white;
    }

    .btn-clear-cart {
        display: block;
        text-align: center;
        margin-top: 1rem;
        font-size: 0.85rem;
        color: var(--text-muted);
        text-decoration: underline;
        cursor: pointer;
        background: none;
        border: none;
        width: 100%;
    }
    .btn-clear-cart:hover { color: var(--danger); }

    /* Alert Style */
    .bayi-alert {
        background: #ECFDF5;
        border: 1px solid #10B981;
        color: #065F46;
        border-radius: var(--radius);
        padding: 1rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    /* Empty State */
    .empty-cart {
        text-align: center;
        padding: 4rem 1rem;
        background: white;
        border-radius: var(--radius);
        border: 1px dashed var(--border);
    }
    .empty-cart-icon {
        font-size: 4rem;
        color: var(--border);
        margin-bottom: 1.5rem;
    }

    /* RESPONSIVE */
    @media (max-width: 992px) {
        .cart-grid { grid-template-columns: 1fr; }
        .summary-card { position: relative; top: 0; margin-top: 1rem; }
    }
    @media (max-width: 576px) {
        .cart-item-card {
            grid-template-columns: 80px 1fr;
            grid-template-rows: auto auto auto;
            gap: 1rem;
        }
        .item-img-box { grid-row: 1 / 3; width: 80px; height: 80px; }
        .item-info { grid-column: 2 / -1; }
        .qty-control { grid-column: 2; width: fit-content; }
        .item-actions {
            grid-column: 1 / -1;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid var(--border);
            padding-top: 1rem;
            margin-top: 0.5rem;
        }
        .btn-remove { margin-top: 0; }
    }
</style>

<div class="cart-header">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="cart-title">
                    <i class="fas fa-shopping-bag text-primary"></i>
                    Alışveriş Sepetim
                    <span class="cart-count-badge" id="headerItemCount">{{ $sepetCount }} Ürün</span>
                </h1>
            </div>
            <div>
                <a href="{{ route('urun.index') }}" class="btn btn-outline-secondary btn-sm fw-bold">
                    <i class="fas fa-arrow-left me-1"></i> Alışverişe Dön
                </a>
            </div>
        </div>
    </div>
</div>

<div class="container mb-5">
    @if(count($sepetler) > 0)
        
        @if(auth()->check() && auth()->user()->isBayi())
            <div class="bayi-alert">
                <i class="fas fa-user-shield fa-2x"></i>
                <div>
                    <strong>Bayi Girişi Aktif:</strong> Fiyatlar firmanıza özel iskonto oranlarıyla güncellenmiştir.
                </div>
            </div>
        @endif

        <div class="cart-grid">
            <div class="cart-items-wrapper" id="sepetItems">
                @foreach($sepetler as $item)
                    <div class="cart-item-card" data-urun-id="{{ $item['id'] }}" data-fiyat="{{ $item['fiyat'] }}">
                        
                        <div class="item-img-box">
                            <img src="{{ $item['resim_url'] ? asset($item['resim_url']) : 'https://via.placeholder.com/150' }}" alt="{{ $item['urun_ad'] }}">
                        </div>

                        <div class="item-info">
                            <span class="item-brand">{{ $item['marka'] ?? 'Marka' }}</span>
                            <a href="#" class="item-title">{{ $item['urun_ad'] }}</a>
                            <span class="item-model">Model: {{ $item['model'] ?? 'N/A' }}</span>
                        </div>

                        <div class="qty-control">
                            <button class="qty-btn" onclick="updateQuantity({{ $item['id'] }}, -1)" {{ $item['adet'] <= 1 ? 'disabled' : '' }}>
                                <i class="fas fa-minus"></i>
                            </button>
                            <div class="qty-display qty-input-val">{{ $item['adet'] }}</div>
                            <button class="qty-btn" onclick="updateQuantity({{ $item['id'] }}, 1)">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>

                        <div class="item-actions">
                            <div class="text-end">
                                <div class="total-price">
                                    ₺<span class="item-total">{{ number_format($item['fiyat'] * $item['adet'], 2, ',', '.') }}</span>
                                </div>
                                <div class="unit-price">
                                    {{ number_format($item['fiyat'], 2, ',', '.') }} ₺/adet
                                </div>
                            </div>
                            <button class="btn-remove" onclick="removeItem({{ $item['id'] }})">
                                <i class="far fa-trash-alt"></i> Kaldır
                            </button>
                        </div>

                    </div>
                @endforeach
            </div>

            <div class="sidebar">
                <div class="summary-card">
                    <div class="summary-header">
                        Sipariş Özeti
                    </div>

                    <div class="summary-row">
                        <span>Ara Toplam</span>
                        <span>₺<span id="subtotal">{{ number_format($toplam, 2, ',', '.') }}</span></span>
                    </div>
                    <div class="summary-row">
                        <span>Kargo Ücreti</span>
                        <span class="text-success fw-bold">Ücretsiz</span>
                    </div>
                    <div class="summary-row">
                        <span>KDV (%20)</span>
                        <span>Dahil</span>
                    </div>

                    <div class="summary-total">
                        <span class="total-label">Toplam Tutar</span>
                        <span class="total-amount">₺<span id="totalPrice">{{ number_format($toplam, 2, ',', '.') }}</span></span>
                    </div>

                    <a href="{{ route('siparis.olustur') }}" class="btn-checkout">
                        Sepeti Onayla <i class="fas fa-chevron-right ms-2"></i>
                    </a>

                    <form action="{{ route('sepet.temizle') }}" method="POST" id="clearCartForm">
                        @csrf @method('DELETE')
                        <button type="button" onclick="confirmClearCart()" class="btn-clear-cart">
                            Sepeti Temizle
                        </button>
                    </form>

                    <div class="mt-4 text-center text-muted small">
                        <i class="fas fa-lock me-1"></i> Güvenli Ödeme Altyapısı
                    </div>
                </div>
            </div>
        </div>

    @else
        <div class="empty-cart">
            <div class="empty-cart-icon">
                <i class="fas fa-shopping-basket"></i>
            </div>
            <h2 class="fw-bold text-dark mb-3">Sepetiniz Şu An Boş</h2>
            <p class="text-muted mb-4">İhtiyacınız olan teknoloji ürünlerini keşfetmeye hemen başlayın.</p>
            <a href="{{ route('urun.index') }}" class="btn-checkout d-inline-flex w-auto px-5">
                Alışverişe Başla
            </a>
        </div>
    @endif
</div>

<script>
// Miktar Güncelleme
function updateQuantity(urunId, change) {
    const itemCard = document.querySelector(`.cart-item-card[data-urun-id="${urunId}"]`);
    const qtyDisplay = itemCard.querySelector('.qty-input-val');
    const minusBtn = itemCard.querySelector('.qty-btn:first-child');
    
    let currentQty = parseInt(qtyDisplay.textContent);
    let newQty = currentQty + change;

    if(newQty < 1) return;

    // UI'ı hemen güncelle (Optimistic UI)
    qtyDisplay.textContent = newQty;
    minusBtn.disabled = (newQty <= 1);

    fetch(`/sepet/guncelle/${urunId}`, {
        method: 'POST',
        headers: { 
            'X-CSRF-TOKEN': '{{ csrf_token() }}', 
            'Content-Type': 'application/json' 
        },
        body: JSON.stringify({adet: newQty})
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            updateTotals(); // Toplamları tekrar hesapla
            // Header'daki sepet sayısını güncelle (Varsa)
            const headerCount = document.getElementById('cartCount'); // Layout'taki id
            const pageHeaderCount = document.getElementById('headerItemCount');
            if(headerCount) headerCount.textContent = data.sepetCount;
            if(pageHeaderCount) pageHeaderCount.textContent = data.sepetCount + ' Ürün';
        }
    })
    .catch(err => {
        // Hata olursa eski haline döndür
        qtyDisplay.textContent = currentQty;
        alert('Bir hata oluştu.');
    });
}

// Ürün Silme
function removeItem(urunId) {
    if(!confirm('Bu ürünü sepetten kaldırmak istiyor musunuz?')) return;

    const itemCard = document.querySelector(`.cart-item-card[data-urun-id="${urunId}"]`);

    fetch(`/sepet/sil/${urunId}`, {
        method: 'DELETE',
        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            // Animasyonlu Silme
            itemCard.style.transition = 'all 0.3s ease';
            itemCard.style.opacity = '0';
            itemCard.style.transform = 'translateX(20px)';
            
            setTimeout(() => {
                itemCard.remove();
                updateTotals();
                
                // Eğer son ürün silindiyse sayfayı yenile (Boş sepet ekranı gelsin)
                if(document.querySelectorAll('.cart-item-card').length === 0) {
                    location.reload();
                }
            }, 300);
        }
    });
}

// Toplamları JS ile Hesaplama (Frontend)
function updateTotals() {
    let subtotal = 0;
    
    document.querySelectorAll('.cart-item-card').forEach(item => {
        const qty = parseInt(item.querySelector('.qty-input-val').textContent);
        const price = parseFloat(item.getAttribute('data-fiyat'));
        
        const itemTotal = qty * price;
        subtotal += itemTotal;

        // Satır toplamını güncelle
        item.querySelector('.item-total').textContent = itemTotal.toLocaleString('tr-TR', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    });

    const formattedTotal = subtotal.toLocaleString('tr-TR', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    
    // Özeti güncelle
    document.getElementById('subtotal').textContent = formattedTotal;
    document.getElementById('totalPrice').textContent = formattedTotal;
}

// Sepeti Temizle Onayı
function confirmClearCart() {
    if(confirm('Sepetinizdeki TÜM ürünler silinecek. Emin misiniz?')) {
        document.getElementById('clearCartForm').submit();
    }
}
</script>
@endsection