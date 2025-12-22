@extends('layouts.admin')
@section('title', 'Değerlendirme Yönetimi')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Ürün Değerlendirmeleri</h1>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Yorum Listesi</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Ürün</th>
                            <th>Kullanıcı</th>
                            <th>Puan</th>
                            <th>Yorum</th>
                            <th>Sizin Cevabınız</th>
                            <th>Durum</th>
                            <th width="150">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($degerlendirmeler as $yorum)
                        <tr>
                            <td>
                                @if($yorum->urun)
                                    <a href="{{ route('urun.incele', $yorum->urun->id) }}" target="_blank">
                                        {{ \Illuminate\Support\Str::limit($yorum->urun->urun_ad, 30) }}
                                    </a>
                                @else
                                    <span class="text-danger">Ürün Silinmiş</span>
                                @endif
                            </td>
                            <td>{{ $yorum->user->name ?? 'Misafir' }}<br><small>{{ $yorum->created_at->format('d.m.Y') }}</small></td>
                            <td>
                                <span class="text-warning">{{ str_repeat('★', $yorum->puan) }}</span>
                                <span class="text-muted">{{ str_repeat('★', 5 - $yorum->puan) }}</span>
                            </td>
                            <td>{{ $yorum->yorum }}</td>
                            <td>
                                @if($yorum->cevap)
                                    <div class="alert alert-info p-2 mb-0" style="font-size: 0.9rem;">
                                        <strong>Cevap:</strong> {{ $yorum->cevap }}
                                        <form action="{{ route('admin.degerlendirmeler.cevapSil', $yorum->id) }}" method="POST" class="d-inline float-end">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-link text-danger p-0 m-0" style="font-size: 0.8rem;">Sil</button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($yorum->onay)
                                    <span class="badge bg-success">Yayında</span>
                                @else
                                    <span class="badge bg-warning text-dark">Gizli</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <form action="{{ route('admin.degerlendirmeler.onayla', $yorum->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm {{ $yorum->onay ? 'btn-secondary' : 'btn-success' }}" title="{{ $yorum->onay ? 'Gizle' : 'Yayınla' }}">
                                            <i class="fas fa-{{ $yorum->onay ? 'eye-slash' : 'check' }}"></i>
                                        </button>
                                    </form>

                                    <button type="button" class="btn btn-sm btn-primary mx-1" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#cevapModal" 
                                            data-id="{{ $yorum->id }}"
                                            data-user="{{ $yorum->user->name ?? 'Kullanıcı' }}"
                                            data-yorum="{{ $yorum->yorum }}"
                                            data-cevap="{{ $yorum->cevap }}">
                                        <i class="fas fa-reply"></i>
                                    </button>

                                    <form action="{{ route('admin.degerlendirmeler.sil', $yorum->id) }}" method="POST" onsubmit="return confirm('Bu yorumu silmek istediğinize emin misiniz?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center">Henüz değerlendirme yok.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $degerlendirmeler->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<div class="modal fade" id="cevapModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="cevapForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Yorumu Cevapla</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3 p-3 bg-light rounded">
                        <small class="text-muted fw-bold" id="modalUser"></small> diyor ki:
                        <p class="mb-0 fst-italic" id="modalYorum"></p>
                    </div>
                    <div class="mb-3">
                        <label for="cevap" class="form-label">Cevabınız:</label>
                        <textarea class="form-control" name="cevap" id="modalCevapInput" rows="4" required placeholder="Müşteriye yanıtınızı yazın..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                    <button type="submit" class="btn btn-primary">Cevabı Kaydet</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    var cevapModal = document.getElementById('cevapModal');
    cevapModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        
        // Verileri al
        var id = button.getAttribute('data-id');
        var user = button.getAttribute('data-user');
        var yorum = button.getAttribute('data-yorum');
        var mevcutCevap = button.getAttribute('data-cevap');

        // Modal içeriğini doldur
        var form = document.getElementById('cevapForm');
        form.action = '/admin/degerlendirmeler/' + id + '/cevapla'; // Rota URL yapısına dikkat

        document.getElementById('modalUser').textContent = user;
        document.getElementById('modalYorum').textContent = yorum;
        document.getElementById('modalCevapInput').value = mevcutCevap ? mevcutCevap : '';
    });
</script>
@endsection