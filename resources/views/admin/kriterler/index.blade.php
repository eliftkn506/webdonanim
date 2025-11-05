@extends('layouts.admin')

@section('title', 'Kriterler - Admin Panel')

@section('content')
<div class="container-xxl flex-grow-1 container-py-4">
    <h4 class="fw-bold py-3 mb-4">Kriterler</h4>

    <div class="mb-4 text-end">
        <a href="{{ route('admin.kriterler.create') }}" class="btn btn-primary">
            <i class="bx bx-plus me-1"></i> Yeni Kriter Ekle
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
                        <th>Kriter Adı</th>
                        <th>Alt Kategori</th>
                        <th class="text-center">İşlemler</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach($kriterler as $kriter)
                        <tr>
                            <td class="fw-semibold">{{ $kriter->id }}</td>
                            <td>{{ $kriter->kriter_ad }}</td>
                            <td>{{ $kriter->altKategori->alt_kategori_ad }}</td>
                            <td class="text-center">
                                <a href="{{ route('admin.kriterler.edit', $kriter->id) }}" class="btn btn-sm btn-warning me-1">
                                    <i class="bx bx-edit me-1"></i> Düzenle
                                </a>
                                <form action="{{ route('admin.kriterler.destroy', $kriter->id) }}" method="POST" class="d-inline">
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
