@extends('layouts.admin')

@section('title', 'Bayilerimiz')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h4 class="fw-bold py-3 mb-0">
                <span class="text-muted fw-light">Bayi Yönetimi /</span> Bayilerimiz
            </h4>
            <p class="text-muted mb-0">Sistemde kayıtlı ve onaylanmış bayilerin listesi.</p>
        </div>
        <a href="{{ route('admin.bayiler.basvurular') }}" class="btn btn-label-warning position-relative">
            <i class="bx bx-time-five me-1"></i> Bekleyen Başvurular
            </a>
    </div>

    <div class="card">
        <div class="card-header border-bottom">
            <h5 class="card-title mb-0">Onaylı Bayi Listesi</h5>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="50">ID</th>
                        <th>Firma / Bayi</th>
                        <th>Yetkili</th>
                        <th>İletişim</th>
                        <th>Onay Tarihi</th>
                        <th class="text-center">İşlemler</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse($bayiler as $bayi)
                        <tr>
                            <td><strong>#{{ $bayi->id }}</strong></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-3">
                                        <span class="avatar-initial rounded-circle bg-label-primary">
                                            {{ substr($bayi->firma_adi ?? 'B', 0, 1) }}
                                        </span>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="fw-semibold text-heading">{{ $bayi->firma_adi ?? 'İsimsiz Firma' }}</span>
                                        </div>
                                </div>
                            </td>
                            <td>
                                <span class="text-nowrap">{{ $bayi->yetkili_ad }} {{ $bayi->yetkili_soyad }}</span>
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <a href="mailto:{{ $bayi->email }}" class="text-body mb-1">
                                        <i class="bx bx-envelope me-1 text-muted"></i> {{ $bayi->email }}
                                    </a>
                                    <a href="tel:{{ $bayi->telefon }}" class="text-body small">
                                        <i class="bx bx-phone me-1 text-muted"></i> {{ $bayi->telefon ?? '-' }}
                                    </a>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-label-success">
                                    {{ $bayi->updated_at->format('d.m.Y') }}
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.bayiler.show', $bayi->id) }}" class="btn btn-sm btn-icon btn-label-info" data-bs-toggle="tooltip" title="Detay Görüntüle">
                                    <i class="bx bx-show-alt"></i>
                                </a>
                                </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center">
                                    <div class="mb-3">
                                        <span class="badge bg-label-secondary p-3 rounded-circle">
                                            <i class='bx bx-store fs-1'></i>
                                        </span>
                                    </div>
                                    <h5 class="text-muted">Kayıt Bulunamadı</h5>
                                    <p class="text-muted mb-0">Henüz onaylanmış bir bayi bulunmuyor.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($bayiler instanceof \Illuminate\Pagination\LengthAwarePaginator && $bayiler->hasPages())
            <div class="card-footer border-top">
                {{ $bayiler->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>
@endsection