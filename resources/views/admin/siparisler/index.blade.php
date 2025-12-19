@extends('layouts.admin')

@section('title', 'Sipariş Yönetimi')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Satış /</span> Siparişler
    </h4>

    <div class="card">
        <div class="card-header border-bottom">
            <h5 class="card-title mb-3">Sipariş Yönetimi</h5>
            
            <form action="{{ route('admin.siparisler.index') }}" method="GET" id="filterForm">
                <div class="d-flex justify-content-between align-items-center row pb-2 gap-3 gap-md-0">
                    <div class="col-md-4">
                        <select name="durum" class="form-select text-capitalize" onchange="document.getElementById('filterForm').submit()">
                            <option value="">Tüm Durumlar</option>
                            <option value="beklemede" {{ request('durum') == 'beklemede' ? 'selected' : '' }}>Beklemede</option>
                            <option value="onaylandi" {{ request('durum') == 'onaylandi' ? 'selected' : '' }}>Onaylandı</option>
                            <option value="hazirlaniyor" {{ request('durum') == 'hazirlaniyor' ? 'selected' : '' }}>Hazırlanıyor</option>
                            <option value="kargoda" {{ request('durum') == 'kargoda' ? 'selected' : '' }}>Kargoda</option>
                            <option value="teslim_edildi" {{ request('durum') == 'teslim_edildi' ? 'selected' : '' }}>Teslim Edildi</option>
                            <option value="iptal_edildi" {{ request('durum') == 'iptal_edildi' ? 'selected' : '' }}>İptal Edildi</option>
                        </select>
                    </div>
                    
                    <div class="col-md-4">
                        </div> 

                    <div class="col-md-4">
                        <div class="input-group">
                            <input type="text" name="q" class="form-control" placeholder="Sipariş No, Müşteri..." value="{{ request('q') }}">
                            <button class="btn btn-outline-primary" type="submit"><i class="bx bx-search"></i></button>
                            
                            @if(request()->has('durum') || request()->has('q'))
                                <a href="{{ route('admin.siparisler.index') }}" class="btn btn-outline-danger" title="Filtreleri Temizle">
                                    <i class='bx bx-x'></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Sipariş No</th>
                        <th>Müşteri</th>
                        <th>Tarih</th>
                        <th>Ödeme</th>
                        <th>Tutar</th>
                        <th>Durum</th>
                        <th class="text-center">İşlem</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse($siparisler as $siparis)
                    <tr onclick="window.location='{{ route('admin.siparisler.show', $siparis->id) }}'" style="cursor: pointer;">
                        <td><span class="fw-bold text-primary">#{{ $siparis->siparis_no }}</span></td>
                        <td>
                            <div class="d-flex justify-content-start align-items-center user-name">
                                <div class="avatar-wrapper">
                                    <div class="avatar avatar-sm me-3">
                                        <span class="avatar-initial rounded-circle bg-label-secondary">
                                            {{ substr($siparis->user->name ?? 'M', 0, 1) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="fw-semibold text-heading">{{ $siparis->user->name ?? 'Misafir' }}</span>
                                    <small class="text-muted">{{ $siparis->user->email ?? '-' }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="text-nowrap">{{ $siparis->created_at->format('d.m.Y') }}</span>
                            <small class="text-muted d-block">{{ $siparis->created_at->format('H:i') }}</small>
                        </td>
                        <td>
                            @if($siparis->odeme_durumu == 'odendi')
                                <span class="badge bg-label-success">Ödendi</span>
                            @elseif($siparis->odeme_durumu == 'iptal_edildi')
                                <span class="badge bg-label-danger">İptal</span>
                            @else
                                <span class="badge bg-label-warning">Bekleniyor</span>
                            @endif
                        </td>
                        <td>
                            <span class="fw-bold">₺{{ number_format($siparis->toplam_tutar, 2) }}</span>
                        </td>
                        <td>
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
                            <span class="badge {{ $badgeClass }} me-1">{{ ucfirst(str_replace('_', ' ', $siparis->durum)) }}</span>
                        </td>
                        <td class="text-center" onclick="event.stopPropagation()">
                            @if($siparis->durum != 'iptal_edildi' && $siparis->durum != 'teslim_edildi')
                                <button type="button" class="btn btn-sm btn-icon btn-label-info" 
                                        onclick="durumModal({{ $siparis->id }},'{{ $siparis->durum }}')"
                                        data-bs-toggle="tooltip" 
                                        title="Hızlı Durum Güncelle">
                                    <i class="bx bx-edit-alt"></i>
                                </button>
                            @else
                                <span class="text-muted"><i class="bx bx-check-double fs-4"></i></span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="d-flex flex-column align-items-center">
                                <i class="bx bx-cart-alt fs-1 text-muted mb-2" style="font-size: 3rem;"></i>
                                <p class="text-muted fw-semibold">Aradığınız kriterlere uygun sipariş bulunamadı.</p>
                                @if(request('durum') || request('q'))
                                    <a href="{{ route('admin.siparisler.index') }}" class="btn btn-sm btn-outline-primary mt-2">Filtreleri Temizle</a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($siparisler->hasPages())
        <div class="card-footer border-top">
            <div class="d-flex justify-content-end">
                {{ $siparisler->links('pagination::bootstrap-5') }}
            </div>
        </div>
        @endif
    </div>
</div>

<div class="modal fade" id="durumModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Sipariş Durumu Güncelle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="modal_siparis_id">
                <div class="mb-3">
                    <label class="form-label">Yeni Durum</label>
                    <select id="modal_durum" class="form-select">
                        <option value="beklemede">Beklemede</option>
                        <option value="onaylandi">Onaylandı</option>
                        <option value="hazirlaniyor">Hazırlanıyor</option>
                        <option value="kargoda">Kargoda</option>
                        <option value="teslim_edildi">Teslim Edildi</option>
                        <option value="iptal_edildi">İptal Edildi</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Not (Opsiyonel)</label>
                    <textarea id="modal_not" class="form-control" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">İptal</button>
                <button type="button" class="btn btn-primary" onclick="durumGuncelle()">Kaydet</button>
            </div>
        </div>
    </div>
</div>

<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Tooltip ve Modal JS aynı kalacak
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
});

function durumModal(id, mevcutDurum){
    document.getElementById('modal_siparis_id').value = id;
    document.getElementById('modal_durum').value = mevcutDurum;
    document.getElementById('modal_not').value = '';
    new bootstrap.Modal(document.getElementById('durumModal')).show();
}

function durumGuncelle(){
    const id = document.getElementById('modal_siparis_id').value;
    const durum = document.getElementById('modal_durum').value;
    const not = document.getElementById('modal_not').value;

    fetch(`/admin/siparisler/${id}/durum-guncelle`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ durum: durum, not: not })
    })
    .then(res => res.json())
    .then(data => {
        if(data.success){
            const modalEl = document.getElementById('durumModal');
            const modal = bootstrap.Modal.getInstance(modalEl);
            modal.hide();
            Swal.fire({
                icon: 'success',
                title: 'Başarılı',
                text: 'Durum güncellendi',
                timer: 1500,
                showConfirmButton: false
            }).then(() => location.reload());
        }
    });
}
</script>
@endsection