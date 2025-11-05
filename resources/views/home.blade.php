@extends('layouts.app')

@section('content')
<style>
:root {
    --primary-color: #1a365d;
    --secondary-color: #2d5aa0;
    --accent-color: #22987e;
    --accent-hover: #1a6b5c;
    --danger-color: #e53e3e;
    --warning-color: #ff8c00;
    --success-color: #38a169;
    --text-primary: #2d3748;
    --text-secondary: #4a5568;
    --text-light: #718096;
    --bg-primary: #f7fafc;
    --bg-secondary: #edf2f7;
    --bg-tertiary: #e2e8f0;
    --border-color: #e2e8f0;
    --white: #ffffff;
    --shadow-light: 0 1px 3px rgba(0, 0, 0, 0.1);
    --shadow-medium: 0 4px 6px rgba(0, 0, 0, 0.1);
    --shadow-heavy: 0 10px 25px rgba(0, 0, 0, 0.15);
    --radius-sm: 0.375rem;
    --radius-md: 0.5rem;
    --radius-lg: 0.75rem;
    --radius-xl: 1rem;
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Hero Section */
.hero-wrapper {
    position: relative;
    margin-top: 80px;
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    border-radius: var(--radius-xl);
    overflow: hidden;
    box-shadow: var(--shadow-heavy);
}

.hero-carousel {
    position: relative;
    height: 70vh;
    min-height: 500px;
}

.carousel-item {
    position: relative;
    height: 100%;
}

.carousel-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.8s ease;
}

.carousel-item.active img {
    transform: scale(1.05);
}

.carousel-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, rgba(26, 54, 93, 0.8) 0%, rgba(34, 152, 126, 0.6) 100%);
    z-index: 2;
}

.hero-content {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 3;
    text-align: center;
    color: white;
    max-width: 800px;
    padding: 0 2rem;
}

.hero-badge {
    display: inline-block;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    padding: 0.5rem 1.5rem;
    border-radius: 50px;
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
    animation: fadeInUp 1s ease 0.2s both;
}

.hero-title {
    font-size: clamp(2.5rem, 5vw, 4rem);
    font-weight: 900;
    margin-bottom: 1.5rem;
    line-height: 1.2;
    text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    animation: fadeInUp 1s ease 0.4s both;
}

.hero-subtitle {
    font-size: clamp(1.1rem, 2vw, 1.4rem);
    font-weight: 400;
    margin-bottom: 2.5rem;
    opacity: 0.95;
    line-height: 1.6;
    animation: fadeInUp 1s ease 0.6s both;
}

.hero-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
    animation: fadeInUp 1s ease 0.8s both;
}

.btn-hero {
    padding: 1rem 2.5rem;
    font-size: 1.1rem;
    font-weight: 600;
    border-radius: 50px;
    text-decoration: none;
    transition: var(--transition);
    border: none;
    cursor: pointer;
    position: relative;
    overflow: hidden;
}

.btn-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s;
}

.btn-hero:hover::before {
    left: 100%;
}

.btn-primary-hero {
    background: white;
    color: var(--primary-color);
    box-shadow: 0 6px 20px rgba(255, 255, 255, 0.3);
}

.btn-primary-hero:hover {
    background: var(--bg-tertiary);
    color: var(--primary-color);
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(255, 255, 255, 0.4);
}

.btn-secondary-hero {
    background: transparent;
    color: white;
    border: 2px solid rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(10px);
}

.btn-secondary-hero:hover {
    background: white;
    color: var(--primary-color);
    transform: translateY(-3px);
    border-color: white;
}

/* Carousel Controls */
.carousel-control-prev,
.carousel-control-next {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    opacity: 1;
    transition: var(--transition);
    top: 50%;
    transform: translateY(-50%);
}

.carousel-control-prev:hover,
.carousel-control-next:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: translateY(-50%) scale(1.1);
}

.carousel-control-prev-icon,
.carousel-control-next-icon {
    width: 20px;
    height: 20px;
    background-size: 100% 100%;
}

.carousel-indicators {
    bottom: 30px;
}

.carousel-indicators [data-bs-target] {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background-color: rgba(255, 255, 255, 0.5);
    border: none;
    margin: 0 4px;
    transition: var(--transition);
}

.carousel-indicators .active {
    background-color: white;
    transform: scale(1.3);
}

/* Features Section */
.features-section {
    padding: 5rem 0;
    background: var(--bg-primary);
}

.feature-card {
    background: white;
    padding: 2.5rem 2rem;
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-light);
    border: 1px solid var(--border-color);
    transition: var(--transition);
    text-align: center;
    height: 100%;
}

.feature-card:hover {
    transform: translateY(-10px);
    box-shadow: var(--shadow-heavy);
    border-color: var(--accent-color);
}

.feature-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, var(--accent-color), var(--secondary-color));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    color: white;
    font-size: 2rem;
    transition: var(--transition);
}

.feature-card:hover .feature-icon {
    transform: scale(1.1) rotate(5deg);
}

.feature-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 1rem;
}

.feature-description {
    color: var(--text-secondary);
    line-height: 1.6;
}

/* Stats Section */
.stats-section {
    padding: 4rem 0;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: white;
}

.stat-item {
    text-align: center;
    padding: 2rem 1rem;
}

.stat-number {
    font-size: 3.5rem;
    font-weight: 900;
    margin-bottom: 0.5rem;
    color: var(--accent-color);
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
}

.stat-label {
    font-size: 1.1rem;
    font-weight: 500;
    opacity: 0.9;
}

/* Products Section */
.products-section {
    padding: 6rem 0;
    background: var(--bg-secondary);
}

.section-header {
    text-align: center;
    margin-bottom: 4rem;
    max-width: 800px;
    margin-left: auto;
    margin-right: auto;
}

.section-badge {
    display: inline-block;
    background: var(--accent-color);
    color: white;
    padding: 0.5rem 1.5rem;
    border-radius: 50px;
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 1rem;
}

.section-title {
    font-size: 2.5rem;
    font-weight: 800;
    color: var(--text-primary);
    margin-bottom: 1.5rem;
    line-height: 1.3;
}

.section-subtitle {
    font-size: 1.2rem;
    color: var(--text-secondary);
    line-height: 1.6;
}

.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 2rem;
    margin-bottom: 3rem;
}

.product-card {
    background: white;
    border-radius: var(--radius-lg);
    overflow: hidden;
    box-shadow: var(--shadow-light);
    border: 1px solid var(--border-color);
    transition: var(--transition);
    position: relative;
    group: hover;
}

.product-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--accent-color), var(--secondary-color));
    transform: scaleX(0);
    transition: var(--transition);
    transform-origin: left;
    z-index: 1;
}

.product-card:hover::before {
    transform: scaleX(1);
}

.product-card:hover {
    transform: translateY(-8px);
    box-shadow: var(--shadow-heavy);
}

.product-image {
    position: relative;
    height: 220px;
    overflow: hidden;
    background: var(--bg-primary);
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: var(--transition);
}

.product-card:hover .product-image img {
    transform: scale(1.05);
}

.product-badge {
    position: absolute;
    top: 1rem;
    left: 1rem;
    background: var(--warning-color);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: var(--radius-sm);
    font-size: 0.8rem;
    font-weight: 600;
    z-index: 2;
}

.product-info {
    padding: 2rem;
}

.product-brand {
    font-size: 0.9rem;
    color: var(--text-light);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 0.5rem;
}

.product-name {
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 1rem;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.product-price {
    font-size: 1.8rem;
    font-weight: 800;
    color: var(--accent-color);
    margin-bottom: 1.5rem;
}

.product-actions {
    display: flex;
    gap: 0.75rem;
}

.btn-product {
    flex: 1;
    padding: 0.875rem 1.5rem;
    border-radius: var(--radius-md);
    font-weight: 600;
    text-decoration: none;
    text-align: center;
    transition: var(--transition);
    font-size: 0.95rem;
    border: none;
    cursor: pointer;
}

.btn-primary-product {
    background: var(--accent-color);
    color: white;
}

.btn-primary-product:hover {
    background: var(--accent-hover);
    color: white;
    transform: translateY(-2px);
}

.btn-outline-product {
    background: transparent;
    color: var(--text-secondary);
    border: 2px solid var(--border-color);
}

.btn-outline-product:hover {
    background: var(--text-secondary);
    color: white;
    border-color: var(--text-secondary);
    transform: translateY(-2px);
}

/* Categories Section */
.categories-section {
    padding: 6rem 0;
    background: white;
}

.categories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 2rem;
}

.category-card {
    position: relative;
    height: 200px;
    border-radius: var(--radius-lg);
    overflow: hidden;
    box-shadow: var(--shadow-light);
    transition: var(--transition);
    cursor: pointer;
    border: 1px solid var(--border-color);
}

.category-card:hover {
    transform: translateY(-5px) scale(1.02);
    box-shadow: var(--shadow-heavy);
}

.category-background {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    opacity: 0.9;
    transition: var(--transition);
}

.category-card:nth-child(2) .category-background {
    background: linear-gradient(135deg, #667eea, #764ba2);
}

.category-card:nth-child(3) .category-background {
    background: linear-gradient(135deg, #f093fb, #f5576c);
}

.category-card:nth-child(4) .category-background {
    background: linear-gradient(135deg, #4facfe, #00f2fe);
}

.category-card:hover .category-background {
    opacity: 1;
    transform: scale(1.05);
}

.category-content {
    position: relative;
    z-index: 2;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;
    color: white;
    padding: 2rem;
}

.category-icon {
    font-size: 3.5rem;
    margin-bottom: 1.5rem;
    opacity: 0.95;
    transition: var(--transition);
}

.category-card:hover .category-icon {
    transform: scale(1.1) rotate(5deg);
}

.category-name {
    font-size: 1.4rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.category-count {
    font-size: 0.95rem;
    opacity: 0.85;
    font-weight: 500;
}

/* Newsletter Section */
.newsletter-section {
    padding: 5rem 0;
    background: linear-gradient(135deg, var(--accent-color), var(--secondary-color));
    color: white;
    position: relative;
    overflow: hidden;
}

.newsletter-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none"><path d="M985.66,92.83C906.67,72,823.78,31,743.84,14.19c-82.26-17.34-168.06-16.33-250.45.39-57.84,11.73-114,31.07-172,41.86A600.21,600.21,0,0,1,0,27.35V120H1200V95.8C1132.19,118.92,1055.71,111.31,985.66,92.83Z" fill="rgba(255,255,255,0.1)"></path></svg>');
    background-size: cover;
    background-position: bottom;
}

.newsletter-content {
    text-align: center;
    max-width: 600px;
    margin: 0 auto;
    position: relative;
    z-index: 2;
}

.newsletter-title {
    font-size: 2.5rem;
    font-weight: 800;
    margin-bottom: 1.5rem;
}

.newsletter-subtitle {
    font-size: 1.2rem;
    opacity: 0.9;
    margin-bottom: 3rem;
    line-height: 1.6;
}

.newsletter-form {
    display: flex;
    max-width: 500px;
    margin: 0 auto;
    gap: 1rem;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border-radius: 60px;
    padding: 0.5rem;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.newsletter-input {
    flex: 1;
    padding: 1rem 1.5rem;
    border: none;
    border-radius: 50px;
    font-size: 1rem;
    background: transparent;
    color: white;
    outline: none;
}

.newsletter-input::placeholder {
    color: rgba(255, 255, 255, 0.7);
}

.newsletter-button {
    padding: 1rem 2rem;
    background: white;
    color: var(--accent-color);
    border: none;
    border-radius: 50px;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
    white-space: nowrap;
}

.newsletter-button:hover {
    background: var(--bg-tertiary);
    transform: scale(1.05);
}

/* Animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.fade-in-up {
    opacity: 0;
    transform: translateY(30px);
    transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
}

.fade-in-up.animate {
    opacity: 1;
    transform: translateY(0);
}

/* Loading States */
.loading-shimmer {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: shimmer 2s infinite;
}

@keyframes shimmer {
    0% { background-position: -200% 0; }
    100% { background-position: 200% 0; }
}

/* Responsive Design */
@media (max-width: 768px) {
    .hero-content {
        padding: 0 1rem;
    }
    
    .hero-title {
        font-size: 2.5rem;
    }
    
    .hero-subtitle {
        font-size: 1.1rem;
    }
    
    .hero-buttons {
        flex-direction: column;
        align-items: center;
    }
    
    .btn-hero {
        width: 100%;
        max-width: 280px;
    }
    
    .products-grid {
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
    }
    
    .newsletter-form {
        flex-direction: column;
        border-radius: var(--radius-lg);
        padding: 1rem;
    }
    
    .newsletter-input {
        border-radius: var(--radius-md);
        background: rgba(255, 255, 255, 0.1);
    }
    
    .newsletter-button {
        border-radius: var(--radius-md);
    }
    
    .section-title {
        font-size: 2rem;
    }
}

@media (max-width: 480px) {
    .hero-carousel {
        height: 60vh;
        min-height: 400px;
    }
    
    .feature-card,
    .stat-item {
        padding: 1.5rem 1rem;
    }
    
    .categories-grid {
        grid-template-columns: 1fr;
    }
}

/* Scroll to top button */
.scroll-top {
    position: fixed;
    bottom: 30px;
    right: 30px;
    width: 60px;
    height: 60px;
    background: var(--accent-color);
    color: white;
    border: none;
    border-radius: 50%;
    cursor: pointer;
    transition: var(--transition);
    z-index: 1000;
    opacity: 0;
    visibility: hidden;
    box-shadow: var(--shadow-medium);
}

.scroll-top.show {
    opacity: 1;
    visibility: visible;
}

.scroll-top:hover {
    background: var(--accent-hover);
    transform: translateY(-3px);
    box-shadow: var(--shadow-heavy);
}

/* Toast Notification */
.toast-notification {
    position: fixed;
    top: 100px;
    right: 20px;
    background: var(--success-color);
    color: white;
    padding: 1rem 1.5rem;
    border-radius: var(--radius-md);
    box-shadow: var(--shadow-heavy);
    z-index: 9999;
    transform: translateX(400px);
    transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    font-weight: 600;
}

.toast-notification.error {
    background: var(--danger-color);
}

.toast-notification.show {
    transform: translateX(0);
}
</style>

<!-- Hero Section -->
<section class="hero-wrapper container-fluid px-0">
    <div id="heroCarousel" class="carousel slide carousel-fade hero-carousel" data-bs-ride="carousel" data-bs-interval="5000">
        <div class="carousel-indicators">
            @for($i = 0; $i < 5; $i++)
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="{{ $i }}" 
                        class="{{ $i == 0 ? 'active' : '' }}" aria-label="Slide {{ $i + 1 }}"></button>
            @endfor
        </div>

        <div class="carousel-inner">
            @for($i = 1; $i <= 5; $i++)
            <div class="carousel-item {{ $i == 1 ? 'active' : '' }}">
                <img src="{{ asset('resimler/slide'.$i.'.png') }}" 
                     alt="Teknoloji Slider {{ $i }}" 
                     onerror="this.src='https://images.unsplash.com/photo-1518709268805-4e9042af2176?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&h=600';">
                <div class="carousel-overlay"></div>
            </div>
            @endfor
        </div>

        <div class="hero-content">
            <div class="hero-badge">
                <i class="fas fa-bolt me-2"></i>Türkiye'nin Teknoloji Merkezi
            </div>
            <h1 class="hero-title">
                Teknolojinin <span style="color: var(--accent-color);">Gücünü</span><br>
                Keşfedin
            </h1>
            <p class="hero-subtitle">
                En son teknoloji ürünleri, profesyonel hizmet ve rekabetçi fiyatlarla 
                dijital dünyanın kapılarını açıyoruz.
            </p>
            <div class="hero-buttons">
                <a href="{{ route('urun.index') }}" class="btn-hero btn-primary-hero">
                    <i class="fas fa-laptop me-2"></i>Ürünleri İncele
                </a>
                <a href="{{ route('wizard.index') }}" class="btn-hero btn-secondary-hero">
                    <i class="fas fa-cogs me-2"></i>PC Toplama
                </a>
            </div>
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Önceki</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Sonraki</span>
        </button>
    </div>
</section>

<!-- Features Section -->
<section class="features-section">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="feature-card fade-in-up">
                    <div class="feature-icon">
                        <i class="fas fa-shipping-fast"></i>
                    </div>
                    <h3 class="feature-title">Hızlı Teslimat</h3>
                    <p class="feature-description">
                        Türkiye geneline hızlı ve güvenli teslimat ile ürününüz kısa sürede elinizde.
                    </p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="feature-card fade-in-up" style="animation-delay: 0.1s">
                    <div class="feature-icon">
                        <i class="fas fa-award"></i>
                    </div>
                    <h3 class="feature-title">Kalite Garantisi</h3>
                    <p class="feature-description">
                        Sadece orijinal ve garantili ürünler. Kaliteden ödün vermiyoruz.
                    </p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="feature-card fade-in-up" style="animation-delay: 0.2s">
                    <div class="feature-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h3 class="feature-title">7/24 Destek</h3>
                    <p class="feature-description">
                        Uzman ekibimiz her zaman yanınızda. Teknik destek ve danışmanlık hizmeti.
                    </p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="feature-card fade-in-up" style="animation-delay: 0.3s">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3 class="feature-title">Güvenli Alışveriş</h3>
                    <p class="feature-description">
                        SSL sertifikası ve güvenli ödeme sistemleri ile alışverişiniz tamamen güvende.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="stats-section">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="stat-item fade-in-up">
                    <div class="stat-number" data-count="5000">0</div>
                    <div class="stat-label">Ürün Çeşidi</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-item fade-in-up" style="animation-delay: 0.1s">
                    <div class="stat-number" data-count="50000">0</div>
                    <div class="stat-label">Mutlu Müşteri</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-item fade-in-up" style="animation-delay: 0.2s">
                    <div class="stat-number" data-count="24">24</div>
                    <div class="stat-label">Saat Destek</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-item fade-in-up" style="animation-delay: 0.3s">
                    <div class="stat-number" data-count="99">0</div>
                    <div class="stat-label">% Müşteri Memnuniyeti</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Products Section -->
<section class="products-section" id="urunler">
    <div class="container">
        <div class="section-header fade-in-up">
            <div class="section-badge">
                <i class="fas fa-star me-2"></i>Öne Çıkan Ürünler
            </div>
            <h2 class="section-title">En Popüler Teknoloji Ürünleri</h2>
            <p class="section-subtitle">
                Teknoloji tutkunları için özenle seçilmiş, en yeni ve en popüler ürünleri keşfedin. 
                Kalite, performans ve fiyat avantajı bir arada.
            </p>
        </div>
        
        <div class="products-grid">
            @forelse($urunler ?? [] as $index => $urun)
            <div class="product-card fade-in-up" style="animation-delay: {{ $index * 0.1 }}s">
                @if($loop->index < 3)
                <div class="product-badge">
                    <i class="fas fa-fire me-1"></i>YENİ
                </div>
                @endif
                
                <div class="product-image">
                    <img src="{{ $urun->resim_url ?? 'https://images.unsplash.com/photo-1518709268805-4e9042af2176?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&h=300' }}" 
                         alt="{{ $urun->urun_ad }}" 
                         loading="lazy">
                </div>
                
                <div class="product-info">
                    <div class="product-brand">{{ $urun->marka ?? 'Teknoloji' }}</div>
                    <h3 class="product-name">{{ $urun->urun_ad }}</h3>
                    <div class="product-price">₺{{ number_format($urun->fiyat, 2, ',', '.') }}</div>
                    
                    <div class="product-actions">
                        <a href="{{ route('urun.incele', $urun->id) }}" class="btn-product btn-outline-product">
                            <i class="fas fa-eye me-1"></i>İncele
                        </a>
                        <button class="btn-product btn-primary-product" onclick="sepeteEkle({{ $urun->id }})">
                            <i class="fas fa-cart-plus me-1"></i>Sepete Ekle
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <!-- Loading Skeletons -->
            @for($i = 0; $i < 8; $i++)
            <div class="product-card">
                <div class="product-image loading-shimmer"></div>
                <div class="product-info">
                    <div class="product-brand loading-shimmer" style="height: 20px; margin-bottom: 10px; border-radius: 4px;"></div>
                    <div class="product-name loading-shimmer" style="height: 50px; margin-bottom: 15px; border-radius: 4px;"></div>
                    <div class="product-price loading-shimmer" style="height: 35px; margin-bottom: 20px; border-radius: 4px;"></div>
                    <div class="product-actions">
                        <div class="btn-product loading-shimmer" style="height: 45px; border-radius: 8px;"></div>
                        <div class="btn-product loading-shimmer" style="height: 45px; border-radius: 8px;"></div>
                    </div>
                </div>
            </div>
            @endfor
            @endforelse
        </div>
        
        <div class="text-center fade-in-up">
            <a href="{{ route('urun.index') }}" class="btn-hero btn-primary-hero">
                <i class="fas fa-arrow-right me-2"></i>Tüm Ürünleri Görüntüle
            </a>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="categories-section">
    <div class="container">
        <div class="section-header fade-in-up">
            <div class="section-badge">
                <i class="fas fa-layer-group me-2"></i>Kategoriler
            </div>
            <h2 class="section-title">Ürün Kategorilerimiz</h2>
            <p class="section-subtitle">
                Bilgisayar bileşenlerinden aksesuarlara kadar geniş ürün yelpazemizle 
                teknoloji ihtiyaçlarınızın tamamını karşılıyoruz.
            </p>
        </div>
        
        <div class="categories-grid">
            <div class="category-card fade-in-up" onclick="window.location.href='{{ route('urun.kategori',1) }}'">
                <div class="category-background"></div>
                <div class="category-content">
                    <i class="fas fa-microchip category-icon"></i>
                    <h3 class="category-name">İşlemciler</h3>
                    <p class="category-count">Intel & AMD Seçenekleri</p>
                </div>
            </div>
            
            <div class="category-card fade-in-up" onclick="window.location.href='{{ route('urun.kategori',2) }}'" style="animation-delay: 0.1s">
                <div class="category-background"></div>
                <div class="category-content">
                    <i class="fas fa-memory category-icon"></i>
                    <h3 class="category-name">Ekran Kartları</h3>
                    <p class="category-count">NVIDIA & AMD</p>
                </div>
            </div>
            
            <div class="category-card fade-in-up" onclick="window.location.href='{{ route('urun.kategori',3) }}'" style="animation-delay: 0.2s">
                <div class="category-background"></div>
                <div class="category-content">
                    <i class="fas fa-hdd category-icon"></i>
                    <h3 class="category-name">Depolama</h3>
                    <p class="category-content">SSD & HDD Çözümleri</p>
                </div>
            </div>
            
            <div class="category-card fade-in-up" onclick="window.location.href='{{ route('urun.kategori',4) }}'" style="animation-delay: 0.3s">
                <div class="category-background"></div>
                <div class="category-content">
                    <i class="fas fa-desktop category-icon"></i>
                    <h3 class="category-name">Anakartlar</h3>
                    <p class="category-count">Tüm Soket Tipleri</p>
                </div>
            </div>
            
            <div class="category-card fade-in-up" onclick="window.location.href='{{ route('urun.kategori',5) }}'" style="animation-delay: 0.4s">
                <div class="category-background"></div>
                <div class="category-content">
                    <i class="fas fa-bolt category-icon"></i>
                    <h3 class="category-name">Güç Kaynakları</h3>
                    <p class="category-count">Modüler & Standart</p>
                </div>
            </div>
            
            <div class="category-card fade-in-up" onclick="window.location.href='{{ route('urun.kategori',6) }}'" style="animation-delay: 0.5s">
                <div class="category-background"></div>
                <div class="category-content">
                    <i class="fas fa-fan category-icon"></i>
                    <h3 class="category-name">Soğutma</h3>
                    <p class="category-count">Hava & Su Soğutma</p>
                </div>
            </div>
            
            <div class="category-card fade-in-up" onclick="window.location.href='{{ route('urun.kategori',7) }}'" style="animation-delay: 0.6s">
                <div class="category-background"></div>
                <div class="category-content">
                    <i class="fas fa-tv category-icon"></i>
                    <h3 class="category-name">Monitörler</h3>
                    <p class="category-count">Gaming & Profesyonel</p>
                </div>
            </div>
            
            <div class="category-card fade-in-up" onclick="window.location.href='{{ route('urun.kategori',8) }}'" style="animation-delay: 0.7s">
                <div class="category-background"></div>
                <div class="category-content">
                    <i class="fas fa-keyboard category-icon"></i>
                    <h3 class="category-name">Aksesuarlar</h3>
                    <p class="category-count">Klavye, Mouse & Daha Fazlası</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Newsletter Section -->
<section class="newsletter-section">
    <div class="container">
        <div class="newsletter-content fade-in-up">
            <h2 class="newsletter-title">Teknolojiden Haberdar Olun</h2>
            <p class="newsletter-subtitle">
                Yeni ürünler, özel kampanyalar ve teknoloji haberlerinden ilk siz haberdar olun. 
                E-posta listemize katılın ve avantajları kaçırmayın.
            </p>
            
            <form class="newsletter-form" action="{{ route('register') }}" method="get">
                <input type="email" class="newsletter-input" placeholder="E-posta adresiniz..." required>
                <button type="submit" class="newsletter-button">
                    <i class="fas fa-paper-plane me-2"></i>Abone Ol
                </button>
            </form>
        </div>
    </div>
</section>

<!-- Scroll to Top Button -->
<button class="scroll-top" id="scrollTop" onclick="scrollToTop()">
    <i class="fas fa-arrow-up"></i>
</button>

<script>
// Intersection Observer for animations
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('animate');
        }
    });
}, observerOptions);

// Counter Animation
function animateCounter(element, target, suffix = '') {
    let current = 0;
    const increment = target / 100;
    const timer = setInterval(() => {
        current += increment;
        if (current >= target) {
            current = target;
            clearInterval(timer);
        }
        element.textContent = Math.floor(current).toLocaleString() + suffix;
    }, 20);
}

// Scroll to top functionality
function scrollToTop() {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}

// Show/hide scroll to top button
window.addEventListener('scroll', () => {
    const scrollTop = document.getElementById('scrollTop');
    if (window.pageYOffset > 300) {
        scrollTop.classList.add('show');
    } else {
        scrollTop.classList.remove('show');
    }
});

// Toast notification system
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `toast-notification ${type}`;
    toast.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
        ${message}
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.classList.add('show');
    }, 100);
    
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => {
            if (document.body.contains(toast)) {
                document.body.removeChild(toast);
            }
        }, 400);
    }, 3000);
}

// Cart functionality
function sepeteEkle(urunId) {
    // Check if user is authenticated
    @auth
    fetch('/sepet/ekle', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        },
        body: JSON.stringify({
            urun_id: urunId,
            adet: 1
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update cart count if element exists
            const cartCount = document.getElementById('sepet-count');
            if (cartCount) {
                cartCount.textContent = data.sepet_count;
            }
            
            showToast('Ürün sepete başarıyla eklendi!', 'success');
        } else {
            showToast('Ürün sepete eklenirken bir hata oluştu!', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Bir hata oluştu. Lütfen tekrar deneyin.', 'error');
    });
    @else
    // Redirect to login if not authenticated
    window.location.href = '{{ route('login') }}';
    @endauth
}

// Newsletter form handling
document.addEventListener('DOMContentLoaded', function() {
    // Initialize animations
    document.querySelectorAll('.fade-in-up').forEach(el => {
        observer.observe(el);
    });
    
    // Initialize counters when they become visible
    const counters = document.querySelectorAll('.stat-number');
    const counterObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && !entry.target.classList.contains('animated')) {
                const target = parseInt(entry.target.getAttribute('data-count'));
                const suffix = entry.target.textContent.includes('%') ? '%' : '+';
                animateCounter(entry.target, target, suffix);
                entry.target.classList.add('animated');
            }
        });
    });
    
    counters.forEach(counter => {
        counterObserver.observe(counter);
    });
    
    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Newsletter form submission
    const newsletterForm = document.querySelector('.newsletter-form');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            const email = this.querySelector('input[type="email"]').value;
            if (email) {
                showToast('E-posta adresiniz başarıyla kaydedildi!', 'success');
            }
        });
    }
    
    // Carousel auto-pause on hover
    const carousel = document.getElementById('heroCarousel');
    if (carousel) {
        carousel.addEventListener('mouseenter', () => {
            bootstrap.Carousel.getInstance(carousel).pause();
        });
        
        carousel.addEventListener('mouseleave', () => {
            bootstrap.Carousel.getInstance(carousel).cycle();
        });
    }
    
    // Lazy loading for images
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src || img.src;
                    img.classList.remove('loading-shimmer');
                    imageObserver.unobserve(img);
                }
            });
        });
        
        document.querySelectorAll('img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });
    }
});

// Parallax effect for hero section
window.addEventListener('scroll', () => {
    const scrolled = window.pageYOffset;
    const rate = scrolled * -0.5;
    const heroImages = document.querySelectorAll('.carousel-item img');
    
    heroImages.forEach(img => {
        if (img.closest('.carousel-item').classList.contains('active')) {
            img.style.transform = `translateY(${rate}px) scale(1.05)`;
        }
    });
});

// Performance optimization
window.addEventListener('load', () => {
    // Remove loading states
    document.querySelectorAll('.loading-shimmer').forEach(el => {
        el.classList.remove('loading-shimmer');
    });
});

// Toast notification system
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `toast-notification ${type}`;
    toast.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
        ${message}
    `;
    
    document.body.appendChild(toast);
    
    // Show toast
    setTimeout(() => toast.classList.add('show'), 100);
    
    // Hide toast after 3s
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => {
            toast.remove();
        }, 400); // transition süresi
    }, 3000);
}

// Animasyon ve counter init
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.fade-in-up').forEach(el => observer.observe(el));

    document.querySelectorAll('.stat-number').forEach(el => {
        const target = parseInt(el.dataset.count) || 0;
        animateCounter(el, target);
    });
});
</script>
@endsection