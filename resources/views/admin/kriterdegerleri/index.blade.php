@extends('layouts.admin')

@section('title', 'Kriter Değerleri - Admin Panel')

@section('content')
<div class="container-xxl flex-grow-1 container-py-4">
    <h4 class="fw-bold py-3 mb-4">Kriter Değerleri</h4>

    <div class="mb-4 text-end">
        <a href="{{ route('admin.kriterdegerleri.create') }}" class="btn btn-primary">
            <i class="bx bx-plus me-1"></i> Yeni Kriter Değeri Ekle
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Kriter</th>
                        <th>Alt Kategori</th>
                        <th>Değer</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach($degerler as $deger)
                        <tr>
                            <td>{{ $deger->id }}</td>
                            <td>{{ $deger->kriter->kriter_ad }}</td>
                            <td>{{ $deger->altKategori->alt_kategori_ad }}</td>
                            <td>{{ $deger->deger }}</td>
                            <td>
                                <a href="{{ route('admin.kriterdegerleri.edit', $deger->id) }}" class="btn btn-sm btn-warning">
                                    <i class="bx bx-edit me-1"></i> Düzenle
                                </a>
                                <form action="{{ route('admin.kriterdegerleri.destroy', $deger->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Silmek istediğinize emin misiniz?')" class="btn btn-sm btn-danger">
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
</div>
@endsection
