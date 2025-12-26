<!doctype html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Avantaj Bilişim'))</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* --- GLOBAL RENK PALETİ --- */
        :root {
            --primary-gradient: linear-gradient(135deg, #00d4aa 0%, #00a896 100%);
            --secondary-gradient: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            
            --primary-color: #00d4aa;
            --primary-dark: #00a896;
            --secondary-color: #1e293b;
            
            --text-main: #1e293b;
            --text-muted: #64748b;
            --bg-light: #f8fafc;
            --border-color: #e2e8f0;
            
            --radius-md: 0.75rem;
            --radius-xl: 1.25rem;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-light);
            color: var(--text-main);
            line-height: 1.6;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* ========== TOP BAR ========== */
        .top-bar {
            background-color: var(--secondary-color);
            color: white;
            padding: 10px 0;
            font-size: 0.85rem;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        .top-bar-content { display: flex; justify-content: space-between; align-items: center; }
        .top-bar-left { display: flex; gap: 20px; align-items: center; }
        
        .top-bar-item { 
            color: rgba(255, 255, 255, 0.85); 
            text-decoration: none; 
            transition: var(--transition); 
            display: flex; 
            align-items: center; 
            gap: 8px;
            font-weight: 500;
        }
        .top-bar-item:hover { color: var(--primary-color); }

        .top-bar-cart-pill {
            background: rgba(0, 212, 170, 0.1);
            padding: 6px 18px;
            border-radius: 50px;
            color: white !important;
            font-weight: 700;
            border: 1px solid rgba(0, 212, 170, 0.3);
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            transition: var(--transition);
        }
        .top-bar-cart-pill:hover { 
            background: var(--primary-gradient); 
            border-color: transparent;
            transform: translateY(-1px);
        }

        .top-bar-auth-btn {
            color: white; font-weight: 700; text-decoration: none; padding: 6px 20px; border-radius: 50px;
            border: 2px solid rgba(255,255,255,0.2); transition: var(--transition); font-size: 0.8rem;
        }
        .top-bar-auth-register { background: var(--primary-gradient); border: none; }

        /* ========== MAIN NAVBAR ========== */
        .main-navbar {
            background: white;
            padding: 15px 0;
            position: sticky;
            top: 0;
            z-index: 1030;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            border-bottom: 1px solid var(--border-color);
            margin-bottom: 0 !important;
        }

        .navbar-brand img { height: 48px; width: auto; }

        .nav-menu-list { display: flex; align-items: center; gap: 4px; list-style: none; margin: 0; padding: 0; }
        
        .nav-link-modern {
            padding: 10px 18px; 
            color: var(--secondary-color); 
            text-decoration: none;
            font-weight: 700; 
            font-size: 15px; 
            border-radius: 12px; 
            transition: var(--transition);
            white-space: nowrap;
            display: flex;
            align-items: center;
            gap: 8px;
            border: none;
            background: transparent;
        }
        .nav-link-modern:hover, .nav-link-modern.active { 
            color: var(--primary-color); 
            background: #f0fdfa; 
        }

        .dropdown-menu-modern {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            padding: 10px;
            margin-top: 10px !important;
        }
        .dropdown-item-modern {
            border-radius: 10px;
            padding: 10px 15px;
            font-weight: 600;
            color: var(--secondary-color);
            transition: var(--transition);
        }
        .dropdown-item-modern:hover { background-color: #f0fdfa; color: var(--primary-color); transform: translateX(5px); }

        .search-wrapper-modern { flex: 1; max-width: 380px; position: relative; margin: 0 20px; }
        .search-input-modern {
            width: 100%; height: 48px; padding: 0 50px 0 20px;
            background: #f1f5f9; border: 2px solid transparent; border-radius: 50px;
            font-size: 14px; transition: var(--transition); outline: none;
        }
        .search-input-modern:focus { background: white; border-color: var(--primary-color); }
        .search-btn-modern {
            position: absolute; right: 6px; top: 5px; height: 38px; width: 38px;
            background: var(--secondary-color); color: white; border: none; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
        }

        .user-avatar-pill {
            width: 48px; height: 48px; 
            background: var(--primary-gradient); color: white;
            border-radius: 14px; display: flex; align-items: center; justify-content: center;
            font-weight: 800; border: none;
        }
        .navbar-cart-btn {
            width: 48px; height: 48px; background: #f1f5f9;
            border-radius: 14px; display: flex; align-items: center; justify-content: center;
            color: var(--secondary-color); position: relative; text-decoration: none;
            border: 1px solid var(--border-color); transition: var(--transition);
        }
        .navbar-cart-btn:hover { background: var(--primary-color); color: white; border-color: var(--primary-color); }

        .badge-primary-custom {
            background-color: var(--primary-color) !important;
            color: var(--secondary-color) !important;
            font-weight: 800;
        }

        /* Ana İçerik */
        .main-content { flex: 1; padding: 0 !important; }

        /* ========== FOOTER (DÜZENLENDİ) ========== */
        .footer-modern { 
            background: var(--secondary-color); 
            color: #94a3b8; 
            padding: 80px 0 30px; 
            margin-top: auto; 
            position: relative;
        }
        
        /* Footer Üst Çizgisi (Gradient) */
        .footer-modern::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px; background: var(--primary-gradient);
        }
        
        .footer-brand-title { font-size: 1.8rem; font-weight: 800; color: white; margin-bottom: 20px; display: block; letter-spacing: -0.5px; }
        
        .footer-desc {
            font-size: 0.95rem; line-height: 1.7; opacity: 0.8; margin-bottom: 2rem; max-width: 300px;
        }

        .footer-heading {
            color: white; font-weight: 800; font-size: 1rem; margin-bottom: 1.5rem; text-transform: uppercase; letter-spacing: 0.5px;
        }

        .footer-link-item { 
            color: #94a3b8; text-decoration: none; display: block; margin-bottom: 12px; font-weight: 500; transition: var(--transition); 
        }
        .footer-link-item:hover { color: var(--primary-color); padding-left: 8px; }

        .social-box-item {
            width: 40px; height: 40px; border-radius: 10px;
            background: rgba(255,255,255,0.05); color: white;
            display: inline-flex; align-items: center; justify-content: center;
            margin-right: 10px; transition: var(--transition); border: 1px solid rgba(255,255,255,0.1);
        }
        .social-box-item:hover { background: var(--primary-color); color: white; transform: translateY(-3px); border-color: var(--primary-color); }

        .contact-item {
            display: flex; align-items: flex-start; gap: 12px; margin-bottom: 1rem; font-size: 0.9rem;
        }
        .contact-item i { color: var(--primary-color); margin-top: 4px; }

        .copyright-area {
            border-top: 1px solid rgba(255,255,255,0.05);
            padding-top: 30px; margin-top: 50px;
            text-align: center; font-size: 0.85rem; opacity: 0.6;
        }

        @media (max-width: 1150px) {
            .nav-menu-list, .search-wrapper-modern { display: none !important; }
        }
    </style>
</head>
<body>

<div class="top-bar d-none d-lg-block">
    <div class="container">
        <div class="top-bar-content">
            <div class="top-bar-left">
                <span class="top-bar-item"><i class="fas fa-phone-alt"></i> 0850 533 3444</span>
                <span class="top-bar-item d-none d-xl-flex"><i class="fas fa-envelope"></i> bilgi@avantajbilisim.com</span>
            </div>
            
            <div class="d-flex align-items-center gap-3">
                @auth
                    <a href="{{ route('sepet.index') }}" class="top-bar-cart-pill">
                        <i class="fas fa-shopping-basket"></i>
                        <span>Sepetim (<span id="cartCountTopBar">{{ session('sepet') ? array_sum(array_column(session('sepet'), 'adet')) : 0 }}</span>)</span>
                    </a>
                @endauth
                
                <a href="#" class="top-bar-item small">Sipariş Takibi</a>
                
                @guest
                    <div class="d-flex gap-2 ms-2">
                        <a href="{{ route('login') }}" class="top-bar-auth-btn">Giriş Yap</a>
                        <a href="{{ route('register') }}" class="top-bar-auth-btn top-bar-auth-register">Ücretsiz Kayıt</a>
                    </div>
                @endguest
            </div>
        </div>
    </div>
</div>

<nav class="main-navbar">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between">
            
            <div class="navbar-left-side">
                <a href="/" class="navbar-brand">
                    <img src="{{ asset('resimler/logo3.png') }}" alt="Avantaj Bilişim">
                </a>
            </div>

            <ul class="nav-menu-list d-none d-lg-flex">
                <li><a href="{{ route('home') }}" class="nav-link-modern {{ Route::is('home') ? 'active' : '' }}">Anasayfa</a></li>
                <li><a href="{{ route('urun.index') }}" class="nav-link-modern">Ürünler</a></li>
                
                <li class="dropdown">
                    <button class="nav-link-modern dropdown-toggle" type="button" data-bs-toggle="dropdown">Kurumsal</button>
                    <ul class="dropdown-menu dropdown-menu-modern shadow">
                        <li><a class="dropdown-item dropdown-item-modern" href="{{ route('hakkimizda') }}"><i class="fas fa-info-circle me-2"></i> Hakkımızda</a></li>
                        <li><a class="dropdown-item dropdown-item-modern" href="{{ route('iletisim') }}"><i class="fas fa-envelope me-2"></i> İletişim</a></li>
                    </ul>
                </li>

                <li>
                    <a href="{{ route('wizard.index') }}" class="nav-link-modern" style="color: var(--primary-color);">
                        <i class="fas fa-magic"></i> PC Toplama
                    </a>
                </li>
            </ul>

            <div class="search-wrapper-modern d-none d-lg-block">
                <form method="GET" action="{{ route('urun.ara') }}">
                    <input type="text" name="q" class="search-input-modern" placeholder="Parça, marka veya ürün ara...">
                    <button type="submit" class="search-btn-modern"><i class="fas fa-search"></i></button>
                </form>
            </div>

            <div class="d-flex align-items-center gap-3">
                @guest
                    <a href="{{ route('sepet.index') }}" class="navbar-cart-btn position-relative">
                        <i class="fas fa-shopping-bag fs-5"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill badge-primary-custom shadow-sm" id="cartCountNav">
                            {{ session('sepet') ? array_sum(array_column(session('sepet'), 'adet')) : 0 }}
                        </span>
                    </a>
                @else
                    <div class="dropdown">
                        <button class="user-avatar-pill" type="button" data-bs-toggle="dropdown">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</button>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 p-2 mt-3" style="border-radius: 20px; min-width: 250px;">
                            <li class="p-3 border-bottom mb-2 text-center">
                                <div class="fw-bold text-dark">{{ Auth::user()->name }}</div>
                                <div class="small text-muted">{{ Auth::user()->email }}</div>
                            </li>
                            @if(Auth::user()->role === 'admin')
                                <li><a class="dropdown-item rounded-3 py-2 fw-600" href="{{ route('admin.dashboard') }}"><i class="fas fa-tachometer-alt me-2 text-primary"></i> Yönetim Paneli</a></li>
                            @endif
                            <li><a class="dropdown-item rounded-3 py-2 fw-600" href="{{ route('profil') }}"><i class="fas fa-user-circle me-2 text-primary"></i> Hesabım</a></li>
                            <li><a class="dropdown-item rounded-3 py-2 fw-600" href="{{ route('siparislerim') }}"><i class="fas fa-box-open me-2 text-success"></i> Siparişlerim</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item rounded-3 py-2 text-danger fw-700" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt me-2"></i> Güvenli Çıkış
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                            </li>
                        </ul>
                    </div>
                @endguest

                <button class="navbar-cart-btn d-lg-none border-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileNavMenu">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </div>
</nav>

<main class="main-content">
    @yield('content')
</main>

<footer class="footer-modern">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-4 col-md-6">
                <span class="footer-brand-title">Avantaj Bilişim</span>
                <p class="footer-desc">
                    Teknolojinin en yeni adresi. Bilgisayar bileşenleri, hazır sistemler ve kurumsal çözümlerle, uygun fiyat ve güvenilir hizmet anlayışıyla yanınızdayız.
                </p>
                <div class="d-flex gap-2">
                    <a href="#" class="social-box-item"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-box-item"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-box-item"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-box-item"><i class="fab fa-youtube"></i></a>
                </div>
            </div>

            <div class="col-lg-2 col-md-6 col-6">
                <h6 class="footer-heading">Kurumsal</h6>
                <a href="/hakkimizda" class="footer-link-item">Hakkımızda</a>
                <a href="/iletisim" class="footer-link-item">İletişim</a>
                <a href="#" class="footer-link-item">Banka Hesapları</a>
                <a href="#" class="footer-link-item">Kargo Takibi</a>
            </div>

            <div class="col-lg-2 col-md-6 col-6">
                <h6 class="footer-heading">Alışveriş</h6>
                <a href="/urunler" class="footer-link-item">Tüm Ürünler</a>
                <a href="/wizard" class="footer-link-item text-white fw-bold">PC Toplama</a>
                <a href="#" class="footer-link-item">İade Koşulları</a>
                <a href="#" class="footer-link-item">Gizlilik Politikası</a>
            </div>

            <div class="col-lg-4 col-md-6">
                <h6 class="footer-heading">İletişim Bilgileri</h6>
                <div class="contact-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>Teknoloji Mah. Bilişim Cad. No:1, Kat:3<br>Selçuklu / KONYA</span>
                </div>
                <div class="contact-item">
                    <i class="fas fa-phone-alt"></i>
                    <span>0850 533 3444 (Müşteri Hizmetleri)</span>
                </div>
                <div class="contact-item">
                    <i class="fas fa-envelope"></i>
                    <span>bilgi@avantajbilisim.com</span>
                </div>
                
              
            </div>
        </div>

        <div class="copyright-area">
            &copy; {{ date('Y') }} Avantaj Bilişim Teknoloji A.Ş. Tüm Hakları Saklıdır.
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
// ========== MERKEZİ SEPET GÜNCELLEME FONKSİYONU ==========
window.updateAllCartCounts = function(count) {
    console.log('🛒 Sepet sayısı güncelleniyor:', count);
    
    // Tüm sepet sayaç elementlerini bul ve güncelle
    const cartElements = [
        document.getElementById('cartCountTopBar'),
        document.getElementById('cartCountNav'),
        document.getElementById('cartCount'),
        document.getElementById('cartCountMobile')
    ];
    
    cartElements.forEach(el => {
        if (el) {
            el.textContent = count;
            // Animasyon efekti
            el.style.transform = 'scale(1.5)';
            el.style.transition = 'transform 0.3s';
            setTimeout(() => {
                el.style.transform = 'scale(1)';
            }, 300);
        }
    });
}

// ========== SEPETE EKLEME FONKSİYONU (Global) ==========
window.sepeteEkle = function(urunId) {
    // Tıklanan butonu bul (Event Delegation gerekebilir, o yüzden parametre ile değil event ile alıyoruz)
    const btn = event.currentTarget; 
    const originalContent = btn.innerHTML;
    
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    btn.disabled = true;

    fetch('{{ route("sepet.ekle") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ 
            id: urunId, 
            adet: 1 
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Sunucu hatası');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Sepet sayısını güncelle
            if (data.sepetCount || data.sepet_count) {
                const count = data.sepetCount || data.sepet_count;
                window.updateAllCartCounts(count);
            }

            // Buton geri bildirimi
            btn.classList.add('bg-success', 'text-white', 'border-0'); // Bootstrap classları
            btn.innerHTML = '<i class="fas fa-check"></i>';
            
            // Toast bildirimi (eğer varsa)
            if (typeof showToast === 'function') {
                showToast('Ürün sepete eklendi!', 'success');
            } else {
                console.log('✅ Ürün sepete eklendi!');
            }
            
            // 2 saniye sonra butonu eski haline getir
            setTimeout(() => {
                btn.innerHTML = originalContent;
                btn.classList.remove('bg-success', 'text-white', 'border-0');
                btn.disabled = false;
            }, 2000);

        } else {
            alert(data.message || 'Bir hata oluştu');
            btn.innerHTML = originalContent;
            btn.disabled = false;
        }
    })
    .catch(error => {
        console.error('❌ Hata:', error);
        alert('İşlem sırasında bir hata oluştu.');
        btn.innerHTML = originalContent;
        btn.disabled = false;
    });
}

// Eski fonksiyonlar için alias (Geriye uyumluluk)
window.updateNavbarCart = window.updateAllCartCounts;

// Sepetten Silme (Opsiyonel - Sepet sayfasında kullanılabilir)
window.removeFromCart = function(id) {
    if(!confirm('Ürünü sepetten silmek istiyor musunuz?')) return;

    fetch(`/sepet/sil/${id}`, {
        method: 'DELETE',
        headers: { 
            'Content-Type': 'application/json', 
            'X-CSRF-TOKEN': '{{ csrf_token() }}' 
        }
    })
    .then(res => res.json())
    .then(data => { 
        if(data.success) location.reload(); 
    });
}
</script>

@stack('scripts')
</body>
</html>