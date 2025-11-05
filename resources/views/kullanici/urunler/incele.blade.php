@extends('layouts.app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
:root {
    --product-bg: #ffffff;
    --product-border: #e2e8f0;
    --accent-light: rgba(49, 130, 206, 0.1);
    --success-light: rgba(16, 185, 129, 0.1);
    --warning-light: rgba(245, 158, 11, 0.1);
    --info-bg: #f8fafc;
}

/* Breadcrumb */
.product-breadcrumb {
    background: var(--bg-secondary);
    padding: 1rem 0;
    margin-bottom: 2rem;
    border-bottom: 1px solid var(--border-color);
}

.breadcrumb-modern {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin: 0;
    padding: 0;
    list-style: none;
    font-size: 0.9rem;
}

.breadcrumb-item a {
    color: var(--accent-color);
    text-decoration: none;
    transition: color 0.3s ease;
}

.breadcrumb-item a:hover {
    color: var(--accent-hover);
}

.breadcrumb-separator {
    color: var(--text-secondary);
}

/* Product Gallery */
.product-gallery {
    position: sticky;
    top: 100px;
}

.main-image-container {
    position: relative;
    background: var(--info-bg);
    border-radius: var(--radius-lg);
    overflow: hidden;
    margin-bottom: 1rem;
    height: 450px;
    border: 1px solid var(--product-border);
}

.main-image {
    width: 100%;
    height: 100%;
    object-fit: contain;
    padding: 2rem;
    transition: transform 0.3s ease;
}

.main-image:hover {
    transform: scale(1.05);
}

.image-badges {
    position: absolute;
    top: 1rem;
    left: 1rem;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    z-index: 2;
}

.image-badge {
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    backdrop-filter: blur(10px);
}

.badge-new { background: rgba(16, 185, 129, 0.9); color: white; }
.badge-discount { background: rgba(239, 68, 68, 0.9); color: white; }
.badge-stock { background: rgba(245, 158, 11, 0.9); color: white; }

.thumbnail-gallery {
    display: flex;
    gap: 0.75rem;
    overflow-x: auto;
    padding: 0.5rem 0;
}

.thumbnail {
    width: 80px;
    height: 80px;
    border-radius: var(--radius-md);
    border: 2px solid var(--product-border);
    object-fit: cover;
    cursor: pointer;
    transition: all 0.3s ease;
    flex-shrink: 0;
}

.thumbnail:hover,
.thumbnail.active {
    border-color: var(--accent-color);
    transform: scale(1.05);
}

/* Product Info */
.product-header {
    margin-bottom: 2rem;
}

.product-title {
    font-size: 2rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 1rem;
    line-height: 1.3;
}

.product-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 1.5rem;
    align-items: center;
    margin-bottom: 1.5rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid var(--product-border);
}

.product-rating {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.stars-container {
    display: flex;
    gap: 0.2rem;
}

.star {
    color: #fbbf24;
    font-size: 1rem;
}

.star.empty {
    color: #d1d5db;
}

.rating-count {
    color: var(--text-secondary);
    font-size: 0.9rem;
    margin-left: 0.5rem;
}

.product-brand {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--text-secondary);
    font-weight: 500;
}

.product-sku {
    color: var(--text-secondary);
    font-size: 0.9rem;
}

/* Price Section */
.price-section {
    background: var(--success-light);
    border: 1px solid rgba(16, 185, 129, 0.2);
    border-radius: var(--radius-lg);
    padding: 1.5rem;
    margin-bottom: 2rem;
}

.price-main {
    font-size: 2.5rem;
    font-weight: 800;
    color: var(--success-color);
    margin-bottom: 0.5rem;
}

.price-original {
    font-size: 1.2rem;
    color: var(--text-secondary);
    text-decoration: line-through;
    margin-right: 0.75rem;
}

.price-discount {
    background: var(--danger-color);
    color: white;
    padding: 0.3rem 0.75rem;
    border-radius: 15px;
    font-size: 0.85rem;
    font-weight: 600;
}

.price-installment {
    color: var(--accent-color);
    font-size: 0.95rem;
    margin-top: 0.75rem;
}

.price-kdv {
    font-size: 0.7rem;
    color: rgba(0, 0, 0, 0.4);
    font-weight: 400;
    margin-left: 0.25rem;
}

/* Stock Status */
.stock-status {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 1.5rem;
    padding: 1rem;
    border-radius: var(--radius-md);
    font-weight: 500;
}

.stock-available {
    background: var(--success-light);
    color: var(--success-color);
    border: 1px solid rgba(16, 185, 129, 0.2);
}

.stock-low {
    background: var(--warning-light);
    color: var(--warning-color);
    border: 1px solid rgba(245, 158, 11, 0.2);
}

.stock-out {
    background: rgba(239, 68, 68, 0.1);
    color: var(--danger-color);
    border: 1px solid rgba(239, 68, 68, 0.2);
}

/* Add to Cart Section */
.cart-section {
    background: var(--product-bg);
    border: 1px solid var(--product-border);
    border-radius: var(--radius-lg);
    padding: 1.5rem;
    margin-bottom: 2rem;
    position: sticky;
    top: 120px;
    box-shadow: var(--shadow-sm);
}

.quantity-container {
    margin-bottom: 1.5rem;
}

.quantity-label {
    font-weight: 600;
    margin-bottom: 0.75rem;
    color: var(--text-primary);
}

.quantity-control {
    display: flex;
    align-items: center;
    width: fit-content;
    border: 2px solid var(--product-border);
    border-radius: var(--radius-md);
    overflow: hidden;
}

.qty-btn {
    width: 45px;
    height: 45px;
    border: none;
    background: var(--bg-tertiary);
    color: var(--text-secondary);
    font-size: 1.2rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.qty-btn:hover {
    background: var(--accent-color);
    color: white;
}

.qty-input {
    width: 80px;
    height: 45px;
    border: none;
    text-align: center;
    font-size: 1rem;
    font-weight: 600;
    color: var(--text-primary);
}

.add-to-cart-btn {
    width: 100%;
    padding: 1rem 2rem;
    background: var(--accent-color);
    color: white;
    border: none;
    border-radius: var(--radius-md);
    font-size: 1.1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.add-to-cart-btn:hover {
    background: var(--accent-hover);
    transform: translateY(-2px);
}

.add-to-cart-btn:disabled {
    background: var(--text-secondary);
    cursor: not-allowed;
    transform: none;
}

.action-buttons {
    display: flex;
    gap: 0.75rem;
}

.action-btn {
    flex: 1;
    padding: 0.75rem 1rem;
    border: 2px solid var(--product-border);
    background: var(--product-bg);
    color: var(--text-secondary);
    border-radius: var(--radius-md);
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    text-align: center;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.action-btn:hover {
    border-color: var(--accent-color);
    color: var(--accent-color);
    text-decoration: none;
}

.action-btn.favorite-active {
    border-color: #ef4444;
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
}

.action-btn.favorite-loading {
    opacity: 0.7;
    pointer-events: none;
}

/* Product Details Tabs */
.product-tabs {
    margin-top: 3rem;
}

.nav-tabs-modern {
    border: none;
    display: flex;
    gap: 0;
    background: var(--bg-tertiary);
    border-radius: var(--radius-lg);
    padding: 0.25rem;
    margin-bottom: 2rem;
}

.nav-tab {
    flex: 1;
    padding: 1rem 1.5rem;
    background: transparent;
    border: none;
    color: var(--text-secondary);
    font-weight: 600;
    cursor: pointer;
    border-radius: var(--radius-md);
    transition: all 0.3s ease;
}

.nav-tab.active {
    background: var(--product-bg);
    color: var(--accent-color);
    box-shadow: var(--shadow-sm);
}

.tab-content {
    background: var(--product-bg);
    border: 1px solid var(--product-border);
    border-radius: var(--radius-lg);
    padding: 2rem;
}

.tab-pane {
    display: none;
}

.tab-pane.active {
    display: block;
}

/* Specifications Table */
.specs-table {
    width: 100%;
    border-collapse: collapse;
}

.specs-table tr {
    border-bottom: 1px solid var(--product-border);
}

.specs-table tr:last-child {
    border-bottom: none;
}

.specs-table td {
    padding: 1rem 0;
    vertical-align: top;
}

.spec-label {
    font-weight: 600;
    color: var(--text-primary);
    width: 200px;
    padding-right: 2rem;
}

.spec-value {
    color: var(--text-secondary);
}

/* Features Grid */
.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-top: 1.5rem;
}

.feature-card {
    background: var(--info-bg);
    border: 1px solid var(--product-border);
    border-radius: var(--radius-md);
    padding: 1.5rem;
    text-align: center;
    transition: all 0.3s ease;
}

.feature-card:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-md);
}

.feature-icon {
    font-size: 2.5rem;
    color: var(--accent-color);
    margin-bottom: 1rem;
}

.feature-title {
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

.feature-desc {
    color: var(--text-secondary);
    font-size: 0.9rem;
}

/* Related Products */
.related-products {
    margin-top: 4rem;
    padding-top: 3rem;
    border-top: 1px solid var(--product-border);
}

.section-title {
    font-size: 1.8rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 2rem;
    text-align: center;
}

.related-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
}

.related-card {
    background: var(--product-bg);
    border: 1px solid var(--product-border);
    border-radius: var(--radius-lg);
    overflow: hidden;
    transition: all 0.3s ease;
    text-decoration: none;
    color: inherit;
}

.related-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-md);
    text-decoration: none;
    color: inherit;
}

.related-image {
    height: 150px;
    background: var(--info-bg);
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.related-image img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    padding: 0.75rem;
}

.related-info {
    padding: 1rem;
}

.related-title {
    font-weight: 600;
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.related-price {
    font-weight: 700;
    color: var(--success-color);
}

/* Modern Toast Notifications */
.toast-container {
    position: fixed;
    top: 100px;
    right: 20px;
    z-index: 1050;
}

.toast-modern {
    background: white;
    border: none;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    min-width: 350px;
    transform: translateX(400px);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    overflow: hidden;
    margin-bottom: 1rem;
}

.toast-modern.show {
    transform: translateX(0);
}

.toast-success { border-left: 4px solid #10b981; }
.toast-error { border-left: 4px solid #ef4444; }
.toast-info { border-left: 4px solid #3b82f6; }
.toast-warning { border-left: 4px solid #f59e0b; }

.toast-header-modern {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border: none;
    padding: 1rem 1.5rem 0.5rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.toast-body-modern {
    padding: 0 1.5rem 1.5rem;
    color: #64748b;
    line-height: 1.6;
}

.toast-icon {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 0.75rem;
    font-size: 1.3rem;
}

.toast-icon-success {
    background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
    color: #059669;
}

.toast-icon-error {
    background: linear-gradient(135deg, #fef2f2 0%, #fecaca 100%);
    color: #dc2626;
}

.toast-icon-info {
    background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
    color: #2563eb;
}

.toast-icon-warning {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    color: #d97706;
}

.toast-title {
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 0;
}

.btn-close-modern {
    background: none;
    border: none;
    color: #94a3b8;
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 50%;
    transition: all 0.2s ease;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-close-modern:hover {
    background: rgba(0,0,0,0.1);
    color: #64748b;
}

/* Loading Animation */
.loading-spinner {
    display: inline-block;
    width: 20px;
    height: 20px;
    border: 2px solid rgba(255,255,255,.3);
    border-radius: 50%;
    border-top-color: #fff;
    animation: spin 0.8s ease-in-out infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Login Modal Styles */
.login-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
}

.login-modal-overlay.show {
    opacity: 1;
    visibility: visible;
}

.login-modal {
    background: white;
    border-radius: 16px;
    padding: 2rem;
    max-width: 400px;
    width: 90%;
    text-align: center;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
    transform: scale(0.9);
    transition: transform 0.3s ease;
}

.login-modal-overlay.show .login-modal {
    transform: scale(1);
}

.login-modal-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #ef4444, #f97316);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: white;
    margin: 0 auto 1.5rem;
}

.login-modal h3 {
    margin-bottom: 1rem;
    color: #1e293b;
}

.login-modal p {
    color: #64748b;
    margin-bottom: 2rem;
}

.login-modal-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
}

.btn-primary {
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    color: white;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
}

.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    color: white;
    text-decoration: none;
}

.btn-secondary {
    background: transparent;
    color: #64748b;
    border: 2px solid #e2e8f0;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-secondary:hover {
    border-color: #cbd5e1;
    color: #475569;
}

/* Responsive */
@media (max-width: 992px) {
    .product-gallery {
        position: static;
        margin-bottom: 2rem;
    }
    
    .cart-section {
        position: static;
    }
    
    .main-image-container {
        height: 300px;
    }
    
    .product-title {
        font-size: 1.6rem;
    }
    
    .price-main {
        font-size: 2rem;
    }
    
    .spec-label {
        width: auto;
        padding-right: 1rem;
    }
    
    .nav-tabs-modern {
        flex-direction: column;
    }
    
    .action-buttons {
        flex-direction: column;
    }
    
    .toast-modern {
        min-width: 300px;
    }
}

@media (max-width: 768px) {
    .product-meta {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.75rem;
    }
    
    .features-grid {
        grid-template-columns: 1fr;
    }
    
    .related-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .specs-table {
        font-size: 0.9rem;
    }
    
    .specs-table td {
        padding: 0.75rem 0;
    }
    
    .toast-container {
        left: 20px;
        right: 20px;
    }
    
    .toast-modern {
        min-width: auto;
    }
}
</style>

<!-- Toast Container -->
<div class="toast-container" id="toastContainer"></div>

<!-- Login Modal -->
<div class="login-modal-overlay" id="loginModal">
    <div class="login-modal">
        <div class="login-modal-icon">
            <i class="fas fa-heart"></i>
        </div>
        <h3>Giriş Yapmanız Gerekiyor</h3>
        <p>Ürünleri favorilerinize eklemek için hesabınıza giriş yapmanız gerekmektedir.</p>
        <div class="login-modal-buttons">
            <a href="{{ route('login') }}" class="btn-primary">Giriş Yap</a>
            <button class="btn-secondary" onclick="closeLoginModal()">İptal</button>
        </div>
    </div>
</div>

<!-- Breadcrumb -->
<div class="product-breadcrumb">
    <div class="container">
        <nav>
            <ol class="breadcrumb-modern">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Anasayfa</a></li>
                <li class="breadcrumb-item"><span class="breadcrumb-separator">/</span></li>
                <li class="breadcrumb-item"><a href="{{ route('urun.index') }}">Ürünler</a></li>
                <li class="breadcrumb-item"><span class="breadcrumb-separator">/</span></li>
                @if($urun->altKategori)
                    <li class="breadcrumb-item">
                        <a href="{{ route('urun.kategori', $urun->altKategori->kategori->id) }}">
                            {{ $urun->altKategori->kategori->kategori_ad }}
                        </a>
                    </li>
                    <li class="breadcrumb-item"><span class="breadcrumb-separator">/</span></li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('urun.altkategori', $urun->altKategori->id) }}">
                            {{ $urun->altKategori->alt_kategori_ad }}
                        </a>
                    </li>
                    <li class="breadcrumb-item"><span class="breadcrumb-separator">/</span></li>
                @endif
                <li class="breadcrumb-item current">{{ $urun->urun_ad }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container">
    <div class="row g-4">
        <!-- Product Gallery -->
        <div class="col-lg-6">
            <div class="product-gallery">
                <div class="main-image-container">
                    <div class="image-badges">
                        <span class="image-badge badge-new">Yeni</span>
                        @if($urun->stok > 0 && $urun->stok <= 5)
                            <span class="image-badge badge-stock">Son {{ $urun->stok }} Adet</span>
                        @endif
                    </div>
                    <img src="{{ $urun->resim_url ? asset($urun->resim_url) : 'https://via.placeholder.com/400x400?text=' . urlencode($urun->urun_ad) }}" 
                         alt="{{ $urun->urun_ad }}" 
                         class="main-image"
                         id="mainImage">
                </div>
                
                <div class="thumbnail-gallery">
                    <img src="{{ $urun->resim_url ? asset($urun->resim_url) : 'https://via.placeholder.com/80x80?text=1' }}" 
                         class="thumbnail active" 
                         onclick="changeMainImage(this.src)"
                         alt="Ürün resmi 1">
                    @for($i = 2; $i <= 4; $i++)
                        <img src="https://via.placeholder.com/80x80?text={{ $i }}" 
                             class="thumbnail" 
                             onclick="changeMainImage(this.src)"
                             alt="Ürün resmi {{ $i }}">
                    @endfor
                </div>
            </div>
        </div>

        <!-- Product Info -->
        <div class="col-lg-6">
            <!-- Product Header -->
            <div class="product-header">
                <h1 class="product-title">{{ $urun->urun_ad }}</h1>
                
                <div class="product-meta">
                    <div class="product-rating">
                        <div class="stars-container">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star star {{ $i <= 4 ? '' : 'empty' }}"></i>
                            @endfor
                        </div>
                        <span class="rating-count">({{ rand(15, 250) }} değerlendirme)</span>
                    </div>
                    
                    <div class="product-brand">
                        <i class="fas fa-building me-1"></i>
                        <strong>{{ $urun->marka }}</strong>
                    </div>
                    
                    @if($urun->barkod_no)
                        <div class="product-sku">
                            <i class="fas fa-barcode me-1"></i>
                            SKU: {{ $urun->barkod_no }}
                        </div>
                    @endif
                </div>

                @if($urun->aciklama)
                    <div class="product-description">
                        <p class="text-secondary">{{ $urun->aciklama }}</p>
                    </div>
                @endif
            </div>

          <!-- Price Section -->
@if($satisFiyati > 0)
    <div class="price-section">
        <div class="d-flex align-items-center gap-3 mb-2">
            @if($isBayi && $bayiFiyat && $standartFiyat > $bayiFiyat)
                <!-- Bayi fiyatı göster -->
                <div class="price-main">
                    ₺{{ number_format($bayiFiyat, 2, ',', '.') }}
                    <small class="price-kdv">KDV Dahil</small>
                </div>
                <div>
                    <span class="price-original">
                        ₺{{ number_format($standartFiyat, 2, ',', '.') }}
                    </span>
                    <span class="price-discount">
                        @php
                            $indirimYuzdesi = round((($standartFiyat - $bayiFiyat) / $standartFiyat) * 100);
                        @endphp
                        %{{ $indirimYuzdesi }} Bayi İndirimi
                    </span>
                </div>
            @elseif($kampanya)
                <!-- İndirimli fiyat göster -->
                <div class="price-main">
                    ₺{{ number_format($indirimliFiyat, 2, ',', '.') }}
                    <small class="price-kdv">KDV Dahil</small>
                </div>
                <div>
                    <span class="price-original">
                        ₺{{ number_format($satisFiyati, 2, ',', '.') }}
                    </span>
                    <span class="price-discount">%{{ number_format($kampanya->indirim_orani, 0) }} İndirim</span>
                </div>
            @else
                <!-- Normal fiyat göster -->
                <div class="price-main">
                    ₺{{ number_format($satisFiyati, 2, ',', '.') }}
                    <small class="price-kdv">KDV Dahil</small>
                </div>
            @endif
        </div>

        @php
            $finalPrice = $isBayi && $bayiFiyat ? $bayiFiyat : ($kampanya ? $indirimliFiyat : $satisFiyati);
        @endphp

        @if($finalPrice > 500)
            <div class="price-installment">
                <i class="fas fa-credit-card me-1"></i>
                {{ number_format($finalPrice / 12, 2, ',', '.') }} ₺'den başlayan 12 taksit imkânı
            </div>
        @endif
    </div>
@else
    <div class="price-section">
        <div class="price-main text-muted">
            Fiyat Yok
        </div>
        <p class="text-secondary mb-0 mt-2">Bu ürün için henüz fiyat tanımlanmamıştır.</p>
    </div>
@endif

            <!-- Stock Status -->
            <div class="stock-status {{ $urun->stok > 10 ? 'stock-available' : ($urun->stok > 0 ? 'stock-low' : 'stock-out') }}">
                <i class="fas fa-{{ $urun->stok > 0 ? 'check-circle' : 'times-circle' }} me-2"></i>
                @if($urun->stok > 10)
                    <span>Stokta mevcut ({{ $urun->stok }} adet)</span>
                @elseif($urun->stok > 0)
                    <span>Sınırlı stok! Son {{ $urun->stok }} adet</span>
                @else
                    <span>Stokta yok</span>
                @endif
            </div>

            <!-- Cart Section -->
            <div class="cart-section">
                <form id="addToCartForm" onsubmit="handleAddToCart(event)">
                    @csrf
                    <input type="hidden" name="id" value="{{ $urun->id }}">
                    <input type="hidden" name="urun_ad" value="{{ $urun->urun_ad }}">
                    <input type="hidden" name="fiyat" value="{{ $urun->fiyat }}">
                    <input type="hidden" name="resim_url" value="{{ $urun->resim_url }}">
                    <input type="hidden" name="marka" value="{{ $urun->marka }}">
                    <input type="hidden" name="model" value="{{ $urun->model }}">

                    <div class="quantity-container">
                        <div class="quantity-label">Adet:</div>
                        <div class="quantity-control">
                            <button type="button" class="qty-btn" onclick="changeQuantity(-1)">-</button>
                            <input type="number" class="qty-input" id="quantity" value="1" min="1" max="{{ $urun->stok ?: 1 }}" onchange="updateHiddenAdet()">
                            <button type="button" class="qty-btn" onclick="changeQuantity(1)">+</button>
                        </div>
                    </div>

                    <button type="submit" class="add-to-cart-btn" id="addToCartBtn"
                            {{ $urun->stok <= 0 ? 'disabled' : '' }}>
                        <i class="fas fa-shopping-cart me-2"></i>
                        {{ $urun->stok > 0 ? 'Sepete Ekle' : 'Stokta Yok' }}
                    </button>
                </form>

                <div class="action-buttons">
                    <button class="action-btn {{ $isFavorite ? 'favorite-active' : '' }}" 
                            id="favoriteBtn"
                            onclick="toggleFavorite({{ $urun->id }}, '{{ $urun->urun_ad }}')">
                        <i class="fas fa-heart me-1" id="favoriteIcon"></i>
                        <span id="favoriteText">{{ $isFavorite ? 'Favorilerde' : 'Favorilere Ekle' }}</span>
                    </button>
                    <button class="action-btn" onclick="addToCompare({{ $urun->id }})">
                        <i class="fas fa-balance-scale me-1"></i>Karşılaştır
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Details Tabs -->
    <div class="product-tabs">
        <div class="nav-tabs-modern">
            <button class="nav-tab active" onclick="showTab('description')">Açıklama</button>
            <button class="nav-tab" onclick="showTab('specifications')">Teknik Özellikler</button>
            <button class="nav-tab" onclick="showTab('features')">Özellikler</button>
            <button class="nav-tab" onclick="showTab('reviews')">Değerlendirmeler</button>
        </div>

        <!-- Description Tab -->
        <div class="tab-content">
            <div class="tab-pane active" id="description">
                <h3 class="mb-3">Ürün Açıklaması</h3>
                <p class="text-secondary mb-4">
                    {{ $urun->aciklama ?: 'Bu ürün, yüksek kalite standartları ile üretilmiş olup, modern teknoloji ve kullanıcı dostu tasarım anlayışı ile geliştirilmiştir. Uzun ömürlü kullanım ve mükemmel performans için ideal bir seçimdir.' }}
                </p>
                
                <div class="features-grid">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div class="feature-title">Kalite Garantisi</div>
                        <div class="feature-desc">2 yıl resmi garanti</div>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-shipping-fast"></i>
                        </div>
                        <div class="feature-title">Hızlı Kargo</div>
                        <div class="feature-desc">1-2 iş gününde teslimat</div>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-undo-alt"></i>
                        </div>
                        <div class="feature-title">Kolay İade</div> 
                        <div class="feature-desc">14 gün ücretsiz iade</div>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-headset"></i>
                        </div>
                        <div class="feature-title">7/24 Destek</div>
                        <div class="feature-desc">Teknik destek hizmeti</div>
                    </div>
                </div>
            </div>

            <!-- Specifications Tab -->
            <div class="tab-pane" id="specifications">
                <h3 class="mb-4">Teknik Özellikler</h3>
                <table class="specs-table">
                    <tr>
                        <td class="spec-label">Marka:</td>
                        <td class="spec-value">{{ $urun->marka }}</td>
                    </tr>
                    <tr>
                        <td class="spec-label">Model:</td>
                        <td class="spec-value">{{ $urun->model }}</td>
                    </tr>
                    @if($urun->barkod_no)
                    <tr>
                        <td class="spec-label">Barkod No:</td>
                        <td class="spec-value">{{ $urun->barkod_no }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td class="spec-label">Stok Durumu:</td>
                        <td class="spec-value">{{ $urun->stok > 0 ? $urun->stok . ' adet mevcut' : 'Stokta yok' }}</td>
                    </tr>
                    @foreach($urun->urunKriterDegerleri as $kriterDeger)
                    <tr>
                        <td class="spec-label">{{ $kriterDeger->kriter->kriter_ad }}:</td>
                        <td class="spec-value">{{ $kriterDeger->kriterDeger->deger }}</td>
                    </tr>
                    @endforeach
                </table>
            </div>

            <!-- Features Tab -->
            <div class="tab-pane" id="features">
                <h3 class="mb-4">Ürün Özellikleri</h3>

                @if(!empty($urun->urunKriterDegerleri) && $urun->urunKriterDegerleri->count() > 0)
                    <div class="row g-4">
                        @foreach($urun->urunKriterDegerleri->groupBy('kriter.kriter_ad') as $kriterAd => $degerler)
                            <div class="col-md-6">
                                <div class="feature-card">
                                    <div class="feature-icon">
                                        <i class="fas fa-circle"></i>
                                    </div>
                                    <div class="feature-title">{{ $kriterAd }}</div>
                                    <div class="feature-desc">
                                        @foreach($degerler as $deger)
                                            <span class="badge bg-secondary me-1">
                                                {{ $deger->kriterDeger->deger ?? '-' }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-info-circle text-secondary mb-3" style="font-size: 3rem;"></i>
                        <p class="text-secondary">Bu ürün için detaylı özellik bilgileri bulunmuyor.</p>
                    </div>
                @endif
            </div>

            <!-- Reviews Tab -->
            <div class="tab-pane" id="reviews">
                <h3 class="mb-4">Müşteri Değerlendirmeleri</h3>
                
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="text-center p-4 bg-light rounded">
                            <div class="display-4 fw-bold text-warning">4.2</div>
                            <div class="stars-container justify-content-center mb-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star star {{ $i <= 4 ? '' : 'empty' }}"></i>
                                @endfor
                            </div>
                            <div class="text-secondary">{{ rand(50, 200) }} değerlendirme</div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="rating-breakdown">
                            @for($i = 5; $i >= 1; $i--)
                                <div class="d-flex align-items-center mb-2">
                                    <span class="me-2">{{ $i }} yıldız</span>
                                    <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                        <div class="progress-bar bg-warning" style="width: {{ rand(10, 80) }}%"></div>
                                    </div>
                                    <span class="text-secondary small">{{ rand(5, 50) }}</span>
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <button class="btn btn-outline-primary">Tüm Değerlendirmeleri Görüntüle</button>
                </div>
            </div>
        </div>
    </div>

<!-- Related Products -->
@if(isset($benzerUrunler) && $benzerUrunler->count() > 0)
    <div class="related-products">
        <h2 class="section-title">Benzer Ürünler</h2>
        <div class="related-grid">
            @foreach($benzerUrunler as $benzer)
                @php
                    $user = auth()->user();
                    
                    // Kullanıcıya göre fiyat al
                    $benzerSatisFiyati = $benzer->getFiyatForUser($user);
                    $benzerStandartFiyat = $benzer->getStandartFiyat();
                    
                    // Bayi kontrolü
                    $benzerIsBayi = $user && $user->isBayi();
                    $benzerBayiFiyat = $benzerIsBayi ? $benzer->getBayiFiyat() : null;

                    // Kampanya kontrolü
                    $benzerKampanya = DB::table('kampanya_indirim')
                        ->where('urun_id', $benzer->id)
                        ->where('aktif', 1)
                        ->where('baslangic_tarihi', '<=', now())
                        ->where('bitis_tarihi', '>=', now())
                        ->first();
                    
                    $benzerIndirimliFiyat = $benzerSatisFiyati;
                    if($benzerKampanya && $benzerSatisFiyati > 0) {
                        $benzerIndirimliFiyat = $benzerSatisFiyati * (1 - $benzerKampanya->indirim_orani / 100);
                    }
                @endphp

                <a href="{{ route('urun.incele', $benzer->id) }}" class="related-card">
                    <div class="related-image">
                        <img src="{{ $benzer->resim_url ? asset($benzer->resim_url) : 'https://via.placeholder.com/150x150?text=' . urlencode($benzer->urun_ad) }}" 
                             alt="{{ $benzer->urun_ad }}">
                    </div>
                    <div class="related-info">
                        <div class="related-title">{{ $benzer->urun_ad }}</div>
                        
                        @if($benzerSatisFiyati > 0)
                            @if($benzerIsBayi && $benzerBayiFiyat && $benzerStandartFiyat > $benzerBayiFiyat)
                                <div class="related-price">
                                    ₺{{ number_format($benzerBayiFiyat, 2, ',', '.') }}
                                </div>
                                <small class="text-muted text-decoration-line-through">
                                    ₺{{ number_format($benzerStandartFiyat, 2, ',', '.') }}
                                </small>
                            @elseif($benzerKampanya)
                                <div class="related-price">
                                    ₺{{ number_format($benzerIndirimliFiyat, 2, ',', '.') }}
                                </div>
                                <small class="text-muted text-decoration-line-through">
                                    ₺{{ number_format($benzerSatisFiyati, 2, ',', '.') }}
                                </small>
                            @else
                                <div class="related-price">
                                    ₺{{ number_format($benzerSatisFiyati, 2, ',', '.') }}
                                </div>
                            @endif
                        @else
                            <div class="text-muted small">Fiyat Yok</div>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
    </div>
@endif

<script>
// Global variables
let currentQuantity = 1;
let maxQuantity = {{ $urun->stok ?: 1 }};
let isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};
let currentUser = @json(auth()->user());

// CSRF token
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

// DOM Ready
document.addEventListener('DOMContentLoaded', function() {
    initializeProduct();
});

// Initialize product page
function initializeProduct() {
    console.log('Dinamik favori sistemi yüklendi');
    updateFavoriteButton({{ $isFavorite ? 'true' : 'false' }});
}

// Show login modal
function showLoginModal() {
    const modal = document.getElementById('loginModal');
    modal.classList.add('show');
}

// Close login modal
function closeLoginModal() {
    const modal = document.getElementById('loginModal');
    modal.classList.remove('show');
}

// Change main image
function changeMainImage(src) {
    document.getElementById('mainImage').src = src;
    
    // Update active thumbnail
    document.querySelectorAll('.thumbnail').forEach(thumb => {
        thumb.classList.remove('active');
    });
    event.target.classList.add('active');
}

// Change quantity
function changeQuantity(delta) {
    const input = document.getElementById('quantity');
    const newValue = parseInt(input.value || 1) + delta;
    
    if (newValue >= 1 && newValue <= maxQuantity) {
        input.value = newValue;
        currentQuantity = newValue;
        updateHiddenAdet();
    } else if (newValue > maxQuantity) {
        showToast('Maksimum ' + maxQuantity + ' adet seçebilirsiniz!', 'warning');
    }
}

// Update hidden adet input
function updateHiddenAdet() {
    const quantity = document.getElementById('quantity').value;
    currentQuantity = parseInt(quantity);
}

// Show tab
function showTab(tabName) {
    // Hide all tabs
    document.querySelectorAll('.tab-pane').forEach(pane => {
        pane.classList.remove('active');
    });
    
    // Remove active class from all nav tabs
    document.querySelectorAll('.nav-tab').forEach(tab => {
        tab.classList.remove('active');
    });
    
    // Show selected tab
    document.getElementById(tabName).classList.add('active');
    event.target.classList.add('active');
}

// Toggle favorite - Ana fonksiyon
function toggleFavorite(urunId, urunAd) {
    // Giriş kontrolü
    if (!isAuthenticated) {
        showLoginModal();
        return;
    }
    
    const button = document.getElementById('favoriteBtn');
    const icon = document.getElementById('favoriteIcon');
    const text = document.getElementById('favoriteText');
    
    // Loading durumu
    button.classList.add('favorite-loading');
    text.textContent = 'İşleniyor...';
    
    // AJAX isteği - Doğru route kullan
    fetch('{{ route("favori.toggle") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            urun_id: urunId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const isFavorite = data.action === 'added';
            updateFavoriteButton(isFavorite);
            showAdvancedToast({
                title: isFavorite ? 'Favorilere Eklendi!' : 'Favorilerden Çıkarıldı!',
                message: data.message,
                type: 'success',
                duration: 3000
            });
            
            // Global event dispatch
            window.dispatchEvent(new CustomEvent('favoriteToggled', {
                detail: { urunId, isFavorite, action: data.action }
            }));
            
        } else {
            showAdvancedToast({
                title: 'Hata!',
                message: data.message || 'Bir hata oluştu',
                type: 'error'
            });
        }
    })
    .catch(error => {
        console.error('Favori hatası:', error);
        showAdvancedToast({
            title: 'Hata!',
            message: 'İşlem sırasında bir hata oluştu',
            type: 'error'
        });
    })
    .finally(() => {
        button.classList.remove('favorite-loading');
    });
}

// Update favorite button appearance
function updateFavoriteButton(isFavorite) {
    const button = document.getElementById('favoriteBtn');
    const icon = document.getElementById('favoriteIcon');
    const text = document.getElementById('favoriteText');
    
    if (isFavorite) {
        button.classList.add('favorite-active');
        text.textContent = 'Favorilerde';
    } else {
        button.classList.remove('favorite-active');
        text.textContent = 'Favorilere Ekle';
    }
}

// Sepete ekleme işlemi
function handleAddToCart(event) {
    event.preventDefault();
    
    const form = document.getElementById('addToCartForm');
    const button = document.getElementById('addToCartBtn');
    const quantity = parseInt(document.getElementById('quantity').value);
    
    if (quantity < 1 || quantity > maxQuantity) {
        showToast('Geçersiz adet miktarı!', 'error');
        return;
    }
    
    // Loading durumu
    button.innerHTML = '<span class="loading-spinner me-2"></span>Sepete Ekleniyor...';
    button.disabled = true;
    
    const formData = new FormData(form);
    formData.set('adet', quantity);
    
    fetch('{{ route("sepet.ekle") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            handleCartSuccess(data, quantity);
        } else {
            showToast(data.message || 'Ürün sepete eklenemedi!', 'error');
        }
    })
    .catch(error => {
        console.error('Sepet hatası:', error);
        showToast('Bir hata oluştu! Lütfen tekrar deneyin.', 'error');
    })
    .finally(() => {
        resetButton(button);
    });
}

// Sepete ekleme başarılı
function handleCartSuccess(data, quantity) {
    updateCartCount(data.sepet_count || quantity);
    
    showAdvancedToast({
        title: 'Başarılı!',
        message: `${quantity} adet ürün sepete eklendi`,
        type: 'success',
        duration: 4000
    });
    
    // Miktar sıfırla
    document.getElementById('quantity').value = 1;
    currentQuantity = 1;
}

// Advanced toast notification
function showAdvancedToast({title, message, type = 'info', duration = 3000}) {
    const container = document.getElementById('toastContainer');
    const toastId = 'toast_' + Date.now();
    
    const typeConfig = {
        success: { icon: 'fas fa-check-circle', title: title || 'Başarılı!' },
        error: { icon: 'fas fa-exclamation-circle', title: title || 'Hata!' },
        info: { icon: 'fas fa-info-circle', title: title || 'Bilgi' },
        warning: { icon: 'fas fa-exclamation-triangle', title: title || 'Uyarı!' }
    };
    
    const config = typeConfig[type] || typeConfig.info;
    
    const toast = document.createElement('div');
    toast.id = toastId;
    toast.className = `toast-modern toast-${type}`;
    toast.innerHTML = `
        <div class="toast-header-modern">
            <div class="d-flex align-items-center">
                <div class="toast-icon toast-icon-${type}">
                    <i class="${config.icon}"></i>
                </div>
                <strong class="toast-title">${config.title}</strong>
            </div>
            <button type="button" class="btn-close-modern" onclick="closeToast('${toastId}')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="toast-body-modern">
            ${message}
        </div>
    `;
    
    container.appendChild(toast);
    
    setTimeout(() => toast.classList.add('show'), 100);
    setTimeout(() => closeToast(toastId), duration);
}

// Close toast
function closeToast(toastId) {
    const toast = document.getElementById(toastId);
    if (toast) {
        toast.classList.remove('show');
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 400);
    }
}

// Update cart count
function updateCartCount(count) {
    const cartBadges = document.querySelectorAll('.sepet-count, #sepet-count, [data-cart-count]');
    
    cartBadges.forEach(badge => {
        if (badge) {
            badge.textContent = count;
            badge.classList.add('bounce');
            setTimeout(() => badge.classList.remove('bounce'), 600);
        }
    });
}

// Reset button
function resetButton(button) {
    setTimeout(() => {
        button.innerHTML = '<i class="fas fa-shopping-cart me-2"></i>Sepete Ekle';
        button.disabled = false;
    }, 300);
}

// Add to compare
function addToCompare(urunId) {
    let compareList = JSON.parse(localStorage.getItem('compareList') || '[]');
    
    if (compareList.includes(urunId)) {
        showToast('Bu ürün zaten karşılaştırma listesinde!', 'info');
        return;
    }
    
    if (compareList.length >= 4) {
        showToast('En fazla 4 ürün karşılaştırabilirsiniz!', 'error');
        return;
    }
    
    compareList.push(urunId);
    localStorage.setItem('compareList', JSON.stringify(compareList));
    showToast('Ürün karşılaştırma listesine eklendi!', 'success');
}

// Eski showToast fonksiyonu (geriye uyumluluk)
function showToast(message, type = 'info') {
    showAdvancedToast({
        message: message,
        type: type,
        duration: 3000
    });
}

// Global favorite event listener
window.addEventListener('favoriteToggled', function(e) {
    const { urunId, isFavorite } = e.detail;
    
    // Bu sayfa aynı ürünse button güncelle
    if (urunId == {{ $urun->id }}) {
        updateFavoriteButton(isFavorite);
    }
});

// ESC tuşu ile modal kapatma
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeLoginModal();
    }
});

// Modal dış alanına tıklayınca kapatma
document.getElementById('loginModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeLoginModal();
    }
});
</script>
@endsection