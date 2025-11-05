@extends('layouts.admin')

@section('title', 'Fiyat Yönetimi')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">
                <i class="fas fa-tags me-2"></i>Fiyat Tanımları
            </h4>
            <a href="{{ route('admin.fiyatlar.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>Yeni Fiyat Ekle
            </a>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Fiyat Türü</th>
                            <th>Maliyet</th>
                            <th>Kar Oranı</th>
                            <th>Bayi İndirimi</th>
                            <th>Vergi Oranı</th>
                            <th>Temel Fiyat</th>
                            <th>Vergi Dahil</th>
                            <th>Bayi Fiyat</th>
                            <th>İlişkili Ürün</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($fiyatlar as $fiyat)
                            @php
                                $hesaplama = app('App\Http\Controllers\Admin\UrunFiyatController')->hesaplaFiyat($fiyat);
                                $urun = $fiyat->urun; // Her fiyata ait ürün
                            @endphp
                            <tr>
                                <td><strong>#{{ $fiyat->fiyat_id }}</strong></td>
                                <td>
                                    @if($fiyat->fiyat_turu == 'standart')
                                        <span class="badge bg-primary">Standart</span>
                                    @elseif($fiyat->fiyat_turu == 'bayi')
                                        <span class="badge bg-info">Bayi</span>
                                    @else
                                        <span class="badge bg-success">Kampanya</span>
                                    @endif
                                </td>
                                <td>{{ number_format($fiyat->maliyet, 2) }} ₺</td>
                                <td>%{{ number_format($fiyat->kar_orani, 2) }}</td>
                                <td>%{{ number_format($fiyat->bayi_indirimi, 2) }}</td>
                                <td>%{{ number_format($fiyat->vergi_orani, 2) }}</td>
                                <td>{{ number_format($hesaplama['temel_fiyat'], 2) }} ₺</td>
                                <td><strong>{{ number_format($hesaplama['vergi_dahil_fiyat'], 2) }} ₺</strong></td>
                                <td>
                                    @if($hesaplama['bayi_fiyat'])
                                        {{ number_format($hesaplama['bayi_fiyat'], 2) }} ₺
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($urun)
                                        <a href="{{ route('admin.urunler.edit', $urun) }}" class="text-decoration-none">
                                            <span class="badge bg-secondary">{{ $urun->urun_ad }}</span>
                                        </a>
                                    @else
                                        <span class="text-muted">Ürün Yok</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.fiyatlar.edit', $fiyat) }}" 
                                           class="btn btn-sm btn-warning" 
                                           title="Düzenle">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.fiyatlar.destroy', $fiyat) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Bu fiyat tanımını silmek istediğinize emin misiniz?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-danger" 
                                                    title="Sil"
                                                    {{ $urun ? 'disabled' : '' }}>
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center py-4">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Henüz fiyat tanımı eklenmemiş.</p>
                                    <a href="{{ route('admin.fiyatlar.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus me-1"></i>İlk Fiyatı Ekle
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $fiyatlar->links() }}
            </div>
        </div>
    </div>

    <!-- Bilgi Kartları -->
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card border-primary">
                <div class="card-body">
                    <h6 class="card-title text-primary">
                        <i class="fas fa-info-circle me-2"></i>Fiyat Türleri
                    </h6>
                    <ul class="mb-0">
                        <li><strong>Standart:</strong> Normal satış fiyatı</li>
                        <li><strong>Bayi:</strong> Bayiler için özel fiyat</li>
                        <li><strong>Kampanya:</strong> İndirimli kampanya fiyatı</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-info">
                <div class="card-body">
                    <h6 class="card-title text-info">
                        <i class="fas fa-calculator me-2"></i>Hesaplama Formülü
                    </h6>
                    <ul class="mb-0">
                        <li>Temel Fiyat = Maliyet + (Maliyet × Kar Oranı)</li>
                        <li>Vergi Dahil = Temel Fiyat + (Temel Fiyat × Vergi)</li>
                        <li>Bayi Fiyat = Vergi Dahil - (Vergi Dahil × İndirim)</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-warning">
                <div class="card-body">
                    <h6 class="card-title text-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>Önemli Notlar
                    </h6>
                    <ul class="mb-0">
                        <li>Ürünlerle ilişkili fiyatlar silinemez</li>
                        <li>Fiyat değişiklikleri anında geçerli olur</li>
                        <li>Tarih aralığı ile fiyat planlayabilirsiniz</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.table-hover tbody tr:hover {
    background-color: #f8f9fa;
    transition: all 0.3s ease;
}

.btn-group .btn {
    margin: 0 2px;
}

.card {
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.card:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.badge {
    font-size: 0.85rem;
    padding: 0.35rem 0.65rem;
}
</style>
@endsection
