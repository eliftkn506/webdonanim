<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme">
    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">

        <!-- Sol toggle -->
        <div class="navbar-nav align-items-center">
            <a class="nav-link px-0" href="javascript:void(0);" id="layout-menu-toggle">
                <i class="fas fa-bars"></i>
            </a>
        </div>

        <!-- Sağ profil / çıkış -->
        <ul class="navbar-nav flex-row align-items-center ms-auto">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="#" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        <img src="{{ asset('sneat/assets/img/avatars/1.png') }}" alt class="rounded-circle">
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-user me-2"></i> Profil
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('logout') }}">
                            <i class="fas fa-sign-out-alt me-2"></i> Çıkış
                        </a>
                    </li>
                </ul>
            </li>
        </ul>

    </div>
</nav>
