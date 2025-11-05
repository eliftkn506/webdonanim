@extends('layouts.admin')

@section('title', 'Sipariş Yönetimi - Admin Panel')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Sipariş Tablosu -->
<div class="order-table">
    <div class="table-header">
        <h5 class="mb-0"><i class="fas fa-shopping-cart me-2"></i>Siparişler</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead style="background:#f8f9fa;">
                <tr>
                    <th>Sipariş No</th>
                    <th>Müşteri</th>
                    <th>Tarih</th>
                    <th>Tutar</th>
                    <th>Durum</th>
                    <th>Ödeme</th>
                    <th>İşlemler</th>
                </tr>
            </thead>
            <tbody>
                @forelse($siparisler as $siparis)
                <tr>
                    <td>{{ $siparis->siparis_no }}</td>
                    <td>{{ $siparis->user->name ?? 'Misafir' }}</td>
                    <td>{{ $siparis->created_at->format('d.m.Y') }}</td>
                    <td>₺{{ number_format($siparis->toplam_tutar + $siparis->kdv_tutari - $siparis->indirim_tutari,2) }}</td>
                    <td>{{ ucfirst(str_replace('_',' ',$siparis->durum)) }}</td>
                    <td>{{ ucfirst($siparis->odeme_durumu) }}</td>
                    <td>
                        <a href="{{ route('admin.siparisler.show',$siparis->id) }}" class="btn btn-sm btn-primary">Detay</a>
                        @if($siparis->durum != 'iptal_edildi')
                        <button class="btn btn-sm btn-success" onclick="durumModal({{ $siparis->id }},'{{ $siparis->durum }}')">Güncelle</button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5">Sipariş bulunamadı</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Durum Güncelleme Modal -->
<div class="modal fade" id="durumModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Sipariş Durumu Güncelle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="durumForm">
                    <input type="hidden" id="siparis_id">
                    <div class="mb-3">
                        <label class="form-label">Yeni Durum</label>
                        <select class="form-select" id="durum" required>
                            @foreach(['beklemede','onaylandi','hazirlaniyor','kargoda','teslim_edildi','iptal_edildi'] as $durum)
                                <option value="{{ $durum }}">{{ ucfirst(str_replace('_',' ',$durum)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Not (Opsiyonel)</label>
                        <textarea class="form-control" id="not" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                <button type="button" class="btn btn-primary" onclick="durumGuncelle()">Güncelle</button>
            </div>
        </div>
    </div>
</div>

<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
function durumModal(id, mevcutDurum){
    document.getElementById('siparis_id').value = id;
    document.getElementById('durum').value = mevcutDurum;
    document.getElementById('not').value = '';
    new bootstrap.Modal(document.getElementById('durumModal')).show();
}

function durumGuncelle(){
    const id = document.getElementById('siparis_id').value;
    const durum = document.getElementById('durum').value;
    const not = document.getElementById('not').value;

    fetch(`/admin/siparisler/${id}/durum-guncelle`, { // <-- Blade url yerine direkt path
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ durum: durum, not: not })
    })
    .then(res => {
        if(!res.ok) throw new Error('Sunucu hatası');
        return res.json();
    })
    .then(data => {
        if(data.success){
            const modalEl = document.getElementById('durumModal');
            const modal = bootstrap.Modal.getInstance(modalEl);
            modal.hide();

            Swal.fire({
                icon: 'success',
                title: 'Başarılı',
                text: data.message,
                timer: 1500,
                showConfirmButton: false
            }).then(()=> location.reload());
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Hata',
                text: data.message || 'Güncelleme başarısız'
            });
        }
    })
    .catch(err => {
        console.error(err);
        Swal.fire({
            icon: 'error',
            title: 'Hata',
            text: 'Sunucuya ulaşılamıyor'
        });
    });
}
</script>

@endsection
