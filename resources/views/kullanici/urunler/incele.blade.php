@extends('layouts.app')
@section('title', $urun->urun_ad . ' - Avantaj Bilişim')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
:root {
    /* Anasayfa ve Navbar ile Tam Uyumlu Renkler */
    --primary-turq: #00d4aa;    /* Logodaki Su Yeşili */
    --primary-dark: #00a896;    /* Koyu Su Yeşili */
    --secondary-navy: #1e293b;  /* Koyu Lacivert */
    --accent-orange: #f59e0b;   /* Vurgu Turuncusu */
    --bg-light: #f8fafc;
    
    --product-bg: #ffffff;
    --border-color: #e2e8f0;
    
    --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    --hover-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    --radius-lg: 1rem;
}

body {
    background-color: var(--bg-light);
}

/* Breadcrumb Modernize */
.product-breadcrumb {
    background: white;
    padding: 1.25rem 0;
    border-bottom: 1px solid var(--border-color);
    margin-bottom: 0;
}

.breadcrumb-list {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin: 0;
    padding: 0;
    list-style: none;
    font-size: 0.9rem;
    color: var(--text-muted);
}

.breadcrumb-item a {
    color: var(--secondary-navy);
    text-decoration: none;
    font-weight: 600;
    transition: var(--transition);
}

.breadcrumb-item a:hover {
    color: var(--primary-turq);
}

.breadcrumb-item.current {
    color: var(--primary-dark);
    font-weight: 700;
    max-width: 300px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Product Container */
.product-container {
    background: white;
    border-radius: 20px;
    box-shadow: var(--card-shadow);
    padding: 2.5rem;
    margin-top: 3rem;
    margin-bottom: 3rem;
    border: 1px solid var(--border-color);
}

/* Gallery */
.gallery-wrapper {
    position: sticky;
    top: 100px;
}

.main-image-box {
    position: relative;
    border: 1px solid var(--border-color);
    border-radius: 15px;
    padding: 2rem;
    background: white;
    margin-bottom: 1.5rem;
    height: 480px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    transition: var(--transition);
}

.main-image-box:hover {
    border-color: var(--primary-turq);
    box-shadow: 0 10px 25px rgba(0, 212, 170, 0.1);
}

.main-image {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
    transition: transform 0.5s ease;
}

.main-image-box:hover .main-image {
    transform: scale(1.05);
}

.badge-wrapper {
    position: absolute;
    top: 1rem;
    left: 1rem;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    z-index: 10;
}

.badge-custom {
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-size: 0.75rem;
    font-weight: 800;
    text-transform: uppercase;
    color: white;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}
.badge-new { background-color: var(--primary-turq); color: var(--secondary-navy); }
.badge-sale { background-color: #ef4444; }

.thumbnail-list {
    display: flex;
    gap: 1rem;
    overflow-x: auto;
    padding: 5px;
}

.thumbnail-item {
    width: 85px;
    height: 85px;
    border: 2px solid #f1f5f9;
    border-radius: 12px;
    cursor: pointer;
    transition: var(--transition);
    opacity: 0.8;
    object-fit: contain;
    background: white;
    padding: 5px;
}

.thumbnail-item:hover, .thumbnail-item.active {
    border-color: var(--primary-turq);
    opacity: 1;
    transform: translateY(-3px);
    box-shadow: var(--shadow-md);
}

/* Product Info */
.product-brand {
    color: var(--primary-dark);
    font-weight: 800;
    font-size: 1rem;
    text-transform: uppercase;
    margin-bottom: 0.75rem;
    letter-spacing: 1.5px;
}

.product-title {
    font-size: 2.25rem;
    font-weight: 800;
    color: var(--secondary-navy);
    line-height: 1.2;
    margin-bottom: 1.25rem;
}

.product-meta {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid var(--border-color);
    margin-bottom: 1.5rem;
    color: var(--text-muted);
    font-size: 0.95rem;
}

.rating-stars { color: #f59e0b; font-size: 1.1rem; }
.meta-divider { width: 1px; height: 18px; background: #cbd5e1; }

.product-short-desc {
    color: #475569;
    line-height: 1.8;
    margin-bottom: 2rem;
    font-size: 1.1rem;
}

/* Price Box */
.price-box {
    background: #f0fdfa;
    border: 1px solid rgba(0, 212, 170, 0.2);
    border-radius: 16px;
    padding: 2rem;
    margin-bottom: 2rem;
}

.price-row {
    display: flex;
    align-items: flex-end;
    gap: 1.25rem;
    margin-bottom: 0.75rem;
}

.current-price {
    font-size: 3rem;
    font-weight: 900;
    color: var(--secondary-navy);
    line-height: 1;
}

.old-price {
    font-size: 1.4rem;
    color: #94a3b8;
    text-decoration: line-through;
    margin-bottom: 8px;
}

.discount-tag {
    background-color: var(--accent-orange);
    color: white;
    padding: 0.4rem 1rem;
    border-radius: 50px;
    font-weight: 800;
    font-size: 0.85rem;
    margin-bottom: 12px;
}

.stock-status {
    display: flex;
    align-items: center;
    gap: 0.6rem;
    font-weight: 700;
    margin-bottom: 2rem;
    font-size: 1rem;
}
.stock-in { color: #10b981; }
.stock-out { color: #ef4444; }

/* Actions */
.action-area {
    display: flex;
    flex-wrap: wrap;
    gap: 1.25rem;
}

.quantity-wrapper {
    display: flex;
    align-items: center;
    border: 2px solid var(--border-color);
    border-radius: 12px;
    background: white;
    transition: var(--transition);
}

.quantity-wrapper:focus-within {
    border-color: var(--primary-turq);
}

.qty-btn {
    width: 50px;
    height: 55px;
    background: transparent;
    border: none;
    font-size: 1.3rem;
    color: var(--secondary-navy);
    cursor: pointer;
    transition: 0.2s;
    font-weight: 700;
}

.qty-btn:hover { background: #f1f5f9; color: var(--primary-turq); }

.qty-input {
    width: 60px;
    text-align: center;
    border: none;
    font-weight: 800;
    color: var(--secondary-navy);
    font-size: 1.2rem;
    outline: none;
}

.add-cart-btn {
    flex: 2;
    background: var(--primary-turq);
    color: var(--secondary-navy);
    border: none;
    padding: 0 2.5rem;
    border-radius: 12px;
    font-weight: 800;
    font-size: 1.1rem;
    cursor: pointer;
    transition: var(--transition);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.8rem;
    min-width: 240px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    box-shadow: 0 8px 20px rgba(0, 212, 170, 0.2);
}

.add-cart-btn:hover {
    background: var(--primary-dark);
    color: white;
    transform: translateY(-3px);
    box-shadow: 0 12px 25px rgba(0, 212, 170, 0.3);
}

.add-cart-btn:disabled {
    background: #cbd5e1;
    color: #64748b;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

.fav-btn {
    width: 60px;
    height: 55px;
    border: 2px solid var(--border-color);
    background: white;
    border-radius: 12px;
    color: #64748b;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    cursor: pointer;
    transition: var(--transition);
}

.fav-btn:hover {
    border-color: #ef4444;
    color: #ef4444;
    background: #fff1f2;
}

.fav-btn.active {
    background: #fff1f2;
    border-color: #ef4444;
    color: #ef4444;
}

/* Tabs */
.details-tabs {
    margin-top: 5rem;
}

.nav-pills .nav-link {
    color: var(--text-muted);
    font-weight: 700;
    padding: 1.25rem 2.5rem;
    border-radius: 12px;
    transition: all 0.3s;
    background: white;
    border: 1px solid var(--border-color);
    margin-right: 10px;
}

.nav-pills .nav-link.active {
    background-color: var(--secondary-navy);
    color: white;
    border-color: var(--secondary-navy);
    box-shadow: 0 8px 15px rgba(30, 41, 59, 0.2);
}

.tab-content {
    background: white;
    padding: 3rem;
    border-radius: 20px;
    border: 1px solid var(--border-color);
    margin-top: 1.5rem;
    box-shadow: var(--card-shadow);
}

/* Specs Table */
.specs-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
}
.specs-table tr:nth-child(even) { background-color: #f8fafc; }
.specs-table td {
    padding: 1.25rem 1.75rem;
    border-bottom: 1px solid #f1f5f9;
    color: #475569;
    font-size: 1.05rem;
}
.specs-table td:first-child {
    font-weight: 700;
    color: var(--secondary-navy);
    width: 35%;
    background-color: rgba(0, 212, 170, 0.03);
}

/* REVIEWS CSS */
.rating-summary-box {
    background: #f8fafc;
    border-radius: 15px;
    padding: 2.5rem;
    text-align: center;
    border: 2px solid var(--bg-light);
}
.rating-big-score {
    font-size: 4rem;
    font-weight: 900;
    color: var(--secondary-navy);
    line-height: 1;
}
.rating-stars-static {
    color: #f59e0b;
    font-size: 1.4rem;
    margin: 0.75rem 0;
}
.rating-bars-item {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 12px;
    font-size: 1rem;
    color: var(--text-muted);
}
.progress-custom {
    flex-grow: 1;
    height: 10px;
    background-color: #e2e8f0;
    border-radius: 10px;
    overflow: hidden;
}
.progress-bar-fill {
    height: 100%;
    background-color: var(--primary-turq);
}

.review-item {
    border-bottom: 1px solid var(--border-color);
    padding: 2rem 0;
}
.review-item:last-child { border-bottom: none; }
.review-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.75rem;
}
.review-user {
    font-weight: 700;
    color: var(--secondary-navy);
    display: flex;
    align-items: center;
    gap: 0.75rem;
}
.review-avatar {
    width: 40px;
    height: 40px;
    background: var(--primary-turq);
    color: var(--secondary-navy);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 800;
}
.review-date { font-size: 0.9rem; color: #94a3b8; }
.review-text { color: #475569; line-height: 1.8; font-size: 1.05rem; }

/* Star Rating Input */
.rating-input-group {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
    gap: 8px;
}
.rating-input-group input { display: none; }
.rating-input-group label {
    font-size: 2.25rem;
    color: #cbd5e1;
    cursor: pointer;
    transition: var(--transition);
}
.rating-input-group label:hover,
.rating-input-group label:hover ~ label,
.rating-input-group input:checked ~ label {
    color: #f59e0b;
}

/* Related Products */
.related-section {
    margin-top: 6rem;
    margin-bottom: 4rem;
}
.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2.5rem;
    padding-bottom: 1rem;
    border-bottom: 3px solid var(--primary-turq);
}
.section-title {
    font-size: 2rem;
    font-weight: 800;
    color: var(--secondary-navy);
    margin: 0;
}

/* Responsive */
@media (max-width: 991px) {
    .main-image-box { height: 380px; }
    .product-title { font-size: 1.8rem; }
    .action-area { flex-direction: column; }
    .add-cart-btn { width: 100%; height: 60px; }
    .quantity-wrapper { justify-content: center; width: 100%; }
    .fav-btn { width: 100%; }
    .nav-pills { flex-direction: column; }
    .nav-pills .nav-link { margin-right: 0; margin-bottom: 10px; text-align: center; }
}
</style>

<div class="product-breadcrumb">
    <div class="container">
        <ul class="breadcrumb-list">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Anasayfa</a></li>
            <li class="breadcrumb-item">/</li>
            <li class="breadcrumb-item"><a href="{{ route('urun.index') }}">Ürünler</a></li>
            <li class="breadcrumb-item">/</li>
            @if($urun->altKategori)
                <li class="breadcrumb-item">
                    <a href="{{ route('urun.kategori', $urun->altKategori->kategori->id) }}">
                        {{ $urun->altKategori->kategori->kategori_ad }}
                    </a>
                </li>
                <li class="breadcrumb-item">/</li>
                <li class="breadcrumb-item">
                    <a href="{{ route('urun.altkategori', $urun->altKategori->id) }}">
                        {{ $urun->altKategori->alt_kategori_ad }}
                    </a>
                </li>
                <li class="breadcrumb-item">/</li>
            @endif
            <li class="breadcrumb-item current">{{ $urun->urun_ad }}</li>
        </ul>
    </div>
</div>

<div class="container py-4">
    <div class="product-container">
        <div class="row g-5">
            <div class="col-lg-6">
                <div class="gallery-wrapper">
                    <div class="main-image-box">
                        <div class="badge-wrapper">
                            @if($urun->created_at->diffInDays(now()) < 30)
                                <span class="badge-custom badge-new">YENİ ÜRÜN</span>
                            @endif
                            @if($kampanya)
                                <span class="badge-custom badge-sale">BÜYÜK FIRSAT -%{{ $kampanya->indirim_orani }}</span>
                            @endif
                        </div>
                        <img src="{{ $urun->resim_url ? asset($urun->resim_url) : 'https://via.placeholder.com/600x600?text=' . urlencode($urun->urun_ad) }}" 
                             alt="{{ $urun->urun_ad }}" 
                             class="main-image" 
                             id="mainImage">
                    </div>
                    
                    <div class="thumbnail-list">
                        <img src="{{ $urun->resim_url ? asset($urun->resim_url) : 'https://via.placeholder.com/100x100' }}" class="thumbnail-item active" onclick="changeImage(this)">
                        <img src="https://via.placeholder.com/100x100?text=Açı+2" class="thumbnail-item" onclick="changeImage(this)">
                        <img src="https://via.placeholder.com/100x100?text=Açı+3" class="thumbnail-item" onclick="changeImage(this)">
                        <img src="https://via.placeholder.com/100x100?text=Detay" class="thumbnail-item" onclick="changeImage(this)">
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="product-info-wrapper">
                    <div class="product-brand">{{ $urun->marka }}</div>
                    <h1 class="product-title">{{ $urun->urun_ad }}</h1>
                    
                    <div class="product-meta">
                        <div class="d-flex align-items-center gap-2">
                            <div class="rating-stars">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= round($ortalamaPuan))
                                        <i class="fas fa-star"></i>
                                    @else
                                        <i class="far fa-star"></i>
                                    @endif
                                @endfor
                            </div>
                            <a href="#reviews" onclick="document.getElementById('pills-reviews-tab').click()" class="text-decoration-none fw-bold" style="color: var(--primary-turq)">
                                ({{ $yorumSayisi }} Müşteri Yorumu)
                            </a>
                        </div>
                        <div class="meta-divider"></div>
                        <div>STOK KODU: <strong class="text-dark">{{ $urun->barkod_no ?? 'AVN-' . $urun->id }}</strong></div>
                    </div>

                    <div class="product-short-desc">
                        {{ \Illuminate\Support\Str::limit($urun->aciklama, 200) }}
                    </div>

                    <div class="price-box shadow-sm">
                        @if($satisFiyati > 0)
                            @if($kampanya || ($isBayi && $bayiFiyat < $standartFiyat))
                                <div class="discount-tag">AVANTAJLI FİYAT</div>
                                <div class="price-row">
                                    <div class="current-price">
                                        ₺{{ number_format($isBayi ? $bayiFiyat : $indirimliFiyat, 2, ',', '.') }}
                                    </div>
                                    <div class="old-price">
                                        ₺{{ number_format($standartFiyat, 2, ',', '.') }}
                                    </div>
                                </div>
                            @else
                                <div class="current-price">
                                    ₺{{ number_format($satisFiyati, 2, ',', '.') }}
                                </div>
                            @endif
                            <div class="fw-bold mt-2" style="color: var(--secondary-navy)">
                                <i class="fas fa-credit-card me-2 text-primary"></i> 
                                Peşin Fiyatına <strong>{{ number_format(($isBayi ? $bayiFiyat : $indirimliFiyat)/6, 2, ',', '.') }} ₺ x 6</strong> Taksit Seçeneği
                            </div>
                        @else
                            <h3 class="text-muted fw-bold">Stok Bilgisi ve Fiyat İçin Arayınız</h3>
                        @endif
                    </div>

                    <div class="stock-status {{ $urun->stok > 0 ? 'stock-in' : 'stock-out' }}">
                        @if($urun->stok > 0)
                            <i class="fas fa-check-double me-2"></i> STOKTA MEVCUT (Hemen Kargo)
                        @else
                            <i class="fas fa-exclamation-triangle me-2"></i> BU ÜRÜN GEÇİCİ OLARAK TEMİN EDİLEMİYOR
                        @endif
                    </div>

                    <form id="addToCartForm" onsubmit="handleAddToCart(event)">
                        @csrf
                        <input type="hidden" name="id" value="{{ $urun->id }}">
                        
                        <div class="action-area">
                            @if($urun->stok > 0)
                                <div class="quantity-wrapper">
                                    <button type="button" class="qty-btn" onclick="updateQty(-1)">-</button>
                                    <input type="number" name="adet" id="quantity" class="qty-input" value="1" min="1" max="{{ $urun->stok }}" readonly>
                                    <button type="button" class="qty-btn" onclick="updateQty(1)">+</button>
                                </div>
                                
                                <button type="submit" class="add-cart-btn" id="addToCartBtn">
                                    <i class="fas fa-cart-plus fs-4"></i> SEPETE EKLE
                                </button>
                            @else
                                <button type="button" class="add-cart-btn w-100" style="background: #94a3b8; cursor: not-allowed;" disabled>
                                    <i class="fas fa-bell me-2"></i> STOKTA YOK
                                </button>
                            @endif

                            <button type="button" class="fav-btn {{ $isFavorite ? 'active' : '' }}" onclick="toggleFavorite({{ $urun->id }})" title="Favorilerime Ekle">
                                <i class="{{ $isFavorite ? 'fas' : 'far' }} fa-heart"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="details-tabs" id="reviews">
        <ul class="nav nav-pills" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="pills-desc-tab" data-bs-toggle="pill" data-bs-target="#pills-desc" type="button" role="tab">ÜRÜN AÇIKLAMASI</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-specs-tab" data-bs-toggle="pill" data-bs-target="#pills-specs" type="button" role="tab">TEKNİK ÖZELLİKLER</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-reviews-tab" data-bs-toggle="pill" data-bs-target="#pills-reviews" type="button" role="tab">
                    MÜŞTERİ YORUMLARI ({{ $yorumSayisi }})
                </button>
            </li>
        </ul>
        
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-desc" role="tabpanel">
                <div class="prose max-w-none" style="color: #475569; font-size: 1.1rem; line-height: 1.9;">
                    {!! nl2br(e($urun->aciklama)) !!}
                </div>
            </div>

            <div class="tab-pane fade" id="pills-specs" role="tabpanel">
                <div class="table-responsive">
                    <table class="specs-table">
                        <tbody>
                            <tr><td>Üretici Marka</td><td><strong>{{ $urun->marka }}</strong></td></tr>
                            <tr><td>Ürün Modeli</td><td>{{ $urun->model }}</td></tr>
                            @foreach($urun->urunKriterDegerleri as $deger)
                                <tr>
                                    <td>{{ $deger->kriter->kriter_ad }}</td>
                                    <td>{{ $deger->kriterDeger->deger }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="tab-pane fade" id="pills-reviews" role="tabpanel">
                <div class="row g-4 mb-5">
                    <div class="col-md-4">
                        <div class="rating-summary-box">
                            <div class="rating-big-score">{{ number_format($ortalamaPuan, 1) }}</div>
                            <div class="rating-stars-static">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="{{ $i <= round($ortalamaPuan) ? 'fas' : 'far' }} fa-star"></i>
                                @endfor
                            </div>
                            <div class="fw-bold text-muted small uppercase">Genel Ürün Puanı</div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="px-lg-4">
                            @foreach([5, 4, 3, 2, 1] as $star)
                                @php
                                    $count = $yildizDagilimi[$star] ?? 0;
                                    $percent = $yorumSayisi > 0 ? ($count / $yorumSayisi) * 100 : 0;
                                @endphp
                                <div class="rating-bars-item">
                                    <div style="width: 50px; font-weight: 700;">{{ $star }} Yıldız</div>
                                    <div class="progress-custom">
                                        <div class="progress-bar-fill" style="width: {{ $percent }}%"></div>
                                    </div>
                                    <div style="width: 40px; text-align: right; font-weight: 600;">{{ $count }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="row g-5">
                    <div class="col-lg-7">
                        <h4 class="mb-4 fw-800" style="color: var(--secondary-navy)">Tüm Değerlendirmeler</h4>
                        <div class="reviews-list">
                            @forelse($urun->degerlendirmeler as $yorum)
                                <div class="review-item">
                                    <div class="review-header">
                                        <div class="review-user">
                                            <div class="review-avatar">{{ strtoupper(substr($yorum->user->name ?? 'M', 0, 1)) }}</div>
                                            <div>
                                                <div class="fw-bold">{{ $yorum->user->name ?? 'Avantaj Müşterisi' }}</div>
                                                <div class="text-warning" style="font-size: 0.8rem;">
                                                    @for($i=1; $i<=5; $i++)
                                                        <i class="{{ $i <= $yorum->puan ? 'fas' : 'far' }} fa-star"></i>
                                                    @endfor
                                                </div>
                                            </div>
                                        </div>
                                        <div class="review-date">{{ $yorum->created_at->format('d/m/Y') }}</div>
                                    </div>
                                    <p class="review-text">{{ $yorum->yorum }}</p>

                                    @if($yorum->cevap)
                                        <div class="admin-reply bg-light p-4 rounded-4 ms-4 mt-3 border-start border-primary border-4 shadow-sm">
                                            <div class="d-flex align-items-center mb-2 text-primary fw-bold">
                                                <i class="fas fa-shield-halved me-2"></i> Avantaj Bilişim Yanıtı
                                            </div>
                                            <p class="mb-0 text-muted italic" style="font-size: 0.95rem;">"{{ $yorum->cevap }}"</p>
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <div class="text-center py-5 bg-light rounded-4 border-dashed border-2">
                                    <i class="fas fa-pen-nib fa-3x text-muted mb-3 opacity-30"></i>
                                    <h5 class="text-muted">Bu ürünü henüz kimse yorumlamadı.</h5>
                                    <p class="text-muted small">Deneyimlerinizi diğer kullanıcılarla paylaşan ilk kişi olun!</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="col-lg-5">
                        <div class="p-4 rounded-4 shadow-sm border bg-white" style="position: sticky; top: 100px;">
                            <h4 class="mb-4 fw-800" style="color: var(--secondary-navy)">Deneyimini Paylaş</h4>
                            
                            @auth
                                <form action="{{ route('urun.degerlendirme', $urun->id) }}" method="POST">
                                    @csrf
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Ürüne Puanınız:</label>
                                        <div class="rating-input-group">
                                            <input type="radio" id="star5" name="puan" value="5" required><label for="star5"><i class="fas fa-star"></i></label>
                                            <input type="radio" id="star4" name="puan" value="4"><label for="star4"><i class="fas fa-star"></i></label>
                                            <input type="radio" id="star3" name="puan" value="3"><label for="star3"><i class="fas fa-star"></i></label>
                                            <input type="radio" id="star2" name="puan" value="2"><label for="star2"><i class="fas fa-star"></i></label>
                                            <input type="radio" id="star1" name="puan" value="1"><label for="star1"><i class="fas fa-star"></i></label>
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Yorumunuz:</label>
                                        <textarea name="yorum" class="form-control rounded-3 border-2" rows="5" placeholder="Ürün performansı ve kalitesi hakkında ne düşünüyorsunuz?" required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100 py-3 fw-bold rounded-3 shadow" style="background: var(--secondary-navy); border: none;">
                                        YORUMU GÖNDER
                                    </button>
                                </form>
                            @else
                                <div class="text-center py-4">
                                    <div class="mb-3 text-warning"><i class="fas fa-user-lock fa-3x"></i></div>
                                    <p class="mb-3 text-muted fw-bold">Yorum yapabilmek için üye girişi yapmalısınız.</p>
                                    <a href="{{ route('login') }}" class="btn btn-outline-primary px-5 py-2 rounded-pill fw-bold">GİRİŞ YAP</a>
                                </div>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(isset($benzerUrunler) && $benzerUrunler->count() > 0)
    <div class="related-section">
        <div class="section-header">
            <h2 class="section-title">İlginizi Çekebilecek Diğer Ürünler</h2>
            <a href="{{ route('urun.index') }}" class="text-decoration-none fw-bold" style="color: var(--primary-turq)">Tümünü Gör →</a>
        </div>
        <div class="row row-cols-2 row-cols-md-4 g-4">
            @foreach($benzerUrunler as $benzer)
            <div class="col">
                <a href="{{ route('urun.incele', $benzer->id) }}" class="text-decoration-none group">
                    <div class="card h-100 border-0 shadow-sm hover-shadow transition-all" style="border-radius: 15px; overflow: hidden; border: 1px solid #f1f5f9 !important;">
                        <div class="p-3 text-center bg-white">
                            <img src="{{ $benzer->resim_url ? asset($benzer->resim_url) : 'https://via.placeholder.com/300x300' }}" 
                                 class="img-fluid" 
                                 alt="{{ $benzer->urun_ad }}" 
                                 style="height: 180px; object-fit: contain; transition: 0.3s;"
                                 onmouseover="this.style.transform='scale(1.05)'"
                                 onmouseout="this.style.transform='scale(1)'">
                        </div>
                        <div class="card-body p-3">
                            <h6 class="card-title text-dark fw-bold text-truncate mb-2">{{ $benzer->urun_ad }}</h6>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="fw-black fs-5" style="color: var(--secondary-navy)">₺{{ number_format($benzer->fiyat, 2, ',', '.') }}</div>
                                <div class="text-warning small"><i class="fas fa-star"></i> 4.8</div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<div class="modal fade" id="loginModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 p-5 text-center shadow-lg">
            <div class="mb-4 text-warning"><i class="fas fa-heart-circle-exclamation fa-4x"></i></div>
            <h3 class="fw-bold">Favorilere Ekle</h3>
            <p class="text-muted px-4">Bu ürünü favorilerinize ekleyip takip edebilmek için lütfen hesabınıza giriş yapın.</p>
            <div class="d-grid gap-2 mt-4">
                <a href="{{ route('login') }}" class="btn btn-primary py-3 rounded-3 fw-bold" style="background: var(--primary-turq); border: none; color: var(--secondary-navy)">GİRİŞ YAP</a>
                <button type="button" class="btn btn-light py-3 rounded-3 fw-bold" data-bs-dismiss="modal">VAZGEÇ</button>
            </div>
            <p class="mt-3 small">Hesabınız yok mu? <a href="{{ route('register') }}" class="fw-bold text-primary">Hemen Kayıt Ol</a></p>
        </div>
    </div>
</div>

<script>
    // Görsel Değiştirme Fonksiyonu
    function changeImage(el) {
        const mainImg = document.getElementById('mainImage');
        mainImg.style.opacity = '0';
        setTimeout(() => {
            mainImg.src = el.src;
            mainImg.style.opacity = '1';
        }, 200);
        document.querySelectorAll('.thumbnail-item').forEach(item => item.classList.remove('active'));
        el.classList.add('active');
    }

    // Adet Güncelleme Fonksiyonu
    function updateQty(change) {
        const input = document.getElementById('quantity');
        let newVal = parseInt(input.value) + change;
        const max = parseInt(input.getAttribute('max'));
        if(newVal >= 1 && newVal <= max) {
            input.value = newVal;
        }
    }

    // ========== SEPETE EKLEME AJAX (Entegre Edildi) ==========
    function handleAddToCart(e) {
        e.preventDefault();
        const btn = document.getElementById('addToCartBtn');
        const form = document.getElementById('addToCartForm');
        
        btn.disabled = true;
        const originalHtml = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> SEPETE EKLENİYOR...';

        const formData = new FormData(form);
        
        // FormData'yı JSON'a dönüştür (Backend JSON bekliyorsa)
        const jsonData = {};
        formData.forEach((value, key) => {
            jsonData[key] = value;
        });

        fetch('{{ route("sepet.ekle") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(jsonData)
        })
        .then(res => {
            if (!res.ok) throw new Error('Sunucu hatası');
            return res.json();
        })
        .then(data => {
            if(data.success) {
                // Layout'taki global fonksiyonu tetikle (Sepet sayacı güncelleme)
                if (typeof window.updateAllCartCounts === 'function') {
                    const count = data.sepetCount || data.sepet_count || 0;
                    window.updateAllCartCounts(count);
                } else {
                    // Alternatif: ID üzerinden manuel güncelleme
                    const cartCounts = [document.getElementById('cartCount'), document.getElementById('cartCountMobile')];
                    cartCounts.forEach(el => {
                        if(el) el.innerText = data.sepetCount || data.sepet_count;
                    });
                }
                
                // Buton Durumu Güncelle
                btn.innerHTML = '<i class="fas fa-check-double"></i> SEPETE EKLENDİ';
                btn.style.backgroundColor = '#10b981';
                btn.style.color = 'white';
                
                // Toast Mesajı
                if(window.showToast) {
                    window.showToast('Ürün başarıyla sepete eklendi!', 'success');
                }

                setTimeout(() => {
                    btn.innerHTML = originalHtml;
                    btn.style.backgroundColor = '';
                    btn.style.color = '';
                    btn.disabled = false;
                }, 2500);
            } else {
                alert(data.message || 'Bir hata oluştu');
                btn.innerHTML = originalHtml;
                btn.disabled = false;
            }
        })
        .catch(err => {
            console.error('❌ Sepete ekleme hatası:', err);
            btn.innerHTML = originalHtml;
            btn.disabled = false;
            if(window.showToast) window.showToast('İşlem başarısız.', 'error');
        });
    }

    // ========== FAVORİ İŞLEMİ AJAX (Entegre Edildi) ==========
    function toggleFavorite(id) {
        @guest
            new bootstrap.Modal(document.getElementById('loginModal')).show();
            return;
        @endguest

        fetch('{{ route("favori.toggle") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ urun_id: id })
        })
        .then(res => res.json())
        .then(data => {
            const btn = document.querySelector('.fav-btn');
            const icon = btn.querySelector('i');
            if(data.action === 'added') {
                btn.classList.add('active');
                icon.classList.replace('far', 'fas');
                if(window.showToast) window.showToast('Favorilerinize eklendi!', 'success');
            } else {
                btn.classList.remove('active');
                icon.classList.replace('fas', 'far');
                if(window.showToast) window.showToast('Favorilerinizden kaldırıldı.', 'info');
            }
        })
        .catch(err => console.error('❌ Favori hatası:', err));
    }
</script>
@endsection