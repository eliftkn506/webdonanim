@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                "{{ $kriter->kriter_ad ?? 'Kriter' }}" İçin Değer Listesi
            </h6>
            
            {{-- DÜZELTME: Ekleme Butonu --}}
            <a href="{{ route('admin.kriterdegerleri.create', $kriter->id) }}" class="btn btn-primary btn-sm">
                <i class="fa fa-plus"></i> Yeni Değer Ekle
            </a>
        </div>
        <div class="card-body">
            
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Değer Adı</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($degerler as $deger)
                        <tr>
                            <td>{{ $deger->id }}</td>
                            <td>{{ $deger->deger }}</td>
                            <td>
                                {{-- DÜZELTME: Düzenle --}}
                                <a href="{{ route('admin.kriterdegerleri.edit', $deger->id) }}" class="btn btn-warning btn-sm">
                                    Düzenle
                                </a>

                                {{-- DÜZELTME: Sil --}}
                                <form action="{{ route('admin.kriterdegerleri.destroy', $deger->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Silmek istediğinize emin misiniz?')">
                                        Sil
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center">Değer bulunamadı.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                {{-- DÜZELTME: Geri Dön --}}
                <a href="{{ route('admin.altkategoriler.kriterler', $kriter->alt_kategori_id) }}" class="btn btn-secondary">
                    &larr; Kriterlere Geri Dön
                </a>
            </div>

        </div>
    </div>
</div>
@endsection