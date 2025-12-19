@extends('layouts.admin')

@section('title', 'Uyumluluk Kuralları')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h4 class="fw-bold py-3 mb-0">
                <span class="text-muted fw-light">Sihirbaz /</span> Uyumluluk Kuralları
            </h4>
            <p class="text-muted mb-0">PC toplama sihirbazı için parça uyumluluk mantığını yönetin.</p>
        </div>
        <div class="d-flex gap-2">
            <form action="{{ route('admin.uyumluluk.yeniden-hesapla') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" 
                        class="btn btn-label-warning"
                        onclick="return confirm('Tüm uyumluluklar yeniden hesaplanacak. Onaylıyor musunuz?')">
                    <i class='bx bx-refresh me-1'></i> İlişkileri Yenile
                </button>
            </form>
            <a href="{{ route('admin.uyumluluk.create') }}" class="btn btn-primary">
                <i class='bx bx-plus me-1'></i> Yeni Kural Ekle
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header border-bottom">
            <h5 class="card-title mb-0">Tanımlı Kurallar Listesi</h5>
        </div>

        @if($kurallar->count() > 0)
        <div class="table-responsive text-nowrap">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="60">ID</th>
                        <th width="40%">Ana Bileşen (Kaynak)</th>
                        <th width="50" class="text-center">İlişki</th>
                        <th width="40%">Hedef Bileşen (Kontrol)</th>
                        <th width="100" class="text-center">İşlemler</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach($kurallar as $kural)
                    <tr>
                        <td><strong>#{{ $kural->id }}</strong></td>
                        
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm me-3">
                                    <span class="avatar-initial rounded bg-label-primary">
                                        <i class='bx bx-microchip'></i>
                                    </span>
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="fw-semibold text-heading">
                                        {{ $kural->anaKategori->alt_kategori_ad ?? 'Kategori Bulunamadı' }}
                                    </span>
                                    
                                    <small class="text-muted d-flex align-items-center flex-wrap gap-1">
                                        <span class="badge bg-label-secondary" style="font-size: 0.65rem;">
                                            {{ $kural->anaKategori->kategori->kategori_ad ?? '-' }}
                                        </span>
                                        <i class='bx bx-chevron-right' style="font-size: 10px;"></i>
                                        <span class="text-primary fw-bold">
                                            {{ $kural->anaKriter->kriter_ad ?? 'Kriter Yok' }}
                                        </span>
                                    </small>
                                </div>
                            </div>
                        </td>
                        
                        <td class="text-center">
                            <div class="badge bg-label-secondary rounded-pill p-2">
                                <i class='bx bx-right-arrow-alt'></i>
                            </div>
                        </td>
                        
                        <td>
                            <div class="d-flex align-items-center justify-content-end text-end">
                                <div class="d-flex flex-column me-3">
                                    <span class="fw-semibold text-heading">
                                        {{ $kural->hedefKategori->alt_kategori_ad ?? 'Kategori Bulunamadı' }}
                                    </span>
                                    
                                    <small class="text-muted d-flex align-items-center justify-content-end flex-wrap gap-1">
                                        <span class="text-success fw-bold">
                                            {{ $kural->hedefKriter->kriter_ad ?? 'Kriter Yok' }}
                                        </span>
                                        <i class='bx bx-chevron-left' style="font-size: 10px;"></i>
                                        <span class="badge bg-label-secondary" style="font-size: 0.65rem;">
                                            {{ $kural->hedefKategori->kategori->kategori_ad ?? '-' }}
                                        </span>
                                    </small>
                                </div>
                                <div class="avatar avatar-sm">
                                    <span class="avatar-initial rounded bg-label-success">
                                        <i class='bx bx-hdd'></i>
                                    </span>
                                </div>
                            </div>
                        </td>
                        
                        <td class="text-center">
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ route('admin.uyumluluk.edit', $kural->id) }}">
                                        <i class="bx bx-edit-alt me-1"></i> Düzenle
                                    </a>
                                    <form action="{{ route('admin.uyumluluk.destroy', $kural->id) }}" method="POST" onsubmit="return confirm('Silmek istediğinize emin misiniz?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bx bx-trash me-1"></i> Sil
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($kurallar->hasPages())
        <div class="card-footer border-top">
            <div class="d-flex justify-content-end">
                {{ $kurallar->links('pagination::bootstrap-5') }}
            </div>
        </div>
        @endif

        @else
        <div class="card-body text-center py-5">
            <h4 class="mb-2">Henüz kural tanımlanmamış</h4>
            <a href="{{ route('admin.uyumluluk.create') }}" class="btn btn-primary">
                <i class='bx bx-plus me-1'></i> İlk Kuralı Oluştur
            </a>
        </div>
        @endif
    </div>
</div>
@endsection