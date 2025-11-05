<aside class="layout-menu menu-vertical menu bg-menu-theme">
    <!-- Logo / Marka -->
    <div class="app-brand demo">
        <a href="{{ route('admin.dashboard') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                <!-- Logo buraya -->
            </span>
            <span class="app-brand-text demo menu-text fw-bold">Admin Panel</span>
        </a>
    </div>

    <!-- Menü Başlangıç -->
    <ul class="menu-inner py-1">

        <!-- 1. Gösterge Paneli -->
        <li class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <a href="{{ route('admin.dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons fas fa-home"></i>
                <div>Gösterge Paneli</div>
            </a>
        </li>

        <!-- 2. Kullanıcı Profili -->
        <li class="menu-item {{ request()->routeIs('admin.profil.*') ? 'active' : '' }}">
            <a href="" class="menu-link">
                <i class="menu-icon tf-icons fas fa-user"></i>
                <div>Kullanıcı Profili</div>
            </a>
        </li>

        <!-- 3. E-Ticaret -->
        <li class="menu-header small text-uppercase mt-3">E-Ticaret</li>

        <!-- Ürünler -->
        <li class="menu-item {{ request()->routeIs('admin.urunler.*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons fas fa-box-open"></i>
                <div>Ürünler</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('admin.urunler.index') ? 'active' : '' }}">
                    <a href="{{ route('admin.urunler.index') }}" class="menu-link">Ürün Listesi</a>
                </li>
                <li class="menu-item {{ request()->routeIs('admin.urunler.create') ? 'active' : '' }}">
                    <a href="{{ route('admin.urunler.create') }}" class="menu-link">Ürün Ekle</a>
                </li>
            </ul>
        </li>

        <!-- Kategoriler -->
        <li class="menu-item {{ request()->routeIs('admin.kategoriler.*') || request()->routeIs('admin.altkategoriler.*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons fas fa-folder"></i>
                <div>Kategoriler</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('admin.kategoriler.index') ? 'active' : '' }}">
                    <a href="{{ route('admin.kategoriler.index') }}" class="menu-link">Kategori Listesi</a>
                </li>
                <li class="menu-item {{ request()->routeIs('admin.kategoriler.create') ? 'active' : '' }}">
                    <a href="{{ route('admin.kategoriler.create') }}" class="menu-link">Kategori Ekle</a>
                </li>
                <li class="menu-item {{ request()->routeIs('admin.altkategoriler.*') ? 'active open' : '' }}">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <div>Alt Kategoriler</div>
                    </a>
                    <ul class="menu-sub">
                        <li class="menu-item {{ request()->routeIs('admin.altkategoriler.index') ? 'active' : '' }}">
                            <a href="{{ route('admin.altkategoriler.index') }}" class="menu-link">Alt Kategori Listesi</a>
                        </li>
                        <li class="menu-item {{ request()->routeIs('admin.altkategoriler.create') ? 'active' : '' }}">
                            <a href="{{ route('admin.altkategoriler.create') }}" class="menu-link">Alt Kategori Ekle</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </li>

        <!-- Kriterler -->
        <li class="menu-item {{ request()->routeIs('admin.kriterler.*') || request()->routeIs('admin.kriterdegerleri.*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons fas fa-tasks"></i>
                <div>Kriterler</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('admin.kriterler.index') ? 'active' : '' }}">
                    <a href="{{ route('admin.kriterler.index') }}" class="menu-link">Kriter Listesi</a>
                </li>
                <li class="menu-item {{ request()->routeIs('admin.kriterdegerleri.index') ? 'active' : '' }}">
                    <a href="{{ route('admin.kriterdegerleri.index') }}" class="menu-link">Kriter Değerleri</a>
                </li>
                <li class="menu-item {{ request()->routeIs('admin.kriterler.create') ? 'active' : '' }}">
                    <a href="{{ route('admin.kriterler.create') }}" class="menu-link">Kriter Ekle</a>
                </li>
            </ul>
        </li>

        <!-- Fiyatlandırma (YENİ) -->
        <li class="menu-item {{ request()->routeIs('admin.fiyatlar.*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons fas fa-tags"></i>
                <div>Fiyatlandırma</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('admin.fiyatlar.index') ? 'active' : '' }}">
                    <a href="{{ route('admin.fiyatlar.index') }}" class="menu-link">Fiyat Listesi</a>
                </li>
                <li class="menu-item {{ request()->routeIs('admin.fiyatlar.create') ? 'active' : '' }}">
                    <a href="{{ route('admin.fiyatlar.create') }}" class="menu-link">Yeni Fiyat Ekle</a>
                </li>
            </ul>
        </li>

        <!-- Sihirbaz -->
        <li class="menu-item {{ request()->routeIs('admin.uyumluluk.*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons fas fa-magic"></i>
                <div>Sihirbaz</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('admin.urunler.uyumlu') ? 'active' : '' }}">
                    <a href="{{ route('admin.urunler.uyumlu') }}" class="menu-link">Uyumlu Ürünler</a>
                </li>
                <li class="menu-item {{ request()->routeIs('admin.uyumluluk.*') ? 'active open' : '' }}">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <div>Uyumluluk Kuralları</div>
                    </a>
                    <ul class="menu-sub">
                        <li class="menu-item {{ request()->routeIs('admin.uyumluluk.index') ? 'active' : '' }}">
                            <a href="" class="menu-link">Kural Listesi</a>
                        </li>
                        <li class="menu-item {{ request()->routeIs('admin.uyumluluk.create') ? 'active' : '' }}">
                            <a href="" class="menu-link">Kural Düzenle</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </li>

        <!-- 4. Satış (Siparişler) -->
        <li class="menu-header small text-uppercase mt-3">Satış</li>
        <li class="menu-item {{ request()->routeIs('admin.siparisler.*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons fas fa-shopping-cart"></i>
                <div>Siparişler</div>
                <span class="badge badge-center rounded-pill bg-danger ms-auto" id="bekleyen-siparis-badge" style="display: none;">0</span>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('admin.siparisler.index') && !request('durum') ? 'active' : '' }}">
                    <a href="{{ route('admin.siparisler.index') }}" class="menu-link">Tüm Siparişler</a>
                </li>
                <li class="menu-item {{ request('durum') == 'beklemede' ? 'active' : '' }}">
                    <a href="{{ route('admin.siparisler.index', ['durum' => 'beklemede']) }}" class="menu-link">
                        Bekleyen Siparişler
                        <span class="badge bg-warning ms-2" id="bekleyen-count">0</span>
                    </a>
                </li>
                <li class="menu-item {{ request('durum') == 'onaylandi' ? 'active' : '' }}">
                    <a href="{{ route('admin.siparisler.index', ['durum' => 'onaylandi']) }}" class="menu-link">
                        Onaylanan Siparişler
                    </a>
                </li>
            </ul>
        </li>

        <!-- 5. Kampanya ve Kuponlar -->
        <li class="menu-header small text-uppercase mt-3">Kampanya ve Kuponlar</li>
        <li class="menu-item {{ request()->routeIs('admin.kampanyalar.*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons fas fa-percent"></i>
                <div>Kampanyalar</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('admin.kampanyalar.index') ? 'active' : '' }}">
                    <a href="{{ route('admin.kampanyalar.index') }}" class="menu-link">Kampanya Listesi</a>
                </li>
                <li class="menu-item {{ request()->routeIs('admin.kampanyalar.create') ? 'active' : '' }}">
                    <a href="{{ route('admin.kampanyalar.create') }}" class="menu-link">Kampanya Oluştur</a>
                </li>
            </ul>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.kuponlar.*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons fas fa-ticket-alt"></i>
                <div>Kuponlar</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('admin.kuponlar.index') ? 'active' : '' }}">
                    <a href="{{ route('admin.kuponlar.index') }}" class="menu-link">Kupon Listesi</a>
                </li>
                <li class="menu-item {{ request()->routeIs('admin.kuponlar.create') ? 'active' : '' }}">
                    <a href="{{ route('admin.kuponlar.create') }}" class="menu-link">Kupon Oluştur</a>
                </li>
            </ul>
        </li>
      <!-- Bayiler -->
        <li class="menu-item {{ request()->routeIs('admin.bayiler.*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons fas fa-users"></i>
                <div>Bayiler</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('admin.bayiler.basvurular') ? 'active' : '' }}">
                    <a href="{{ route('admin.bayiler.basvurular') }}" class="menu-link">Başvurular</a>
                </li>
                <li class="menu-item {{ request()->routeIs('admin.bayiler.index') ? 'active' : '' }}">
                    <a href="{{ route('admin.bayiler.index') }}" class="menu-link">Bayilerimiz</a>
                </li>
            </ul>
        </li>


    </ul>
</aside>

<!-- Sidebar JS -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Menu toggle
    document.querySelectorAll('.menu-toggle').forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            let parent = toggle.closest('.menu-item');
            parent.classList.toggle('open');
            let submenu = parent.querySelector('.menu-sub');
            if(submenu) {
                submenu.style.display = parent.classList.contains('open') ? 'block' : 'none';
            }
        });
    });

    // Aktif submenu aç
    document.querySelectorAll('.menu-item.active.open').forEach(item => {
        const submenu = item.querySelector('.menu-sub');
        if(submenu) submenu.style.display = 'block';
        
        // Parent menüleri de aç
        let parentItem = item.closest('.menu-sub')?.closest('.menu-item');
        while(parentItem) {
            parentItem.classList.add('open');
            const parentSubmenu = parentItem.querySelector(':scope > .menu-sub');
            if(parentSubmenu) parentSubmenu.style.display = 'block';
            parentItem = parentItem.closest('.menu-sub')?.closest('.menu-item');
        }
    });

    // Bekleyen sipariş sayısı
    function updateBekleyenSiparisler() {
        fetch('/admin/siparisler/bekleyen')
            .then(res => res.json())
            .then(data => {
                const count = data.count || 0;
                const badge = document.getElementById('bekleyen-siparis-badge');
                const countElement = document.getElementById('bekleyen-count');

                if(count > 0){
                    badge.style.display = 'flex';
                    badge.textContent = count;
                    countElement.textContent = count;

                    const previousCount = parseInt(localStorage.getItem('bekleyen_siparis_count') || '0');
                    if(count > previousCount) {
                        showDesktopNotification('Yeni Sipariş!', `${count} bekleyen sipariş var`);
                    }
                    localStorage.setItem('bekleyen_siparis_count', count);
                } else {
                    badge.style.display = 'none';
                    countElement.textContent = '0';
                }
            })
            .catch(err => console.error(err));
    }

    function showDesktopNotification(title, body) {
        if (!("Notification" in window)) return;
        if (Notification.permission === "granted") {
            new Notification(title, {body, icon:'/favicon.ico', badge:'/favicon.ico'});
        } else if (Notification.permission !== "denied") {
            Notification.requestPermission().then(permission => {
                if(permission === "granted"){
                    new Notification(title, {body, icon:'/favicon.ico'});
                }
            });
        }
    }

    updateBekleyenSiparisler();
    setInterval(updateBekleyenSiparisler, 30000);
    document.addEventListener('visibilitychange', function(){
        if(!document.hidden) updateBekleyenSiparisler();
    });
});
</script>

<style>
/* Sidebar stil ve animasyon */
.layout-menu .menu-item > .menu-link {
    transition: all 0.3s ease;
    color: #2C3E50;
}
.layout-menu .menu-item.active > .menu-link,
.layout-menu .menu-item > .menu-link:hover {
    background: linear-gradient(90deg,#2980B9,#6DD5FA);
    color: #fff;
}
.menu-sub {
    display: none;
    padding-left: 1rem;
}
.menu-sub .menu-link {
    font-size: 0.9rem;
    padding-left: 1.5rem;
}
.menu-sub .menu-sub {
    padding-left: 2rem;
}
.menu-sub .menu-sub .menu-link {
    padding-left: 2.5rem;
    font-size: 0.85rem;
}
.menu-header {
    font-size: 0.75rem;
    color: #6c757d;
    padding-left: 1rem;
    font-weight: 600;
}

/* Badge animasyonu */
@keyframes pulse {
    0%,100% {transform:scale(1);}
    50% {transform:scale(1.1);}
}
#bekleyen-siparis-badge {
    animation: pulse 2s infinite;
}
.badge {
    font-size: 0.65rem;
    padding: 0.25rem 0.5rem;
}
</style>