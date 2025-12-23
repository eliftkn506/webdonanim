@extends('layouts.app')
@section('title', 'Ürünler Filtrele')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
/* Stil kodları */
:root {
    --primary: #2563eb;
    --primary-dark: #1e40af;
    --primary-light: #dbeafe;
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
    background: var(--light);
    color: var(--dark);
}

.page-header {
    background: white;
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
.breadcrumb a:hover {
    text-decoration: underline;
}

.main-container {
    display: grid;
    grid-template-columns: 300px 1fr;
    gap: 2rem;
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 1rem;
}

.filter-sidebar {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    height: fit-content;
    position: sticky;
    top: 20px;
    border: 1px solid var(--border);
    box-shadow: 0 4px 12px rgba(0,0,0,0.03);
    max-height: calc(100vh - 100px);
    overflow-y: auto;
}

.filter-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid var(--border);
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
.filter-clear:hover {
    color: var(--primary-dark);
}

.filter-section {
    margin-bottom: 1.5rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid var(--border);
}
.filter-section:last-child {
    border: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.filter-section-title {
    font-weight: 600;
    margin-bottom: 1rem;
    font-size: 0.9375rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.filter-select,
.price-input {
    width: 100%;
    padding: 0.625rem 0.75rem;
    border: 1px solid var(--border);
    border-radius: 8px;
    font-size: 0.875rem;
    background-color: var(--light);
    transition: all 0.2s ease-in-out;
}

.filter-select {
    background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpath d='M6 9l4 4 4-4'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 0.75rem center;
    background-size: 1em;
    padding-right: 2.5rem;
}

.filter-select:focus,
.price-input:focus {
    outline: none;
    border-color: var(--primary);
    background-color: white;
    box-shadow: 0 0 0 3px var(--primary-light);
}

.price-inputs {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.75rem;
}

.checkbox-list {
    max-height: 250px;
    overflow-y: auto;
    padding-right: 0.5rem;
}

.checkbox-list::-webkit-scrollbar {
    width: 6px;
}

.checkbox-list::-webkit-scrollbar-track {
    background: var(--light);
    border-radius: 3px;
}

.checkbox-list::-webkit-scrollbar-thumb {
    background: var(--border);
    border-radius: 3px;
}

.checkbox-list::-webkit-scrollbar-thumb:hover {
    background: var(--secondary);
}

.checkbox-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
}
.checkbox-item label {
    display: flex;
    align-items: center;
    cursor: pointer;
    color: var(--dark);
    font-weight: 500;
    flex: 1;
}
.checkbox-item input[type="checkbox"],
.checkbox-item input[type="radio"] {
    margin-right: 0.5rem;
    accent-color: var(--primary);
    cursor: pointer;
    width: 1rem;
    height: 1rem;
}
.checkbox-count {
    color: var(--secondary);
    font-size: 0.75rem;
    background: var(--light);
    padding: 0.2rem 0.5rem;
    border-radius: 10px;
    font-weight: 600;
}

.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.8);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s;
}

.loading-overlay.show {
    opacity: 1;
    visibility: visible;
}

.loading-spinner {
    width: 50px;
    height: 50px;
    border: 4px solid var(--border);
    border-top-color: var(--primary);
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

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
    box-shadow: 0 4px 12px rgba(0,0,0,0.03);
}

.products-count {
    font-weight: 500;
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
    background-color: var(--light);
    background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpath d='M6 9l4 4 4-4'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 0.75rem center;
    background-size: 1em;
    padding-right: 2.5rem;
}
.sort-select:focus {
    outline: none;
    border-color: var(--primary);
}

.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(290px, 1fr));
    gap: 1.5rem;
}

.product-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid var(--border);
    transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    display: flex;
    flex-direction: column;
    height: 100%;
    box-shadow: 0 4px 12px rgba(0,0,0,0.04);
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
}

.product-image-wrapper {
    position: relative;
    background: #fff;
    padding: 1rem;
    height: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-bottom: 1px solid var(--border);
}

.product-image {
    width: 100%;
    height: 100%;
    object-fit: contain;
    transition: transform 0.3s;
}

.product-card:hover .product-image {
    transform: scale(1.03);
}

.product-actions {
    position: absolute;
    top: 0.75rem;
    right: 0.75rem;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    opacity: 0;
    transition: all 0.3s;
    transform: translateX(10px);
}

.product-card:hover .product-actions {
    opacity: 1;
    transform: translateX(0);
}

.action-btn {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    border: 1px solid var(--border);
    background: white;
    color: var(--secondary);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
    box-shadow: 0 2px 8px rgba(0,0,0,0.07);
}

.action-btn:hover {
    background: var(--primary);
    color: white;
    transform: scale(1.1);
    border-color: var(--primary);
}

.action-btn.active {
    color: #ef4444;
    border-color: #ef4444;
}

.product-info {
    padding: 1.25rem;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.product-brand {
    font-size: 0.8rem;
    color: var(--primary);
    font-weight: 600;
    margin-bottom: 0.5rem;
    display: inline-block;
}

.product-title {
    font-size: 1.05rem;
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
    border-top: 1px solid var(--border);
}

.price-wrapper {
    margin-bottom: 1rem;
    min-height: 60px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.no-price-text {
    font-size: 1rem;
    font-weight: 500;
    color: var(--secondary);
}

.current-price {
    font-size: 1.6rem;
    font-weight: 700;
    color: var(--dark);
    display: block;
}

.current-price.discounted {
    color: var(--primary);
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
    background-color: var(--danger);
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 700;
}
.discount-badge.bayi {
    background-color: var(--warning);
    color: var(--dark);
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
    height: 40px;
    border: none;
    background: var(--light);
    cursor: pointer;
    font-weight: 600;
    transition: all 0.2s;
    color: var(--secondary);
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
    color: var(--dark);
}

.add-cart-btn {
    flex: 1;
    height: 40px;
    padding: 0 1rem;
    background: var(--primary);
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
    background: var(--primary-dark);
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
}

.add-cart-btn:disabled {
    background: #10b981;
    color: white;
    cursor: not-allowed;
}

.btn-incele {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 40px;
    width: 100%;
    text-align: center;
    padding: 0 1rem;
    border: 1px solid var(--primary);
    color: var(--primary);
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s;
    font-size: 0.875rem;
}
.btn-incele:hover {
    background: var(--primary);
    color: white;
}

.pagination-wrapper {
    margin-top: 2.5rem;
    display: flex;
    justify-content: center;
}
</style>

<div class="loading-overlay" id="loadingOverlay">
    <div class="loading-spinner"></div>
</div>

<div class="page-header">
    <div class="container">
        <h1 class="page-title">
            @if(isset($searchQuery))
                "{{ $searchQuery }}" Arama Sonuçları
            @else
                Ürünlerimiz
            @endif
        </h1>
        <div class="breadcrumb">
            <a href="{{ route('home') }}">Anasayfa</a>
            <span>/</span>
            @if(isset($kategori) && !isset($altKategori))
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
    <aside class="filter-sidebar">
        <div class="filter-header">
            <h3 class="filter-title">🔍 Filtreler</h3>
            <a href="{{ route('urun.index') . (request('q') ? '?q=' . request('q') : '') }}" class="filter-clear">Temizle</a>
        </div>

        <form method="GET" id="filterForm">
            @if(request('q'))
                <input type="hidden" name="q" value="{{ request('q') }}">
            @endif
            <input type="hidden" name="sort" value="{{ request('sort') }}" id="sort_field_hidden">
            
            <div class="filter-section">
                <div class="filter-section-title">📁 Kategori</div>
                <select name="kategori_id" class="filter-select auto-submit-select" id="kategori_select">
                    <option value="">Tümü</option>
                    @foreach($kategoriler ?? [] as $kat)
                        <option value="{{ $kat->id }}" 
                                {{ (request('kategori_id') == $kat->id) || (isset($kategori) && $kategori->id == $kat->id) ? 'selected' : '' }}>
                            {{ $kat->kategori_ad }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-section" id="alt_kategori_section" style="{{ (!empty($altKategoriler) && $altKategoriler->count() > 0) || request('kategori_id') ? 'display: block;' : 'display: none;' }}">
                <div class="filter-section-title">📂 Alt Kategori</div>
                <select name="alt_kategori_id" class="filter-select auto-submit-select" id="alt_kategori_select">
                    <option value="">Tümü</option>
                    @foreach($altKategoriler ?? [] as $alt)
                        <option value="{{ $alt->id }}" 
                                {{ (request('alt_kategori_id') == $alt->id) || (isset($altKategori) && $altKategori->id == $alt->id) ? 'selected' : '' }}>
                            {{ $alt->alt_kategori_ad }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div id="marka_section" class="filter-section" style="{{ !empty($markalar) && count($markalar) > 0 ? 'display: block;' : 'display: none;' }}">
                <div class="filter-section-title">🏷️ Marka</div>
                <div class="checkbox-list" id="marka_list">
                    @if(!empty($markalar))
                        @foreach($markalar as $marka)
                            @php
                                $checked = is_array(request('marka')) ? in_array($marka, request('marka')) : ($marka == request('marka'));
                            @endphp
                            <div class="checkbox-item">
                                <label>
                                    <input type="checkbox" name="marka[]" value="{{ $marka }}" {{ $checked ? 'checked' : '' }} class="auto-submit-checkbox">
                                    {{ $marka }}
                                </label>
                                <span class="checkbox-count">({{ $markaCounts[$marka] ?? 0 }})</span>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <div id="model_section" class="filter-section" style="{{ !empty($modeller) && count($modeller) > 0 ? 'display: block;' : 'display: none;' }}">
                <div class="filter-section-title">🔧 Model</div>
                <div class="checkbox-list" id="model_list">
                    @if(!empty($modeller))
                        @foreach($modeller as $model)
                            @php
                                $checked = is_array(request('model')) ? in_array($model, request('model')) : ($model == request('model'));
                            @endphp
                            <div class="checkbox-item">
                                <label>
                                    <input type="checkbox" name="model[]" value="{{ $model }}" {{ $checked ? 'checked' : '' }} class="auto-submit-checkbox">
                                    {{ $model }}
                                </label>
                                <span class="checkbox-count">({{ $modelCounts[$model] ?? 0 }})</span>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <div id="dynamic_kriter_filters">
                @if(!empty($kriterler) && $kriterler->count() > 0)
                    @foreach($kriterler as $kriter)
                        <div class="filter-section">
                            <div class="filter-section-title">⚙️ {{ $kriter->kriter_ad }}</div>
                            <div class="checkbox-list">
                                @foreach($kriter->degerler as $deger)
                                    @if($deger->urun_count > 0)
                                        @php
                                            $checked = request()->has("kriterler.{$kriter->id}") && 
                                                        in_array($deger->id, request("kriterler.{$kriter->id}"));
                                        @endphp
                                        <div class="checkbox-item">
                                            <label>
                                                <input type="checkbox" name="kriterler[{{ $kriter->id }}][]" value="{{ $deger->id }}" {{ $checked ? 'checked' : '' }} class="auto-submit-checkbox">
                                                {{ $deger->deger }}
                                            </label>
                                            <span class="checkbox-count">({{ $deger->urun_count }})</span>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            <div class="filter-section">
                <div class="filter-section-title">💰 Fiyat Aralığı</div>
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

    <section class="products-section">
        <div class="products-header">
            <div class="products-count">
                <strong>{{ $urunler->total() }}</strong> ürün bulundu
            </div>
            <div class="products-sort">
                <form method="GET" id="sortForm" class="products-sort">
                    @foreach(request()->except(['sort', 'page']) as $key => $value)
                        @if(is_array($value))
                            @foreach($value as $v) <input type="hidden" name="{{ $key }}[]" value="{{ $v }}"> @endforeach
                        @else
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endif
                    @endforeach
                    <select name="sort" class="sort-select" onchange="this.form.submit()">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>En Yeni</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Ucuzdan Pahalıya</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Pahalıdan Ucuza</option>
                    </select>
                </form>
            </div>
        </div>

        <div class="products-grid">
            @forelse($urunler as $urun)
                @php
                    $user = auth()->user();
                    $satisFiyati = $urun->getFiyatForUser($user) ?? 0;
                    $standartFiyat = $urun->getStandartFiyat() ?? 0;
                    $isBayi = $user && $user->isBayi();
                    $bayiFiyat = $isBayi ? $urun->getBayiFiyat() : null;

                    $kampanya = DB::table('kampanya_indirim')->where('urun_id', $urun->id)->where('aktif', 1)->where('baslangic_tarihi', '<=', now())->where('bitis_tarihi', '>=', now())->first();
                    $indirimliFiyat = ($kampanya && $satisFiyati > 0) ? $satisFiyati * (1 - $kampanya->indirim_orani / 100) : $satisFiyati;
                    
                    // FAVORİ KONTROLÜ DÜZELTİLDİ (Hatanın kaynağı burasıydı)
                    $isFav = false;
                    if($user) {
                        $isFav = DB::table('favoriUrunler')->where('user_id', $user->id)->where('urun_id', $urun->id)->exists();
                    }
                @endphp

                <div class="product-card">
                    <div class="product-image-wrapper">
                        <img src="{{ $urun->resim_url ? asset($urun->resim_url) : 'https://via.placeholder.com/300x300' }}" class="product-image">
                        <div class="product-actions">
                            <button class="action-btn {{ $isFav ? 'active' : '' }}" onclick="toggleFavorite({{ $urun->id }}, this)">
                                <i class="{{ $isFav ? 'fas' : 'far' }} fa-heart"></i>
                            </button>
                            <a href="{{ route('urun.incele', $urun->id) }}" class="action-btn"><i class="fas fa-search-plus"></i></a>
                        </div>
                    </div>

                    <div class="product-info">
                        @if($urun->marka) <div class="product-brand">{{ $urun->marka }}</div> @endif
                        <h3 class="product-title"><a href="{{ route('urun.incele', $urun->id) }}">{{ $urun->urun_ad }}</a></h3>

                        <div class="price-section">
                            <div class="price-wrapper">
                                @if($satisFiyati > 0)
                                    @if($isBayi && $bayiFiyat && $standartFiyat > $bayiFiyat)
                                        <span class="current-price discounted">₺{{ number_format($bayiFiyat, 2, ',', '.') }}</span>
                                        <div class="price-discount"><span class="original-price">₺{{ number_format($standartFiyat, 2, ',', '.') }}</span> <span class="discount-badge bayi">Bayi</span></div>
                                    @elseif($kampanya)
                                        <span class="current-price discounted">₺{{ number_format($indirimliFiyat, 2, ',', '.') }}</span>
                                        <div class="price-discount"><span class="original-price">₺{{ number_format($satisFiyati, 2, ',', '.') }}</span> <span class="discount-badge">-%{{ $kampanya->indirim_orani }}</span></div>
                                    @else
                                        <span class="current-price">₺{{ number_format($satisFiyati, 2, ',', '.') }}</span>
                                    @endif
                                @else
                                    <span class="no-price-text">Fiyat İçin Arayınız</span>
                                @endif
                            </div>

                            @if($satisFiyati > 0 && $urun->stok > 0)
                                <div class="cart-section">
                                    <div class="qty-selector">
                                        <button class="qty-btn" onclick="decreaseQty({{ $urun->id }})">−</button>
                                        <input type="number" class="qty-input" id="qty_{{ $urun->id }}" value="1" min="1" max="{{ $urun->stok }}" readonly>
                                        <button class="qty-btn" onclick="increaseQty({{ $urun->id }})">+</button>
                                    </div>
                                    <button class="add-cart-btn" onclick="addToCart({{ $urun->id }}, this)">
                                        <i class="fas fa-shopping-cart"></i> EKLE
                                    </button>
                                </div>
                            @else
                                <a href="{{ route('urun.incele', $urun->id) }}" class="btn-incele">İncele</a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5 w-100"><h3>Ürün Bulunamadı</h3></div>
            @endforelse
        </div>

        <div class="pagination-wrapper">
            {{ $urunler->appends(request()->all())->links('pagination::bootstrap-4') }}
        </div>
    </section>
</div>

<div class="modal fade" id="loginModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 p-5 text-center shadow-lg">
            <div class="mb-4 text-warning"><i class="fas fa-user-lock fa-4x"></i></div>
            <h3 class="fw-bold">Giriş Yapmalısınız</h3>
            <p class="text-muted">Bu işlemi yapabilmek için lütfen hesabınıza giriş yapın.</p>
            <div class="d-grid gap-2 mt-4">
                <a href="{{ route('login') }}" class="btn btn-primary py-3 rounded-3 fw-bold">GİRİŞ YAP</a>
                <button type="button" class="btn btn-light py-3 rounded-3 fw-bold" data-bs-dismiss="modal">VAZGEÇ</button>
            </div>
        </div>
    </div>
</div>

<script>
// SEPETE EKLEME AJAX
function addToCart(urunId, btn) {
    const qtyInput = document.getElementById('qty_' + urunId);
    const adet = qtyInput ? qtyInput.value : 1;
    
    const originalHtml = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i>';

    fetch('{{ route("sepet.ekle") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ id: urunId, adet: adet })
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            if (typeof window.updateAllCartCounts === 'function') {
                window.updateAllCartCounts(data.sepet_count || data.sepetCount);
            }
            
            btn.innerHTML = '<i class="fas fa-check"></i>';
            btn.classList.add('btn-success');
            if(window.showToast) window.showToast('Ürün sepete eklendi!', 'success');

            setTimeout(() => {
                btn.innerHTML = originalHtml;
                btn.classList.remove('btn-success');
                btn.disabled = false;
            }, 2000);
        } else {
            alert(data.message || 'Hata oluştu');
            btn.innerHTML = originalHtml;
            btn.disabled = false;
        }
    })
    .catch(err => {
        console.error(err);
        btn.innerHTML = originalHtml;
        btn.disabled = false;
    });
}

// FAVORİ İŞLEMİ AJAX
function toggleFavorite(id, btn) {
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
        const icon = btn.querySelector('i');
        if(data.action === 'added') {
            icon.classList.replace('far', 'fas');
            btn.classList.add('active');
            if(window.showToast) window.showToast('Favorilere eklendi!', 'success');
        } else {
            icon.classList.replace('fas', 'far');
            btn.classList.remove('active');
            if(window.showToast) window.showToast('Favorilerden kaldırıldı.', 'info');
        }
    });
}

// MİKTAR AYARLARI
function increaseQty(id) {
    const input = document.getElementById('qty_' + id);
    let val = parseInt(input.value);
    const max = parseInt(input.getAttribute('max'));
    if(val < max) input.value = val + 1;
}

function decreaseQty(id) {
    const input = document.getElementById('qty_' + id);
    let val = parseInt(input.value);
    if(val > 1) input.value = val - 1;
}

// FİLTRELEME
document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById("filterForm");
    const loadingOverlay = document.getElementById("loadingOverlay");

    function submitForm() {
        loadingOverlay.classList.add("show");
        filterForm.submit();
    }

    document.querySelectorAll(".auto-submit-select, .auto-submit-checkbox, .auto-submit-radio").forEach(el => {
        el.addEventListener("change", submitForm);
    });

    document.getElementById("kategori_select")?.addEventListener("change", function() {
        const altSelect = document.getElementById("alt_kategori_select");
        if(altSelect) altSelect.value = "";
        submitForm();
    });
});
</script>
@endsection