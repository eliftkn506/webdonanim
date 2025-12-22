@extends('layouts.admin')
@section('title', 'Kategori Yönetimi')

@section('content')
<div class="container-xxl flex-grow-1 container-py-4">
    
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h4 class="fw-bold mb-0">
                <span class="text-muted fw-light">Yönetim /</span> Kategoriler
            </h4>
            <small class="text-muted">Tüm ana kategorileri ve bağlı alt kategorileri buradan yönetebilirsiniz.</small>
        </div>
        
        <a href="{{ route('admin.kategoriler.create') }}" class="btn btn-primary">
            <i class="bx bx-plus me-1"></i> Yeni Kategori Ekle
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-header border-bottom d-flex justify-content-between align-items-center bg-transparent">
            <h5 class="card-title mb-0">Kategori Listesi</h5>
            <div class="d-flex align-items-center">
                <div class="input-group input-group-merge" style="max-width: 250px;">
                    <span class="input-group-text" id="basic-addon-search31"><i class="bx bx-search"></i></span>
                    <input type="text" class="form-control" placeholder="Ara..." aria-label="Ara..." aria-describedby="basic-addon-search31">
                </div>
            </div>
        </div>

        <div class="table-responsive text-nowrap">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th style="width: 30%;">KATEGORİ ADI</th>
                        <th style="width: 50%;">ALT KATEGORİLER</th>
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
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm me-3">
                                    <span class="avatar-initial rounded-circle bg-label-primary">
                                        {{ strtoupper(substr($kategori->kategori_ad, 0, 1)) }}
                                    </span>
                                </div>
                                <div>
                                    <h6 class="mb-0 text-dark fw-semibold">{{ $kategori->kategori_ad }}</h6>
                                    <small class="text-muted">ID: #{{ $kategori->id }}</small>
                                </div>
                            </div>
                        </td>
                        
                        <td class="text-wrap">
                            <div class="d-flex flex-wrap gap-1">
                                @if($kategori->altKategoriler->count() > 0)
                                    @foreach($kategori->altKategoriler->take(5) as $alt)
                                        <a href="{{ route('admin.kategoriler.altkategoriler', $kategori->id) }}" class="badge bg-label-info text-decoration-none">
                                            {{ $alt->alt_kategori_ad }}
                                        </a>
                                    @endforeach

                                    @if($kategori->altKategoriler->count() > 5)
                                        <a href="{{ route('admin.kategoriler.altkategoriler', $kategori->id) }}" class="badge bg-label-secondary text-decoration-none">
                                            +{{ $kategori->altKategoriler->count() - 5 }} diğer
                                        </a>
                                    @endif
                                @else
                                    <span class="text-muted fst-italic small">Alt kategori bulunamadı</span>
                                @endif
                            </div>
                        </td>

                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.kategoriler.altkategoriler', $kategori->id) }}" 
                                   class="btn btn-sm btn-icon btn-label-info" 
                                   data-bs-toggle="tooltip" 
                                   title="Alt Kategorileri Yönet">
                                    <i class="bx bx-list-ul"></i>
                                </a>

                                <a href="{{ route('admin.kategoriler.edit', $kategori->id) }}" 
                                   class="btn btn-sm btn-icon btn-label-warning" 
                                   data-bs-toggle="tooltip" 
                                   title="Düzenle">
                                    <i class="bx bx-edit-alt"></i>
                                </a>

                                <form action="{{ route('admin.kategoriler.destroy', $kategori->id) }}" method="POST" class="d-inline-block">
                                    @csrf 
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="btn btn-sm btn-icon btn-label-danger" 
                                            onclick="return confirm('Bu kategoriyi ve bağlı tüm alt kategorileri silmek istediğinize emin misiniz?')"
                                            data-bs-toggle="tooltip" 
                                            title="Sil">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5">
                            <div class="d-flex flex-column align-items-center justify-content-center">
                                <i class="bx bx-folder-open fs-1 text-muted mb-2"></i>
                                <h5 class="text-muted">Henüz hiç kategori eklenmemiş.</h5>
                                <a href="{{ route('admin.kategoriler.create') }}" class="btn btn-primary btn-sm mt-2">
                                    İlk Kategoriyi Ekle
                                </a>
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>
@endsection