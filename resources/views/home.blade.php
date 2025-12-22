@extends('layouts.app')
@section('title', 'Ana Sayfa - Teknoloji Dünyası')

@section('content')

<style>
    /* --- 1. HERO SLIDER (FULL WIDTH - TAM GENİŞLİK) --- */
    .hero-wrapper-fluid {
        position: relative;
        /* Kenar boşluklarını kaldırdık, tam ekran yaptık */
        width: 100%;
        overflow: hidden;
        margin-bottom: 3rem;
    }
    .carousel-item {
        /* Yükseklik artırıldı, daha sinematik */
        height: 650px;
        background-color: #0f172a;
    }
    
    /* Görsel Zoom Animasyonu */
    .hero-image-container {
        position: absolute; top: 0; left: 0; width: 100%; height: 100%; overflow: hidden;
    }
    .hero-image-container img {
        width: 100%; height: 100%; object-fit: cover;
        /* Zoom animasyonu */
        animation: zoomEffect 25s infinite alternate;
    }
    @keyframes zoomEffect {
        0% { transform: scale(1); }
        100% { transform: scale(1.15); }
    }

    /* Slider İçerik Alanı - Tam ekran olsa da yazı ortalı kalsın diye */
    .hero-content-inner {
        max-width: 1400px; /* İçerik çok yayılmasın */
        margin: 0 auto;
        height: 100%;
        display: flex;
        align-items: center;
        position: relative;
        padding: 0 1.5rem;
        z-index: 5;
    }

    /* Animasyonlar */
    .hero-content-animate { opacity: 0; transform: translateY(40px); transition: all 0.8s cubic-bezier(0.215, 0.610, 0.355, 1.000); }
    .carousel-item.active .hero-content-animate { opacity: 1; transform: translateY(0); }
    
    .delay-1 { transition-delay: 0.2s; }
    .delay-2 { transition-delay: 0.4s; }
    .delay-3 { transition-delay: 0.6s; }

    /* Butonlar */
    .btn-hero-primary {
        background: linear-gradient(135deg, var(--warning-color), #f59e0b); 
        color: white; border: none; padding: 14px 40px; font-weight: 700; border-radius: 50px;
        box-shadow: 0 10px 25px rgba(245, 158, 11, 0.4); transition: all 0.3s; text-transform: uppercase; letter-spacing: 1px;
    }
    .btn-hero-primary:hover { transform: translateY(-3px); box-shadow: 0 20px 40px rgba(245, 158, 11, 0.6); color: white; }
    
    .btn-hero-glass {
        background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);
        color: white; padding: 14px 40px; font-weight: 700; border-radius: 50px; transition: all 0.3s; text-transform: uppercase; letter-spacing: 1px;
    }
    .btn-hero-glass:hover { background: white; color: var(--primary-color); transform: translateY(-3px); }

    /* --- 2. FEATURES (HİZMET KUTULARI) --- */
    .feature-box {
        background: white; padding: 2rem 1.5rem; border-radius: 16px;
        border: 1px solid #f1f5f9; transition: all 0.3s ease;
        display: flex; flex-direction: column; align-items: center; text-align: center; gap: 15px; height: 100%;
        box-shadow: 0 4px 6px rgba(0,0,0,0.02);
    }
    .feature-box:hover { transform: translateY(-10px); box-shadow: 0 20px 40px rgba(0,0,0,0.08); border-color: transparent; }
    .feature-icon-circle {
        width: 70px; height: 70px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.8rem; margin-bottom: 5px;
        transition: transform 0.3s;
    }
    .feature-box:hover .feature-icon-circle { transform: scale(1.1) rotate(5deg); }

    /* --- 3. KATEGORİ KARTLARI --- */
    .cat-card-modern {
        display: block; position: relative; overflow: hidden; border-radius: 20px; height: 300px;
        box-shadow: 0 10px 20px rgba(0,0,0,0.05); transition: transform 0.3s ease;
    }
    .cat-card-modern:hover { transform: translateY(-5px); box-shadow: 0 25px 50px rgba(0,0,0,0.15); }
    .cat-card-modern img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94); }
    .cat-card-modern:hover img { transform: scale(1.1); }
    .cat-overlay {
        position: absolute; inset: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.2) 50%, transparent 100%);
        display: flex; flex-direction: column; justify-content: flex-end; padding: 2rem; color: white;
    }

    /* --- 4. ÜRÜN KARTLARI --- */
    .product-card-modern {
        background: white; border: 1px solid #f1f5f9; border-radius: 20px;
        overflow: hidden; transition: all 0.3s ease; position: relative; height: 100%;
        display: flex; flex-direction: column;
    }
    .product-card-modern:hover { border-color: transparent; box-shadow: 0 20px 30px -10px rgba(0,0,0,0.1); transform: translateY(-8px); }
    
    .pcm-badge {
        position: absolute; top: 15px; left: 15px; z-index: 2;
        padding: 6px 14px; border-radius: 30px; font-size: 0.75rem; font-weight: 800; color: white;
        box-shadow: 0 4px 10px rgba(0,0,0,0.15); text-transform: uppercase;
    }
    .badge-hot { background: linear-gradient(45deg, #ff416c, #ff4b2b); }
    .badge-new { background: linear-gradient(45deg, #4facfe, #00f2fe); }

    .pcm-image-wrapper {
        position: relative; height: 260px; padding: 30px; background: radial-gradient(circle, #f8fafc 0%, #f1f5f9 100%);
        display: flex; align-items: center; justify-content: center; overflow: hidden;
    }
    .pcm-image-wrapper img { max-width: 100%; max-height: 100%; object-fit: contain; transition: transform 0.4s ease; filter: drop-shadow(0 10px 15px rgba(0,0,0,0.05)); }
    .product-card-modern:hover .pcm-image-wrapper img { transform: scale(1.1) translateY(-5px); }

    .pcm-actions {
        position: absolute; right: 15px; top: 15px; display: flex; flex-direction: column; gap: 10px;
        opacity: 0; transform: translateX(20px); transition: all 0.3s ease;
    }
    .product-card-modern:hover .pcm-actions { opacity: 1; transform: translateX(0); }
    
    .pcm-btn {
        width: 42px; height: 42px; border-radius: 50%; background: white; border: none;
        color: var(--text-primary); display: flex; align-items: center; justify-content: center;
        transition: 0.2s; cursor: pointer; box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .pcm-btn:hover { background: var(--primary-color); color: white; transform: scale(1.1); }

    .pcm-content { padding: 1.5rem; flex-grow: 1; display: flex; flex-direction: column; }
    .pcm-title {
        font-size: 1.05rem; font-weight: 700; margin-bottom: 10px; line-height: 1.4;
        display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; height: 46px;
    }
    .pcm-title a { color: var(--text-primary); text-decoration: none; transition: 0.2s; }
    .pcm-title a:hover { color: var(--accent-color); }
    
    .pcm-price { font-size: 1.4rem; font-weight: 800; color: var(--primary-color); letter-spacing: -0.5px; }
    
    .pcm-add-btn {
        width: 100%; padding: 12px; border-radius: 12px; border: 2px solid #f1f5f9;
        background: transparent; font-weight: 700; color: var(--text-primary); margin-top: 15px;
        transition: 0.2s; display: flex; align-items: center; justify-content: center; gap: 8px; font-size: 0.9rem;
    }
    .pcm-add-btn:hover { background: var(--primary-color); border-color: var(--primary-color); color: white; box-shadow: 0 10px 20px rgba(0,0,0,0.1); }

    /* --- 5. HAREKETLİ MARKA BANDI --- */
    .brands-slider-area {
        background: white; padding: 50px 0; border-top: 1px solid #f1f5f9; border-bottom: 1px solid #f1f5f9;
        overflow: hidden; position: relative; margin-bottom: 0;
    }
    .brands-slider-track {
        display: flex; width: calc(200px * 14); animation: scrollBrands 40s linear infinite;
    }
    .brand-slide {
        width: 200px; display: flex; align-items: center; justify-content: center; opacity: 0.6; transition: 0.3s;
    }
    .brand-slide:hover { opacity: 1; }
    .brand-slide i {
        font-size: 4rem; color: #cbd5e1; transition: all 0.3s ease;
    }
    .brand-slide:hover i { color: var(--primary-color); transform: scale(1.1); }

    @keyframes scrollBrands { 0% { transform: translateX(0); } 100% { transform: translateX(calc(-200px * 7)); } }

    /* Başlıklar */
    .section-header-modern { text-align: center; margin-bottom: 3.5rem; }
    .section-header-modern h2 { font-weight: 800; font-size: 2.5rem; color: var(--text-primary); margin-bottom: 0.75rem; letter-spacing: -1px; }
    .section-header-modern p { color: var(--text-secondary); max-width: 600px; margin: 0 auto; font-size: 1.1rem; }

    @media (max-width: 991px) {
        .carousel-item { height: 500px; }
        .hero-content-inner { justify-content: center; text-align: center; }
        .col-lg-7 { padding: 0 !important; }
        .d-flex.gap-3 { justify-content: center; }
        .hero-title { font-size: 2.5rem !important; }
    }
</style>

<section class="hero-wrapper-fluid">
    <div id="heroCarousel" class="carousel slide carousel-fade h-100" data-bs-ride="carousel" data-bs-interval="6000">
        <div class="carousel-indicators gap-2 mb-5">
            @for($i = 0; $i < 3; $i++)
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="{{ $i }}" class="{{ $i == 0 ? 'active' : '' }}" style="width: 12px; height: 12px; border-radius: 50%; border: 2px solid white; background-color: transparent; opacity: 0.7; transition: 0.3s;"></button>
            @endfor
        </div>

        <div class="carousel-inner h-100">
            <div class="carousel-item active">
                <div class="hero-image-container">
                    <img src="{{ asset('resimler/slide1.png') }}" onerror="this.src='https://images.unsplash.com/photo-1624705002806-5d72df19c2df?q=80&w=2070&auto=format&fit=crop'" alt="Gaming">
                    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(90deg, rgba(15,23,42,0.9) 0%, rgba(15,23,42,0.6) 50%, rgba(15,23,42,0.2) 100%);"></div>
                </div>
                <div class="hero-content-inner">
                    <div class="col-lg-7 text-white">
                        <div class="hero-content-animate delay-1">
                            <span class="badge bg-danger px-3 py-2 mb-4" style="letter-spacing: 2px; box-shadow: 0 0 20px rgba(220,38,38,0.5);">OYUN CANAVARI</span>
                        </div>
                        <h1 class="display-3 fw-bolder mb-4 hero-content-animate delay-2 hero-title" style="line-height: 1.1;">
                            Sınırları Zorlayan <br> <span style="color: var(--warning-color); text-shadow: 0 0 30px rgba(245,158,11,0.5);">Performans</span>
                        </h1>
                        <p class="lead mb-5 hero-content-animate delay-3" style="opacity: 0.9; max-width: 600px; font-size: 1.25rem;">
                            RTX 40 Serisi ekran kartları ve sıvı soğutmalı sistemlerle oyunun kurallarını yeniden yazın.
                        </p>
                        <div class="d-flex gap-3 hero-content-animate delay-3">
                            <a href="{{ route('urun.index') }}" class="btn-hero-primary">
                                Hemen İncele
                            </a>
                            <a href="{{ route('wizard.index') }}" class="btn-hero-glass">
                                <i class="fas fa-microchip me-2"></i> PC Topla
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="carousel-item">
                <div class="hero-image-container">
                    <img src="{{ asset('resimler/slide2.png') }}" onerror="this.src='https://images.unsplash.com/photo-1525547719571-a2d4ac8945e2?q=80&w=2070&auto=format&fit=crop'" alt="Teknoloji">
                    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(90deg, rgba(15,23,42,0.9) 0%, rgba(15,23,42,0.6) 50%, rgba(15,23,42,0.2) 100%);"></div>
                </div>
                <div class="hero-content-inner">
                    <div class="col-lg-7 text-white">
                        <div class="hero-content-animate delay-1">
                            <span class="badge bg-info text-dark px-3 py-2 mb-4" style="letter-spacing: 2px;">KURUMSAL ÇÖZÜMLER</span>
                        </div>
                        <h1 class="display-3 fw-bolder mb-4 hero-content-animate delay-2 hero-title" style="line-height: 1.1;">
                            İşinizi Geleceğe <br> <span style="color: #63b3ed;">Taşıyın</span>
                        </h1>
                        <p class="lead mb-5 hero-content-animate delay-3" style="opacity: 0.9; max-width: 600px; font-size: 1.25rem;">
                            Yüksek performanslı iş istasyonları ve sunucu çözümleriyle verimliliğinizi maksimuma çıkarın.
                        </p>
                        <div class="d-flex gap-3 hero-content-animate delay-3">
                            <a href="{{ route('urun.kategori', 1) }}" class="btn-hero-primary">
                                Fırsatları Gör
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="carousel-item">
                <div class="hero-image-container">
                    <img src="{{ asset('resimler/slide3.png') }}" onerror="this.src='https://images.unsplash.com/photo-1595225476474-87563907a212?q=80&w=2070&auto=format&fit=crop'" alt="Ekipman">
                    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(90deg, rgba(15,23,42,0.9) 0%, rgba(15,23,42,0.6) 50%, rgba(15,23,42,0.2) 100%);"></div>
                </div>
                <div class="hero-content-inner">
                    <div class="col-lg-7 text-white">
                        <div class="hero-content-animate delay-1">
                            <span class="badge bg-success px-3 py-2 mb-4" style="letter-spacing: 2px;">PROFESYONEL EKİPMAN</span>
                        </div>
                        <h1 class="display-3 fw-bolder mb-4 hero-content-animate delay-2 hero-title" style="line-height: 1.1;">
                            Setup'ını <br> <span style="color: #68d391;">Tamamla</span>
                        </h1>
                        <p class="lead mb-5 hero-content-animate delay-3" style="opacity: 0.9; max-width: 600px; font-size: 1.25rem;">
                            Mekanik klavyeler, ultra hafif mouse'lar ve stüdyo kalitesinde mikrofonlar.
                        </p>
                        <div class="d-flex gap-3 hero-content-animate delay-3">
                            <a href="{{ route('urun.kategori', 8) }}" class="btn-hero-primary">
                                Alışverişe Başla
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev" style="width: 5%; opacity: 0.5;">
            <span class="carousel-control-prev-icon" aria-hidden="true" style="width: 3rem; height: 3rem; background-color: rgba(255,255,255,0.1); border-radius: 50%; backdrop-filter: blur(5px);"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next" style="width: 5%; opacity: 0.5;">
            <span class="carousel-control-next-icon" aria-hidden="true" style="width: 3rem; height: 3rem; background-color: rgba(255,255,255,0.1); border-radius: 50%; backdrop-filter: blur(5px);"></span>
        </button>
    </div>
</section>

<div class="container">

    <section class="mb-5 fade-in-up" style="animation-delay: 0.2s;">
        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="feature-box">
                    <div class="feature-icon-circle" style="background: #eff6ff; color: #3b82f6;">
                        <i class="fas fa-truck-fast"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1 fs-5">Hızlı Teslimat</h6>
                        <small class="text-secondary">Aynı gün kargoda</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="feature-box">
                    <div class="feature-icon-circle" style="background: #f0fdf4; color: #22c55e;">
                        <i class="fas fa-shield-halved"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1 fs-5">Güvenli Ödeme</h6>
                        <small class="text-secondary">256-bit SSL Koruma</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="feature-box">
                    <div class="feature-icon-circle" style="background: #fffbeb; color: #f59e0b;">
                        <i class="fas fa-rotate-left"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1 fs-5">Kolay İade</h6>
                        <small class="text-secondary">14 gün cayma hakkı</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="feature-box">
                    <div class="feature-icon-circle" style="background: #fef2f2; color: #ef4444;">
                        <i class="fas fa-headset"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1 fs-5">7/24 Destek</h6>
                        <small class="text-secondary">Uzman teknik ekip</small>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div> <section class="py-5 mb-5" style="background-color: #f8fafc;">
    <div class="container">
        <div class="section-header-modern fade-in-up">
            <h2>Popüler Kategoriler</h2>
            <p>İhtiyacınız olan donanımı en iyi markalarla keşfedin.</p>
        </div>

        <div class="row g-4 justify-content-center">
            @php
                $cats = [
                    ['id' => 1, 'icon' => 'microchip', 'name' => 'İşlemciler', 'sub' => 'Intel & AMD'],
                    ['id' => 2, 'icon' => 'memory', 'name' => 'Ekran Kartı', 'sub' => 'NVIDIA & Radeon'],
                    ['id' => 3, 'icon' => 'hdd', 'name' => 'Depolama', 'sub' => 'SSD & HDD'],
                    ['id' => 4, 'icon' => 'desktop', 'name' => 'Anakartlar', 'sub' => 'Tüm Soketler'],
                    ['id' => 5, 'icon' => 'bolt', 'name' => 'Güç Kaynağı', 'sub' => '80+ Gold'],
                    ['id' => 7, 'icon' => 'tv', 'name' => 'Monitörler', 'sub' => '144Hz & IPS'],
                ];
            @endphp

            @foreach($cats as $cat)
            <div class="col-lg-2 col-md-4 col-6 fade-in-up" style="animation-delay: {{ $loop->index * 0.1 }}s;">
                <a href="{{ route('urun.kategori', $cat['id']) }}" class="cat-card-modern h-100">
                    <img src="https://source.unsplash.com/random/400x500?tech,{{$cat['icon']}}" onerror="this.src='https://via.placeholder.com/400x500?text={{$cat['name']}}'" alt="{{ $cat['name'] }}">
                    <div class="cat-overlay">
                        <i class="fas fa-{{ $cat['icon'] }} mb-2 fs-3 text-warning"></i>
                        <h5 class="fw-bold mb-1">{{ $cat['name'] }}</h5>
                        <p class="m-0 small opacity-75">{{ $cat['sub'] }}</p>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

<div class="container">
    
    <section class="mb-5" id="urunler">
        <div class="section-header-modern fade-in-up">
            <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary px-3 py-2 mb-3">FIRSATLAR</span>
            <h2>Haftanın Yıldız Ürünleri</h2>
            <p>En çok satan ve yüksek puanlı ürünleri sizin için seçtik.</p>
        </div>

        <div class="row g-4">
            @forelse($urunler ?? [] as $index => $urun)
            <div class="col-lg-3 col-md-6 col-sm-6 fade-in-up" style="animation-delay: {{ $index * 0.05 }}s">
                <div class="product-card-modern">
                    @if($loop->index < 2)
                        <div class="pcm-badge badge-hot"><i class="fas fa-fire me-1"></i>ÇOK SATAN</div>
                    @elseif($loop->index == 2)
                        <div class="pcm-badge badge-new"><i class="fas fa-star me-1"></i>YENİ</div>
                    @endif

                    <div class="pcm-image-wrapper">
                        <a href="{{ route('urun.incele', $urun->id) }}">
                            <img src="{{ $urun->resim_url ?? 'https://via.placeholder.com/400x400.png?text=Urun' }}" alt="{{ $urun->urun_ad }}" loading="lazy">
                        </a>
                        <div class="pcm-actions">
                            <button class="pcm-btn" onclick="sepeteEkle({{ $urun->id }})" title="Sepete Ekle" data-bs-toggle="tooltip">
                                <i class="fas fa-cart-plus"></i>
                            </button>
                            <a href="{{ route('urun.incele', $urun->id) }}" class="pcm-btn" title="İncele">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </div>

                    <div class="pcm-content">
                        <div class="pcm-category">{{ $urun->marka ?? 'GENEL' }}</div>
                        <h3 class="pcm-title">
                            <a href="{{ route('urun.incele', $urun->id) }}">{{ $urun->urun_ad }}</a>
                        </h3>
                        <div class="d-flex align-items-center mb-3">
                            <div class="text-warning small me-2">
                                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                            </div>
                            <span class="text-muted small" style="font-size: 0.8rem;">(24 Yorum)</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-end mt-auto">
                            <div class="pcm-price">₺{{ number_format($urun->fiyat, 2, ',', '.') }}</div>
                        </div>
                        <button class="pcm-add-btn" onclick="sepeteEkle({{ $urun->id }})">
                            <i class="fas fa-basket-shopping"></i> Sepete Ekle
                        </button>
                    </div>
                </div>
            </div>
            @empty
                @for($i = 0; $i < 4; $i++)
                <div class="col-lg-3 col-md-6">
                    <div class="product-card-modern bg-light border-0 h-100 d-flex align-items-center justify-content-center">
                        <div class="spinner-border text-muted" role="status"></div>
                    </div>
                </div>
                @endfor
            @endforelse
        </div>

        <div class="text-center mt-5">
            <a href="{{ route('urun.index') }}" class="btn btn-outline-primary rounded-pill px-5 py-3 fw-bold border-2">
                Tüm Ürünleri Görüntüle <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
    </section>

    <section class="my-5 fade-in-up">
        <div class="position-relative rounded-4 overflow-hidden p-5 text-white text-center" 
             style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); box-shadow: 0 20px 40px -10px rgba(0,0,0,0.3);">
            <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0.1; background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 20px 20px;"></div>
            <div class="position-relative z-1">
                <h2 class="display-5 fw-bold mb-3">Hayalindeki Bilgisayarı Topla</h2>
                <p class="lead mb-4 mx-auto" style="max-width: 700px; color: rgba(255,255,255,0.9);">
                    Parçaların uyumluluğu konusunda endişelenme. Akıllı PC toplama sihirbazımız ile saniyeler içinde birbiriyle tam uyumlu sistemini oluştur.
                </p>
                <a href="{{ route('wizard.index') }}" class="btn btn-warning btn-lg rounded-pill px-5 fw-bold text-dark" style="box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
                    <i class="fas fa-magic me-2"></i>Sihirbazı Başlat
                </a>
            </div>
        </div>
    </section>

</div> <section class="brands-slider-area fade-in-up">
    <div class="container text-center mb-4">
        <h5 class="text-muted fw-bold text-uppercase" style="letter-spacing: 2px;">Resmi İş Ortaklarımız</h5>
    </div>
    
    <div class="brands-slider-track">
        <div class="brand-slide"><i class="fab fa-apple"></i></div>
        <div class="brand-slide"><i class="fab fa-windows"></i></div>
        <div class="brand-slide"><i class="fab fa-android"></i></div>
        <div class="brand-slide"><i class="fab fa-google"></i></div>
        <div class="brand-slide"><i class="fab fa-amazon"></i></div>
        <div class="brand-slide"><i class="fas fa-microchip"></i></div>
        <div class="brand-slide"><i class="fas fa-server"></i></div>
        
        <div class="brand-slide"><i class="fab fa-apple"></i></div>
        <div class="brand-slide"><i class="fab fa-windows"></i></div>
        <div class="brand-slide"><i class="fab fa-android"></i></div>
        <div class="brand-slide"><i class="fab fa-google"></i></div>
        <div class="brand-slide"><i class="fab fa-amazon"></i></div>
        <div class="brand-slide"><i class="fas fa-microchip"></i></div>
        <div class="brand-slide"><i class="fas fa-server"></i></div>
    </div>
</section>

@endsection

@push('scripts')
<script>
// Sepete Ekleme Fonksiyonu
function sepeteEkle(urunId) {
    const btn = event.currentTarget;
    const originalContent = btn.innerHTML;
    
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    btn.disabled = true;

    fetch(`/sepet/ekle/${urunId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ adet: 1 })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Ürün başarıyla sepete eklendi!', 'success');
            const cartCountElement = document.getElementById('cartCount');
            if(cartCountElement) {
                cartCountElement.textContent = data.cart_count;
                cartCountElement.style.transform = 'scale(1.5)';
                setTimeout(() => cartCountElement.style.transform = 'scale(1)', 300);
            }
            if(typeof updateCartDropdown === 'function') {
                updateCartDropdown(data.cart_item, urunId);
            }
            btn.classList.add('btn-success', 'text-white');
            btn.innerHTML = '<i class="fas fa-check"></i> Eklendi';
        } else {
            showToast(data.message || 'Hata oluştu', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Sunucu hatası', 'error');
    })
    .finally(() => {
        setTimeout(() => {
            btn.innerHTML = originalContent;
            btn.classList.remove('btn-success', 'text-white');
            btn.disabled = false;
        }, 2000);
    });
}
</script>
@endpush