@extends('layouts.admin')
@section('title', 'Alt Kategoriler')

@section('content')
<div class="container-xxl flex-grow-1 container-py-4">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb breadcrumb-style1">
        <li class="breadcrumb-item">
            <a href="{{ route('admin.kategoriler.index') }}" class="text-muted">Kategoriler</a>
        </li>
        <li class="breadcrumb-item active">{{ $kategori->kategori_ad }}</li>
      </ol>
    </nav>

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h4 class="fw-bold mb-0">
                <span class="text-muted fw-light">Kategori:</span> {{ $kategori->kategori_ad }}
            </h4>
            <small class="text-muted">Bu kategoriye ait alt kategorileri ve özelliklerini yönetin.</small>
        </div>
        
        <a href="{{ route('admin.kategoriler.altkategoriler.create', $kategori->id) }}" class="btn btn-primary">
            <i class="bx bx-plus me-1"></i> Yeni Alt Kategori
        </a>
    </div>

    <div class="card">
        <div class="table-responsive text-nowrap">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="w-50">ALT KATEGORİ ADI</th>
                        <th class="text-center">ÖZELLİK DURUMU</th>
                        <th class="text-end" style="min-width: 250px;">İŞLEMLER</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse($altKategoriler as $alt)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm me-3">
                                    <span class="avatar-initial rounded-circle bg-label-primary">
                                        <i class='bx bx-subdirectory-right'></i>
                                    </span>
                                </div>
                                <div>
                                    <h6 class="mb-0 text-dark fw-semibold">{{ $alt->alt_kategori_ad }}</h6>
                                    <small class="text-muted">ID: #{{ $alt->id }}</small>
                                </div>
                            </div>
                        </td>

                        <td class="text-center">
                            @if($alt->kriterler->count() > 0)
                                <span class="badge bg-label-success px-3 py-2">
                                    <i class="bx bx-check-circle me-1"></i> {{ $alt->kriterler->count() }} Özellik Tanımlı
                                </span>
                            @else
                                <span class="badge bg-label-warning px-3 py-2">
                                    <i class="bx bx-error-circle me-1"></i> Özellik Yok
                                </span>
                            @endif
                        </td>

                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-2">
                                
                                {{-- ANA BUTON: Kriter Yönetimi (Artık Gizli Değil!) --}}
                                <a href="{{ route('admin.altkategoriler.kriterler', $alt->id) }}" 
                                   class="btn btn-sm btn-label-primary fw-bold">
                                    <i class="bx bx-slider-alt me-1"></i> Özellikleri Yönet
                                </a>

                                {{-- Düzenle Butonu (İkon) --}}
                                <a href="{{ route('admin.altkategoriler.edit', $alt->id) }}" 
                                   class="btn btn-sm btn-icon btn-label-warning"
                                   data-bs-toggle="tooltip" 
                                   data-bs-placement="top" 
                                   title="Düzenle">
                                    <i class="bx bx-edit-alt"></i>
                                </a>

                                {{-- Silme Butonu (İkon) --}}
                                <form action="{{ route('admin.altkategoriler.destroy', $alt->id) }}" method="POST" class="d-inline-block">
                                    @csrf 
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="btn btn-sm btn-icon btn-label-danger"
                                            onclick="return confirm('Bu alt kategoriyi ve bağlı tüm özellikleri silmek istediğinize emin misiniz?')"
                                            data-bs-toggle="tooltip" 
                                            data-bs-placement="top" 
                                            title="Sil">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center py-5">
                            <div class="d-flex flex-column align-items-center justify-content-center">
                                <div class="mb-3">
                                    <span class="avatar-initial rounded-circle bg-label-secondary p-4 fs-3">
                                        <i class="bx bx-folder-open"></i>
                                    </span>
                                </div>
                                <h5 class="text-muted">Henüz alt kategori eklenmemiş.</h5>
                                <a href="{{ route('admin.kategoriler.altkategoriler.create', $kategori->id) }}" class="btn btn-primary mt-2">
                                    İlk Alt Kategoriyi Ekle
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Tooltipleri Çalıştırmak için Ufak Script --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>
@endsection