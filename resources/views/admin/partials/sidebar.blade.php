<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('admin.dashboard') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                <svg width="25" viewBox="0 0 25 42" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                    <defs>
                        <path d="M13.7918663,0.358365126 L3.39788168,7.44174259 C0.566865006,9.69408886 -0.379795268,12.4788597 0.557900856,15.7960551 C0.68998853,16.2305145 1.09562888,17.7872135 3.12357076,19.2293357 C3.8146334,19.7207684 5.32369333,20.3834223 7.65075054,21.2172976 L7.59773219,21.2525164 L2.63468769,24.5493413 C0.445452254,26.3002124 0.0884951797,28.5083815 1.56381646,31.1738486 C2.83770406,32.8170431 5.20850219,33.2640127 7.09180128,32.5391577 C8.347334,32.0559211 11.4559176,30.0011079 16.4175519,26.3747182 C18.0338572,24.4997857 18.6973423,22.4544883 18.4080071,20.2388261 C17.963753,17.5346866 16.1776345,15.5799961 13.0496516,14.3747546 L10.9194936,13.4715819 L18.6192054,7.984237 L13.7918663,0.358365126 Z" id="path-1"></path>
                        <path d="M5.47320593,6.00457225 C4.05321814,8.216144 4.36334763,10.0722806 6.40359441,11.5729822 C8.61520715,12.571656 10.0999176,13.2171421 10.8577257,13.5094407 L15.5088241,14.433041 L18.6192054,7.984237 C15.5364148,3.11535317 13.9273018,0.573395879 13.7918663,0.358365126 C13.5790555,0.511491653 10.8061687,2.3935607 5.47320593,6.00457225 Z" id="path-3"></path>
                    </defs>
                    <g id="g-app-brand" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <g id="Brand-Logo" transform="translate(-27.000000, -15.000000)">
                            <g id="Icon" transform="translate(27.000000, 15.000000)">
                                <g id="Mask" transform="translate(0.000000, 8.000000)">
                                    <mask id="mask-2" fill="white">
                                        <use xlink:href="#path-1"></use>
                                    </mask>
                                    <use fill="#696cff" xlink:href="#path-1"></use>
                                    <g id="Path-3" mask="url(#mask-2)">
                                        <use fill="#696cff" xlink:href="#path-3"></use>
                                        <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-3"></use>
                                    </g>
                                </g>
                            </g>
                        </g>
                    </g>
                </svg>
            </span>
            <span class="app-brand-text demo menu-text fw-bolder ms-2">AdminPanel</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        
        <li class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <a href="{{ route('admin.dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Analytics">Gösterge Paneli</div>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.profil.*') ? 'active' : '' }}">
            <a href="{{ route('profil') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user"></i>
                <div>Kullanıcı Profili</div>
            </a>
        </li>

        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">E-Ticaret Yönetimi</span>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.urunler.*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-box"></i>
                <div>Ürünler</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('admin.urunler.index') ? 'active' : '' }}">
                    <a href="{{ route('admin.urunler.index') }}" class="menu-link">
                        <div>Ürün Listesi</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('admin.urunler.create') ? 'active' : '' }}">
                    <a href="{{ route('admin.urunler.create') }}" class="menu-link">
                        <div>Ürün Ekle</div>
                    </a>
                </li>
            </ul>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.kategoriler.*') || request()->routeIs('admin.altkategoriler.*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-category"></i>
                <div>Kategoriler</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('admin.kategoriler.index') ? 'active' : '' }}">
                    <a href="{{ route('admin.kategoriler.index') }}" class="menu-link">
                        <div>Kategori Listesi</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('admin.altkategoriler.index') ? 'active' : '' }}">
                    <a href="{{ route('admin.altkategoriler.index') }}" class="menu-link">
                        <div>Alt Kategoriler</div>
                    </a>
                </li>
            </ul>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.kriterler.*') || request()->routeIs('admin.kriterdegerleri.*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-list-check"></i>
                <div>Özellikler (Kriter)</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('admin.kriterler.index') ? 'active' : '' }}">
                    <a href="{{ route('admin.kriterler.index') }}" class="menu-link">
                        <div>Kriter Listesi</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('admin.kriterdegerleri.index') ? 'active' : '' }}">
                    <a href="{{ route('admin.kriterdegerleri.index') }}" class="menu-link">
                        <div>Kriter Değerleri</div>
                    </a>
                </li>
            </ul>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.fiyatlar.*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-tag"></i>
                <div>Fiyatlandırma</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('admin.fiyatlar.index') ? 'active' : '' }}">
                    <a href="{{ route('admin.fiyatlar.index') }}" class="menu-link">
                        <div>Fiyat Listesi</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('admin.fiyatlar.create') ? 'active' : '' }}">
                    <a href="{{ route('admin.fiyatlar.create') }}" class="menu-link">
                        <div>Fiyat Oluştur</div>
                    </a>
                </li>
            </ul>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.uyumluluk.*') || request()->routeIs('admin.urunler.uyumlu') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-desktop"></i>
                <div>PC Toplama Sihirbazı</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('admin.urunler.uyumlu') ? 'active' : '' }}">
                    <a href="{{ route('admin.urunler.uyumlu') }}" class="menu-link">
                        <div>Uyumlu Ürünler</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('admin.uyumluluk.index') ? 'active' : '' }}">
                    <a href="{{ route('admin.uyumluluk.index') }}" class="menu-link">
                        <div>Uyumluluk Kuralları</div>
                    </a>
                </li>
            </ul>
        </li>

        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Satış & Pazarlama</span>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.siparisler.*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-cart"></i>
                <div>Siparişler</div>
                <div class="badge bg-danger rounded-pill ms-auto" id="bekleyen-siparis-badge" style="display: none;">0</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('admin.siparisler.index') && !request('durum') ? 'active' : '' }}">
                    <a href="{{ route('admin.siparisler.index') }}" class="menu-link">
                        <div>Tüm Siparişler</div>
                    </a>
                </li>
                <li class="menu-item {{ request('durum') == 'beklemede' ? 'active' : '' }}">
                    <a href="{{ route('admin.siparisler.index', ['durum' => 'beklemede']) }}" class="menu-link">
                        <div>Bekleyenler</div>
                        <span class="badge badge-center bg-label-warning ms-auto" id="bekleyen-count">0</span>
                    </a>
                </li>
                <li class="menu-item {{ request('durum') == 'onaylandi' ? 'active' : '' }}">
                    <a href="{{ route('admin.siparisler.index', ['durum' => 'onaylandi']) }}" class="menu-link">
                        <div>Onaylananlar</div>
                    </a>
                </li>
            </ul>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.kampanyalar.*') || request()->routeIs('admin.kuponlar.*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-gift"></i>
                <div>Kampanyalar & Kupon</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('admin.kampanyalar.index') ? 'active' : '' }}">
                    <a href="{{ route('admin.kampanyalar.index') }}" class="menu-link">
                        <div>Kampanyalar</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('admin.kuponlar.index') ? 'active' : '' }}">
                    <a href="{{ route('admin.kuponlar.index') }}" class="menu-link">
                        <div>Kupon Kodları</div>
                    </a>
                </li>
            </ul>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.bayiler.*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-store-alt"></i>
                <div>Bayi Yönetimi</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('admin.bayiler.basvurular') ? 'active' : '' }}">
                    <a href="{{ route('admin.bayiler.basvurular') }}" class="menu-link">
                        <div>Başvurular</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('admin.bayiler.index') ? 'active' : '' }}">
                    <a href="{{ route('admin.bayiler.index') }}" class="menu-link">
                        <div>Bayi Listesi</div>
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</aside>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Bekleyen sipariş sayısı güncelleme fonksiyonu
    function updateBekleyenSiparisler() {
        // Hata durumunda konsolu kirletmemek için sessizce geç
        fetch("{{ route('admin.siparisler.bekleyen') }}")
            .then(res => {
                if (!res.ok) throw new Error('Network response was not ok');
                return res.json();
            })
            .then(data => {
                const count = data.count || 0;
                const badge = document.getElementById('bekleyen-siparis-badge');
                const countElement = document.getElementById('bekleyen-count');

                if(badge) {
                    if(count > 0){
                        badge.style.display = 'flex'; // flex ile ortala
                        badge.textContent = count;
                    } else {
                        badge.style.display = 'none';
                    }
                }
                if(countElement) countElement.textContent = count;
            })
            .catch(err => {
                // Sessiz kal veya logla
                // console.log('Sipariş badge güncellenemedi');
            });
    }

    // Sayfa yüklendiğinde çalıştır
    updateBekleyenSiparisler();
    
    // Her 30 saniyede bir güncelle
    setInterval(updateBekleyenSiparisler, 30000);
});
</script>