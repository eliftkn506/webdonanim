@extends('layouts.app')
@section('title', $urun->urun_ad . ' - Avantaj Bilişim')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
:root {
    /* Ana Tema Renkleri */
    --primary-color: #1a365d;       /* Lacivert */
    --secondary-color: #22987e;     /* Yeşil */
    --accent-color: #3182ce;        /* Mavi */
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

/* Breadcrumb */
.product-breadcrumb {
    background: white;
    padding: 1rem 0;
    border-bottom: 1px solid var(--border-color);
    margin-bottom: 2rem;
}

.breadcrumb-list {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin: 0;
    padding: 0;
    list-style: none;
    font-size: 0.9rem;
    color: #64748b;
}

.breadcrumb-item a {
    color: var(--primary-color);
    text-decoration: none;
    font-weight: 500;
    transition: var(--transition);
}

.breadcrumb-item a:hover {
    color: var(--secondary-color);
}

.breadcrumb-item.current {
    color: #94a3b8;
    max-width: 300px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Product Container */
.product-container {
    background: white;
    border-radius: var(--radius-lg);
    box-shadow: var(--card-shadow);
    padding: 2rem;
    margin-bottom: 3rem;
}

/* Gallery */
.gallery-wrapper {
    position: sticky;
    top: 2rem;
}

.main-image-box {
    position: relative;
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    padding: 2rem;
    background: white;
    margin-bottom: 1rem;
    height: 450px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.main-image {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
    transition: transform 0.5s ease;
}

.main-image-box:hover .main-image {
    transform: scale(1.1);
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
    padding: 0.35rem 0.85rem;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    color: white;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
.badge-new { background-color: var(--secondary-color); }
.badge-sale { background-color: #ef4444; }

.thumbnail-list {
    display: flex;
    gap: 0.75rem;
    overflow-x: auto;
    padding-bottom: 0.5rem;
}

.thumbnail-item {
    width: 80px;
    height: 80px;
    border: 2px solid transparent;
    border-radius: 8px;
    cursor: pointer;
    transition: var(--transition);
    opacity: 0.7;
    object-fit: cover;
    background: #f8fafc;
}

.thumbnail-item:hover, .thumbnail-item.active {
    border-color: var(--primary-color);
    opacity: 1;
}

/* Product Info */
.product-brand {
    color: var(--accent-color);
    font-weight: 700;
    font-size: 0.9rem;
    text-transform: uppercase;
    margin-bottom: 0.5rem;
    letter-spacing: 0.5px;
}

.product-title {
    font-size: 2rem;
    font-weight: 800;
    color: var(--primary-color);
    line-height: 1.3;
    margin-bottom: 1rem;
}

.product-meta {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid var(--border-color);
    margin-bottom: 1.5rem;
    color: #64748b;
    font-size: 0.9rem;
}

.rating-stars { color: #f59e0b; }
.meta-divider { width: 1px; height: 15px; background: #cbd5e1; }

.product-short-desc {
    color: #475569;
    line-height: 1.7;
    margin-bottom: 2rem;
    font-size: 1.05rem;
}

/* Price Box */
.price-box {
    background: #f8fafc;
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 2rem;
}

.price-row {
    display: flex;
    align-items: flex-end;
    gap: 1rem;
    margin-bottom: 0.5rem;
}

.current-price {
    font-size: 2.5rem;
    font-weight: 800;
    color: var(--primary-color);
    line-height: 1;
}

.old-price {
    font-size: 1.2rem;
    color: #94a3b8;
    text-decoration: line-through;
    margin-bottom: 5px;
}

.discount-tag {
    background-color: #fee2e2;
    color: #ef4444;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-weight: 700;
    font-size: 0.85rem;
    margin-bottom: 8px;
}

.stock-status {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
}
.stock-in { color: var(--secondary-color); }
.stock-out { color: #ef4444; }

/* Actions */
.action-area {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
}

.quantity-wrapper {
    display: flex;
    align-items: center;
    border: 2px solid var(--border-color);
    border-radius: 8px;
    background: white;
}

.qty-btn {
    width: 45px;
    height: 45px;
    background: transparent;
    border: none;
    font-size: 1.2rem;
    color: var(--primary-color);
    cursor: pointer;
    transition: 0.2s;
}

.qty-btn:hover { background: #f1f5f9; }

.qty-input {
    width: 50px;
    text-align: center;
    border: none;
    font-weight: 700;
    color: var(--primary-color);
    font-size: 1.1rem;
    -moz-appearance: textfield;
}

.add-cart-btn {
    flex: 2;
    background: var(--secondary-color);
    color: white;
    border: none;
    padding: 0 2rem;
    border-radius: 8px;
    font-weight: 700;
    font-size: 1.1rem;
    cursor: pointer;
    transition: var(--transition);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    min-width: 200px;
}

.add-cart-btn:hover {
    background: #1a7f6c;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(34, 152, 126, 0.3);
}

.add-cart-btn:disabled {
    background: #cbd5e1;
    cursor: not-allowed;
    transform: none;
}

.fav-btn {
    width: 50px;
    height: 50px;
    border: 2px solid var(--border-color);
    background: white;
    border-radius: 8px;
    color: #64748b;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
    cursor: pointer;
    transition: var(--transition);
}

.fav-btn:hover {
    border-color: #ef4444;
    color: #ef4444;
}

.fav-btn.active {
    background: #fef2f2;
    border-color: #ef4444;
    color: #ef4444;
}

/* Tabs */
.details-tabs {
    margin-top: 4rem;
}

.nav-pills .nav-link {
    color: #64748b;
    font-weight: 600;
    padding: 1rem 2rem;
    border-radius: 8px;
    transition: all 0.3s;
    background: transparent;
}

.nav-pills .nav-link.active {
    background-color: var(--primary-color);
    color: white;
    box-shadow: 0 4px 6px rgba(26, 54, 93, 0.2);
}

.tab-content {
    background: white;
    padding: 2.5rem;
    border-radius: 16px;
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
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #f1f5f9;
    color: #475569;
}
.specs-table td:first-child {
    font-weight: 600;
    color: var(--primary-color);
    width: 30%;
}

/* REVIEWS CSS */
.rating-summary-box {
    background: #f8fafc;
    border-radius: 12px;
    padding: 2rem;
    text-align: center;
    border: 1px solid var(--border-color);
    height: 100%;
}
.rating-big-score {
    font-size: 3.5rem;
    font-weight: 800;
    color: var(--primary-color);
    line-height: 1;
}
.rating-stars-static {
    color: #f59e0b;
    font-size: 1.2rem;
    margin: 0.5rem 0;
}
.rating-bars-item {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 8px;
    font-size: 0.9rem;
    color: #64748b;
}
.progress-custom {
    flex-grow: 1;
    height: 8px;
    background-color: #e2e8f0;
    border-radius: 4px;
    overflow: hidden;
}
.progress-bar-fill {
    height: 100%;
    background-color: #f59e0b;
}

.review-item {
    border-bottom: 1px solid var(--border-color);
    padding: 1.5rem 0;
}
.review-item:last-child { border-bottom: none; }
.review-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
}
.review-user {
    font-weight: 700;
    color: var(--primary-color);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.review-avatar {
    width: 35px;
    height: 35px;
    background: var(--accent-color);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.9rem;
}
.review-date { font-size: 0.85rem; color: #94a3b8; }
.review-text { color: #475569; line-height: 1.6; }

/* Star Rating Input */
.rating-input-group {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
    gap: 5px;
}
.rating-input-group input { display: none; }
.rating-input-group label {
    font-size: 2rem;
    color: #cbd5e1;
    cursor: pointer;
    transition: color 0.2s;
}
.rating-input-group label:hover,
.rating-input-group label:hover ~ label,
.rating-input-group input:checked ~ label {
    color: #f59e0b;
}

/* Related Products */
.related-section {
    margin-top: 5rem;
}
.section-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #e2e8f0;
}
.section-title {
    font-size: 1.75rem;
    font-weight: 800;
    color: var(--primary-color);
    margin: 0;
}

/* Responsive */
@media (max-width: 991px) {
    .main-image-box { height: 350px; }
    .product-title { font-size: 1.75rem; }
    .action-area { flex-direction: column; }
    .add-cart-btn { width: 100%; height: 50px; }
    .quantity-wrapper { justify-content: center; }
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

<div class="container">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="product-container">
        <div class="row g-5">
            <div class="col-lg-6">
                <div class="gallery-wrapper">
                    <div class="main-image-box">
                        <div class="badge-wrapper">
                            @if($urun->created_at->diffInDays(now()) < 30)
                                <span class="badge-custom badge-new">YENİ</span>
                            @endif
                            @if($kampanya)
                                <span class="badge-custom badge-sale">-%{{ $kampanya->indirim_orani }}</span>
                            @endif
                        </div>
                        <img src="{{ $urun->resim_url ? asset($urun->resim_url) : 'https://via.placeholder.com/600x600?text=' . urlencode($urun->urun_ad) }}" 
                             alt="{{ $urun->urun_ad }}" 
                             class="main-image" 
                             id="mainImage">
                    </div>
                    
                    <div class="thumbnail-list">
                        <img src="{{ $urun->resim_url ? asset($urun->resim_url) : 'https://via.placeholder.com/100x100' }}" class="thumbnail-item active" onclick="changeImage(this)">
                        <img src="https://via.placeholder.com/100x100?text=2" class="thumbnail-item" onclick="changeImage(this)">
                        <img src="https://via.placeholder.com/100x100?text=3" class="thumbnail-item" onclick="changeImage(this)">
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="product-info-wrapper">
                    <div class="product-brand">{{ $urun->marka }}</div>
                    <h1 class="product-title">{{ $urun->urun_ad }}</h1>
                    
                    <div class="product-meta">
                        <div class="d-flex align-items-center gap-2">
                            <div class="rating-stars text-warning">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= round($ortalamaPuan))
                                        <i class="fas fa-star"></i>
                                    @else
                                        <i class="far fa-star"></i>
                                    @endif
                                @endfor
                            </div>
                            <a href="#reviews" onclick="document.getElementById('pills-reviews-tab').click()" class="text-decoration-none text-muted">
                                ({{ $yorumSayisi }} Değerlendirme)
                            </a>
                        </div>
                        <div class="meta-divider"></div>
                        <div>Stok Kodu: <strong>{{ $urun->barkod_no ?? 'N/A' }}</strong></div>
                    </div>

                    <div class="product-short-desc">
                        {{ \Illuminate\Support\Str::limit($urun->aciklama, 150) }}
                    </div>

                    <div class="price-box">
                        @if($satisFiyati > 0)
                            @if($kampanya || ($isBayi && $bayiFiyat < $standartFiyat))
                                <div class="discount-tag">Fırsat Ürünü</div>
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
                            <div class="text-muted small mt-2">
                                <i class="fas fa-credit-card me-1"></i> 
                                {{ number_format(($isBayi ? $bayiFiyat : $indirimliFiyat)/6, 2, ',', '.') }} ₺ x 6 Taksit İmkanı
                            </div>
                        @else
                            <h3 class="text-muted">Fiyat Sorunuz</h3>
                        @endif
                    </div>

                    <div class="stock-status {{ $urun->stok > 0 ? 'stock-in' : 'stock-out' }}">
                        @if($urun->stok > 0)
                            <i class="fas fa-check-circle me-2"></i> Stokta Var ({{ $urun->stok }} Adet)
                        @else
                            <i class="fas fa-times-circle me-2"></i> Stokta Yok
                        @endif
                    </div>

                    <form id="addToCartForm" onsubmit="handleAddToCart(event)">
                        @csrf
                        <input type="hidden" name="id" value="{{ $urun->id }}">
                        
                        <div class="action-area">
                            @if($urun->stok > 0)
                                <div class="quantity-wrapper">
                                    <button type="button" class="qty-btn" onclick="updateQty(-1)">-</button>
                                    <input type="number" name="adet" id="quantity" class="qty-input" value="1" min="1" max="{{ $urun->stok }}">
                                    <button type="button" class="qty-btn" onclick="updateQty(1)">+</button>
                                </div>
                                
                                <button type="submit" class="add-cart-btn" id="addToCartBtn">
                                    <i class="fas fa-shopping-cart"></i> Sepete Ekle
                                </button>
                            @else
                                <button type="button" class="add-cart-btn" disabled>Tükendi</button>
                            @endif

                            <button type="button" class="fav-btn {{ $isFavorite ? 'active' : '' }}" onclick="toggleFavorite({{ $urun->id }})">
                                <i class="{{ $isFavorite ? 'fas' : 'far' }} fa-heart"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="details-tabs" id="reviews">
        <ul class="nav nav-pills mb-4" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-desc-tab" data-bs-toggle="pill" data-bs-target="#pills-desc" type="button" role="tab">Ürün Açıklaması</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-specs-tab" data-bs-toggle="pill" data-bs-target="#pills-specs" type="button" role="tab">Teknik Özellikler</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="pills-reviews-tab" data-bs-toggle="pill" data-bs-target="#pills-reviews" type="button" role="tab">
                    Değerlendirmeler ({{ $yorumSayisi }})
                </button>
            </li>
        </ul>
        
        <div class="tab-content" id="pills-tabContent">
            
            <div class="tab-pane fade" id="pills-desc" role="tabpanel">
                <div class="prose max-w-none text-muted">
                    {!! nl2br(e($urun->aciklama)) !!}
                </div>
            </div>

            <div class="tab-pane fade" id="pills-specs" role="tabpanel">
                <table class="specs-table">
                    <tbody>
                        <tr><td>Marka</td><td>{{ $urun->marka }}</td></tr>
                        <tr><td>Model</td><td>{{ $urun->model }}</td></tr>
                        @foreach($urun->urunKriterDegerleri as $deger)
                            <tr>
                                <td>{{ $deger->kriter->kriter_ad }}</td>
                                <td>{{ $deger->kriterDeger->deger }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="tab-pane fade show active" id="pills-reviews" role="tabpanel">
                
                <div class="row mb-5">
                    <div class="col-md-4 mb-4 mb-md-0">
                        <div class="rating-summary-box">
                            <div class="rating-big-score">{{ number_format($ortalamaPuan, 1) }}</div>
                            <div class="rating-stars-static">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= round($ortalamaPuan))
                                        <i class="fas fa-star"></i>
                                    @else
                                        <i class="far fa-star"></i>
                                    @endif
                                @endfor
                            </div>
                            <div class="text-muted small">{{ $yorumSayisi }} yorum</div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="h-100 d-flex flex-column justify-content-center">
                            @foreach([5, 4, 3, 2, 1] as $star)
                                @php
                                    $count = $yildizDagilimi[$star] ?? 0;
                                    $percent = $yorumSayisi > 0 ? ($count / $yorumSayisi) * 100 : 0;
                                @endphp
                                <div class="rating-bars-item">
                                    <div style="width: 60px;"><i class="fas fa-star text-warning me-1"></i> {{ $star }}</div>
                                    <div class="progress-custom">
                                        <div class="progress-bar-fill" style="width: {{ $percent }}%"></div>
                                    </div>
                                    <div style="width: 40px; text-align: right;">{{ $count }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-7">
                        <h4 class="mb-4 text-primary fw-bold">Müşteri Yorumları</h4>
                        <div class="reviews-list">
                            @forelse($urun->degerlendirmeler as $yorum)
                                <div class="review-item">
                                    <div class="review-header">
                                        <div class="review-user">
                                            <div class="review-avatar">{{ strtoupper(substr($yorum->user->name ?? 'M', 0, 1)) }}</div>
                                            {{ $yorum->user->name ?? 'Misafir' }}
                                        </div>
                                        <div class="review-date">{{ $yorum->created_at->format('d.m.Y') }}</div>
                                    </div>
                                    <div class="text-warning mb-2" style="font-size: 0.9rem;">
                                        @for($i=1; $i<=5; $i++)
                                            <i class="{{ $i <= $yorum->puan ? 'fas' : 'far' }} fa-star"></i>
                                        @endfor
                                    </div>
                                    <p class="review-text">{{ $yorum->yorum }}</p>

                                    @if($yorum->cevap)
                                        <div class="admin-reply bg-light p-3 rounded ms-4 mt-2 border-start border-primary border-3">
                                            <div class="d-flex align-items-center mb-1 text-primary fw-bold">
                                                <i class="fas fa-store me-2"></i> Mağaza Yanıtı
                                            </div>
                                            <p class="mb-0 text-muted small">{{ $yorum->cevap }}</p>
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <div class="alert alert-light border text-center py-4">
                                    <i class="far fa-comment-dots fa-2x text-muted mb-2"></i>
                                    <p class="mb-0 text-muted">Henüz bu ürün için yorum yapılmamış. İlk yorumu siz yapın!</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="col-lg-5 mt-4 mt-lg-0">
                        <div class="bg-light p-4 rounded-4 border">
                            <h4 class="mb-3 text-primary fw-bold">Değerlendirme Yap</h4>
                            
                            @auth
                                <form action="{{ route('urun.degerlendirme', $urun->id) }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Puanınız</label>
                                        <div class="rating-input-group">
                                            <input type="radio" id="star5" name="puan" value="5" required><label for="star5" title="5 Yıldız"><i class="fas fa-star"></i></label>
                                            <input type="radio" id="star4" name="puan" value="4"><label for="star4" title="4 Yıldız"><i class="fas fa-star"></i></label>
                                            <input type="radio" id="star3" name="puan" value="3"><label for="star3" title="3 Yıldız"><i class="fas fa-star"></i></label>
                                            <input type="radio" id="star2" name="puan" value="2"><label for="star2" title="2 Yıldız"><i class="fas fa-star"></i></label>
                                            <input type="radio" id="star1" name="puan" value="1"><label for="star1" title="1 Yıldız"><i class="fas fa-star"></i></label>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Yorumunuz</label>
                                        <textarea name="yorum" class="form-control" rows="4" placeholder="Ürün hakkındaki düşüncelerinizi yazın..." required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">Değerlendirmeyi Gönder</button>
                                </form>
                            @else
                                <div class="text-center py-4">
                                    <p class="mb-3 text-muted">Yorum yapabilmek için giriş yapmalısınız.</p>
                                    <a href="{{ route('login') }}" class="btn btn-primary px-4 rounded-pill">Giriş Yap</a>
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
            <h2 class="section-title">Bunları da Beğenebilirsiniz</h2>
        </div>
        <div class="row row-cols-2 row-cols-md-4 g-4">
            @foreach($benzerUrunler as $benzer)
            <div class="col">
                <a href="{{ route('urun.incele', $benzer->id) }}" class="text-decoration-none">
                    <div class="card h-100 border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
                        <img src="{{ $benzer->resim_url ? asset($benzer->resim_url) : 'https://via.placeholder.com/300x300' }}" class="card-img-top p-3" alt="{{ $benzer->urun_ad }}" style="height: 200px; object-fit: contain;">
                        <div class="card-body">
                            <h6 class="card-title text-dark fw-bold text-truncate">{{ $benzer->urun_ad }}</h6>
                            <div class="fw-bold text-primary">₺{{ number_format($benzer->fiyat, 2, ',', '.') }}</div>
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
        <div class="modal-content border-0 rounded-4 p-4 text-center">
            <div class="mb-3 text-warning"><i class="fas fa-lock fa-3x"></i></div>
            <h4>Giriş Yapmalısınız</h4>
            <p class="text-muted">Bu işlemi gerçekleştirmek için lütfen giriş yapın.</p>
            <div class="d-flex gap-2 justify-content-center mt-3">
                <a href="{{ route('login') }}" class="btn btn-primary px-4 rounded-pill">Giriş Yap</a>
                <button type="button" class="btn btn-light px-4 rounded-pill" data-bs-dismiss="modal">İptal</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Görsel Değiştirme
    function changeImage(el) {
        document.getElementById('mainImage').src = el.src;
        document.querySelectorAll('.thumbnail-item').forEach(item => item.classList.remove('active'));
        el.classList.add('active');
    }

    // Adet Güncelleme
    function updateQty(change) {
        const input = document.getElementById('quantity');
        let newVal = parseInt(input.value) + change;
        const max = parseInt(input.getAttribute('max'));
        if(newVal >= 1 && newVal <= max) {
            input.value = newVal;
        }
    }

    // Sepete Ekleme
    function handleAddToCart(e) {
        e.preventDefault();
        const btn = document.getElementById('addToCartBtn');
        const form = document.getElementById('addToCartForm');
        
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Ekleniyor...';

        const formData = new FormData(form);

        fetch('{{ route("sepet.ekle") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                // Sepet sayısını güncelle (Navbar'daki id="cartCount")
                const cartCount = document.getElementById('cartCount');
                if(cartCount) cartCount.innerText = data.sepet_count;
                
                btn.innerHTML = '<i class="fas fa-check"></i> Eklendi';
                btn.classList.replace('btn-secondary', 'btn-success'); // Stil değişimi
                setTimeout(() => {
                    btn.innerHTML = '<i class="fas fa-shopping-cart"></i> Sepete Ekle';
                    btn.classList.replace('btn-success', 'btn-secondary'); // Eski stile dönüş
                    btn.disabled = false;
                }, 2000);
            }
        })
        .catch(err => {
            console.error(err);
            btn.disabled = false;
            btn.innerText = 'Hata Oluştu';
        });
    }

    // Favori İşlemi
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
            } else {
                btn.classList.remove('active');
                icon.classList.replace('fas', 'far');
            }
        });
    }
</script>
@endsection