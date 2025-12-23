@extends('layouts.admin')
@section('title', 'Kategori Yönetimi')

@section('content')
<div class="container-xxl flex-grow-1 container-py-4">
    
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h4 class="fw-bold mb-0">
                <span class="text-muted fw-light">Yönetim /</span> Kategoriler
            </h4>
            <small class="text-muted">Kategorileri ve görsellerini buradan yönetebilirsiniz.</small>
        </div>
        
        <a href="{{ route('admin.kategoriler.create') }}" class="btn btn-primary">
            <i class="bx bx-plus me-1"></i> Yeni Kategori Ekle
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header border-bottom d-flex justify-content-between align-items-center bg-transparent">
            <h5 class="card-title mb-0">Kategori Listesi</h5>
            <div class="d-flex align-items-center">
                <div class="input-group input-group-merge" style="max-width: 250px;">
                    <span class="input-group-text"><i class="bx bx-search"></i></span>
                    <input type="text" class="form-control" placeholder="Ara..." aria-label="Ara...">
                </div>
            </div>
        </div>

        <div class="table-responsive text-nowrap">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th style="width: 100px;">GÖRSEL</th> <th style="width: 25%;">KATEGORİ ADI</th>
                        <th style="width: 40%;">ALT KATEGORİLER</th>
                        <th class="text-end" style="width: 100px;">İŞLEMLER</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse($kategoriler as $kategori)
                    <tr>
                        <td>
                            <span class="badge bg-label-secondary rounded p-2">
                                <i class="bx bx-hash"></i> {{ $loop->iteration }}
                            </span>
                        </td>
                        {{-- Resim Gösterimi --}}
                        <td>
                            @if($kategori->image)
                                <img src="{{ asset('storage/' . $kategori->image) }}" alt="img" class="rounded" width="50" height="50" style="object-fit: cover;">
                            @else
                                <span class="badge bg-label-secondary p-3">
                                    <i class="bx bx-image-alt fs-4"></i>
                                </span>
                            @endif
                        </td>
                        <td>
                            <h6 class="mb-0 text-dark fw-semibold">{{ $kategori->kategori_ad }}</h6>
                            <small class="text-muted">ID: #{{ $kategori->id }}</small>
                        </td>
                        
                        <td class="text-wrap">
                            <div class="d-flex flex-wrap gap-1">
                                @if($kategori->altKategoriler && $kategori->altKategoriler->count() > 0)
                                    @foreach($kategori->altKategoriler->take(5) as $alt)
                                        <span class="badge bg-label-info">{{ $alt->alt_kategori_ad }}</span>
                                    @endforeach
                                    @if($kategori->altKategoriler->count() > 5)
                                        <span class="badge bg-label-secondary">+{{ $kategori->altKategoriler->count() - 5 }} diğer</span>
                                    @endif
                                @else
                                    <span class="text-muted fst-italic small">Alt kategori yok</span>
                                @endif
                            </div>
                        </td>

                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-2">
                                {{-- Alt Kategori Yönetimi Linki (Varsa) --}}
                                @if(Route::has('admin.kategoriler.altkategoriler'))
                                <a href="{{ route('admin.kategoriler.altkategoriler', $kategori->id) }}" class="btn btn-sm btn-icon btn-label-info" title="Alt Kategoriler">
                                    <i class="bx bx-list-ul"></i>
                                </a>
                                @endif

                                <a href="{{ route('admin.kategoriler.edit', $kategori->id) }}" class="btn btn-sm btn-icon btn-label-warning" title="Düzenle">
                                    <i class="bx bx-edit-alt"></i>
                                </a>

                                <form action="{{ route('admin.kategoriler.destroy', $kategori->id) }}" method="POST" class="d-inline-block">
                                    @csrf 
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-icon btn-label-danger" onclick="return confirm('Silmek istediğinize emin misiniz?')" title="Sil">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <div class="d-flex flex-column align-items-center justify-content-center">
                                <i class="bx bx-folder-open fs-1 text-muted mb-2"></i>
                                <h5 class="text-muted">Henüz hiç kategori eklenmemiş.</h5>
                                <a href="{{ route('admin.kategoriler.create') }}" class="btn btn-primary btn-sm mt-2">İlk Kategoriyi Ekle</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if(isset($kategoriler) && method_exists($kategoriler, 'links'))
        <div class="card-footer d-flex justify-content-end">
            {{ $kategoriler->links() }}
        </div>
        @endif
    </div>
</div>
@endsection