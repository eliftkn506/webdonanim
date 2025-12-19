@extends('layouts.admin')

@section('title', 'Bayi Başvuruları')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h4 class="fw-bold py-3 mb-0">
                <span class="text-muted fw-light">Bayi Yönetimi /</span> Başvurular
            </h4>
            <p class="text-muted mb-0">Onay bekleyen ve geçmiş bayi başvurularını yönetin.</p>
        </div>
        <a href="{{ route('admin.bayiler.index') }}" class="btn btn-label-primary">
            <i class="bx bx-store-alt me-1"></i> Onaylı Bayiler
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="bx bx-check-circle me-2 fs-4"></i>
                <div class="d-flex flex-column">
                    <span class="fw-bold">{{ session('success') }}</span>
                    
                    @if(session('password'))
                        <div class="mt-2 p-3 bg-white bg-opacity-25 rounded border border-success border-opacity-25">
                            <small class="d-block mb-1">Otomatik oluşturulan kullanıcı bilgileri:</small>
                            <div class="d-flex gap-4">
                                <div>
                                    <span class="text-muted small">E-posta:</span><br>
                                    <strong>{{ session('email', 'N/A') }}</strong>
                                </div>
                                <div>
                                    <span class="text-muted small">Şifre:</span><br>
                                    <div class="input-group input-group-sm">
                                        <span class="form-control bg-white" id="generatedPass">{{ session('password') }}</span>
                                        <button class="btn btn-secondary" onclick="copyToClipboard('generatedPass', this)">
                                            <i class="bx bx-copy"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-2 text-danger small">
                                <i class="bx bx-error-circle me-1"></i>
                                Lütfen bu şifreyi kaydedin, sayfa yenilendiğinde kaybolacaktır!
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header border-bottom">
            <h5 class="card-title mb-0">Başvuru Listesi</h5>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="50">ID</th>
                        <th>Firma Bilgileri</th>
                        <th>Yetkili</th>
                        <th>İletişim</th>
                        <th>Durum</th>
                        <th>Tarih</th>
                        <th class="text-center">İşlemler</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse($basvurular as $basvuru)
                        <tr>
                            <td><strong>#{{ $basvuru->id }}</strong></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-3">
                                        <span class="avatar-initial rounded-circle bg-label-info">
                                            {{ substr($basvuru->firma_adi ?? 'F', 0, 1) }}
                                        </span>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="fw-semibold text-heading">{{ $basvuru->firma_adi ?? '-' }}</span>
                                        <small class="text-muted">{{ $basvuru->vergi_no ?? 'VN Yok' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="text-nowrap">{{ $basvuru->yetkili_ad }} {{ $basvuru->yetkili_soyad }}</span>
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="mb-1"><i class="bx bx-envelope me-1 text-muted"></i> {{ $basvuru->email }}</span>
                                    <small class="text-muted"><i class="bx bx-phone me-1"></i> {{ $basvuru->telefon ?? '-' }}</small>
                                </div>
                            </td>
                            <td>
                                @php
                                    $durumBadge = match($basvuru->durum) {
                                        'onaylandi' => 'bg-label-success',
                                        'reddedildi' => 'bg-label-danger',
                                        'beklemede' => 'bg-label-warning',
                                        default => 'bg-label-secondary'
                                    };
                                    $durumText = match($basvuru->durum) {
                                        'onaylandi' => 'Onaylandı',
                                        'reddedildi' => 'Reddedildi',
                                        'beklemede' => 'Beklemede',
                                        default => $basvuru->durum
                                    };
                                @endphp
                                <span class="badge {{ $durumBadge }}">{{ $durumText }}</span>
                            </td>
                            <td>
                                <span class="small text-muted" title="{{ $basvuru->created_at }}">
                                    {{ $basvuru->created_at->format('d.m.Y H:i') }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{ route('admin.bayiler.show', $basvuru->id) }}">
                                            <i class="bx bx-show-alt me-1"></i> Detaylar
                                        </a>
                                        
                                        @if($basvuru->durum == 'beklemede')
                                            <div class="dropdown-divider"></div>
                                            
                                            <form action="{{ route('admin.bayiler.approve', $basvuru->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="dropdown-item text-success" onclick="return confirm('Bu başvuruyu onaylamak ve bayi hesabı oluşturmak istiyor musunuz?')">
                                                    <i class="bx bx-check-circle me-1"></i> Onayla
                                                </button>
                                            </form>

                                            <form action="{{ route('admin.bayiler.reject', $basvuru->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Bu başvuruyu reddetmek istediğinize emin misiniz?')">
                                                    <i class="bx bx-x-circle me-1"></i> Reddet
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center">
                                    <div class="mb-3">
                                        <span class="badge bg-label-secondary p-3 rounded-circle">
                                            <i class='bx bx-file-blank fs-1'></i>
                                        </span>
                                    </div>
                                    <h5 class="text-muted">Başvuru Bulunamadı</h5>
                                    <p class="text-muted mb-0">Henüz listelenecek bir bayi başvurusu yok.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($basvurular instanceof \Illuminate\Pagination\LengthAwarePaginator && $basvurular->hasPages())
            <div class="card-footer border-top">
                {{ $basvurular->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>

<script>
function copyToClipboard(elementId, btnElement) {
    const text = document.getElementById(elementId).innerText;
    navigator.clipboard.writeText(text).then(() => {
        const originalHtml = btnElement.innerHTML;
        btnElement.innerHTML = '<i class="bx bx-check"></i>';
        btnElement.classList.remove('btn-secondary');
        btnElement.classList.add('btn-success');
        
        setTimeout(() => {
            btnElement.innerHTML = originalHtml;
            btnElement.classList.remove('btn-success');
            btnElement.classList.add('btn-secondary');
        }, 2000);
    });
}
</script>
@endsection