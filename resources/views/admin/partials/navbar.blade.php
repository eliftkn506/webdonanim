<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme shadow-sm">
    <div class="navbar-nav-left d-flex align-items-center">
        <!-- Sol toggle -->
        <a class="nav-link px-0 me-3" href="javascript:void(0);" id="layout-menu-toggle">
            <i class="fas fa-bars fs-5"></i>
        </a>

        <!-- Arama (Opsiyonel) -->
        <form class="d-none d-md-flex" role="search">
            <input type="text" class="form-control form-control-sm rounded-pill" placeholder="Ara..." aria-label="Search">
            <button class="btn btn-sm btn-primary ms-2" type="submit"><i class="fas fa-search"></i></button>
        </form>
    </div>

    <div class="navbar-nav-right d-flex align-items-center ms-auto">

        <!-- Bildirim -->
        <li class="nav-item dropdown me-3 list-unstyled">
            <a class="nav-link dropdown-toggle position-relative" href="#" data-bs-toggle="dropdown">
                <i class="fas fa-bell fs-5"></i>
                <span class="badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle p-1">3</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
                <li class="dropdown-header">Bildirimler</li>
                <li><a class="dropdown-item" href="#">Yeni sipariş alındı</a></li>
                <li><a class="dropdown-item" href="#">Stok uyarısı</a></li>
                <li><a class="dropdown-item text-center text-primary" href="#">Tümünü Gör</a></li>
            </ul>
        </li>

        <!-- Mesaj / inbox -->
        <li class="nav-item dropdown me-3 list-unstyled">
            <a class="nav-link dropdown-toggle position-relative" href="#" data-bs-toggle="dropdown">
                <i class="fas fa-envelope fs-5"></i>
                <span class="badge bg-success rounded-pill position-absolute top-0 start-100 translate-middle p-1">5</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
                <li class="dropdown-header">Mesajlar</li>
                <li><a class="dropdown-item" href="#">John Doe: Merhaba!</a></li>
                <li><a class="dropdown-item" href="#">Jane Smith: Rapor hazır</a></li>
                <li><a class="dropdown-item text-center text-primary" href="#">Tümünü Gör</a></li>
            </ul>
        </li>

        <!-- Profil / çıkış -->
        <li class="nav-item dropdown list-unstyled">
            <a class="nav-link dropdown-toggle hide-arrow d-flex align-items-center" href="#" data-bs-toggle="dropdown">
                <div class="avatar avatar-online me-2">
                    <img src="{{ asset('sneat/assets/img/avatars/1.png') }}" alt class="rounded-circle" width="36" height="36">
                </div>
                <span class="fw-semibold d-none d-md-block">{{ Auth::user()->name ?? 'Admin' }}</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-user me-2"></i> Profil
                    </a>
                </li>
                
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item" href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt me-2"></i> Çıkış
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </li>

    </div>
</nav>

<style>
/* Navbar hover ve ikon efektleri */
.navbar-nav .nav-link {
    color: #2C3E50;
    transition: all 0.3s ease;
}
.navbar-nav .nav-link:hover {
    color: #2980B9;
}
.navbar-nav .dropdown-menu {
    min-width: 200px;
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
}
.badge {
    font-size: 0.65rem;
    line-height: 1;
}
.avatar-online::after {
    content: '';
    position: absolute;
    bottom: 0;
    right: 0;
    width: 0.6rem;
    height: 0.6rem;
    background: #28a745;
    border: 2px solid #fff;
    border-radius: 50%;
}
</style>
