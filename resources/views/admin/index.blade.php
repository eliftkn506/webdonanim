@extends('layouts.admin')

@section('title', 'Dashboard - Admin Panel')

@section('content')
<style>
.notification-toast {
    position: fixed;
    top: 80px;
    right: 20px;
    z-index: 9999;
    min-width: 350px;
    animation: slideIn 0.3s ease;
}

@keyframes slideIn {
    from { transform: translateX(400px); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
}

.siparis-card {
    background: white;
    border-radius: 12px;
    padding: 1rem;
    margin-bottom: 0.75rem;
    border-left: 4px solid #fbbf24;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    cursor: pointer;
}

.siparis-card:hover {
    transform: translateX(-5px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.siparis-card.yeni {
    border-left-color: #ef4444;
    animation: newOrder 1s ease;
}

@keyframes newOrder {
    0%, 100% { background: white; }
    50% { background: #fef2f2; }
}

.stat-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.15);
}

.admin-card {
    transition: all 0.3s ease;
    border: none;
    overflow: hidden;
}

.admin-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.2);
}

.admin-card .card-body {
    position: relative;
    z-index: 1;
}

.admin-card i {
    transition: transform 0.3s ease;
}

.admin-card:hover i {
    transform: scale(1.2) rotate(5deg);
}

.quick-stats {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 15px;
    padding: 1.5rem;
    color: white;
    margin-bottom: 1.5rem;
}

.metric-badge {
    background: rgba(255,255,255,0.2);
    padding: 0.5rem 1rem;
    border-radius: 20px;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}
</style>

<div class="row">
  <!-- Hoşgeldin Kartı -->
  <div class="col-lg-8 mb-4 order-0">
    <div class="card">
      <div class="d-flex align-items-end row">
        <div class="col-sm-7">
          <div class="card-body">
            <h5 class="card-title text-primary">Hoşgeldin {{ auth()->user()->name ?? 'Admin' }}! 🎉</h5>
            <p class="mb-4">
              Admin paneline hoşgeldiniz. Sistem yönetimi için gerekli tüm araçlar burada.
            </p>
            <div class="d-flex flex-wrap gap-2">
              <a href="{{ route('admin.siparisler.index') }}" class="btn btn-sm btn-primary">
                <i class="bx bx-shopping-bag me-1"></i>Siparişler
              </a>
              <a href="{{ route('admin.urunler.create') }}" class="btn btn-sm btn-outline-primary">
                <i class="bx bx-plus me-1"></i>Ürün Ekle
              </a>
              <a href="{{ route('admin.kategoriler.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bx bx-folder me-1"></i>Kategoriler
              </a>
              <a href="{{ route('admin.kampanyalar.index') }}" class="btn btn-sm btn-outline-success">
                <i class="bx bx-percent me-1"></i>Kampanyalar
              </a>
            </div>
          </div>
        </div>
        <div class="col-sm-5 text-center text-sm-left">
          <div class="card-body pb-0 px-0 px-md-4">
            <img
              src="{{ asset('sneat/assets/img/illustrations/man-with-laptop-light.png') }}"
              height="140"
              alt="Admin Dashboard"
            />
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Hızlı İstatistikler -->
  <div class="col-lg-4 col-md-4 order-1">
    <div class="row">
      <div class="col-lg-6 col-md-12 col-6 mb-4">
        <div class="card stat-card">
          <div class="card-body">
            <div class="card-title d-flex align-items-start justify-content-between">
              <div class="avatar flex-shrink-0">
                <img
                  src="{{ asset('sneat/assets/img/icons/unicons/chart-success.png') }}"
                  alt="chart success"
                  class="rounded"
                />
              </div>
            </div>
            <span class="fw-semibold d-block mb-1">Bugünün Satışları</span>
            <h3 class="card-title mb-2" id="bugun-satis">₺0</h3>
            <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> Aktif</small>
          </div>
        </div>
      </div>
      <div class="col-lg-6 col-md-12 col-6 mb-4">
        <div class="card stat-card">
          <div class="card-body">
            <div class="card-title d-flex align-items-start justify-content-between">
              <div class="avatar flex-shrink-0">
                <img
                  src="{{ asset('sneat/assets/img/icons/unicons/wallet-info.png') }}"
                  alt="wallet"
                  class="rounded"
                />
              </div>
            </div>
            <span>Bekleyen Sipariş</span>
            <h3 class="card-title text-nowrap mb-1" id="bekleyen-siparis-dash">0</h3>
            <small class="text-warning fw-semibold"><i class="bx bx-clock"></i> Onay Bekliyor</small>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Hızlı Metrikler -->
<div class="quick-stats">
  <div class="row text-center">
    <div class="col-md-3 col-6 mb-3">
      <div class="metric-badge">
        <i class="bx bx-package"></i>
        <div>
          <small class="d-block opacity-75">Toplam Ürün</small>
          <strong id="toplam-urun">0</strong>
        </div>
      </div>
    </div>
    <div class="col-md-3 col-6 mb-3">
      <div class="metric-badge">
        <i class="bx bx-layer"></i>
        <div>
          <small class="d-block opacity-75">Varyasyonlar</small>
          <strong id="toplam-varyasyon">0</strong>
        </div>
      </div>
    </div>
    <div class="col-md-3 col-6 mb-3">
      <div class="metric-badge">
        <i class="bx bx-category"></i>
        <div>
          <small class="d-block opacity-75">Kategoriler</small>
          <strong id="toplam-kategori">0</strong>
        </div>
      </div>
    </div>
    <div class="col-md-3 col-6 mb-3">
      <div class="metric-badge">
        <i class="bx bx-link"></i>
        <div>
          <small class="d-block opacity-75">Uyumlu Ürün</small>
          <strong id="toplam-uyumlu">0</strong>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Sipariş İstatistikleri -->
<div class="row">
  <div class="col-12">
    <div class="card">
      <h5 class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bx bx-bar-chart-alt-2 me-2"></i>Sipariş İstatistikleri</span>
        <a href="{{ route('admin.siparisler.index') }}" class="btn btn-primary btn-sm">
          <i class="bx bx-show me-1"></i>Tümünü Gör
        </a>
      </h5>
      <div class="card-body">
        <div class="row g-4">
          <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card bg-warning text-white h-100 stat-card">
              <div class="card-body text-center">
                <i class="bx bx-time display-4 mb-3"></i>
                <h5 class="card-title text-white">Bekleyen</h5>
                <h2 class="text-white mb-2" id="stat-bekleyen">0</h2>
                <small>Onay bekliyor</small>
              </div>
            </div>
          </div>

          <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card bg-success text-white h-100 stat-card">
              <div class="card-body text-center">
                <i class="bx bx-check-circle display-4 mb-3"></i>
                <h5 class="card-title text-white">Onaylanan</h5>
                <h2 class="text-white mb-2" id="stat-onaylanan">0</h2>
                <small>Hazırlanıyor</small>
              </div>
            </div>
          </div>

          <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card bg-info text-white h-100 stat-card">
              <div class="card-body text-center">
                <i class="bx bx-package display-4 mb-3"></i>
                <h5 class="card-title text-white">Kargoda</h5>
                <h2 class="text-white mb-2" id="stat-kargoda">0</h2>
                <small>Yolda</small>
              </div>
            </div>
          </div>

          <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card bg-primary text-white h-100 stat-card">
              <div class="card-body text-center">
                <i class="bx bx-check-double display-4 mb-3"></i>
                <h5 class="card-title text-white">Teslim Edilen</h5>
                <h2 class="text-white mb-2" id="stat-teslim">0</h2>
                <small>Tamamlandı</small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row mt-4">
  <!-- Yönetim Kartları -->
  <div class="col-lg-8">
    <div class="card">
      <h5 class="card-header">
        <i class="bx bx-grid-alt me-2"></i>Yönetim Paneli
      </h5>
      <div class="card-body">
        <div class="row g-3">
          <!-- Sipariş Yönetimi -->
          <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card admin-card bg-danger text-white h-100">
              <div class="card-body text-center py-4">
                <i class="bx bx-shopping-bag display-3 mb-3"></i>
                <h6 class="card-title text-white mb-2">Sipariş Yönetimi</h6>
                <p class="card-text small mb-3">Sipariş takibi ve yönetimi</p>
                <a href="{{ route('admin.siparisler.index') }}" class="btn btn-light btn-sm">
                  <i class="bx bx-right-arrow-alt"></i> Yönet
                </a>
              </div>
            </div>
          </div>

          <!-- Kategori Yönetimi -->
          <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card admin-card bg-primary text-white h-100">
              <div class="card-body text-center py-4">
                <i class="bx bx-folder display-3 mb-3"></i>
                <h6 class="card-title text-white mb-2">Kategoriler</h6>
                <p class="card-text small mb-3">Kategori ekleme ve düzenleme</p>
                <a href="{{ route('admin.kategoriler.index') }}" class="btn btn-light btn-sm">
                  <i class="bx bx-right-arrow-alt"></i> Yönet
                </a>
              </div>
            </div>
          </div>

          <!-- Alt Kategori -->
          <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card admin-card bg-success text-white h-100">
              <div class="card-body text-center py-4">
                <i class="bx bx-folder-open display-3 mb-3"></i>
                <h6 class="card-title text-white mb-2">Alt Kategoriler</h6>
                <p class="card-text small mb-3">Alt kategori işlemleri</p>
                <a href="{{ route('admin.altkategoriler.index') }}" class="btn btn-light btn-sm">
                  <i class="bx bx-right-arrow-alt"></i> Yönet
                </a>
              </div>
            </div>
          </div>

          <!-- Kriterler -->
          <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card admin-card bg-warning text-white h-100">
              <div class="card-body text-center py-4">
                <i class="bx bx-task display-3 mb-3"></i>
                <h6 class="card-title text-white mb-2">Kriterler</h6>
                <p class="card-text small mb-3">Ürün kriterleri yönetimi</p>
                <a href="{{ route('admin.kriterler.index') }}" class="btn btn-light btn-sm">
                  <i class="bx bx-right-arrow-alt"></i> Yönet
                </a>
              </div>
            </div>
          </div>

          <!-- Kriter Değerleri -->
          <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card admin-card bg-info text-white h-100">
              <div class="card-body text-center py-4">
                <i class="bx bx-slider-alt display-3 mb-3"></i>
                <h6 class="card-title text-white mb-2">Kriter Değerleri</h6>
                <p class="card-text small mb-3">Kriter değer yönetimi</p>
                <a href="{{ route('admin.kriterdegerleri.index') }}" class="btn btn-light btn-sm">
                  <i class="bx bx-right-arrow-alt"></i> Yönet
                </a>
              </div>
            </div>
          </div>

          <!-- Ürünler -->
          <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card admin-card bg-dark text-white h-100">
              <div class="card-body text-center py-4">
                <i class="bx bx-package display-3 mb-3"></i>
                <h6 class="card-title text-white mb-2">Ürün Yönetimi</h6>
                <p class="card-text small mb-3">Ürün ekleme ve yönetme</p>
                <a href="{{ route('admin.urunler.index') }}" class="btn btn-light btn-sm">
                  <i class="bx bx-right-arrow-alt"></i> Yönet
                </a>
              </div>
            </div>
          </div>

          <!-- Uyumlu Ürünler -->
          <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card admin-card text-white h-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
              <div class="card-body text-center py-4">
                <i class="bx bx-link display-3 mb-3"></i>
                <h6 class="card-title text-white mb-2">Uyumlu Ürünler</h6>
                <p class="card-text small mb-3">Ürün uyumluluk yönetimi</p>
                <a href="{{ route('admin.urunler.uyumlu') }}" class="btn btn-light btn-sm">
                  <i class="bx bx-right-arrow-alt"></i> Yönet
                </a>
              </div>
            </div>
          </div>

          <!-- Kampanyalar -->
          <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card admin-card text-white h-100" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
              <div class="card-body text-center py-4">
                <i class="bx bx-percent display-3 mb-3"></i>
                <h6 class="card-title text-white mb-2">Kampanyalar</h6>
                <p class="card-text small mb-3">Kampanya ekleme ve düzenleme</p>
                <a href="{{ route('admin.kampanyalar.index') }}" class="btn btn-light btn-sm">
                  <i class="bx bx-right-arrow-alt"></i> Yönet
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bekleyen Siparişler -->
  <div class="col-lg-4">
    <div class="card h-100">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="m-0"><i class="bx bx-bell text-warning me-2"></i>Bekleyen Siparişler</h5>
        <span class="badge bg-warning" id="bekleyen-liste-count">0</span>
      </div>
      <div class="card-body" style="max-height: 600px; overflow-y: auto;">
        <div id="bekleyen-siparisler-liste">
          <div class="text-center text-muted py-5">
            <i class="bx bx-inbox display-1 mb-3"></i>
            <p>Bekleyen sipariş yok</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Bildirim Container -->
<div id="notification-container"></div>

<script>
// Dashboard verileri yükleme
function loadDashboardData() {
    fetch('/admin/siparisler/bekleyen')
        .then(res => res.json())
        .then(data => {
            updateStats(data);
            updateBekleyenListe(data.siparisler);
        })
        .catch(err => console.error('Veri yükleme hatası:', err));
    
    // Ürün istatistiklerini yükle
    loadProductStats();
}

// Ürün istatistikleri
function loadProductStats() {
    fetch('/admin/dashboard/stats')
        .then(res => res.json())
        .then(data => {
            document.getElementById('toplam-urun').textContent = data.toplam_urun || 0;
            document.getElementById('toplam-varyasyon').textContent = data.toplam_varyasyon || 0;
            document.getElementById('toplam-kategori').textContent = data.toplam_kategori || 0;
            document.getElementById('toplam-uyumlu').textContent = data.toplam_uyumlu || 0;
        })
        .catch(err => console.error('Ürün stats hatası:', err));
}

// İstatistikleri güncelle
function updateStats(data) {
    document.getElementById('bekleyen-siparis-dash').textContent = data.count || 0;
    document.getElementById('stat-bekleyen').textContent = data.count || 0;
    document.getElementById('bekleyen-liste-count').textContent = data.count || 0;
    
    if(data.onaylanan) document.getElementById('stat-onaylanan').textContent = data.onaylanan;
    if(data.kargoda) document.getElementById('stat-kargoda').textContent = data.kargoda;
    if(data.teslim) document.getElementById('stat-teslim').textContent = data.teslim;
    if(data.bugun_satis) document.getElementById('bugun-satis').textContent = '₺' + parseFloat(data.bugun_satis).toFixed(2);
}

// Bekleyen siparişler listesi
function updateBekleyenListe(siparisler) {
    const liste = document.getElementById('bekleyen-siparisler-liste');
    
    if(!siparisler || siparisler.length === 0) {
        liste.innerHTML = '<div class="text-center text-muted py-5"><i class="bx bx-inbox display-1 mb-3"></i><p>Bekleyen sipariş yok</p></div>';
        return;
    }
    
    liste.innerHTML = '';
    siparisler.forEach((siparis, index) => {
        const card = document.createElement('div');
        card.className = 'siparis-card' + (index === 0 ? ' yeni' : '');
        const toplam = parseFloat(siparis.toplam_tutar) + parseFloat(siparis.kdv_tutari) - parseFloat(siparis.indirim_tutari);
        
        card.innerHTML = `
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <strong class="text-primary">#${siparis.siparis_no}</strong>
                    <div class="text-muted small">${siparis.user ? siparis.user.name : 'Misafir'}</div>
                    <div class="text-muted small"><i class="bx bx-time"></i> ${new Date(siparis.created_at).toLocaleString('tr-TR')}</div>
                </div>
                <div class="text-end">
                    <strong class="text-success">₺${toplam.toFixed(2)}</strong>
                    <div><span class="badge bg-warning">Beklemede</span></div>
                </div>
            </div>
        `;
        card.onclick = () => window.location.href = `/admin/siparisler/${siparis.id}`;
        liste.appendChild(card);
    });
}

// Bildirim göster
function showNotification(title, message, type = 'info') {
    const colors = {success: '#10b981', warning: '#f59e0b', danger: '#ef4444', info: '#3b82f6'};
    const icons = {success: 'check-circle', warning: 'error-circle', danger: 'x-circle', info: 'info-circle'};
    
    const notification = document.createElement('div');
    notification.className = 'notification-toast alert alert-dismissible fade show';
    notification.style.cssText = `background: ${colors[type]}; color: white; box-shadow: 0 10px 25px rgba(0,0,0,0.2); border: none;`;
    notification.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="bx bx-${icons[type]} fs-2 me-3"></i>
            <div>
                <strong>${title}</strong>
                <div class="small">${message}</div>
            </div>
        </div>
        <button type="button" class="btn-close btn-close-white" onclick="this.parentElement.remove()"></button>
    `;
    document.getElementById('notification-container').appendChild(notification);
    if(type === 'warning' || type === 'danger') playNotificationSound();
    setTimeout(() => notification.remove(), 5000);
}

// Bildirim sesi
function playNotificationSound() {
    const audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBjGH0fPTgjMGHm7A7+OZSA8PVKzn77BdGAU+ltryxnMnBSuAzvLaiTcIGWi77OSfTRAMUKfj8LZjHAY4kdfyzHksBSR3x/DdkEAKFF606+uoVRQKRp/g8r5sIQYxh9Hz04IzBh5uwO/jmUgPD1Ss5++wXRgFPpba8sZzJwUrgM7y2ok3CBlou+zkn00QDFCn4/C2YxwGOJHX8sx5LAUkd8fw3ZBACg==');
    audio.volume = 0.3;
    audio.play().catch(() => {});
}

// Otomatik güncelleme
let previousCount = 0;
function autoUpdate() {
    fetch('/admin/siparisler/bekleyen')
        .then(res => res.json())
        .then(data => {
            const currentCount = data.count || 0;
            if(currentCount > previousCount && previousCount > 0) {
                showNotification('Yeni Sipariş!', `${currentCount - previousCount} yeni sipariş alındı`, 'warning');
            }
            previousCount = currentCount;
            updateStats(data);
            updateBekleyenListe(data.siparisler);
        })
        .catch(err => console.error('Güncelleme hatası:', err));
}

// Sayfa yüklendiğinde
document.addEventListener('DOMContentLoaded', function() {
    loadDashboardData();
    fetch('/admin/siparisler/bekleyen').then(res => res.json()).then(data => { previousCount = data.count || 0; });
    setInterval(autoUpdate, 30000);
    document.addEventListener('visibilitychange', function() { if (!document.hidden) autoUpdate(); });
    if ("Notification" in window && Notification.permission === "default") Notification.requestPermission();
});
</script>
@endsection