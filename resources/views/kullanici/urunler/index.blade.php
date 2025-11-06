@extends('layouts.app')
@section('title', 'Ürünler ')

@section('content')
<style>
:root {
    --primary: #2563eb;
    --primary-dark: #1e40af;
    --secondary: #64748b;
    --success: #10b981;
    --danger: #ef4444;
    --warning: #f59e0b;
    --dark: #1e293b;
    --light: #f8fafc;
    --border: #e2e8f0;
}

body {
    font-family: 'Inter', system-ui, sans-serif;
    background: #f8fafc;
    color: #1e293b;
}

.page-header {
    background: linear-gradient(135deg, #fff 0%, #f1f5f9 100%);
    padding: 2rem 0;
    margin-bottom: 2rem;
    border-bottom: 1px solid var(--border);
}

.page-title {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.breadcrumb {
    display: flex;
    gap: 0.5rem;
    font-size: 0.875rem;
    color: var(--secondary);
}

.breadcrumb a {
    color: var(--primary);
    text-decoration: none;
}

.main-container {
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 2rem;
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 1rem;
}

/* Filter Sidebar */
.filter-sidebar {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    height: fit-content;
    position: sticky;
    top: 20px;
    border: 1px solid var(--border);
}

.filter-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid var(--light);
}

.filter-title {
    font-size: 1.125rem;
    font-weight: 700;
}

.filter-clear {
    color: var(--primary);
    font-size: 0.875rem;
    text-decoration: none;
    font-weight: 600;
}

.filter-section {
    margin-bottom: 1.5rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid var(--border);
}

.filter-section:last-child {
    border: none;
}

.filter-section-title {
    font-weight: 600;
    margin-bottom: 1rem;
    font-size: 0.9375rem;
}

.filter-select {
    width: 100%;
    padding: 0.625rem;
    border: 1px solid var(--border);
    border-radius: 8px;
    font-size: 0.875rem;
}

.price-inputs {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.5rem;
}

.price-input {
    padding: 0.625rem;
    border: 1px solid var(--border);
    border-radius: 8px;
    font-size: 0.875rem;
}

/* Products Section */
.products-section {
    min-width: 0;
}

.products-header {
    background: white;
    padding: 1.25rem;
    border-radius: 12px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    border: 1px solid var(--border);
}

.products-count {
    font-weight: 600;
    color: var(--dark);
}

.products-sort {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.sort-select {
    padding: 0.625rem 1rem;
    border: 1px solid var(--border);
    border-radius: 8px;
    font-size: 0.875rem;
}

/* Product Grid */
.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1.5rem;
}

.product-card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    border: 1px solid var(--border);
    transition: all 0.3s;
    display: flex;
    flex-direction: column;
    height: 100%;
}

.product-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 24px rgba(0,0,0,0.1);
    border-color: var(--primary);
}

.product-image-wrapper {
    position: relative;
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    padding: 1.5rem;
    height: 240px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.product-image {
    width: 100%;
    height: 100%;
    object-fit: contain;
    transition: transform 0.3s;
}

.product-card:hover .product-image {
    transform: scale(1.05);
}

.product-actions {
    position: absolute;
    top: 1rem;
    right: 1rem;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    opacity: 0;
    transition: opacity 0.3s;
}

.product-card:hover .product-actions {
    opacity: 1;
}

.action-btn {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    border: none;
    background: rgba(255,255,255,0.95);
    color: var(--secondary);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.action-btn:hover {
    background: var(--primary);
    color: white;
    transform: scale(1.1);
}

.product-specs {
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,0.85);
    color: white;
    opacity: 0;
    transition: opacity 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1.5rem;
}

.product-card:hover .product-specs {
    opacity: 1;
}

.specs-list {
    list-style: none;
    padding: 0;
    margin: 0;
    font-size: 0.875rem;
    line-height: 1.8;
}

.specs-list li {
    display: flex;
    gap: 0.5rem;
}

.specs-list strong {
    color: var(--warning);
    min-width: 80px;
}

.product-info {
    padding: 1.25rem;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.product-brand {
    font-size: 0.75rem;
    color: var(--secondary);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.5rem;
}

.product-title {
    font-size: 1rem;
    font-weight: 600;
    color: var(--dark);
    margin-bottom: 1rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    min-height: 3rem;
    line-height: 1.5;
}

.product-title a {
    color: inherit;
    text-decoration: none;
}

.product-title a:hover {
    color: var(--primary);
}

.price-section {
    margin-top: auto;
    padding-top: 1rem;
    border-top: 1px solid var(--light);
}

.price-wrapper {
    margin-bottom: 1rem;
}

.current-price {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--primary);
    display: block;
}

.price-discount {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-top: 0.25rem;
}

.original-price {
    font-size: 0.9375rem;
    color: var(--secondary);
    text-decoration: line-through;
}

.discount-badge {
    background: linear-gradient(135deg, #fbbf24, #f59e0b);
    color: #78350f;
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 700;
}

.installment-text {
    font-size: 0.8125rem;
    color: var(--secondary);
    margin-top: 0.5rem;
    font-style: italic;
}

.cart-section {
    display: flex;
    gap: 0.75rem;
    align-items: center;
}

.qty-selector {
    display: flex;
    border: 1px solid var(--border);
    border-radius: 8px;
    overflow: hidden;
}

.qty-btn {
    width: 32px;
    height: 36px;
    border: none;
    background: var(--light);
    cursor: pointer;
    font-weight: 600;
    transition: all 0.2s;
}

.qty-btn:hover {
    background: var(--primary);
    color: white;
}

.qty-input {
    width: 40px;
    border: none;
    text-align: center;
    font-weight: 600;
    font-size: 0.875rem;
}

.add-cart-btn {
    flex: 1;
    padding: 0.625rem 1rem;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    font-size: 0.875rem;
}

.add-cart-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
}

.empty-state {
    grid-column: 1/-1;
    text-align: center;
    padding: 4rem 2rem;
}

.empty-icon {
    font-size: 4rem;
    color: var(--secondary);
    opacity: 0.3;
    margin-bottom: 1rem;
}

.pagination-wrapper {
    margin-top: 2rem;
    display: flex;
    justify-content: center;
}

.pagination {
    display: flex;
    gap: 0.5rem;
}

.page-link {
    padding: 0.625rem 1rem;
    border: 1px solid var(--border);
    background: white;
    color: var(--dark);
    text-decoration: none;
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.2s;
}

.page-link:hover {
    border-color: var(--primary);
    color: var(--primary);
}

.page-link.active {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
}

@media (max-width: 1024px) {
    .main-container {
        grid-template-columns: 1fr;
    }
    
    .filter-sidebar {
        position: static;
    }
}

@media (max-width: 768px) {
    .products-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }
    
    .product-image-wrapper {
        height: 180px;
        padding: 1rem;
    }
}
</style>

<div class="page-header">
    <div class="container">
        <h1 class="page-title">Ürünlerimiz</h1>
        <div class="breadcrumb">
            <a href="{{ route('home') }}">Anasayfa</a>
            <span>/</span>
            @if(isset($kategori))
                <span>{{ $kategori->kategori_ad }}</span>
            @elseif(isset($altKategori))
                <a href="{{ route('urun.kategori', $altKategori->kategori->id) }}">{{ $altKategori->kategori->kategori_ad }}</a>
                <span>/</span>
                <span>{{ $altKategori->alt_kategori_ad }}</span>
            @else
                <span>Tüm Ürünler</span>
            @endif
        </div>
    </div>
</div>

<div class="main-container">
    <!-- Filters -->
    <aside class="filter-sidebar">
        <div class="filter-header">
            <h3 class="filter-title">Filtreler</h3>
            <a href="{{ url()->current() }}" class="filter-clear">Temizle</a>
        </div>

        <form method="GET" id="filterForm">
            <div class="filter-section">
                <div class="filter-section-title">Kategori</div>
                <select name="kategori_id" class="filter-select">
                    <option value="">Tümü</option>
                    @foreach($kategoriler ?? [] as $kat)
                        <option value="{{ $kat->id }}" {{ request('kategori_id') == $kat->id ? 'selected' : '' }}>
                            {{ $kat->kategori_ad }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-section">
                <div class="filter-section-title">Alt Kategori</div>
                <select name="alt_kategori_id" class="filter-select">
                    <option value="">Tümü</option>
                    @foreach($altKategoriler ?? [] as $alt)
                        <option value="{{ $alt->id }}" {{ request('alt_kategori_id') == $alt->id ? 'selected' : '' }}>
                            {{ $alt->alt_kategori_ad }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-section">
                <div class="filter-section-title">Fiyat Aralığı</div>
                <div class="price-inputs">
                    <input type="number" name="min_fiyat" placeholder="Min" class="price-input" value="{{ request('min_fiyat') }}">
                    <input type="number" name="max_fiyat" placeholder="Max" class="price-input" value="{{ request('max_fiyat') }}">
                </div>
            </div>

            <button type="submit" class="add-cart-btn" style="width: 100%;">
                <i class="fas fa-filter"></i> Filtrele
            </button>
        </form>
    </aside>

    <!-- Products -->
    <section class="products-section">
        <div class="products-header">
            <div class="products-count">
                <strong>{{ $urunler->total() }}</strong> ürün bulundu
            </div>
            <div class="products-sort">
                <select class="sort-select" onchange="window.location.search='?sort='+this.value">
                    <option value="newest">En Yeni</option>
                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Ucuzdan Pahalıya</option>
                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Pahalıdan Ucuza</option>
                </select>
            </div>
        </div>

        <div class="products-grid">
            @forelse($urunler as $urun)
                @php
                    $user = auth()->user();
                    
                    // Kullanıcıya göre fiyat al
                    $satisFiyati = $urun->getFiyatForUser($user);
                    $standartFiyat = $urun->getStandartFiyat();
                    
                    // Bayi kontrolü
                    $isBayi = $user && $user->isBayi();
                    $bayiFiyat = $isBayi ? $urun->getBayiFiyat() : null;

                    // Kampanya kontrolü
                    $kampanya = DB::table('kampanya_indirim')
                        ->where('urun_id', $urun->id)
                        ->where('aktif', 1)
                        ->where('baslangic_tarihi', '<=', now())
                        ->where('bitis_tarihi', '>=', now())
                        ->first();
                    
                    $indirimliFiyat = $satisFiyati;
                    if($kampanya && $satisFiyati > 0) {
                        $indirimliFiyat = $satisFiyati * (1 - $kampanya->indirim_orani / 100);
                    }
                @endphp

                <div class="product-card">
                    <div class="product-image-wrapper">
                        <img src="{{ $urun->resim_url ? asset($urun->resim_url) : 'https://via.placeholder.com/300x300?text=Ürün' }}" 
                             alt="{{ $urun->urun_ad }}" 
                             class="product-image">
                        
                        <div class="product-actions">
                            <button class="action-btn" onclick="toggleFavorite({{ $urun->id }})">
                                <i class="far fa-heart"></i>
                            </button>
                            <a href="{{ route('urun.incele', $urun->id) }}" class="action-btn">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>

                        @if($urun->urunKriterDegerleri->count() > 0)
                        <div class="product-specs">
                            <ul class="specs-list">
                                @foreach($urun->urunKriterDegerleri->take(4) as $kd)
                                    <li>
                                        <strong>{{ $kd->kriter->kriter_ad }}:</strong>
                                        <span>{{ $kd->kriterDeger->deger }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    </div>

                    <div class="product-info">
                        @if($urun->marka)
                            <div class="product-brand">{{ $urun->marka }}</div>
                        @endif
                        
                        <h3 class="product-title">
                            <a href="{{ route('urun.incele', $urun->id) }}">{{ $urun->urun_ad }}</a>
                        </h3>

                        <div class="price-section">
                            <div class="price-wrapper">
                                @if($satisFiyati > 0)
                                    @if($isBayi && $bayiFiyat && $standartFiyat > $bayiFiyat)
                                        {{-- Bayi için indirimli fiyat göster --}}
                                        <span class="current-price">₺{{ number_format($bayiFiyat, 2, ',', '.') }}</span>
                                        <div class="price-discount">
                                            <span class="original-price">₺{{ number_format($standartFiyat, 2, ',', '.') }}</span>
                                            <span class="discount-badge">Bayi İndirimi</span>
                                        </div>
                                    @elseif($kampanya)
                                        {{-- Kampanya fiyatı --}}
                                        <span class="current-price">₺{{ number_format($indirimliFiyat, 2, ',', '.') }}</span>
                                        <div class="price-discount">
                                            <span class="original-price">₺{{ number_format($satisFiyati, 2, ',', '.') }}</span>
                                            <span class="discount-badge">-%{{ $kampanya->indirim_orani }}</span>
                                        </div>
                                    @else
                                        {{-- Normal fiyat --}}
                                        <span class="current-price">₺{{ number_format($satisFiyati, 2, ',', '.') }}</span>
                                    @endif
                                    
                                    @if(($isBayi && $bayiFiyat ? $bayiFiyat : $indirimliFiyat) > 1000)
                                        <div class="installment-text">
                                            {{ number_format(($isBayi && $bayiFiyat ? $bayiFiyat : $indirimliFiyat) / 12, 0) }} ₺'den başlayan taksitle
                                        </div>
                                    @endif
                                @else
                                    <span class="current-price text-muted">Fiyat Yok</span>
                                @endif
                            </div>

                            @if($satisFiyati > 0)
                            <div class="cart-section">
                                <div class="qty-selector">
                                    <button class="qty-btn" onclick="decreaseQty({{ $urun->id }})">−</button>
                                    <input type="number" class="qty-input" id="qty_{{ $urun->id }}" value="1" min="1" max="99">
                                    <button class="qty-btn" onclick="increaseQty({{ $urun->id }})">+</button>
                                </div>
                                <button class="add-cart-btn" onclick="addToCart({{ $urun->id }})">
                                    <i class="fas fa-shopping-cart"></i> Sepete Ekle
                                </button>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <div class="empty-icon"><i class="fas fa-inbox"></i></div>
                    <h3>Ürün Bulunamadı</h3>
                    <p>Aradığınız kriterlere uygun ürün bulunmamaktadır.</p>
                </div>
            @endforelse
        </div>

        @if($urunler->hasPages())
            <div class="pagination-wrapper">
                <div class="pagination">
                    @if($urunler->onFirstPage())
                        <span class="page-link" style="opacity: 0.5; cursor: not-allowed;">‹</span>
                    @else
                        <a href="{{ $urunler->previousPageUrl() }}" class="page-link">‹</a>
                    @endif

                    @foreach($urunler->getUrlRange(1, $urunler->lastPage()) as $page => $url)
                        <a href="{{ $url }}" class="page-link {{ $page == $urunler->currentPage() ? 'active' : '' }}">
                            {{ $page }}
                        </a>
                    @endforeach

                    @if($urunler->hasMorePages())
                        <a href="{{ $urunler->nextPageUrl() }}" class="page-link">›</a>
                    @else
                        <span class="page-link" style="opacity: 0.5; cursor: not-allowed;">›</span>
                    @endif
                </div>
            </div>
        @endif
    </section>
</div>

<script>
function increaseQty(id) {
    const input = document.getElementById('qty_' + id);
    if(input.value < 99) input.value++;
}

function decreaseQty(id) {
    const input = document.getElementById('qty_' + id);
    if(input.value > 1) input.value--;
}

function addToCart(urunId) {
    const qty = document.getElementById('qty_' + urunId).value;
    const card = document.getElementById('qty_' + urunId).closest('.product-card');
    const urunAd = card.querySelector('.product-title a').textContent.trim();
    const fiyatText = card.querySelector('.current-price').textContent.replace('₺','').replace('.','').replace(',','.');
    const fiyat = parseFloat(fiyatText);
    const resim = card.querySelector('.product-image').src;
    const marka = card.querySelector('.product-brand')?.textContent || '';

    const button = card.querySelector('.add-cart-btn');
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Ekleniyor...';
    button.disabled = true;

    fetch("{{ route('sepet.ekle') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({
            id: urunId,
            adet: parseInt(qty),
            urun_ad: urunAd,
            fiyat: fiyat,
            resim_url: resim,
            marka: marka,
            model: ''
        })
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            const badge = document.getElementById('navbarCartCount');
            if(badge) badge.textContent = data.sepetCount;
            
            button.innerHTML = '<i class="fas fa-check"></i> Eklendi!';
            button.style.background = 'linear-gradient(135deg, #10b981, #059669)';
            
            setTimeout(() => {
                button.innerHTML = originalText;
                button.disabled = false;
                button.style.background = '';
            }, 1500);
        } else {
            button.innerHTML = originalText;
            button.disabled = false;
            alert('Hata oluştu!');
        }
    })
    .catch(() => {
        button.innerHTML = originalText;
        button.disabled = false;
        alert('Hata oluştu!');
    });
}

function toggleFavorite(id) {
    //favori
}
</script>
@endsection