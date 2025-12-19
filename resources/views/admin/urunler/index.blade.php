@extends('layouts.admin')

@section('title', 'Ürün Listesi')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">
            <span class="text-muted fw-light">Ürünler /</span> Listesi
        </h4>
        <a href="{{ route('admin.urunler.create') }}" class="btn btn-primary">
            <i class="bx bx-plus me-1"></i> Yeni Ürün Ekle
        </a>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.urunler.index') }}" class="row g-3">
                <div class="col-md-4">
                    <div class="input-group input-group-merge">
                        <span class="input-group-text"><i class="bx bx-search"></i></span>
                        <input type="text" name="q" class="form-control" placeholder="Ürün adı, barkod veya marka ara..." value="{{ request('q') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="kategori" class="form-select">
                        <option value="">Tüm Kategoriler</option>
                        @foreach(\App\Models\Kategori::all() as $kat)
                            <option value="{{ $kat->id }}" {{ request('kategori') == $kat->id ? 'selected' : '' }}>
                                {{ $kat->kategori_ad }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="stok_durumu" class="form-select">
                        <option value="">Stok Durumu</option>
                        <option value="var" {{ request('stok_durumu') == 'var' ? 'selected' : '' }}>Stokta Var</option>
                        <option value="yok" {{ request('stok_durumu') == 'yok' ? 'selected' : '' }}>Tükendi</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filtrele</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <h5 class="card-header">Ürünler</h5>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="80">Görsel</th>
                        <th>Ürün Bilgileri</th>
                        <th>Kategori</th>
                        <th>Fiyatlar</th>
                        <th>Stok</th>
                        <th class="text-center" width="100">İşlemler</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse($urunler as $urun)
                    <tr onclick="window.location='{{ route('admin.urunler.show', $urun->id) }}'" style="cursor: pointer;">
                        
                        <td>
                            <div class="avatar avatar-md">
                                @if($urun->resim_url && file_exists(public_path($urun->resim_url)))
                                    <img src="{{ asset($urun->resim_url) }}" alt="Avatar" class="rounded-circle" style="object-fit: cover;">
                                @else
                                    <span class="avatar-initial rounded-circle bg-label-secondary">
                                        <i class="bx bx-package"></i>
                                    </span>
                                @endif
                            </div>
                        </td>

                        <td>
                            <div class="d-flex flex-column">
                                <span class="fw-bold text-heading">{{ $urun->urun_ad }}</span>
                                <small class="text-muted">
                                    {{ $urun->marka }} 
                                    @if($urun->barkod_no) | <i class="bx bx-barcode"></i> {{ $urun->barkod_no }} @endif
                                </small>
                            </div>
                        </td>

                        <td>
                            <span class="badge bg-label-primary">
                                {{ $urun->altKategori->kategori->kategori_ad ?? '-' }}
                            </span>
                            <div class="small text-muted mt-1">
                                {{ $urun->altKategori->alt_kategori_ad ?? '' }}
                            </div>
                        </td>

                        <td>
                            @forelse($urun->fiyatlar as $fiyat)
                                @php
                                    $ham = $fiyat->maliyet + ($fiyat->maliyet * $fiyat->kar_orani / 100);
                                    if($fiyat->bayi_indirimi > 0) $ham -= ($ham * $fiyat->bayi_indirimi / 100);
                                    $satis = $ham + ($ham * $fiyat->vergi_orani / 100);
                                    
                                    $renk = match($fiyat->fiyat_turu) {
                                        'standart' => 'info',
                                        'bayi' => 'warning',
                                        'kampanya' => 'success',
                                        default => 'secondary'
                                    };
                                @endphp
                                <div class="d-flex align-items-center mb-1">
                                    <span class="badge badge-dot bg-{{ $renk }} me-2"></span>
                                    <small class="text-muted me-2" style="width: 60px;">{{ ucfirst($fiyat->fiyat_turu) }}</small>
                                    <span class="fw-semibold">₺{{ number_format($satis, 2) }}</span>
                                </div>
                            @empty
                                <span class="text-muted small">Fiyat Girilmemiş</span>
                            @endforelse
                        </td>

                        <td>
                            @if($urun->stok > 0)
                                <span class="badge bg-label-success">{{ $urun->stok }} Adet</span>
                            @else
                                <span class="badge bg-label-danger">Tükendi</span>
                            @endif
                            
                            @if($urun->varyasyonlar->count() > 0)
                                <div class="mt-1" onclick="event.stopPropagation()">
                                    <small class="text-muted cursor-pointer text-primary" data-bs-toggle="modal" data-bs-target="#varyasyonModal{{ $urun->id }}">
                                        <i class="bx bx-layer"></i> {{ $urun->varyasyonlar->count() }} Varyasyon
                                    </small>
                                </div>
                            @endif
                        </td>

                        <td class="text-center" onclick="event.stopPropagation()">
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ route('admin.urunler.edit', $urun->id) }}">
                                        <i class="bx bx-edit-alt me-1"></i> Düzenle
                                    </a>
                                    <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#fiyatModal{{ $urun->id }}">
                                        <i class="bx bx-dollar me-1"></i> Fiyat Ata
                                    </button>
                                    <form action="{{ route('admin.urunler.destroy', $urun->id) }}" method="POST" onsubmit="return confirm('Silmek istediğine emin misin?')">
                                        @csrf @method('DELETE')
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
                        <td colspan="6" class="text-center py-4">
                            <div class="text-muted">Kayıtlı ürün bulunamadı.</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($urunler->hasPages())
        <div class="card-footer d-flex justify-content-end">
            {{ $urunler->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
</div>

@foreach($urunler as $urun)
    @if($urun->varyasyonlar->count() > 0)
    <div class="modal fade" id="varyasyonModal{{ $urun->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $urun->urun_ad }} - Varyasyonlar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Kriterler</th>
                                    <th class="text-end">Stok Durumu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($urun->varyasyonlar as $varyasyon)
                                    <tr>
                                        <td>
                                            @php
                                                $vKriterler = \App\Models\UrunVaryasyonKriterDegeri::where('urun_varyasyon_id', $varyasyon->id)
                                                    ->with(['kriter', 'kriterDeger'])->get();
                                            @endphp
                                            @foreach($vKriterler as $vk)
                                                <span class="badge bg-label-secondary me-1">
                                                    {{ $vk->kriter->kriter_ad ?? '' }}: 
                                                    <strong class="text-dark">{{ $vk->kriterDeger->deger ?? '' }}</strong>
                                                </span>
                                            @endforeach
                                        </td>
                                        <td class="text-end">
                                            @if($varyasyon->stok > 0)
                                                <span class="badge bg-success">{{ $varyasyon->stok }}</span>
                                            @else
                                                <span class="badge bg-danger">0</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Kapat</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="modal fade" id="fiyatModal{{ $urun->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('admin.urunler.fiyat.store', $urun->id) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Fiyat Ata - {{ $urun->urun_ad }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Fiyat Seçimi</label>
                            <select name="fiyat_id" class="form-select" required>
                                <option value="">Seçiniz</option>
                                @foreach(\App\Models\UrunFiyat::all() as $f)
                                    <option value="{{ $f->id }}">{{ $f->fiyat_turu }} - {{ $f->maliyet }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Başlangıç Tarihi</label>
                            <input type="date" name="baslangic_tarihi" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">İptal</button>
                        <button type="submit" class="btn btn-primary">Kaydet</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

<script>
    // Tooltip Aktivasyonu
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>
@endsection