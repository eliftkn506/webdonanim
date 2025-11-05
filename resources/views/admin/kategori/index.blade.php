@extends('layouts.admin')

@section('title', 'Kategoriler - Admin Panel')

@section('content')
<div class="container-xxl flex-grow-1 container-py-4">
    <h4 class="fw-bold py-3 mb-4">Kategoriler</h4>

    <div class="mb-4 text-end">
        <a href="{{ route('admin.kategoriler.create') }}" class="btn btn-primary">
            <i class="bx bx-plus me-1"></i> Yeni Kategori Ekle
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Kategori Adı</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach($kategoriler as $kategori)
                        <tr>
                            <td>{{ $kategori->id }}</td>
                            <td>{{ $kategori->kategori_ad }}</td>
                            <td>
                                <a href="{{ route('admin.kategoriler.edit', $kategori->id) }}" class="btn btn-sm btn-warning">
                                    <i class="bx bx-edit me-1"></i> Düzenle
                                </a>
                                <form action="{{ route('admin.kategoriler.destroy', $kategori->id) }}" method="POST" style="display:inline-block;">
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
