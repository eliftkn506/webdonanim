@extends('layouts.admin')

@section('title', 'Bayi Başvuruları - Admin Panel')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Bayi Başvuruları</h5>
        <a href="{{ route('admin.bayiler.index') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-users me-1"></i> Onaylı Bayiler
        </a>
    </div>
    <div class="card-body">
        {{-- Başarılı Onay Mesajı ve Şifre Gösterimi --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <h5 class="alert-heading">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </h5>
                
                @if(session('password'))
                    <hr>
                    <div class="mt-3">
                        <p class="mb-2"><strong>Oluşturulan Kullanıcı Bilgileri:</strong></p>
                        <div class="bg-light p-3 rounded border">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>E-posta:</strong> {{ session('email', 'N/A') }}
                                </div>
                                <div class="col-md-6">
                                    <strong>Şifre:</strong> 
                                    <span class="badge bg-danger fs-6" id="generatedPassword">
                                        {{ session('password') }}
                                    </span>
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-secondary ms-2" 
                                            onclick="copyPassword()"
                                            title="Şifreyi Kopyala">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="mt-3">
                                <small class="text-danger">
                                    <i class="fas fa-exclamation-triangle"></i> 
                                    Bu şifreyi kaydedin! Bu sayfa yenilendiğinde şifre bir daha gösterilmeyecektir.
                                </small>
                            </div>
                        </div>
                    </div>
                @endif
                
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        @if(session('danger'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('danger') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>#ID</th>
                        <th>Firma Adı</th>
                        <th>Yetkili</th>
                        <th>Email</th>
                        <th>Telefon</th>
                        <th>Durum</th>
                        <th>Tarih</th>
                        <th class="text-center">İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($basvurular as $basvuru)
                    <tr>
                        <td>{{ $basvuru->id }}</td>
                        <td>{{ $basvuru->firma_adi ?? '-' }}</td>
                        <td>{{ $basvuru->yetkili_ad }} {{ $basvuru->yetkili_soyad }}</td>
                        <td>{{ $basvuru->email }}</td>
                        <td>{{ $basvuru->telefon ?? '-' }}</td>
                        <td>
                            @if($basvuru->durum == 'onaylandi')
                                <span class="badge bg-success">Onaylandı</span>
                            @elseif($basvuru->durum == 'reddedildi')
                                <span class="badge bg-danger">Reddedildi</span>
                            @else
                                <span class="badge bg-warning">Beklemede</span>
                            @endif
                        </td>
                        <td>{{ $basvuru->created_at->format('d.m.Y H:i') }}</td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.bayiler.show', $basvuru->id) }}" 
                                   class="btn btn-sm btn-info" 
                                   title="Detay">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                @if($basvuru->durum == 'beklemede')
                                    <form method="POST" 
                                          action="{{ route('admin.bayiler.approve', $basvuru->id) }}" 
                                          class="d-inline"
                                          onsubmit="return confirm('Bu başvuruyu onaylamak istediğinizden emin misiniz?')">
                                        @csrf
                                        <button type="submit" 
                                                class="btn btn-sm btn-success" 
                                                title="Onayla">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    
                                    <form method="POST" 
                                          action="{{ route('admin.bayiler.reject', $basvuru->id) }}" 
                                          class="d-inline"
                                          onsubmit="return confirm('Bu başvuruyu reddetmek istediğinizden emin misiniz?')">
                                        @csrf
                                        <button type="submit" 
                                                class="btn btn-sm btn-danger" 
                                                title="Reddet">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                @else
                                    <span class="badge bg-secondary">İşlem Tamamlandı</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                            Henüz bayi başvurusu bulunmamaktadır.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Şifre Kopyalama JavaScript --}}
@push('scripts')
<script>
function copyPassword() {
    const password = document.getElementById('generatedPassword').innerText;
    
    // Clipboard API kullanarak kopyala
    navigator.clipboard.writeText(password).then(function() {
        // Başarılı kopyalama bildirimi
        const btn = event.target.closest('button');
        const originalHTML = btn.innerHTML;
        
        btn.innerHTML = '<i class="fas fa-check"></i>';
        btn.classList.remove('btn-outline-secondary');
        btn.classList.add('btn-success');
        
        setTimeout(function() {
            btn.innerHTML = originalHTML;
            btn.classList.remove('btn-success');
            btn.classList.add('btn-outline-secondary');
        }, 2000);
    }, function(err) {
        alert('Şifre kopyalanamadı: ' + err);
    });
}
</script>
@endpush
@endsection