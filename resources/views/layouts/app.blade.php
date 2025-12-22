<!doctype html>
<html lang="tr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', config('app.name', 'Avantaj'))</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

<style>
:root {
    --primary-color: #1a365d;
    --primary-light: #2b77cb;
    --secondary-color: #22987e;
    --accent-color: #3182ce;
    --accent-hover: #2563eb;
    --success-color: #10b981;
    --warning-color: #f59e0b;
    --danger-color: #ef4444;
    --text-primary: #1f2937;
    --text-secondary: #6b7280;
    --text-light: #9ca3af;
    --bg-primary: #ffffff;
    --bg-secondary: #f8fafc;
    --bg-tertiary: #f1f5f9;
    --border-color: #e5e7eb;
    --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
    --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
    --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1);
    --radius-sm: 0.375rem;
    --radius-md: 0.5rem;
    --radius-lg: 0.75rem;
    --radius-xl: 1rem;
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

* { margin: 0; padding: 0; box-sizing: border-box; }

body {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    background: var(--bg-secondary);
    color: var(--text-primary);
    line-height: 1.6;
    font-weight: 400;
}

/* Arama input temizliği */
.search-input::-webkit-search-decoration,
.search-input::-webkit-search-cancel-button,
.search-input::-webkit-search-results-button,
.search-input::-webkit-search-results-decoration { -webkit-appearance: none; appearance: none; }

/* ========== NAVBAR DÜZENİ (FLEX & SPACE) ========== */
/* Elemanların sıkışmasını önleyen kritik ayarlar */
.navbar-left-section, 
.navbar-right-section {
    flex-shrink: 0; /* Bu alanlar asla küçülmesin */
}

/* Arama Çubuğu Ayarları */
.search-wrapper {
    position: relative;
    width: 100%;
    max-width: 600px;
    min-width: 300px; /* Minimum genişlik */
    flex-grow: 1; /* Boş alanı kapla */
    margin: 0 1.5rem;
}

.search-form {
    position: relative;
    display: flex;
    align-items: center;
    width: 100%;
}

.search-input {
    width: 100%;
    padding: 0.875rem 90px 0.875rem 3.5rem; /* Sağ boşluk buton için */
    border: 2px solid var(--border-color);
    border-radius: 50px;
    background: var(--bg-tertiary);
    font-size: 0.95rem;
    font-weight: 500;
    transition: var(--transition);
    outline: none;
    height: 48px;
}

.search-input:focus {
    border-color: var(--accent-color);
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
    background: var(--bg-primary);
}

.search-input::placeholder { color: var(--text-light); font-weight: 400; }

.search-icon {
    position: absolute;
    left: 1.25rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-light);
    font-size: 1.1rem;
    z-index: 2;
    pointer-events: none;
}

.search-btn {
    position: absolute;
    right: 6px;
    top: 50%;
    transform: translateY(-50%);
    background: linear-gradient(135deg, var(--accent-color), var(--primary-light));
    color: white;
    border: none;
    border-radius: 50px;
    padding: 0 1.5rem;
    height: 36px;
    font-weight: 600;
    font-size: 0.9rem;
    cursor: pointer;
    transition: var(--transition);
    box-shadow: var(--shadow-sm);
    z-index: 2;
    display: flex;
    align-items: center;
    justify-content: center;
}

.search-btn:hover {
    background: linear-gradient(135deg, var(--accent-hover), var(--primary-color));
    box-shadow: var(--shadow-md);
}

/* ========== HERO CAROUSEL ========== */
.hero-carousel {
    position: relative; height: 500px; color: white; margin-top: 1.5rem;
    border-radius: var(--radius-xl); overflow: hidden; box-shadow: var(--shadow-lg);
}
.hero-carousel .carousel, .hero-carousel .carousel-inner, .hero-carousel .carousel-item { height: 100%; }
.hero-carousel .carousel-item img { width: 100%; height: 100%; object-fit: cover; }
.hero-carousel .carousel-item::after {
    content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%;
    background: linear-gradient(90deg, rgba(26, 54, 93, 0.7) 0%, rgba(34, 152, 126, 0.6) 100%);
}
.hero-content-wrapper { position: absolute; top: 0; left: 0; right: 0; bottom: 0; z-index: 10; padding: 0 5%; }
.hero-content { position: relative; top: 50%; transform: translateY(-50%); max-width: 600px; }
.hero-title { font-size: 3.5rem; font-weight: 900; line-height: 1.2; margin-bottom: 1.5rem; text-shadow: 0 2px 4px rgba(0,0,0,0.2); }
.hero-subtitle { font-size: 1.15rem; font-weight: 400; margin-bottom: 2.5rem; max-width: 500px; color: rgba(255, 255, 255, 0.9); }
.btn-hero {
    padding: 0.875rem 2rem; font-weight: 700; border-radius: 50px; text-decoration: none;
    transition: var(--transition); border: none; font-size: 1rem; box-shadow: var(--shadow-md);
    display: inline-flex; align-items: center; gap: 0.5rem;
}
.btn-primary-hero { background: var(--warning-color); color: white; }
.btn-primary-hero:hover { background: #ffbe33; color: white; transform: translateY(-3px); }
.btn-secondary-hero { background: rgba(255, 255, 255, 0.1); border: 2px solid white; color: white; margin-left: 1rem; }
.btn-secondary-hero:hover { background: white; color: var(--primary-color); transform: translateY(-3px); }

/* ========== TOP BAR ========== */
.top-bar {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: white; padding: 0.5rem 0; font-size: 0.875rem; position: relative; overflow: hidden;
}
.top-bar-content { display: flex; justify-content: space-between; align-items: center; }
.top-bar-left { display: flex; gap: 2rem; align-items: center; }
.top-bar-item { display: flex; align-items: center; gap: 0.5rem; color: rgba(255, 255, 255, 0.9); text-decoration: none; transition: var(--transition); }
.top-bar-item:hover { color: white; }
.top-bar-right { display: flex; gap: 0.75rem; align-items: center; }
.social-link {
    display: flex; align-items: center; justify-content: center; width: 32px; height: 32px;
    background: rgba(255, 255, 255, 0.1); color: white; border-radius: 50%; text-decoration: none;
    font-size: 0.9rem; transition: var(--transition);
}
.social-link:hover { background: rgba(255, 255, 255, 0.2); transform: scale(1.1); }
.top-bar-divider { width: 1px; height: 20px; background: rgba(255, 255, 255, 0.2); margin: 0 0.5rem; }
.top-bar-auth-link {
    display: flex; align-items: center; gap: 0.5rem; color: white; text-decoration: none; font-weight: 600;
    font-size: 0.875rem; padding: 0.25rem 0.75rem; border-radius: var(--radius-sm); transition: var(--transition); border: 1px solid transparent;
}
.top-bar-auth-link:hover { background: rgba(255, 255, 255, 0.1); }
.top-bar-auth-link.top-bar-auth-register { background: var(--warning-color); border-color: var(--warning-color); }
.top-bar-auth-link.top-bar-auth-register:hover { background: #ffbe33; }

/* ========== MAIN NAVBAR ========== */
.main-navbar {
    background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(20px); border-bottom: 1px solid var(--border-color);
    padding: 1rem 0; position: sticky; top: 0; z-index: 1030; transition: var(--transition); box-shadow: var(--shadow-sm);
}
.main-navbar.scrolled { padding: 0.75rem 0; background: rgba(255, 255, 255, 0.98); box-shadow: var(--shadow-md); }

/* Navigation Items */
.nav-menu { display: flex; align-items: center; gap: 0.5rem; list-style: none; margin: 0; padding: 0; }
.nav-link-modern {
    display: flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1rem; color: var(--text-primary);
    text-decoration: none; font-weight: 500; font-size: 0.95rem; border-radius: var(--radius-md); transition: var(--transition); position: relative; white-space: nowrap;
}
.nav-link-modern:hover, .nav-link-modern.active { color: var(--accent-color); background: rgba(59, 130, 246, 0.05); }
.nav-link-modern.btn-outline { border: 2px solid var(--border-color); font-weight: 600; }

/* YENİ: KURUMSAL DROPDOWN CSS */
.corporate-menu { position: relative; }
.corporate-dropdown {
    position: absolute; top: calc(100% + 1rem); left: 0; min-width: 220px;
    background: var(--bg-primary); border: 1px solid var(--border-color);
    border-radius: var(--radius-lg); box-shadow: var(--shadow-xl); padding: 0.75rem;
    opacity: 0; visibility: hidden; transform: translateY(-10px); transition: var(--transition); z-index: 1000;
}
.corporate-menu.show .corporate-dropdown { opacity: 1; visibility: visible; transform: translateY(0); }
.corporate-link {
    display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem;
    color: var(--text-secondary); text-decoration: none; font-weight: 500;
    border-radius: var(--radius-md); transition: var(--transition);
}
.corporate-link:hover { color: var(--accent-color); background: var(--bg-tertiary); transform: translateX(5px); }

/* Mega Menu (Products) */
.mega-dropdown { position: relative; }
.mega-menu {
    position: absolute; top: calc(100% + 1rem); left: 50%; transform: translateX(-50%);
    min-width: 900px; background: var(--bg-primary); border: 1px solid var(--border-color);
    border-radius: var(--radius-lg); box-shadow: var(--shadow-xl); padding: 2.5rem; opacity: 0;
    visibility: hidden; transition: var(--transition); z-index: 1000;
}
.mega-dropdown.show .mega-menu { opacity: 1; visibility: visible; transform: translateX(-50%) translateY(0); }
.mega-menu-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 2rem; }
.category-header { display: flex; align-items: center; gap: 0.75rem; font-weight: 700; font-size: 1.1rem; color: var(--primary-color); margin-bottom: 1rem; text-decoration: none; }
.subcategory-list { list-style: none; margin: 0; padding: 0; }
.subcategory-link { display: block; padding: 0.5rem 0; color: var(--text-secondary); text-decoration: none; transition: 0.2s; }
.subcategory-link:hover { color: var(--accent-color); transform: translateX(5px); }

/* Action Buttons */
.action-buttons { display: flex; align-items: center; gap: 0.75rem; }
.cart-button {
    position: relative; display: flex; align-items: center; justify-content: center; width: 48px; height: 48px;
    background: var(--bg-primary); border: 2px solid var(--border-color); border-radius: 50%;
    color: var(--text-primary); text-decoration: none; font-size: 1.2rem; transition: var(--transition);
}
.cart-button:hover { color: var(--accent-color); border-color: var(--accent-color); }
.cart-badge {
    position: absolute; top: -8px; right: -8px; background: var(--danger-color); color: white;
    font-size: 0.75rem; font-weight: 700; padding: 0.25rem 0.5rem; border-radius: 50px; min-width: 24px;
}

/* User Avatar */
.user-menu { position: relative; }
.user-avatar {
    width: 48px; height: 48px; background: linear-gradient(135deg, var(--accent-color), var(--secondary-color));
    color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: 1.1rem; cursor: pointer; border: 3px solid white; box-shadow: var(--shadow-sm);
}
.user-dropdown {
    position: absolute; top: calc(100% + 1rem); right: 0; min-width: 280px; background: var(--bg-primary);
    border: 1px solid var(--border-color); border-radius: var(--radius-lg); box-shadow: var(--shadow-xl);
    padding: 1.5rem; opacity: 0; visibility: hidden; transition: var(--transition); z-index: 1000;
}
.user-menu.show .user-dropdown { opacity: 1; visibility: visible; transform: translateY(0); }
.user-menu-link {
    display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; color: var(--text-secondary);
    text-decoration: none; font-weight: 500; border-radius: var(--radius-md); transition: var(--transition);
}
.user-menu-link:hover { color: var(--accent-color); background: var(--bg-tertiary); }

/* Cart Dropdown */
.cart-menu { position: relative; }
.cart-dropdown {
    position: absolute; top: calc(100% + 1rem); right: 0; min-width: 350px; max-width: 400px;
    background: var(--bg-primary); border: 1px solid var(--border-color); border-radius: var(--radius-lg);
    box-shadow: var(--shadow-xl); opacity: 0; visibility: hidden; z-index: 1000;
}
.cart-menu.show .cart-dropdown { opacity: 1; visibility: visible; transform: translateY(0); }
.cart-items { max-height: 300px; overflow-y: auto; padding: 1rem; }
.cart-item { display: flex; gap: 1rem; padding: 1rem; border-bottom: 1px solid var(--border-color); align-items: center; }
.cart-empty { padding: 2rem; text-align: center; color: var(--text-light); }

/* Mobile Menu */
.mobile-toggle { background: none; border: none; font-size: 1.5rem; color: var(--text-primary); cursor: pointer; }
.mobile-menu {
    position: absolute; top: 100%; left: 0; right: 0; background: var(--bg-primary);
    border-bottom: 1px solid var(--border-color); max-height: 0; overflow: hidden; transition: var(--transition);
}
.mobile-menu.show { max-height: 600px; padding: 2rem 0; box-shadow: var(--shadow-lg); }
.mobile-nav { list-style: none; padding: 0 1.5rem; margin: 0; }
.mobile-nav-link { display: flex; align-items: center; gap: 1rem; padding: 1rem; color: var(--text-primary); text-decoration: none; font-weight: 500; border-bottom: 1px solid var(--border-color); }

/* Footer */
.footer-modern { background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); color: white; padding: 4rem 0 2rem; margin-top: 5rem; }
.footer-grid { display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 3rem; }
.footer-links { list-style: none; padding: 0; }
.footer-links a { color: rgba(255,255,255,0.8); text-decoration: none; }
.footer-bottom { text-align: center; padding-top: 2rem; border-top: 1px solid rgba(255,255,255,0.1); margin-top: 2rem; }

/* Responsive */
@media (max-width: 1200px) { .mega-menu { min-width: 800px; } }
@media (max-width: 991px) {
    .top-bar { display: none; }
    .nav-menu { display: none !important; }
    .mobile-toggle { display: block !important; }
    .search-wrapper { margin: 0.5rem 0; min-width: auto; max-width: 100%; order: 3; width: 100%; display: none !important; } /* Mobilde üst bar aramasını gizle, menüde göster */
    .mobile-menu .search-wrapper { display: block !important; }
    .footer-grid { grid-template-columns: 1fr; gap: 2rem; }
}
</style>
</head>
<body>

<div class="top-bar d-none d-lg-block">
    <div class="container">
        <div class="top-bar-content">
            <div class="top-bar-left">
                <a href="tel:+908505333444" class="top-bar-item"><i class="fas fa-phone"></i><span>+90 850 533 3444</span></a>
                <a href="mailto:bilgi@avantajbilisim.com" class="top-bar-item"><i class="fas fa-envelope"></i><span>bilgi@avantajbilisim.com</span></a>
                <span class="top-bar-item"><i class="fas fa-clock"></i><span>7/24 Müşteri Hizmetleri</span></span>
            </div>
            <div class="top-bar-right">
                <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                <a href="#" class="social-link"><i class="fab fa-youtube"></i></a>
                
                @guest
                <span class="top-bar-divider"></span>
                <a href="{{ route('login') }}" class="top-bar-auth-link"><i class="fas fa-sign-in-alt"></i> Giriş Yap</a>
                <a href="{{ route('register') }}" class="top-bar-auth-link top-bar-auth-register"><i class="fas fa-user-plus"></i> Kayıt Ol</a>
                @endguest
            </div>
        </div>
    </div>
</div>

<nav class="main-navbar">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between flex-nowrap">
            
            <div class="d-flex align-items-center navbar-left-section" style="gap: 20px;">
                <a href="/" class="navbar-brand">
                    <img src="{{ asset('resimler/logo3.png') }}" alt="Logo" style="height: 40px; width: auto;">
                </a>
                
                <ul class="nav-menu d-none d-lg-flex">
                    <li class="nav-item-modern">
                        <a href="{{ route('home') }}" class="nav-link-modern {{ Route::is('home') ? 'active' : '' }}">
                            <i class="fas fa-home"></i> Anasayfa
                        </a>
                    </li>
                    <li class="nav-item-modern mega-dropdown">
                        <a href="#" class="nav-link-modern dropdown-toggle-arrow">
                            <i class="fas fa-microchip"></i> Ürünler
                        </a>
                        <div class="mega-menu">
                            <div class="mega-menu-grid">
                                <div class="category-column">
                                    <a href="{{ route('urun.index') }}" class="category-header">
                                        <div class="category-icon"><i class="fas fa-th-large"></i></div>
                                        Tüm Ürünler
                                    </a>
                                </div>
                                @php $kategoriler = \App\Models\Kategori::with('altKategoriler')->get(); @endphp
                                @foreach($kategoriler->take(3) as $kategori)
                                <div class="category-column">
                                    <a href="{{ route('urun.kategori', $kategori->id) }}" class="category-header">
                                        <div class="category-icon"><i class="fas fa-{{ $kategori->icon ?? 'microchip' }}"></i></div>
                                        {{ $kategori->kategori_ad }}
                                    </a>
                                    <ul class="subcategory-list">
                                        @foreach($kategori->altKategoriler->take(6) as $alt)
                                        <li class="subcategory-item">
                                            <a href="{{ route('urun.altkategori', $alt->id) }}" class="subcategory-link">
                                                <i class="fas fa-angle-right"></i> {{ $alt->alt_kategori_ad }}
                                            </a>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </li>
                    
                    <li class="nav-item-modern corporate-menu" id="corporateMenu">
                        <a href="#" class="nav-link-modern dropdown-toggle-arrow" id="corporateToggle">
                            <i class="fas fa-building"></i> Kurumsal
                        </a>
                        <div class="corporate-dropdown" id="corporateDropdown">
                            <a href="{{ route('hakkimizda') }}" class="corporate-link">
                                <i class="fas fa-info-circle"></i> Hakkımızda
                            </a>
                            <a href="{{ route('iletisim') }}" class="corporate-link">
                                <i class="fas fa-envelope"></i> İletişim
                            </a>
                        </div>
                    </li>

                    @auth
                    <li class="nav-item-modern">
                        <a href="{{ route('wizard.index') }}" class="nav-link-modern btn-outline">
                            <i class="fas fa-magic"></i> PC Toplama
                        </a>
                    </li>
                    @endauth
                </ul>
            </div>
            
            <div class="search-wrapper d-none d-lg-block">
                <form method="GET" action="{{ route('urun.ara') }}" class="search-form">
                    <i class="fas fa-search search-icon"></i>
                    <input type="search" name="q" class="search-input" placeholder="Ürün ara...">
                    <button type="submit" class="search-btn">
                        <i class="fas fa-search me-1"></i>Ara
                    </button>
                </form>
            </div>
            
            <div class="d-flex align-items-center gap-2 navbar-right-section">
                <div class="action-buttons">
                    <div class="cart-menu" id="cartMenu">
                        <a href="#" class="cart-button" id="cartToggle">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="cart-badge" id="cartCount">{{ session('sepet') ? count(session('sepet')) : 0 }}</span>
                        </a>
                        <div class="cart-dropdown" id="cartDropdown">
                            <div class="cart-header"><h6 class="cart-title">Alışveriş Sepeti</h6></div>
                            <div class="cart-items" id="cartItems">
                                @if(session('sepet') && count(session('sepet')) > 0)
                                    @foreach(session('sepet') as $urunId => $urun)
                                    <div class="cart-item" data-id="{{ $urunId }}">
                                        <div class="cart-item-image"><i class="fas fa-microchip"></i></div>
                                        <div class="cart-item-info">
                                            <div class="cart-item-name">{{ $urun['urun_ad'] }}</div>
                                            <div class="cart-item-details">{{ $urun['adet'] }} adet × ₺{{ number_format($urun['fiyat'], 2, ',', '.') }}</div>
                                        </div>
                                        <button class="cart-item-remove" onclick="removeFromCart({{ $urunId }})"><i class="fas fa-times"></i></button>
                                    </div>
                                    @endforeach
                                @else
                                    <div class="cart-empty"><i class="fas fa-shopping-cart fa-2x mb-2"></i><p>Sepetiniz boş</p></div>
                                @endif
                            </div>
                            @if(session('sepet') && count(session('sepet')) > 0)
                            <div class="cart-footer">
                                <a href="{{ route('sepet.index') }}" class="btn-modern btn-primary w-100"><i class="fas fa-shopping-bag me-2"></i>Sepete Git</a>
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    @guest
                        @else
                    <div class="user-menu" id="userMenu">
                        <div class="user-avatar" id="userToggle">
                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                        </div>
                        <div class="user-dropdown" id="userDropdown">
                            <div class="user-info">
                                <div class="user-name">{{ Auth::user()->name }}</div>
                                <div class="user-email">{{ Auth::user()->email }}</div>
                            </div>
                            @if(Auth::user()->role === 'admin')
                                <a href="{{ route('admin.dashboard') }}" class="user-menu-link"><i class="fas fa-tachometer-alt"></i> Admin Paneli</a>
                            @else
                                <a href="{{ route('profil') }}" class="user-menu-link"><i class="fas fa-user"></i> Profilim</a>
                            @endif
                            <a href="{{ route('sepet.index') }}" class="user-menu-link"><i class="fas fa-shopping-bag"></i> Sepetim</a>
                            @auth
                            <a href="{{ route('wizard.index') }}" class="user-menu-link"><i class="fas fa-magic"></i> PC Toplama</a>
                            @endauth
                            <hr class="my-2">
                            <a href="{{ route('logout') }}" class="user-menu-link danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt"></i> Çıkış Yap
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                        </div>
                    </div>
                    @endguest
                </div>
                
                <button class="mobile-toggle d-lg-none ms-2" id="mobileToggle">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
        
        <div class="mobile-menu" id="mobileMenu">
            <div class="container">
                <div class="mb-4">
                    <form method="GET" action="{{ route('urun.ara') }}" class="search-form">
                        <i class="fas fa-search search-icon"></i>
                        <input type="search" name="q" class="search-input" placeholder="Ürün ara...">
                        <button type="submit" class="search-btn">Ara</button>
                    </form>
                </div>
                <ul class="mobile-nav">
                    <li class="mobile-nav-item"><a href="{{ route('home') }}" class="mobile-nav-link"><i class="fas fa-home"></i> Anasayfa</a></li>
                    <li class="mobile-nav-item"><a href="{{ route('urun.index') }}" class="mobile-nav-link"><i class="fas fa-microchip"></i> Tüm Ürünler</a></li>
                    <li class="mobile-nav-item"><a href="{{ route('hakkimizda') }}" class="mobile-nav-link"><i class="fas fa-info-circle"></i> Hakkımızda</a></li>
                    <li class="mobile-nav-item"><a href="{{ route('iletisim') }}" class="mobile-nav-link"><i class="fas fa-envelope"></i> İletişim</a></li>
                    @auth
                    <li class="mobile-nav-item"><a href="{{ route('wizard.index') }}" class="mobile-nav-link"><i class="fas fa-magic"></i> PC Toplama</a></li>
                    @endauth
                    @guest
                    <li class="mobile-nav-item mt-3">
                         <a href="{{ route('login') }}" class="btn-modern btn-primary w-100"><i class="fas fa-sign-in-alt"></i> Giriş Yap</a>
                    </li>
                      <li class="mobile-nav-item">
                        <a href="{{ route('register') }}" class="btn-modern btn-warning w-100"><i class="fas fa-user-plus"></i> Kayıt Ol</a>
                    </li>
                    @endguest
                </ul>
            </div>
        </div>
    </div>
</nav>

<main class="main-content">
    @yield('content')
</main>

<footer class="footer-modern">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-brand">
                <div class="footer-logo"><i class="fas fa-microchip"></i> Avantaj Bilişim</div>
                <p class="footer-description">Türkiye'nin en büyük teknoloji tedarikçisi.</p>
                <div class="footer-social">
                    <a href="#" class="footer-social-link"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="footer-social-link"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="footer-social-link"><i class="fab fa-twitter"></i></a>
                </div>
            </div>
            <div class="footer-column">
                <h5>Hızlı Erişim</h5>
                <ul class="footer-links">
                    <li><a href="{{ route('home') }}">Anasayfa</a></li>
                    <li><a href="{{ route('urun.index') }}">Ürünler</a></li>
                    <li><a href="{{ route('hakkimizda') }}">Hakkımızda</a></li>
                    <li><a href="{{ route('iletisim') }}">İletişim</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h5>Müşteri Hizmetleri</h5>
                <ul class="footer-links">
                    <li><a href="#">Sipariş Takibi</a></li>
                    <li><a href="#">İade ve Değişim</a></li>
                    <li><a href="#">Sıkça Sorulanlar</a></li>
                </ul>
            </div>
            <div class="footer-column footer-contact">
                <h5>Bize Ulaşın</h5>
                <p><i class="fas fa-phone"></i> 0850 533 3444</p>
                <p><i class="fas fa-envelope"></i> bilgi@avantajbilisim.com</p>
                <p><i class="fas fa-map-marker-alt"></i> İstanbul, Türkiye</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} Avantaj Bilişim. Tüm hakları saklıdır.</p>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Navbar & Dropdown Scripts
window.addEventListener('scroll', function() {
    const navbar = document.querySelector('.main-navbar');
    if (window.scrollY > 20) { navbar.classList.add('scrolled'); } else { navbar.classList.remove('scrolled'); }
});

function initializeDropdown(menuId, toggleId) {
    const menu = document.getElementById(menuId);
    const toggle = document.getElementById(toggleId);
    if (!menu || !toggle) return;
    toggle.addEventListener('click', function(e) {
        e.preventDefault(); e.stopPropagation();
        closeAllDropdowns(menuId); menu.classList.toggle('show');
    });
}

function closeAllDropdowns(exceptMenuId = null) {
    // Kurumsal menü de eklendi
    const dropdowns = document.querySelectorAll('.mega-dropdown, .cart-menu, .user-menu, .corporate-menu');
    dropdowns.forEach(dropdown => {
        let currentId = dropdown.id;
        // Mega dropdown ID kontrolü opsiyonel
        if (currentId !== exceptMenuId) dropdown.classList.remove('show');
    });
}

document.addEventListener('click', function(e) {
    if (!e.target.closest('.mega-dropdown') && 
        !e.target.closest('.cart-menu') && 
        !e.target.closest('.user-menu') &&
        !e.target.closest('.corporate-menu')) {
        closeAllDropdowns();
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const megaDropdown = document.querySelector('.mega-dropdown');
    if (megaDropdown) {
        const megaToggle = megaDropdown.querySelector('.dropdown-toggle-arrow');
        megaDropdown.id = megaDropdown.id || 'megaMenuMain';
        megaToggle.addEventListener('click', function(e) {
            e.preventDefault(); e.stopPropagation();
            closeAllDropdowns(megaDropdown.id); megaDropdown.classList.toggle('show');
        });
    }
    initializeDropdown('cartMenu', 'cartToggle');
    initializeDropdown('userMenu', 'userToggle');
    initializeDropdown('corporateMenu', 'corporateToggle'); // Yeni kurumsal menü başlatıcısı
    
    const mobileToggle = document.getElementById('mobileToggle');
    const mobileMenu = document.getElementById('mobileMenu');
    if (mobileToggle && mobileMenu) {
        mobileToggle.addEventListener('click', function() {
            mobileMenu.classList.toggle('show');
            const icon = mobileToggle.querySelector('i');
            if (mobileMenu.classList.contains('show')) {
                icon.classList.remove('fa-bars'); icon.classList.add('fa-times');
            } else {
                icon.classList.remove('fa-times'); icon.classList.add('fa-bars');
            }
        });
    }
});

// Cart & Toast Logic
function removeFromCart(productId) {
    fetch(`/sepet/sil/${productId}`, {
        method: 'DELETE',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('cartCount').textContent = data.cart_count;
            const cartItem = document.querySelector(`.cart-item[data-id="${productId}"]`);
            if (cartItem) cartItem.remove();
            if (data.cart_count === 0) {
                document.getElementById('cartItems').innerHTML = `<div class="cart-empty"><i class="fas fa-shopping-cart fa-2x mb-2"></i><p>Sepetiniz boş</p></div>`;
                const cartFooter = document.querySelector('.cart-dropdown .cart-footer');
                if(cartFooter) cartFooter.remove();
            }
            showToast('Ürün sepetten kaldırıldı', 'success');
        }
    });
}

function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = 'toast-notification';
    toast.style.cssText = `position: fixed; bottom: 20px; right: 20px; background: ${type === 'success' ? 'var(--success-color)' : 'var(--danger-color)'}; color: white; padding: 1rem 1.5rem; border-radius: var(--radius-md); box-shadow: var(--shadow-lg); z-index: 9999; transform: translateY(100px); opacity: 0; transition: all 0.4s cubic-bezier(0.21, 1.02, 0.73, 1); font-weight: 600;`;
    toast.textContent = message;
    document.body.appendChild(toast);
    setTimeout(() => { toast.style.transform = 'translateY(0)'; toast.style.opacity = '1'; }, 100);
    setTimeout(() => { toast.style.transform = 'translateY(100px)'; toast.style.opacity = '0'; setTimeout(() => { if (document.body.contains(toast)) document.body.removeChild(toast); }, 400); }, 3000);
}
</script>
@stack('scripts')
</body>
</html>