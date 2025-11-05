@extends('layouts.admin')
@section('title', 'Kuponlar')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h4>Kupon Yönetimi</h4>
    <div>
        <button onclick="otomatikAta()" class="btn btn-info me-2">
            <i class="fas fa-magic"></i> Otomatik Ata
        </button>
        <a href="{{ route('admin.kuponlar.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Yeni Kupon Ekle
        </a>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Kupon Kodu</th>
                        <th>Başlık</th>
                        <th>Tür</th>
                        <th>İndirim</th>
                        <th>Kullanım</th>
                        <th>Tarih</th>
                        <th>Durum</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kuponlar as $kupon)
                    <tr>
                        <td>{{ $kupon->id }}</td>
                        <td>
                            <strong>{{ $kupon->kupon_kodu }}</strong>
                        </td>
                        <td>{{ $kupon->baslik }}</td>
                        <td>
                            @switch($kupon->kupon_turu)
                                @case('genel')
                                    <span class="badge bg-primary">Genel</span>
                                    @break
                                @case('kullanici_ozel')
                                    <span class="badge bg-warning">Kullanıcı Özel</span>
                                    <br><small>{{ $kupon->kullanicilar->count() }} kullanıcı</small>
                                    @break
                                @case('kural_bazli')
                                    <span class="badge bg-info">Kural Bazlı</span>
                                    <br><small>{{ $kupon->kullanicilar->count() }} atanmış</small>
                                    @break
                            @endswitch
                        </td>
                        <td>
                            <strong>
                                {{ $kupon->indirim_miktari }} 
                                {{ $kupon->indirim_tipi == 'yuzde' ? '%' : '₺' }}
                            </strong>
                            @if($kupon->minimum_tutar)
                                <br><small class="text-muted">Min: ₺{{ number_format($kupon->minimum_tutar, 2) }}</small>
                            @endif
                        </td>
                        <td>
                            {{ $kupon->kullanilan_adet ?? 0 }} 
                            @if($kupon->kullanim_limiti)
                                / {{ $kupon->kullanim_limiti }}
                            @else
                                / ∞
                            @endif
                        </td>
                        <td>
                            <small>
                                {{ $kupon->baslangic_tarihi->format('d.m.Y') }}<br>
                                {{ $kupon->bitis_tarihi->format('d.m.Y') }}
                            </small>
                        </td>
                        <td>
                            @if($kupon->aktif)
                                @if(now()->between($kupon->baslangic_tarihi, $kupon->bitis_tarihi))
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Beklemede</span>
                                @endif
                            @else
                                <span class="badge bg-danger">Pasif</span>
                            @endif
                            
                            @if($kupon->kupon_turu === 'kural_bazli' && $kupon->otomatik_ata)
                                <br><span class="badge bg-info mt-1">Oto-Atama</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.kuponlar.edit', $kupon->id) }}" 
                                   class="btn btn-sm btn-warning" title="Düzenle">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.kuponlar.destroy', $kupon->id) }}" 
                                      method="POST" style="display:inline-block"
                                      onsubmit="return confirm('Bu kuponu silmek istediğinizden emin misiniz?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" title="Sil">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Henüz kupon bulunmuyor</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function otomatikAta() {
    if (!confirm('Kural bazlı kuponlar uygun kullanıcılara otomatik atanacak. Devam edilsin mi?')) {
        return;
    }
    
    fetch('{{ route("admin.kuponlar.otomatik-ata") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        alert('Başarılı! ' + data.atanan_sayisi + ' kupon atandı.');
        location.reload();
    })
    .catch(error => {
        alert('Hata oluştu: ' + error);
    });
}
</script>

@endsection