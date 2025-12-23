<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('admin.dashboard') }}" class="app-brand-link">
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

        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">E-Ticaret</span>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.urunler.*') || request()->routeIs('admin.degerlendirmeler.*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-box"></i>
                <div data-i18n="Product Management">Ürün Yönetimi</div>
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
                <li class="menu-item {{ request()->routeIs('admin.degerlendirmeler.index') ? 'active' : '' }}">
                    <a href="{{ route('admin.degerlendirmeler.index') }}" class="menu-link">
                        <div>Değerlendirmeler</div>
                        @php
                            $count = 0;
                            try {
                                $count = \App\Models\Degerlendirme::where('onay', 0)->count();
                            } catch (\Exception $e) {}
                        @endphp
                        @if($count > 0)
                            <span class="badge rounded-pill bg-danger ms-auto">{{ $count }}</span>
                        @endif
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
            </ul>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.siparisler.*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-cart"></i>
                <div>Siparişler</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('admin.siparisler.index') ? 'active' : '' }}">
                    <a href="{{ route('admin.siparisler.index') }}" class="menu-link">
                        <div>Tüm Siparişler</div>
                    </a>
                </li>
                <li class="menu-item {{ request('durum') == 'beklemede' ? 'active' : '' }}">
                    <a href="{{ route('admin.siparisler.index', ['durum' => 'beklemede']) }}" class="menu-link">
                        <div>Bekleyenler</div>
                    </a>
                </li>
            </ul>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.kuponlar.*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-purchase-tag"></i>
                <div>Kupon Yönetimi</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('admin.kuponlar.index') ? 'active' : '' }}">
                    <a href="{{ route('admin.kuponlar.index') }}" class="menu-link">
                        <div>Kupon Listesi</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('admin.kuponlar.create') ? 'active' : '' }}">
                    <a href="{{ route('admin.kuponlar.create') }}" class="menu-link">
                        <div>Yeni Kupon Ekle</div>
                    </a>
                </li>
            </ul>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.kampanyalar.*') ? 'active' : '' }}">
            <a href="{{ route('admin.kampanyalar.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-gift"></i>
                <div>Kampanyalar</div>
            </a>
        </li>
        
        <li class="menu-item {{ request()->routeIs('admin.bayiler.*') ? 'active' : '' }}">
            <a href="{{ route('admin.bayiler.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-store-alt"></i>
                <div>Bayiler</div>
            </a>
        </li>

        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Site Yönetimi</span>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.sliders.*') || request()->routeIs('admin.pages.*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-layout"></i>
                <div>CMS Yönetimi</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('admin.sliders.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.sliders.index') }}" class="menu-link">
                        <div>Slider Yönetimi</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('admin.pages.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.pages.index') }}" class="menu-link">
                        <div>Sayfa Yönetimi</div>
                    </a>
                </li>
            </ul>
        </li>

    </ul>
</aside>