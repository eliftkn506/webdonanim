@extends('layouts.app')
@section('title', 'Hesabım | Avantaj Bilişim')

@section('content')

<style>
    :root {
        --primary-brand: #00a99d; /* Sitenin ana yeşil tonu */
        --primary-soft: rgba(0, 169, 157, 0.1);
        --text-main: #2c3e50;
        --text-muted: #95a5a6;
        --bg-body: #f8f9fa;
        --card-radius: 16px;
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    body {
        background-color: var(--bg-body);
    }

    /* Ana Konteyner Ayarı (Navbar çakışmasını önler) */
    .profile-container {
        margin-top: 40px;
        margin-bottom: 60px;
        padding-top: 20px;
    }

    /* Sol Sidebar Kartı */
    .profile-card {
        background: white;
        border: none;
        border-radius: var(--card-radius);
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        overflow: hidden;
        margin-bottom: 20px;
    }

    .user-info-header {
        background: linear-gradient(135deg, var(--primary-brand), #008f85);
        padding: 30px 20px;
        text-align: center;
        color: white;
    }

    .user-avatar-circle {
        width: 80px;
        height: 80px;
        background: white;
        color: var(--primary-brand);
        font-size: 2.5rem;
        font-weight: bold;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        margin: 0 auto 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    /* Menü Linkleri */
    .profile-menu .nav-link {
        color: var(--text-main);
        padding: 16px 25px;
        font-weight: 600;
        border-left: 4px solid transparent;
        transition: var(--transition);
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .profile-menu .nav-link:hover {
        background-color: var(--primary-soft);
        color: var(--primary-brand);
    }

    .profile-menu .nav-link.active {
        background-color: white;
        color: var(--primary-brand);
        border-left-color: var(--primary-brand);
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
    }

    .profile-menu .nav-link i {
        width: 20px;
        text-align: center;
        font-size: 1.1rem;
    }

    /* Sağ İçerik Alanı */
    .content-card {
        background: white;
        border-radius: var(--card-radius);
        border: none;
        padding: 30px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        min-height: 500px;
    }

    .section-title {
        font-weight: 800;
        color: var(--text-main);
        margin-bottom: 25px;
        font-size: 1.5rem;
        position: relative;
        padding-bottom: 15px;
        border-bottom: 2px solid #f1f1f1;
    }

    .section-title::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 60px;
        height: 2px;
        background: var(--primary-brand);
    }

    /* Sipariş Kartı */
    .order-item {
        border: 1px solid #eee;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
        transition: var(--transition);
    }
    
    .order-item:hover {
        border-color: var(--primary-brand);
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.05);
    }

    .status-badge {
        padding: 6px 14px;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    /* Soft Status Renkleri */
    .status-beklemede { background: #fff8e1; color: #f59e0b; }
    .status-onaylandi { background: #e0f2f1; color: #00a99d; }
    .status-kargoda { background: #e3f2fd; color: #2196f3; }
    .status-iptal { background: #ffebee; color: #ef5350; }

    /* Favori & Ürün Kartı */
    .fav-card {
        border: 1px solid #f1f1f1;
        border-radius: 12px;
        overflow: hidden;
        transition: var(--transition);
        height: 100%;
        position: relative;
    }
    
    .fav-card:hover {
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        border-color: transparent;
    }

    .fav-img-wrapper {
        height: 180px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fff;
        padding: 20px;
    }
    
    .fav-img-wrapper img {
        max-height: 100%;
        max-width: 100%;
        object-fit: contain;
    }

    .remove-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        background: white;
        border: 1px solid #eee;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ef5350;
        transition: 0.2s;
        z-index: 2;
    }
    .remove-btn:hover { background: #ef5350; color: white; border-color: #ef5350; }

    /* Boş Durum (Empty State) */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }
    .empty-icon {
        font-size: 4rem;
        color: #e0e0e0;
        margin-bottom: 20px;
    }

    /* Mobil Düzenlemeler */
    @media (max-width: 991px) {
        .profile-container { margin-top: 20px; }
        .profile-menu .nav-link { padding: 12px 15px; font-size: 0.95rem; }
    }
</style>

<div class="container profile-container">
    <div class="row g-4">
        
        <div class="col-lg-3">
            <div class="profile-card">
                <div class="user-info-header">
                    <div class="user-avatar-circle">
                        {{ strtoupper(substr($kullanici->name, 0, 1)) }}
                    </div>
                    <h5 class="mb-1 fw-bold">{{ $kullanici->name }}</h5>
                    <p class="mb-0 opacity-75 small">{{ $kullanici->email }}</p>
                    @if($kullanici->isBayi())
                        <span class="badge bg-warning text-dark mt-2 px-3 py-2 rounded-pill">
                            <i class="fas fa-star me-1"></i>Kurumsal Üye
                        </span>
                    @endif
                </div>
                
                <div class="profile-menu py-2">
                    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist">
                        <button class="nav-link active" id="v-pills-siparis-tab" data-bs-toggle="pill" data-bs-target="#v-pills-siparis" type="button">
                            <i class="fas fa-box-open"></i> Siparişlerim
                        </button>
                        <button class="nav-link" id="v-pills-konfig-tab" data-bs-toggle="pill" data-bs-target="#v-pills-konfig" type="button">
                            <i class="fas fa-desktop"></i> Kayıtlı Sistemler
                        </button>
                        <button class="nav-link" id="v-pills-fav-tab" data-bs-toggle="pill" data-bs-target="#v-pills-fav" type="button">
                            <i class="fas fa-heart"></i> Favori Ürünler
                        </button>
                        <hr class="my-2 mx-3 text-muted opacity-25">
                        <button class="nav-link text-danger" onclick="document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt"></i> Güvenli Çıkış
                        </button>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                    </div>
                </div>
            </div>

            <div class="profile-card p-4 text-center d-none d-lg-block">
                <div class="mb-3 text-primary" style="font-size: 2rem;">
                    <i class="fas fa-headset"></i>
                </div>
                <h6 class="fw-bold">Yardıma mı ihtiyacınız var?</h6>
                <p class="text-muted small mb-3">Müşteri temsilcilerimiz haftanın 7 günü hizmetinizde.</p>
                <a href="tel:08505555555" class="btn btn-outline-dark btn-sm rounded-pill w-100">
                    <i class="fas fa-phone me-1"></i> 0850 555 55 55
                </a>
            </div>
        </div>

        <div class="col-lg-9">
            <div class="content-card">
                <div class="tab-content" id="v-pills-tabContent">
                    
                    <div class="tab-pane fade show active" id="v-pills-siparis" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="section-title mb-0 border-0 p-0">Sipariş Geçmişi</h4>
                        </div>

                        @if($siparisler->count() > 0)
                            @foreach($siparisler as $siparis)
                            <div class="order-item">
                                <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 pb-3 border-bottom">
                                    <div>
                                        <div class="small text-muted mb-1">Sipariş No</div>
                                        <div class="fw-bold text-dark">#{{ $siparis->siparis_no }}</div>
                                    </div>
                                    <div>
                                        <div class="small text-muted mb-1">Tarih</div>
                                        <div class="fw-bold text-dark">{{ $siparis->created_at->format('d.m.Y') }}</div>
                                    </div>
                                    <div>
                                        <div class="small text-muted mb-1">Tutar</div>
                                        <div class="fw-bold text-primary">₺{{ number_format($siparis->toplam_tutar, 2, ',', '.') }}</div>
                                    </div>
                                    <div>
                                        @php
                                            $statusMap = [
                                                'beklemede' => ['class' => 'status-beklemede', 'icon' => 'fa-clock', 'text' => 'Bekleniyor'],
                                                'onaylandi' => ['class' => 'status-onaylandi', 'icon' => 'fa-check', 'text' => 'Onaylandı'],
                                                'kargoda' => ['class' => 'status-kargoda', 'icon' => 'fa-truck', 'text' => 'Kargoda'],
                                                'iptal' => ['class' => 'status-iptal', 'icon' => 'fa-times', 'text' => 'İptal']
                                            ];
                                            $curr = $statusMap[$siparis->durum] ?? $statusMap['beklemede'];
                                        @endphp
                                        <span class="status-badge {{ $curr['class'] }}">
                                            <i class="fas {{ $curr['icon'] }}"></i> {{ $curr['text'] }}
                                        </span>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex gap-2">
                                        @foreach($siparis->urunler->take(3) as $item)
                                            <img src="{{ asset($item->urun->resim_url ?? 'resimler/default.png') }}" 
                                                 class="rounded border p-1" width="50" height="50" style="object-fit: cover;" alt="Ürün">
                                        @endforeach
                                        @if($siparis->urunler->count() > 3)
                                            <div class="rounded border bg-light d-flex align-items-center justify-content-center fw-bold small text-muted" style="width: 50px; height: 50px;">
                                                +{{ $siparis->urunler->count() - 3 }}
                                            </div>
                                        @endif
                                    </div>
                                    <a href="{{ route('siparis.detay', $siparis->id) }}" class="btn btn-sm btn-primary rounded-pill px-4">
                                        Detaylar
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <div class="empty-state">
                                <div class="empty-icon"><i class="fas fa-shopping-basket"></i></div>
                                <h5>Henüz Siparişiniz Yok</h5>
                                <p class="text-muted">İhtiyacınız olan teknolojik ürünleri hemen keşfedin.</p>
                                <a href="{{ route('urun.index') }}" class="btn btn-outline-primary rounded-pill px-4 mt-2">Alışverişe Başla</a>
                            </div>
                        @endif
                    </div>

                    <div class="tab-pane fade" id="v-pills-konfig" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="section-title mb-0 border-0 p-0">Kayıtlı Sistemler</h4>
                            <a href="{{ route('wizard.index') }}" class="btn btn-sm btn-primary rounded-pill">
                                <i class="fas fa-plus me-1"></i> Yeni Topla
                            </a>
                        </div>

                        @if($konfiglar->count() > 0)
                            <div class="row g-3">
                                @foreach($konfiglar as $konfig)
                                <div class="col-md-6">
                                    <div class="fav-card p-3 d-flex flex-column h-100">
                                        <div class="d-flex justify-content-between mb-3">
                                            <div class="bg-light rounded p-2 text-primary">
                                                <i class="fas fa-desktop fa-lg"></i>
                                            </div>
                                            <form action="{{ route('konfigurasyon.sil', $konfig->id) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-sm text-danger p-0" title="Sil" onclick="return confirm('Silmek istiyor musunuz?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                        <h6 class="fw-bold mb-1">{{ $konfig->isim }}</h6>
                                        <p class="text-muted small mb-3">{{ $konfig->urunler->count() }} Parça • {{ $konfig->created_at->diffForHumans() }}</p>
                                        <a href="{{ route('konfigurasyon.sepet', $konfig->id) }}" class="btn btn-success btn-sm w-100 rounded-pill mt-auto">
                                            <i class="fas fa-cart-arrow-down me-1"></i> Sepete Ekle
                                        </a>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="empty-state">
                                <div class="empty-icon"><i class="fas fa-microchip"></i></div>
                                <h5>Kayıtlı Sistem Bulunamadı</h5>
                                <p class="text-muted">PC Toplama sihirbazı ile hayalindeki sistemi oluşturup buraya kaydedebilirsin.</p>
                                <a href="{{ route('wizard.index') }}" class="btn btn-primary rounded-pill px-4 mt-2">Sihirbazı Başlat</a>
                            </div>
                        @endif
                    </div>

                    <div class="tab-pane fade" id="v-pills-fav" role="tabpanel">
                        <h4 class="section-title">Favori Ürünlerim</h4>

                        @if($favoriUrunler->count() > 0)
                            <div class="row g-3">
                                @foreach($favoriUrunler as $favori)
                                <div class="col-lg-4 col-md-6 col-6">
                                    <div class="fav-card">
                                        <form action="{{ route('favori.sil', $favori->id) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="remove-btn" title="Kaldır"><i class="fas fa-times"></i></button>
                                        </form>
                                        
                                        <div class="fav-img-wrapper">
                                            <a href="{{ route('urun.incele', $favori->urun->id) }}">
                                                <img src="{{ asset($favori->urun->resim_url ?? 'resimler/default.png') }}" alt="Ürün">
                                            </a>
                                        </div>
                                        
                                        <div class="p-3 pt-0 text-center">
                                            <h6 class="text-dark small fw-bold mb-2 text-truncate">
                                                <a href="{{ route('urun.incele', $favori->urun->id) }}" class="text-decoration-none text-dark">
                                                    {{ $favori->urun->urun_ad }}
                                                </a>
                                            </h6>
                                            <div class="text-primary fw-bold mb-2">
                                                ₺{{ number_format($favori->urun->fiyat, 2, ',', '.') }}
                                            </div>
                                            <a href="{{ route('urun.incele', $favori->urun->id) }}" class="btn btn-sm btn-outline-primary rounded-pill w-100">İncele</a>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="empty-state">
                                <div class="empty-icon"><i class="far fa-heart"></i></div>
                                <h5>Favori Listeniz Boş</h5>
                                <p class="text-muted">Beğendiğiniz ürünleri kalp ikonuna tıklayarak buraya ekleyebilirsiniz.</p>
                                <a href="{{ route('urun.index') }}" class="btn btn-primary rounded-pill px-4 mt-2">Ürünleri Keşfet</a>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection