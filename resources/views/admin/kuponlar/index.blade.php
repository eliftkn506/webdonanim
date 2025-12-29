@extends('layouts.admin')

@section('title', 'Kupon Yönetimi')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div>
            <h4 class="fw-bold mb-1">
                <i class='bx bx-purchase-tag-alt me-2'></i>Kupon Yönetimi
            </h4>
            <p class="text-muted mb-0">İndirim kuponlarını yönetin, kuralları çalıştırın ve istatistikleri görüntüleyin.</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <button onclick="otomatikAta()" class="btn btn-label-info" data-bs-toggle="tooltip" 
                    data-bs-placement="top" title="Kural bazlı kuponları uygun kullanıcılara otomatik dağıtır">
                <i class='bx bx-magic-wand me-1'></i> Tüm Kuralları Çalıştır
            </button>
            
            <a href="{{ route('admin.kuponlar.create') }}" class="btn btn-primary">
                <i class='bx bx-plus me-1'></i> Yeni Kupon
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center">
                <i class='bx bx-check-circle me-2 fs-4'></i>
                <div>{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center">
                <i class='bx bx-info-circle me-2 fs-4'></i>
                <div>{{ session('info') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center">
                <i class='bx bx-error me-2 fs-4'></i>
                <div>{{ session('error') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filtreleme -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.kuponlar.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Durum</label>
                    <select name="durum" class="form-select">
                        <option value="">Tümü</option>
                        <option value="aktif" {{ request('durum')=='aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="pasif" {{ request('durum')=='pasif' ? 'selected' : '' }}>Pasif</option>
                        <option value="suresi_dolmus" {{ request('durum')=='suresi_dolmus' ? 'selected' : '' }}>Süresi Dolmuş</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Kupon Türü</label>
                    <select name="tur" class="form-select">
                        <option value="">Tümü</option>
                        <option value="genel" {{ request('tur')=='genel' ? 'selected' : '' }}>Genel</option>
                        <option value="kullanici_ozel" {{ request('tur')=='kullanici_ozel' ? 'selected' : '' }}>Kullanıcıya Özel</option>
                        <option value="kural_bazli" {{ request('tur')=='kural_bazli' ? 'selected' : '' }}>Kural Bazlı</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Arama</label>
                    <input type="text" name="arama" class="form-control" placeholder="Kupon kodu veya başlık..." 
                           value="{{ request('arama') }}">
                </div>
                <div class="col-md-2 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class='bx bx-search me-1'></i> Filtrele
                    </button>
                    @if(request()->hasAny(['durum', 'tur', 'arama']))
                        <a href="{{ route('admin.kuponlar.index') }}" class="btn btn-outline-secondary">
                            <i class='bx bx-x'></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Kupon Listesi -->
    <div class="card">
        <div class="card-header border-bottom d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Kuponlar ({{ $kuponlar->total() }})</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width: 180px;">Kupon Kodu</th>
                        <th>Başlık & Açıklama</th>
                        <th style="width: 120px;">Tür</th>
                        <th style="width: 120px;">İndirim</th>
                        <th style="width: 140px;">Kullanım</th>
                        <th style="width: 120px;">Hedef</th>
                        <th style="width: 140px;">Geçerlilik</th>
                        <th style="width: 100px;">Durum</th>
                        <th style="width: 100px;" class="text-center">İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kuponlar as $kupon)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-2 flex-shrink-0">
                                        <span class="avatar-initial rounded bg-label-primary">
                                            <i class='bx bx-purchase-tag'></i>
                                        </span>
                                    </div>
                                    <div>
                                        <span class="fw-bold font-monospace text-primary d-block">
                                            {{ $kupon->kupon_kodu }}
                                        </span>
                                        <small class="text-muted">
                                            ID: {{ $kupon->id }}
                                        </small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <span class="fw-semibold d-block mb-1">{{ Str::limit($kupon->baslik, 40) }}</span>
                                    @if($kupon->aciklama)
                                        <small class="text-muted d-block">{{ Str::limit($kupon->aciklama, 60) }}</small>
                                    @endif
                                    @if($kupon->kupon_turu === 'kural_bazli' && $kupon->kural_kosullari)
                                        <small class="badge bg-label-secondary mt-1">
                                            <i class='bx bx-brain'></i> {{ $kupon->kural_aciklamasi }}
                                        </small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @php
                                    $badgeClass = match($kupon->kupon_turu) {
                                        'genel' => 'bg-label-info',
                                        'kullanici_ozel' => 'bg-label-warning',
                                        'kural_bazli' => 'bg-label-primary',
                                        default => 'bg-label-secondary'
                                    };
                                    $turIcon = match($kupon->kupon_turu) {
                                        'genel' => 'bx-world',
                                        'kullanici_ozel' => 'bx-user',
                                        'kural_bazli' => 'bx-brain',
                                        default => 'bx-tag'
                                    };
                                    $turText = match($kupon->kupon_turu) {
                                        'genel' => 'Genel',
                                        'kullanici_ozel' => 'Özel',
                                        'kural_bazli' => 'Kural Bazlı',
                                        default => 'Diğer'
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }}">
                                    <i class='bx {{ $turIcon }}'></i> {{ $turText }}
                                </span>
                                
                                @if($kupon->kupon_turu === 'kural_bazli' && $kupon->otomatik_ata)
                                    <br>
                                    <small class="badge bg-label-success mt-1">
                                        <i class='bx bx-run'></i> Otomatik
                                    </small>
                                @endif
                            </td>
                            <td>
                                <span class="fw-bold text-success d-block">
                                    {{ $kupon->indirim_tipi == 'yuzde' ? '%' : '₺' }}{{ number_format($kupon->indirim_miktari, 0) }}
                                </span>
                                @if($kupon->minimum_tutar > 0)
                                    <small class="text-muted d-block">Min: ₺{{ number_format($kupon->minimum_tutar, 0) }}</small>
                                @endif
                                @if($kupon->maksimum_indirim && $kupon->indirim_tipi == 'yuzde')
                                    <small class="text-muted d-block">Max: ₺{{ number_format($kupon->maksimum_indirim, 0) }}</small>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <div class="d-flex justify-content-between mb-1 small">
                                        <span class="fw-semibold">{{ $kupon->kullanilan_adet }}</span>
                                        <span class="text-muted">/ {{ $kupon->kullanim_limiti ?? '∞' }}</span>
                                    </div>
                                    @if($kupon->kullanim_limiti)
                                        @php
                                            $yuzde = min(100, ($kupon->kullanilan_adet / $kupon->kullanim_limiti) * 100);
                                            $barColor = $yuzde >= 100 ? 'bg-danger' : ($yuzde > 75 ? 'bg-warning' : 'bg-success');
                                        @endphp
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar {{ $barColor }}" role="progressbar" 
                                                 style="width: {{ $yuzde }}%"></div>
                                        </div>
                                    @else
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-info" role="progressbar" style="width: 100%"></div>
                                        </div>
                                    @endif
                                    @if($kupon->toplam_indirim_tutari > 0)
                                        <small class="text-success mt-1">
                                            ₺{{ number_format($kupon->toplam_indirim_tutari, 2) }} tasarruf
                                        </small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if($kupon->kupon_turu === 'genel')
                                    <span class="badge bg-label-success">
                                        <i class='bx bx-world'></i> Herkese
                                    </span>
                                @else
                                    @php
                                        $atananSayi = $kupon->kullanicilar->count();
                                    @endphp
                                    <span class="badge {{ $atananSayi > 0 ? 'bg-label-primary' : 'bg-label-secondary' }}">
                                        <i class='bx bx-user'></i> {{ $atananSayi }} kişi
                                    </span>
                                @endif
                            </td>
                            <td>
                                <small class="d-block">
                                    <i class='bx bx-calendar-check'></i> {{ $kupon->baslangic_tarihi->format('d.m.Y') }}
                                </small>
                                <small class="d-block">
                                    <i class='bx bx-calendar-x'></i> {{ $kupon->bitis_tarihi->format('d.m.Y') }}
                                </small>
                                <small class="text-muted d-block mt-1">
                                    @if(now()->lt($kupon->baslangic_tarihi))
                                        Başlamadı
                                    @elseif(now()->gt($kupon->bitis_tarihi))
                                        Bitti
                                    @else
                                        {{ $kupon->bitis_tarihi->diffForHumans() }}
                                    @endif
                                </small>
                            </td>
                            <td>
                                @if(!$kupon->aktif)
                                    <span class="badge bg-label-secondary">
                                        <i class='bx bx-x-circle'></i> Pasif
                                    </span>
                                @elseif(now()->gt($kupon->bitis_tarihi))
                                    <span class="badge bg-label-danger">
                                        <i class='bx bx-time'></i> Doldu
                                    </span>
                                @elseif($kupon->kullanim_limiti && $kupon->kullanilan_adet >= $kupon->kullanim_limiti)
                                    <span class="badge bg-label-warning">
                                        <i class='bx bx-error'></i> Tükendi
                                    </span>
                                @else
                                    <span class="badge bg-label-success">
                                        <i class='bx bx-check-circle'></i> Aktif
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" 
                                            data-bs-toggle="dropdown">
                                        <i class='bx bx-dots-vertical-rounded'></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="{{ route('admin.kuponlar.edit', $kupon->id) }}">
                                            <i class='bx bx-edit-alt me-1'></i> Düzenle
                                        </a>
                                        
                                        @if($kupon->kupon_turu === 'kural_bazli')
                                            <button type="button" class="dropdown-item text-info" 
                                                    onclick="tekilKuralCalistir({{ $kupon->id }})">
                                                <i class='bx bx-run me-1'></i> Kuralı Çalıştır
                                            </button>
                                        @endif

                                        <a class="dropdown-item" href="{{ route('admin.kuponlar.istatistikler', $kupon->id) }}">
                                            <i class='bx bx-bar-chart me-1'></i> İstatistikler
                                        </a>

                                        <div class="dropdown-divider"></div>
                                        
                                        <form action="{{ route('admin.kuponlar.destroy', $kupon->id) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('Bu kuponu silmek istediğinize emin misiniz?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class='bx bx-trash me-1'></i> Sil
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center justify-content-center">
                                    <div class="mb-3">
                                        <span class="badge bg-label-secondary p-4 rounded-circle">
                                            <i class='bx bx-purchase-tag-alt' style="font-size: 3rem;"></i>
                                        </span>
                                    </div>
                                    <h5 class="text-muted mb-2">Henüz Kupon Yok</h5>
                                    <p class="text-muted mb-4">Müşterilerinize özel indirimler sunmak için ilk kuponunuzu oluşturun.</p>
                                    <a href="{{ route('admin.kuponlar.create') }}" class="btn btn-primary">
                                        <i class='bx bx-plus me-1'></i> İlk Kuponu Oluştur
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($kuponlar->hasPages())
            <div class="card-footer border-top">
                {{ $kuponlar->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>

<script>
    // Tüm kural bazlı kuponları çalıştır
    function otomatikAta() {
        if (!confirm('Sistemdeki tüm "Kural Bazlı" ve "Otomatik Atama" özellikli kuponlar taranacak ve şartları sağlayan kullanıcılara tanımlanacaktır. Devam edilsin mi?')) {
            return;
        }
        
        const btn = event.target.closest('button');
        const originalContent = btn.innerHTML;
        btn.innerHTML = '<i class="bx bx-loader-alt bx-spin me-1"></i> İşleniyor...';
        btn.disabled = true;

        fetch('{{ route("admin.kuponlar.otomatik-ata") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('✅ İşlem Başarılı!\n\n' + (data.atanan_sayisi || 0) + ' kullanıcıya kupon atandı.');
                location.reload();
            } else {
                throw new Error(data.message || 'Bilinmeyen hata');
            }
        })
        .catch(error => {
            console.error('Hata:', error);
            alert('❌ Hata: ' + error.message);
            btn.innerHTML = originalContent;
            btn.disabled = false;
        });
    }

    // Tekil kural çalıştır
    function tekilKuralCalistir(kuponId) {
        if (!confirm('Bu kupon için kural çalıştırılacak. Uygun kullanıcılara kupon atanacak. Devam edilsin mi?')) {
            return;
        }

        const btn = event.target.closest('button');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="bx bx-loader-alt bx-spin me-1"></i> İşleniyor...';
        btn.disabled = true;

        fetch(`/admin/kuponlar/${kuponId}/kural-calistir`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('✅ Başarılı!\n\n' + (data.atanan_sayisi || 0) + ' kullanıcıya kupon atandı.');
                location.reload();
            } else {
                throw new Error(data.message || 'Bilinmeyen hata');
            }
        })
        .catch(error => {
            console.error('Hata:', error);
            alert('❌ Hata: ' + error.message);
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
    }

    // Tooltip'leri başlat
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endsection