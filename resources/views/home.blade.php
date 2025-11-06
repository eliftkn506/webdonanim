@extends('layouts.app')
@section('title', 'Ana Sayfa ')

@section('content')


<div class="container"> <section class="hero-carousel"> <div id="heroCarousel" class="carousel slide carousel-fade h-100" data-bs-ride="carousel" data-bs-interval="5000">
            
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
                         onerror="this.src='https://via.placeholder.com/1920x600.png?text=Slide+Resmi+Bulunamadı';">
                </div>
                @endfor
            </div>

            <div class="hero-content-wrapper"> <div class="hero-content fade-in">
                    <div class="hero-badge">
                        <i class="fas fa-bolt me-2"></i>Türkiye'nin Teknoloji Merkezi
                    </div>
                    <h1 class="hero-title">
                        Teknolojinin <span style="color: var(--warning-color);">Gücünü</span><br> Keşfedin
                    </h1>
                    <p class="hero-subtitle">
                        En son teknoloji ürünleri, profesyonel hizmet ve rekabetçi fiyatlarla
                        dijital dünyanın kapılarını açıyoruz.
                    </p>
                    <div class="hero-buttons">
                        <a href="{{ route('urun.index') }}" class="btn-hero btn-primary-hero">
                            <i class="fas fa-laptop"></i>Ürünleri İncele
                        </a>
                        <a href="{{ route('wizard.index') }}" class="btn-hero btn-secondary-hero">
                            <i class="fas fa-cogs"></i>PC Toplama
                        </a>
                    </div>
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
</div> <section class="features-section">
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
                    <div class="stat-number" data-count="24">0</div>
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
            <div class="product-card fade-in-up" style="animation-delay: {{ $index * 0.05 }}s">
                @if($loop->index < 3)
                <div class="product-badge">
                    <i class="fas fa-fire me-1"></i>YENİ
                </div>
                @endif
                
                <div class="product-image">
                    <a href="{{ route('urun.incele', $urun->id) }}">
                         <img src="{{ $urun->resim_url ?? 'https://via.placeholder.com/400x300.png?text=Urun+Resmi' }}"
                             alt="{{ $urun->urun_ad }}"
                             loading="lazy">
                    </a>
                </div>
                
                <div class="product-info">
                    <div class="product-brand">{{ $urun->marka ?? 'Teknoloji' }}</div>
                    <h3 class="product-name">
                        <a href="{{ route('urun.incele', $urun->id) }}">{{ $urun->urun_ad }}</a>
                    </h3>
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
            @for($i = 0; $i < 8; $i++)
            <div class="product-card">
                <div class="product-image loading-shimmer" style="height: 220px;"></div>
                <div class="product-info">
                    <div class="product-brand loading-shimmer" style="height: 20px; width: 50%; margin-bottom: 10px; border-radius: 4px;"></div>
                    <div class="product-name loading-shimmer" style="height: 44px; margin-bottom: 15px; border-radius: 4px;"></div>
                    <div class="product-price loading-shimmer" style="height: 35px; width: 70%; margin-bottom: 20px; border-radius: 4px;"></div>
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
            <a href="{{ route('urun.index') }}" class="btn-hero btn-primary-hero" style="background: var(--accent-color);">
                <i class="fas fa-arrow-right me-2"></i>Tüm Ürünleri Görüntüle
            </a>
        </div>
    </div>
</section>

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
                    <p class="category-count">SSD & HDD Çözümleri</p>
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

<section class="newsletter-section">
    <div class="container">
        <div class="newsletter-content fade-in-up">
            <h2 class="newsletter-title">Teknolojiden Haberdar Olun</h2>
            <p class="newsletter-subtitle">
                Yeni ürünler, özel kampanyalar ve teknoloji haberlerinden ilk siz haberdar olun.
                E-posta listemize katılın ve avantajları kaçırmayın.
            </p>
            
            <form class="newsletter-form">
                <input type="email" class="newsletter-input" placeholder="E-posta adresiniz..." required>
                <button type="submit" class="newsletter-button">
                    <i class="fas fa-paper-plane me-2"></i>Abone Ol
                </button>
            </form>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
// Sepete Ekleme Fonksiyonu
function sepeteEkle(urunId) {
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
            showToast('Ürün sepetinize eklendi!', 'success');
            document.getElementById('cartCount').textContent = data.cart_count;
            updateCartDropdown(data.cart_item, urunId);
        } else {
            showToast(data.message || 'Bir hata oluştu', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Sunucu hatası oluştu', 'error');
    });
}

// Sepet dropdown'ını güncelleyen yardımcı fonksiyon
function updateCartDropdown(item, urunId) {
    const cartItemsContainer = document.getElementById('cartItems');
    const emptyCartMessage = cartItemsContainer.querySelector('.cart-empty');
    
    if (emptyCartMessage) {
        emptyCartMessage.remove();
    }
    
    let cartFooter = document.querySelector('.cart-dropdown .cart-footer');
    if (!cartFooter) {
        cartFooter = document.createElement('div');
        cartFooter.className = 'cart-footer';
        cartFooter.innerHTML = `
            <a href="{{ route('sepet.index') }}" class="btn-modern btn-primary w-100">
                <i class="fas fa-shopping-bag me-2"></i>Sepete Git
            </a>
        `;
        document.getElementById('cartDropdown').appendChild(cartFooter);
    }

    let existingItem = cartItemsContainer.querySelector(`.cart-item[data-id="${urunId}"]`);
    if (existingItem) {
        if (item.adet && item.fiyat) {
             existingItem.querySelector('.cart-item-details').textContent = 
                `${item.adet} adet × ₺${parseFloat(item.fiyat).toLocaleString('tr-TR', {minimumFractionDigits: 2})}`;
        }
    } else {
        const newItem = document.createElement('div');
        newItem.className = 'cart-item';
        newItem.setAttribute('data-id', urunId);
        newItem.innerHTML = `
            <div class="cart-item-image">
                <i class="fas fa-microchip"></i>
            </div>
            <div class="cart-item-info">
                <div class="cart-item-name">${item.urun_ad}</div>
                <div class="cart-item-details">
                    ${item.adet} adet × ₺${parseFloat(item.fiyat).toLocaleString('tr-TR', {minimumFractionDigits: 2})}
                </div>
            </div>
            <button class="cart-item-remove" onclick="removeFromCart(${urunId})">
                <i class="fas fa-times"></i>
            </button>
        `;
        cartItemsContainer.appendChild(newItem);
    }
}
</script>
@endpush