@extends('layouts.admin')

@section('title', 'Kampanya Yönetimi')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">Kampanyalar</h4>
        <a href="{{ route('admin.kampanyalar.create') }}" class="btn btn-primary">
            <i class="bx bx-plus me-1"></i> Yeni Kampanya
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Kampanya Adı</th>
                        <th>Kapsam</th>
                        <th>Hedef</th>
                        <th>İndirim</th>
                        <th>Tarih Aralığı</th>
                        <th>Durum</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kampanyalar as $kampanya)
                        <tr>
                            <td><strong>{{ $kampanya->kampanya_adi }}</strong></td>
                            <td>
                                @if($kampanya->kapsam == 'tum')
                                    <span class="badge bg-label-primary">Tüm Mağaza</span>
                                @elseif($kampanya->kapsam == 'kategori')
                                    <span class="badge bg-label-info">Kategori</span>
                                @else
                                    <span class="badge bg-label-warning">Ürün</span>
                                @endif
                            </td>
                            <td>
                                @if($kampanya->kapsam == 'tum')
                                    -
                                @elseif($kampanya->kapsam == 'kategori')
                                    {{ $kampanya->kategori->alt_kategori_ad ?? 'Silinmiş Kategori' }}
                                @else
                                    {{ $kampanya->urun->urun_ad ?? 'Silinmiş Ürün' }}
                                @endif
                            </td>
                            <td>
                                @if($kampanya->indirim_orani)
                                    %{{ $kampanya->indirim_orani }}
                                @else
                                    {{ $kampanya->yeni_fiyat }} ₺
                                @endif
                            </td>
                            <td>
                                <small>{{ $kampanya->baslangic_tarihi }}</small><br>
                                <small>{{ $kampanya->bitis_tarihi }}</small>
                            </td>
                            <td>
                                @if($kampanya->aktif)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-danger">Pasif</span>
                                @endif
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{ route('admin.kampanyalar.edit', $kampanya->id) }}">
                                            <i class="bx bx-edit-alt me-1"></i> Düzenle
                                        </a>
                                        <form action="{{ route('admin.kampanyalar.destroy', $kampanya->id) }}" method="POST" onsubmit="return confirm('Silmek istediğinize emin misiniz?')">
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
        <div class="card-footer">
            {{ $kampanyalar->links() }}
        </div>
    </div>
</div>
@endsection