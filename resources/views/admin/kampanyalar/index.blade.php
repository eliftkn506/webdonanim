@extends('layouts.admin')

@section('title', 'Kampanya Yönetimi - Admin Panel')

@section('content')
<div class="container-xxl flex-grow-1 container-py-4">
    <h4 class="fw-bold py-3 mb-4">Kampanya Yönetimi</h4>

    <div class="mb-3 text-end">
        <a href="{{ route('admin.kampanyalar.create') }}" class="btn btn-primary">
            <i class="bx bx-plus me-1"></i> Yeni Kampanya Ekle
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Kampanya Adı</th>
                        <th>Ürün</th>
                        <th>İndirim Oranı</th>
                        <th>Yeni Fiyat</th>
                        <th>Tarih Aralığı</th>
                        <th>Aktif</th>
                        <th class="text-center">İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kampanyalar as $kampanya)
                        <tr>
                            <td>{{ $kampanya->id }}</td>
                            <td>{{ $kampanya->kampanya_adi }}</td>
                            <td>{{ $kampanya->urun->urun_ad ?? 'Ürün Yok' }}</td>
                            <td>%{{ $kampanya->indirim_orani }}</td>
                            <td>₺{{ number_format($kampanya->yeni_fiyat, 2, ',', '.') }}</td>
                            <td>{{ $kampanya->baslangic_tarihi }} - {{ $kampanya->bitis_tarihi }}</td>
                            <td>{{ $kampanya->aktif ? 'Evet' : 'Hayır' }}</td>
                            <td class="text-center">
                                <a href="{{ route('admin.kampanyalar.edit', $kampanya->id) }}" class="btn btn-sm btn-warning me-1">
                                    <i class="bx bx-edit me-1"></i> Düzenle
                                </a>
                                <form action="{{ route('admin.kampanyalar.destroy', $kampanya->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Silmek istediğine emin misin?')" class="btn btn-sm btn-danger">
                                        <i class="bx bx-trash me-1"></i> Sil
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $kampanyalar->links() }}
    </div>
</div>
@endsection
