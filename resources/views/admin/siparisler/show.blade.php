@extends('layouts.admin')

@section('title', 'Sipariş Detayı #' . $siparis->siparis_no)

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
        <div class="d-flex flex-column justify-content-center">
            <h4 class="mb-1 mt-3">Sipariş #{{ $siparis->siparis_no }}</h4>
            <p class="text-muted">{{ $siparis->created_at->format('d F Y, H:i') }}</p>
        </div>
        <div class="d-flex align-content-center flex-wrap gap-2">
            <a href="{{ route('admin.siparisler.index') }}" class="btn btn-label-secondary">Geri Dön</a>
            <button class="btn btn-primary">Fatura Yazdır</button>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-lg-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title m-0">Sipariş Özeti</h5>
                    <span class="text-muted">ID: {{ $siparis->id }}</span>
                </div>
                <div class="table-responsive">
                    <table class="table border-top">
                        <thead>
                            <tr>
                                <th>Ürün</th>
                                <th class="text-end">Fiyat</th>
                                <th class="text-end">Adet</th>
                                <th class="text-end">Toplam</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($siparis->urunler as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-3">
                                                @if($item->urun && $item->urun->resim_url)
                                                    <img src="{{ asset($item->urun->resim_url) }}" alt="Avatar" class="rounded-circle" style="object-fit: cover;">
                                                @else
                                                    <span class="avatar-initial rounded bg-label-secondary"><i class='bx bx-box'></i></span>
                                                @endif
                                            </div>
                                            <div class="d-flex flex-column">
                                                <span class="text-heading fw-semibold">
                                                    {{ $item->urun ? $item->urun->urun_ad : 'Silinmiş Ürün' }}
                                                </span>
                                                <small class="text-muted">
                                                    {{ $item->urun ? $item->urun->marka . ' ' . $item->urun->model : '' }}
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end">₺{{ number_format($item->birim_fiyat, 2) }}</td>
                                    <td class="text-end">{{ $item->adet }}</td>
                                    <td class="text-end">₺{{ number_format($item->toplam_fiyat, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-3 text-muted">
                                        Bu sipariş için ürün bulunamadı.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="card-body mx-3 pb-0">
                    <div class="row p-3 bg-light rounded">
                        <div class="col-md-6 mb-md-0 mb-3">
                            <div class="d-flex align-items-center mb-3">
                                <span class="badge bg-label-success p-2 me-3 rounded"><i class='bx bx-credit-card'></i></span>
                                <div>
                                    <h6 class="mb-0">Ödeme Yöntemi</h6>
                                    <small>{{ ucfirst($siparis->odeme_durumu) }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 d-flex flex-column justify-content-center">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Ara Toplam:</span>
                                <span class="text-heading">₺{{ number_format($siparis->toplam_tutar, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">KDV:</span>
                                <span class="text-heading">₺{{ number_format($siparis->kdv_tutari, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">İndirim:</span>
                                <span class="text-heading">-₺{{ number_format($siparis->indirim_tutari, 2) }}</span>
                            </div>
                            <hr class="my-2">
                            <div class="d-flex justify-content-between">
                                <span class="fw-bold text-heading">Genel Toplam:</span>
                                <span class="fw-bold text-primary">₺{{ number_format($siparis->toplam_tutar + $siparis->kdv_tutari - $siparis->indirim_tutari, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <small class="text-muted">Sipariş Notu: {{ $siparis->notlar ?? 'Yok' }}</small>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title m-0">Sipariş Hareketleri</h5>
                </div>
                <div class="card-body">
                    <ul class="timeline timeline-dashed mt-3">
                        <li class="timeline-item timeline-item-transparent border-left-dashed">
                            <span class="timeline-point timeline-point-primary"></span>
                            <div class="timeline-event">
                                <div class="timeline-header mb-1">
                                    <h6 class="mb-0">Sipariş Oluşturuldu</h6>
                                    <small class="text-muted">{{ $siparis->created_at->format('d.m.Y H:i') }}</small>
                                </div>
                                <p class="mb-0">Sipariş başarıyla sisteme düştü.</p>
                            </div>
                        </li>
                        </ul>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title m-0">Durum Yönetimi</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Şu Anki Durum</label>
                        @php
                            $badgeClass = match($siparis->durum) {
                                'beklemede' => 'bg-label-warning',
                                'onaylandi' => 'bg-label-info',
                                'hazirlaniyor' => 'bg-label-primary',
                                'kargoda' => 'bg-label-primary',
                                'teslim_edildi' => 'bg-label-success',
                                'iptal_edildi' => 'bg-label-danger',
                                default => 'bg-label-secondary'
                            };
                        @endphp
                        <div class="h5 mb-3">
                            <span class="badge {{ $badgeClass }} w-100 py-2" id="currentDurumBadge">
                                {{ ucfirst(str_replace('_', ' ', $siparis->durum)) }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Durumu Değiştir</label>
                        <select id="durumSelect" class="form-select">
                            <option value="beklemede" {{ $siparis->durum == 'beklemede' ? 'selected' : '' }}>Beklemede</option>
                            <option value="onaylandi" {{ $siparis->durum == 'onaylandi' ? 'selected' : '' }}>Onaylandı</option>
                            <option value="hazirlaniyor" {{ $siparis->durum == 'hazirlaniyor' ? 'selected' : '' }}>Hazırlanıyor</option>
                            <option value="kargoda" {{ $siparis->durum == 'kargoda' ? 'selected' : '' }}>Kargoda</option>
                            <option value="teslim_edildi" {{ $siparis->durum == 'teslim_edildi' ? 'selected' : '' }}>Teslim Edildi</option>
                            <option value="iptal_edildi" {{ $siparis->durum == 'iptal_edildi' ? 'selected' : '' }}>İptal Edildi</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <textarea id="durumNotu" class="form-control" placeholder="Durum notu ekle (Opsiyonel)"></textarea>
                    </div>
                    <button class="btn btn-primary w-100" id="btnUpdateStatus">Güncelle</button>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title m-0">Müşteri Detayları</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-start align-items-center mb-4">
                        <div class="avatar me-3">
                            <span class="avatar-initial rounded bg-label-primary">
                                {{ substr($siparis->user->name ?? 'M', 0, 1) }}
                            </span>
                        </div>
                        <div class="d-flex flex-column">
                            <a href="#" class="text-body text-nowrap">
                                <h6 class="mb-0">{{ $siparis->user->name ?? 'Misafir' }}</h6>
                            </a>
                            <small class="text-muted">Müşteri ID: #{{ $siparis->user->id ?? '-' }}</small>
                        </div>
                    </div>
                    <div class="d-flex justify-content-start align-items-center mb-4">
                        <span class="avatar rounded border d-flex align-items-center justify-content-center me-3 p-2">
                            <i class='bx bx-envelope'></i>
                        </span>
                        <h6 class="text-body text-nowrap mb-0">{{ $siparis->user->email ?? '-' }}</h6>
                    </div>
                    <div class="d-flex justify-content-start align-items-center">
                        <span class="avatar rounded border d-flex align-items-center justify-content-center me-3 p-2">
                            <i class='bx bx-phone'></i>
                        </span>
                        <h6 class="text-body text-nowrap mb-0">{{ $siparis->user->telefon ?? '-' }}</h6>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title m-0">Teslimat Adresi</h6>
                </div>
                <div class="card-body">
                    <p class="mb-0">
                        {{ $siparis->kargo_adresi ?? 'Adres bilgisi bulunamadı.' }}
                    </p>
                </div>
            </div>

        </div>
    </div>
</div>

<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.getElementById('btnUpdateStatus').addEventListener('click', function() {
    let siparisId = {{ $siparis->id }};
    let durum = document.getElementById('durumSelect').value;
    let not = document.getElementById('durumNotu').value;

    fetch("{{ route('admin.siparisler.durumGuncelle', $siparis->id) }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ durum: durum, not: not })
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Güncellendi',
                text: data.message,
                showConfirmButton: false,
                timer: 1500
            }).then(() => location.reload());
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Hata',
                text: 'Güncelleme yapılamadı'
            });
        }
    })
    .catch(err => {
        console.error(err);
        Swal.fire({
            icon: 'error',
            title: 'Sunucu Hatası',
            text: 'İşlem gerçekleştirilemedi.'
        });
    });
});
</script>
@endsection