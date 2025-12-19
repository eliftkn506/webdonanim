@extends('layouts.app')
@section('title', 'Ürünler Filtrele')

@section('content')
<style>
/* Stil kodları aynı kalıyor */
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
/* ... (Tüm CSS kodunuz buraya gelecektir) ... */
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

.product-criteria {
    margin-bottom: 1rem;
}

.criteria-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.criteria-list li {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.85rem;
    color: var(--secondary);
    margin-bottom: 0.5rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid var(--light);
}
.criteria-list li:last-child {
    border-bottom: 0;
    margin-bottom: 0;
}

.criteria-list strong {
    font-weight: 500;
    color: var(--dark);
    margin-right: 0.5rem;
    white-space: nowrap;
}
.criteria-list span {
    text-align: right;
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

.installment-text {
    font-size: 0.8125rem;
    color: var(--secondary);
    margin-top: 0.5rem;
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
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
input[type=number] {
    -moz-appearance: textfield;
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

.empty-state {
    grid-column: 1/-1;
    text-align: center;
    padding: 4rem 2rem;
    background: white;
    border-radius: 12px;
    border: 1px solid var(--border);
}
.empty-icon {
    font-size: 4rem;
    color: var(--secondary);
    opacity: 0.3;
    margin-bottom: 1rem;
}

.pagination-wrapper {
    margin-top: 2.5rem;
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
    box-shadow: 0 2px 4px rgba(0,0,0,0.03);
}
.page-link:hover {
    border-color: var(--primary);
    color: var(--primary);
    box-shadow: 0 4px 8px rgba(0,0,0,0.05);
}
.page-link.active {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
    font-weight: 600;
}

@media (max-width: 1024px) {
    .main-container {
        grid-template-columns: 1fr;
    }
    .filter-sidebar {
        position: static;
        margin-bottom: 2rem;
        max-height: none;
    }
}
@media (max-width: 768px) {
    .products-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }
    .product-image-wrapper {
        height: 180px;
    }
    .products-header {
        flex-direction: column;
        gap: 1rem;
    }
}
@media (max-width: 480px) {
    .products-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    .main-container {
        grid-template-columns: 1fr;
    }
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
            {{-- Temizle butonunu ana ürünler sayfasına yönlendir (eğer arama varsa q parametresini koru) --}}
            <a href="{{ route('urun.index') . (request('q') ? '?q=' . request('q') : '') }}" class="filter-clear">Temizle</a>
        </div>

        <form method="GET" id="filterForm">
            {{-- Search Query ve Sort alanlarını gizli input olarak ekle --}}
            @if(request('q'))
                <input type="hidden" name="q" value="{{ request('q') }}">
            @endif
            
            {{-- Sort alanını gizli tutmak yerine, sıralama formu ile hallediyoruz. Yine de tutabiliriz. --}}
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
                    {{-- Alt kategoriler JavaScript ile doldurulacak, mevcut seçili olan Blade ile render ediliyor --}}
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
                                // Laravel'in request helper'ı ile markanın seçili olup olmadığını kontrol et
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
                                // Laravel'in request helper'ı ile modelin seçili olup olmadığını kontrol et
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
                {{-- Kriterler Alt Kategori seçildiğinde Controller'dan yüklenecek --}}
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
                <small class="text-muted d-block mb-2">₺{{ number_format($minFiyat ?? 0, 0) }} - ₺{{ number_format($maxFiyat ?? 10000, 0) }}</small>
                <div class="price-inputs">
                    <input type="number" name="min_fiyat" placeholder="Min" class="price-input" value="{{ request('min_fiyat') }}" min="0" max="{{ $maxFiyat ?? 10000 }}">
                    <input type="number" name="max_fiyat" placeholder="Max" class="price-input" value="{{ request('max_fiyat') }}" min="0" max="{{ $maxFiyat ?? 10000 }}">
                </div>
            </div>

            <div class="filter-section">
                <div class="filter-section-title">📦 Stok Durumu</div>
                <div class="checkbox-list">
                    <div class="checkbox-item">
                        <label>
                            <input type="radio" name="stok_durumu" value="hepsi" {{ !request('stok_durumu') || request('stok_durumu') == 'hepsi' ? 'checked' : '' }} class="auto-submit-radio">
                            Hepsi
                        </label>
                    </div>
                    <div class="checkbox-item">
                        <label>
                            <input type="radio" name="stok_durumu" value="var" {{ request('stok_durumu') == 'var' ? 'checked' : '' }} class="auto-submit-radio">
                            Stokta Var
                        </label>
                    </div>
                    <div class="checkbox-item">
                        <label>
                            <input type="radio" name="stok_durumu" value="yok" {{ request('stok_durumu') == 'yok' ? 'checked' : '' }} class="auto-submit-radio">
                            Stokta Yok
                        </label>
                    </div>
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
                    {{-- Mevcut filtreleri gizli input olarak ekle --}}
                    @foreach(request()->except(['sort', '_token', 'page']) as $key => $value)
                        @if(is_array($value))
                            @foreach($value as $subKey => $subValue)
                                @if(is_array($subValue))
                                    @foreach($subValue as $v)
                                        <input type="hidden" name="{{ $key }}[{{ $subKey }}][]" value="{{ $v }}">
                                    @endforeach
                                @else
                                    {{-- Marka/Model durumunda --}}
                                    <input type="hidden" name="{{ $key }}[]" value="{{ $subValue }}">
                                @endif
                            @endforeach
                        @elseif(!empty($value))
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endif
                    @endforeach

                    <select name="sort" class="sort-select" onchange="this.form.submit()">
                        <option value="newest" {{ request('sort') == 'newest' || !request('sort') ? 'selected' : '' }}>En Yeni</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Ucuzdan Pahalıya</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Pahalıdan Ucuza</option>
                        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>A-Z Sıralama</option>
                        <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Z-A Sıralama</option>
                    </select>
                </form>
            </div>
        </div>

        <div class="products-grid">
            @forelse($urunler as $urun)
                @php
                    $user = auth()->user();
                    
                    // Fiyat ve indirim hesaplamaları
                    $satisFiyati = $urun->getFiyatForUser($user) ?? 0; 
                    $standartFiyat = $urun->getStandartFiyat() ?? 0; 
                    $isBayi = $user && ($user->isBayi() ?? false); 
                    $bayiFiyat = $isBayi ? ($urun->getBayiFiyat() ?? null) : null; 

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

                    $gosterimFiyati = $isBayi && $bayiFiyat ? $bayiFiyat : $indirimliFiyat; 
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
                                <i class="fas fa-search-plus"></i>
                            </a>
                        </div>
                    </div>

                    <div class="product-info">
                        @if($urun->marka)
                            <div class="product-brand">{{ $urun->marka }}</div>
                        @endif
                        
                        <h3 class="product-title">
                            <a href="{{ route('urun.incele', $urun->id) }}">{{ $urun->urun_ad }}</a>
                        </h3>

                        <div class="product-criteria">
                            @if($urun->urunKriterDegerleri->count() > 0)
                            <ul class="criteria-list">
                                @foreach($urun->urunKriterDegerleri->take(3) as $kd)
                                    @if($kd->kriter && $kd->kriterDeger)
                                    <li>
                                        <strong>{{ $kd->kriter->kriter_ad }}</strong>
                                        <span>{{ $kd->kriterDeger->deger }}</span>
                                    </li>
                                    @endif
                                @endforeach
                            </ul>
                            @endif
                        </div>

                        <div class="price-section">
                            <div class="price-wrapper">
                                @if($satisFiyati > 0)
                                    @if($isBayi && $bayiFiyat && $standartFiyat > $bayiFiyat)
                                        <span class="current-price discounted">₺{{ number_format($bayiFiyat, 2, ',', '.') }}</span>
                                        <div class="price-discount">
                                            <span class="original-price">₺{{ number_format($standartFiyat, 2, ',', '.') }}</span>
                                            <span class="discount-badge bayi">Bayi Fiyatı</span>
                                        </div>
                                    @elseif($kampanya)
                                        <span class="current-price discounted">₺{{ number_format($indirimliFiyat, 2, ',', '.') }}</span>
                                        <div class="price-discount">
                                            <span class="original-price">₺{{ number_format($satisFiyati, 2, ',', '.') }}</span>
                                            <span class="discount-badge">-%{{ $kampanya->indirim_orani }}</span>
                                        </div>
                                    @else
                                        <span class="current-price">₺{{ number_format($satisFiyati, 2, ',', '.') }}</span>
                                    @endif
                                    
                                    @if($gosterimFiyati > 1000)
                                        <div class="installment-text">
                                            {{ number_format($gosterimFiyati / 12, 0) }} ₺'den başlayan taksitle
                                        </div>
                                    @endif
                                @else
                                    <span class="no-price-text">Fiyat Yok</span>
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
                            @else
                                <a href="{{ route('urun.incele', $urun->id) }}" class="btn-incele">
                                    Detayları İncele
                                </a>
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
                    {{ $urunler->links('pagination::bootstrap-4') }}
                </div>
            </div>
        @endif
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log("🚀 Filtre sistemi başlatıldı");

    const kategoriSelect = document.getElementById("kategori_select");
    const altKategoriSelect = document.getElementById("alt_kategori_select");
    const markaList = document.getElementById("marka_list");
    const modelList = document.getElementById("model_list");
    const dynamicKriterFilters = document.getElementById("dynamic_kriter_filters");
    const filterForm = document.getElementById("filterForm");

    const markaSection = document.getElementById("marka_section");
    const modelSection = document.getElementById("model_section");
    const altKategoriSection = document.getElementById("alt_kategori_section");

    const loadingOverlay = document.getElementById("loadingOverlay");

    let isLoading = false;

    /* ---------------------- LOADING ----------------------- */
    function showLoading() {
        if (isLoading) return; 
        isLoading = true;
        loadingOverlay.classList.add("show");
    }

    function hideLoading() {
        isLoading = false;
        loadingOverlay.classList.remove("show");
    }


    /* ---------------------- FORM SUBMIT ---------------------- */
    function submitForm() {
        if (isLoading) return;
        showLoading();
        filterForm.submit();
    }

    /**
     * Mevcut form filtrelerini alıp Query String olarak döndürür.
     */
    function getFormDataAsQueryString(excludeKeys = []) {
        const formData = new FormData(filterForm);
        const params = new URLSearchParams();
        
        const kategoriId = kategoriSelect.value;
        const altKategoriId = altKategoriSelect.value;
        
        // Tüm form girdilerini işle
        for (const [key, value] of formData.entries()) {
            if (value === '' || value === 'hepsi' || key === 'sort' || key === 'page' || key === '_token') {
                continue; 
            }
            
            if (key !== 'kategori_id' && key !== 'alt_kategori_id') {
                if (excludeKeys.includes(key.replace('[]', ''))) {
                    continue;
                }
                
                if (key.startsWith('kriterler[') && excludeKeys.includes('kriterler')) {
                     continue;
                }
                
                params.append(key, value);
            }
        }
        
        // Kategori ve Alt Kategori her zaman mevcut olmalı
        if (kategoriId && !excludeKeys.includes('kategori_id')) {
            params.append('kategori_id', kategoriId);
        }
        if (altKategoriId && !excludeKeys.includes('alt_kategori_id')) {
             params.append('alt_kategori_id', altKategoriId);
        }

        return params.toString();
    }


    /* ---------------------- SEÇİLİ FİLTRELERİ AL (Aynı kaldı) ---------------------- */
    function getCheckedValues() {
        const checked = { markalar: [], modeller: [], kriterler: {} };

        document.querySelectorAll('#marka_list input:checked').forEach(i => checked.markalar.push(i.value));
        document.querySelectorAll('#model_list input:checked').forEach(i => checked.modeller.push(i.value));

        document.querySelectorAll('#dynamic_kriter_filters input:checked').forEach(i => {
            const match = i.name.match(/kriterler\[(\d+)\]/);
            if (match) {
                const id = match[1];
                if (!checked.kriterler[id]) checked.kriterler[id] = [];
                checked.kriterler[id].push(i.value);
            }
        });

        return checked;
    }


    /* ---------------------- KRİTER HTML RENDER (Aynı kaldı) ---------------------- */
    function renderKriterFilters(kriterler, checked) {
        let html = "";

        kriterler.forEach(k => {
            const aktifDeger = k.degerler.filter(x => x.urun_count > 0);
            if (aktifDeger.length === 0) return;

            html += `
                <div class="filter-section">
                    <div class="filter-section-title">⚙️ ${k.kriter_ad}</div>
                    <div class="checkbox-list">
            `;

            aktifDeger.forEach(d => {
                const ch = checked.kriterler[k.id] && checked.kriterler[k.id].includes(String(d.id)) ? "checked" : "";

                html += `
                    <div class="checkbox-item">
                        <label>
                            <input type="checkbox" name="kriterler[${k.id}][]" value="${d.id}" ${ch} class="auto-submit-checkbox">
                            ${d.deger}
                        </label>
                        <span class="checkbox-count">(${d.urun_count})</span>
                    </div>
                `;
            });

            html += `</div></div>`;
        });

        dynamicKriterFilters.innerHTML = html;
        attachAutoSubmitListeners(); 
    }


    /* ---------------------- MARKA / MODEL RENDER (Aynı kaldı) ---------------------- */
    function renderMarkaModel(data, checked) {
        markaList.innerHTML = "";
        modelList.innerHTML = "";

        /* Marka */
        if (data.markalar && data.markalar.length > 0) {
            markaSection.style.display = "block";
            data.markalar.forEach(m => {
                const ch = checked.markalar.includes(m.marka) ? "checked" : "";
                markaList.innerHTML += `
                    <div class="checkbox-item">
                        <label>
                            <input type="checkbox" name="marka[]" value="${m.marka}" ${ch} class="auto-submit-checkbox">
                            ${m.marka}
                        </label>
                        <span class="checkbox-count">(${m.count})</span>
                    </div>
                `;
            });
        } else {
            markaSection.style.display = "none";
        }

        /* Model */
        if (data.modeller && data.modeller.length > 0) {
            modelSection.style.display = "block";
            data.modeller.forEach(m => {
                const ch = checked.modeller.includes(m.model) ? "checked" : "";
                modelList.innerHTML += `
                    <div class="checkbox-item">
                        <label>
                            <input type="checkbox" name="model[]" value="${m.model}" ${ch} class="auto-submit-checkbox">
                            ${m.model}
                        </label>
                        <span class="checkbox-count">(${m.count})</span>
                    </div>
                `;
            });
        } else {
            modelSection.style.display = "none";
        }
        
        attachAutoSubmitListeners(); 
    }


    /* ---------------------- EVENT LISTENER YENİDEN EKLE (Aynı kaldı) ---------------------- */
    function attachAutoSubmitListeners() {
        // Checkbox, Radio, Price Inputs
        document.querySelectorAll(".auto-submit-checkbox, .auto-submit-radio, .price-input").forEach(el => {
            el.removeEventListener("change", submitForm);
            el.addEventListener("change", submitForm);
        });
        
        // Price Inputs (Enter tuşu)
        filterForm.querySelectorAll('.price-input').forEach(input => {
            input.removeEventListener('keydown', function(e) {
                if (e.key === 'Enter') { e.preventDefault(); submitForm(); }
            });
            input.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') { e.preventDefault(); submitForm(); }
            });
        });
    }


    /* ---------------------- ALT KATEGORİ AJAX FİLTRE YÜKLEME ---------------------- */
    function fetchKriterMarkaModel(altKategoriId) {
        if (!altKategoriId) {
            dynamicKriterFilters.innerHTML = "";
            markaList.innerHTML = "";
            modelList.innerHTML = "";
            markaSection.style.display = "none";
            modelSection.style.display = "none";
            return;
        }

        const checked = getCheckedValues();
        
        // Tüm aktif filtreleri al (bu Alt Kategori ID'sini içerir)
        const qs = getFormDataAsQueryString(); 

        const kriterUrl = `{{ route('urun.getKriterler') }}?${qs}`;
        const markaModelUrl = `{{ route('urun.getMarkaModel') }}?${qs}`;

        isLoading = true;
        showLoading();

        Promise.all([
            fetch(kriterUrl).then(r => r.json()),
            fetch(markaModelUrl).then(r => r.json())
        ]).then(([kriterler, markamodel]) => {
            console.log("✅ Filtre verisi geldi.");

            if(kriterler.error || markamodel.error) {
                console.error("Sunucu hatası:", kriterler.error || markamodel.error);
                renderKriterFilters([], checked);
                renderMarkaModel({markalar: [], modeller: []}, checked);
            } else {
                 renderKriterFilters(kriterler, checked);
                 renderMarkaModel(markamodel, checked);
            }

            hideLoading();
        }).catch(error => {
            console.error("❌ AJAX Hatası:", error);
            hideLoading();
        });
    }


    /* ---------------------- KATEGORİ SEÇİMİ ---------------------- */
    kategoriSelect.addEventListener("change", function() {
        const kategoriId = this.value;
        
        // Diğer filtreleri sıfırla
        altKategoriSelect.value = "";
        altKategoriSelect.innerHTML = '<option value="">Tümü</option>';
        dynamicKriterFilters.innerHTML = "";
        markaList.innerHTML = "";
        modelList.innerHTML = "";
        markaSection.style.display = "none";
        modelSection.style.display = "none";

        filterForm.querySelectorAll('input[type="checkbox"]:checked, input[type="radio"]').forEach(input => {
            if (input.name !== 'stok_durumu') input.checked = false;
        });
        filterForm.querySelector('input[name="stok_durumu"][value="hepsi"]').checked = true;
        filterForm.querySelector('input[name="min_fiyat"]').value = '';
        filterForm.querySelector('input[name="max_fiyat"]').value = '';


        // Eğer kategori seçiliyse (veya boşaltılıyorsa), formu gönder (bu Alt Kategoriyi yükler/temizler)
        submitForm();
    });


    /* ---------------------- ALT KATEGORİ SEÇİMİ ---------------------- */
    altKategoriSelect.addEventListener("change", function () {
        const altKategoriId = this.value;
        
        // Filtreleri sıfırla
        dynamicKriterFilters.innerHTML = "";
        markaList.innerHTML = "";
        modelList.innerHTML = "";
        
        // Alt kategori değiştiği anda yeni filtreleri yükle
        if(altKategoriId) {
             fetchKriterMarkaModel(altKategoriId);
        } else {
             // Alt kategori "Tümü" seçildiyse dinamik alanları temizle/gizle
             markaSection.style.display = "none";
             modelSection.style.display = "none";
        }
        
        // Yeni ürün listesini çekmek için formu gönder
        submitForm();
    });
    
    
    // --- GEREKLİ ALT KATEGORİ ÇEKME FONKSİYONU (index sayfasındaki ilk yükleme için) ---
    function fetchAltKategoriler(kategoriId) {
        if (!kategoriId) return;
        
        fetch(`{{ route('urun.getAltKategoriler') }}?kategori_id=${kategoriId}`)
            .then(response => response.json())
            .then(data => {
                altKategoriSelect.innerHTML = '<option value="">Tümü</option>';
                data.forEach(altKat => {
                    const option = document.createElement('option');
                    option.value = altKat.id;
                    option.textContent = altKat.alt_kategori_ad;
                    altKategoriSelect.appendChild(option);
                });
                // URL'den seçili alt kategori varsa ayarla
                const urlParams = new URLSearchParams(window.location.search);
                const currentAltKategoriId = urlParams.get('alt_kategori_id');
                if (currentAltKategoriId && altKategoriSelect.querySelector(`option[value="${currentAltKategoriId}"]`)) {
                    altKategoriSelect.value = currentAltKategoriId;
                }
            })
            .catch(error => console.error('Alt Kategori AJAX Hatası:', error));
    }


    /* ---------------------- SAYFA İLK YÜKLENME ---------------------- */
    // Eğer Alt Kategori ilk yüklemede seçiliyse, dinamik filtreleri yükle
    if (altKategoriSelect.value && altKategoriSelect.children.length > 1) {
        console.log("⏳ İlk yükleme: Alt kategori seçili, dinamik filtreler yükleniyor...");
        fetchKriterMarkaModel(altKategoriSelect.value);
    } else if (kategoriSelect.value) {
        // Eğer kategori seçili ama alt kategoriler henüz yüklenmemişse, yükle
        fetchAltKategoriler(kategoriSelect.value);
    }

    // Genel auto-submit dinleyicilerini ilk başta ekle
    attachAutoSubmitListeners();
    console.log("✅ Filtre sistemi hazır.");
});

// Yardımcı fonksiyonlar (Mevcut haliyle bırakıldı)
function increaseQty(id) { 
    const input = document.getElementById('qty_' + id); 
    let val = parseInt(input.value); 
    if(val < 99) input.value = val + 1; 
}

function decreaseQty(id) { 
    const input = document.getElementById('qty_' + id); 
    let val = parseInt(input.value); 
    if(val > 1) input.value = val - 1; 
}

function addToCart(urunId) { 
    console.log('🛒 Sepete eklendi:', urunId); 
}

function toggleFavorite(id) { 
    console.log('❤️ Favori toggle:', id); 
}
</script>
@endsection