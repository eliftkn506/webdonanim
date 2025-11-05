@extends('layouts.admin')

@section('title', 'Alt Kategoriler - Admin Panel')

@section('content')
<div class="container-xxl flex-grow-1 container-py-4">
    <h4 class="fw-bold py-3 mb-4">
        Alt Kategoriler
    </h4>

    <!-- Başarı Mesajı -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Yeni Alt Kategori Butonu -->
    <div class="mb-3 text-end">
        <a href="{{ route('admin.altkategoriler.create') }}" class="btn btn-primary">
            <i class="bx bx-plus me-1"></i> Yeni Alt Kategori Ekle
        </a>
    </div>

    <!-- Tablo -->
    <div class="card">
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Kategori</th>
                        <th>Alt Kategori Adı</th>
                        <th class="text-center">İşlemler</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach($altKategoriler as $alt)
                    <tr>
                        <td>{{ $alt->id }}</td>
                        <td>{{ $alt->kategori->kategori_ad }}</td>
                        <td>{{ $alt->alt_kategori_ad }}</td>
                        <td class="text-center">
                            <a href="{{ route('admin.altkategoriler.edit', $alt->id) }}" class="btn btn-sm btn-warning">
                                <i class="bx bx-edit"></i>
                            </a>
                            <form action="{{ route('admin.altkategoriler.destroy', $alt->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Silmek istediğinizden emin misiniz?')" class="btn btn-sm btn-danger">
                                    <i class="bx bx-trash"></i>
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
