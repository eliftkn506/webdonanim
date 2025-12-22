@extends('layouts.admin') {{-- Layout ismin neyse onu kullan --}}

@section('title', 'Kriter Düzenle - Admin Panel')

@section('content')
<div class="container-xxl flex-grow-1 container-py-4">
    <h4 class="fw-bold py-3 mb-4">Kriter Düzenle</h4>

    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.kriterler.update', $kriter->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Alt Kategori</label>
                    <select name="alt_kategori_id" class="form-select" required>
                        {{-- Controllerdan gelen $altkategoriler artık burada hata vermeyecek --}}
                        @foreach($altkategoriler as $altkategori)
                            <option value="{{ $altkategori->id }}" {{ $kriter->alt_kategori_id == $altkategori->id ? 'selected' : '' }}>
                                {{ $altkategori->alt_kategori_ad }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Kriter Adı</label>
                    <input type="text" name="kriter_ad" value="{{ $kriter->kriter_ad }}" class="form-control" required>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-save me-1"></i> Güncelle
                    </button>
                    
                    {{-- Geri butonu düzeltildi --}}
                    <a href="{{ route('admin.altkategoriler.kriterler', $kriter->alt_kategori_id) }}" class="btn btn-secondary">
                        <i class="bx bx-arrow-back me-1"></i> Geri
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection