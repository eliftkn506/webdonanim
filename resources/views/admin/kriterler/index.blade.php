@extends('layouts.admin')
@section('title', 'Kriter Yönetimi')

@section('content')
<div class="container-xxl flex-grow-1 container-py-4">
    
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb breadcrumb-style1">
        <li class="breadcrumb-item">
            <a href="{{ route('admin.kategoriler.index') }}" class="text-muted">Kategoriler</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('admin.kategoriler.altkategoriler', $altKategori->kategori_id) }}" class="text-muted">
                {{ $altKategori->kategori->kategori_ad }}
            </a>
        </li>
        <li class="breadcrumb-item active">{{ $altKategori->alt_kategori_ad }}</li>
      </ol>
    </nav>

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h4 class="fw-bold mb-0">
                <span class="text-muted fw-light">Özellikler:</span> {{ $altKategori->alt_kategori_ad }}
            </h4>
            <small class="text-muted">Bu alt kategoriye ait filtreleme özelliklerini (Renk, Beden, Soket Tipi vb.) yönetin.</small>
        </div>
        
        <a href="{{ route('admin.altkategoriler.kriterler.create', $altKategori->id) }}" class="btn btn-primary">
            <i class="bx bx-plus me-1"></i> Yeni Özellik Ekle
        </a>
    </div>

    <div class="card">
        <div class="table-responsive text-nowrap">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width: 30%;">KRİTER ADI</th>
                        <th style="width: 45%;">TANITILMIŞ DEĞERLER</th>
                        <th class="text-end" style="width: 25%;">İŞLEMLER</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse($kriterler as $kriter)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm me-3">
                                    <span class="avatar-initial rounded-circle bg-label-info">
                                        <i class="bx bx-slider"></i>
                                    </span>
                                </div>
                                <div>
                                    <h6 class="mb-0 text-dark fw-semibold">{{ $kriter->kriter_ad }}</h6>
                                    <small class="text-muted">ID: #{{ $kriter->id }}</small>
                                </div>
                            </div>
                        </td>

                        <td>
                            <div class="d-flex flex-wrap gap-1 align-items-center">
                                @if($kriter->degerler->count() > 0)
                                    @foreach($kriter->degerler->take(4) as $deger)
                                        <span class="badge bg-label-secondary">{{ $deger->deger }}</span>
                                    @endforeach

                                    @if($kriter->degerler->count() > 4)
                                        <span class="badge bg-label-primary">
                                            +{{ $kriter->degerler->count() - 4 }} diğer
                                        </span>
                                    @endif
                                @else
                                    <span class="text-muted fst-italic small">
                                        <i class='bx bx-info-circle me-1'></i>Değer girilmemiş
                                    </span>
                                @endif
                            </div>
                        </td>

                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-2">
                                
                                {{-- ANA BUTON: Değerleri Yönet --}}
                                <a href="{{ route('admin.kriterdegerleri.index', $kriter->id) }}" 
                                   class="btn btn-sm btn-label-info fw-bold"
                                   data-bs-toggle="tooltip"
                                   title="Bu özelliğe ait seçenekleri (örn: Kırmızı, Mavi) ekle">
                                    <i class="bx bx-list-check me-1"></i> Değerler
                                </a>

                                {{-- Düzenle --}}
                                <a href="{{ route('admin.kriterler.edit', $kriter->id) }}" 
                                   class="btn btn-sm btn-icon btn-label-warning"
                                   data-bs-toggle="tooltip" 
                                   title="Düzenle">
                                    <i class="bx bx-edit-alt"></i>
                                </a>

                                {{-- Sil --}}
                                <form action="{{ route('admin.kriterler.destroy', $kriter->id) }}" method="POST" class="d-inline-block">
                                    @csrf 
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="btn btn-sm btn-icon btn-label-danger"
                                            onclick="return confirm('Bu kriteri ve bağlı tüm değerleri silmek istediğinize emin misiniz?')"
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
                        <td colspan="3" class="text-center py-5">
                            <div class="d-flex flex-column align-items-center justify-content-center">
                                <div class="mb-3">
                                    <span class="avatar-initial rounded-circle bg-label-secondary p-4 fs-3">
                                        <i class="bx bx-filter-alt"></i>
                                    </span>
                                </div>
                                <h5 class="text-muted">Bu kategori için henüz özellik tanımlanmamış.</h5>
                                <p class="text-muted mb-3">Filtreleme yapabilmek için (Renk, Beden, Hafıza vb.) özellikler ekleyin.</p>
                                <a href="{{ route('admin.altkategoriler.kriterler.create', $altKategori->id) }}" class="btn btn-primary">
                                    İlk Özelliği Ekle
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

{{-- Tooltip Script --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>
@endsection