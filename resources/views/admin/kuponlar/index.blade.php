@extends('layouts.admin')

@section('title', 'Kupon Yönetimi')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h4 class="fw-bold py-3 mb-0">
                <span class="text-muted fw-light">Pazarlama /</span> Kuponlar
            </h4>
            <p class="text-muted mb-0">İndirim kuponlarını yönetin ve kuralları çalıştırın.</p>
        </div>
        <div class="d-flex gap-2">
            <button onclick="otomatikAta()" class="btn btn-label-info" data-bs-toggle="tooltip" data-bs-placement="top" title="Kural bazlı kuponları uygun kullanıcılara dağıtır">
                <i class="bx bx-magic-wand me-1"></i> Kuralları Çalıştır
            </button>
            
            <a href="{{ route('admin.kuponlar.create') }}" class="btn btn-primary">
                <i class="bx bx-plus me-1"></i> Yeni Kupon
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center">
                <i class="bx bx-check-circle me-2 fs-4"></i>
                {{ session('success') }}
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center">
                <i class="bx bx-error me-2 fs-4"></i>
                {{ session('error') }}
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header border-bottom">
            <h5 class="card-title mb-0">Kupon Listesi</h5>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Kupon Kodu</th>
                        <th>Başlık</th>
                        <th>Tür</th>
                        <th>İndirim</th>
                        <th>Kullanım</th>
                        <th>Atanan Kişi</th>
                        <th>Geçerlilik</th>
                        <th>Durum</th>
                        <th class="text-center">İşlemler</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse($kuponlar as $kupon)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-2">
                                        <span class="avatar-initial rounded bg-label-primary">
                                            <i class="bx bx-purchase-tag"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <span class="fw-bold font-monospace text-primary">{{ $kupon->kupon_kodu }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="d-inline-block text-truncate" style="max-width: 150px;" title="{{ $kupon->baslik }}">
                                    {{ $kupon->baslik }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $badgeClass = match($kupon->kupon_turu) {
                                        'genel' => 'bg-label-info',
                                        'kullanici_ozel' => 'bg-label-warning',
                                        'kural_bazli' => 'bg-label-primary',
                                        default => 'bg-label-secondary'
                                    };
                                    $turText = match($kupon->kupon_turu) {
                                        'genel' => 'Genel',
                                        'kullanici_ozel' => 'Özel',
                                        'kural_bazli' => 'Kural Bazlı',
                                        default => 'Diğer'
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ $turText }}</span>
                                
                                @if($kupon->kupon_turu === 'kural_bazli' && $kupon->otomatik_ata)
                                    <i class='bx bx-analyse text-primary ms-1' data-bs-toggle="tooltip" title="Otomatik Atama Açık"></i>
                                @endif
                            </td>
                            <td>
                                <span class="fw-semibold text-success">
                                    {{ floatval($kupon->indirim_miktari) }} {{ $kupon->indirim_tipi == 'yuzde' ? '%' : '₺' }}
                                </span>
                                @if($kupon->minimum_tutar > 0)
                                    <div class="small text-muted" style="font-size: 0.75rem;">Min: ₺{{ number_format($kupon->minimum_tutar, 2) }}</div>
                                @endif
                            </td>
                            <td style="min-width: 120px;">
                                <div class="d-flex flex-column">
                                    <div class="d-flex justify-content-between mb-1 small">
                                        <span>{{ $kupon->kullanilan_adet }}</span>
                                        <span class="text-muted">/ {{ $kupon->kullanim_limiti ?? '∞' }}</span>
                                    </div>
                                    @if($kupon->kullanim_limiti)
                                        @php
                                            $yuzde = min(100, ($kupon->kullanilan_adet / $kupon->kullanim_limiti) * 100);
                                            $barColor = $yuzde >= 100 ? 'bg-danger' : ($yuzde > 75 ? 'bg-warning' : 'bg-primary');
                                        @endphp
                                        <div class="progress" style="height: 4px;">
                                            <div class="progress-bar {{ $barColor }}" role="progressbar" style="width: {{ $yuzde }}%"></div>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if($kupon->kupon_turu === 'genel')
                                    <span class="badge bg-label-success">
                                        <i class="bx bx-world"></i> Herkese Açık
                                    </span>
                                @else
                                    @php
                                        $atananSayi = $kupon->kullanicilar->count();
                                    @endphp
                                    <span class="badge bg-label-{{ $atananSayi > 0 ? 'primary' : 'secondary' }}">
                                        <i class="bx bx-user"></i> {{ $atananSayi }} Kişi
                                    </span>
                                @endif
                            </td>
                            <td>
                                <small class="d-block">{{ \Carbon\Carbon::parse($kupon->baslangic_tarihi)->format('d.m.y') }}</small>
                                <small class="d-block text-muted">{{ \Carbon\Carbon::parse($kupon->bitis_tarihi)->format('d.m.y') }}</small>
                            </td>
                            <td>
                                @if(!$kupon->aktif)
                                    <span class="badge bg-label-secondary">Pasif</span>
                                @elseif(now()->gt($kupon->bitis_tarihi))
                                    <span class="badge bg-label-danger">Süresi Doldu</span>
                                @else
                                    <span class="badge bg-label-success">Aktif</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{ route('admin.kuponlar.edit', $kupon->id) }}">
                                            <i class="bx bx-edit-alt me-1"></i> Düzenle
                                        </a>
                                        
                                        @if($kupon->kupon_turu === 'kural_bazli')
                                            <button type="button" class="dropdown-item text-info" onclick="tekilOtomatikAta({{ $kupon->id }})">
                                                <i class="bx bx-run me-1"></i> Kuralı Çalıştır
                                            </button>
                                        @endif

                                        @if($kupon->kupon_turu !== 'genel')
                                            <a class="dropdown-item" href="{{ route('admin.kuponlar.edit', $kupon->id) }}">
                                                <i class="bx bx-user-check me-1"></i> Atanan Kullanıcılar
                                            </a>
                                        @endif

                                        <div class="dropdown-divider"></div>
                                        <form action="{{ route('admin.kuponlar.destroy', $kupon->id) }}" method="POST" onsubmit="return confirm('Bu kuponu silmek istediğinize emin misiniz?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="bx bx-trash me-1"></i> Sil
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
                                            <i class="bx bx-purchase-tag-alt fs-1"></i>
                                        </span>
                                    </div>
                                    <h5 class="text-muted">Henüz hiç kupon tanımlanmamış.</h5>
                                    <p class="text-muted mb-4">Müşterilerinize indirim sağlamak için ilk kuponunuzu oluşturun.</p>
                                    <a href="{{ route('admin.kuponlar.create') }}" class="btn btn-primary">
                                        <i class="bx bx-plus me-1"></i> Kupon Oluştur
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($kuponlar instanceof \Illuminate\Pagination\LengthAwarePaginator && $kuponlar->hasPages())
            <div class="card-footer border-top">
                {{ $kuponlar->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>

<script>
    function otomatikAta() {
        if (!confirm('Sistemdeki tüm "Kural Bazlı" ve "Otomatik Atama" özellikli kuponlar taranacak ve şartları sağlayan kullanıcılara tanımlanacaktır. Bu işlem kullanıcı sayısına göre zaman alabilir. Onaylıyor musunuz?')) {
            return;
        }
        
        const btn = document.querySelector('button[onclick="otomatikAta()"]');
        const originalContent = btn.innerHTML;
        btn.innerHTML = '<i class="bx bx-loader-alt bx-spin me-1"></i> İşleniyor...';
        btn.disabled = true;

        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        fetch('{{ route("admin.kuponlar.otomatik-ata") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token || '{{ csrf_token() }}'
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => {
                    throw new Error(err.message || 'Sunucu hatası');
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert('✅ İşlem Başarılı!\n\nToplu tarama sonucu ' + (data.atanan_sayisi || 0) + ' adet yeni kupon tanımlandı.');
                location.reload();
            } else {
                throw new Error(data.message || 'Bilinmeyen hata');
            }
        })
        .catch(error => {
            console.error('Hata detayı:', error);
            alert('❌ Hata Oluştu!\n\n' + error.message + '\n\nDetaylar için konsolu kontrol edin (F12).');
            
            btn.innerHTML = originalContent;
            btn.disabled = false;
        });
    }

    function tekilOtomatikAta(kuponId) {
        // Şimdilik genel otomatik atamayı çağırıyoruz
        // İstersen tek kupon için ayrı endpoint ekleyebiliriz
        otomatikAta(); 
    }
</script>
@endsection