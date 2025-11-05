@extends('layouts.admin')

@section('title', 'Yeni Kriter Ekle - Admin Panel')

@section('content')
<div class="container-xxl flex-grow-1 container-py-4">
    <h4 class="fw-bold py-3 mb-4">Yeni Kriter Ekle</h4>

    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.kriterler.store') }}" method="POST">
                @csrf

                <!-- Alt Kategori -->
                <div class="mb-3">
                    <label class="form-label">Alt Kategori</label>
                    <select name="alt_kategori_id" class="form-select" required>
                        <option value="">Seçiniz</option>
                        @foreach($altkategoriler as $altkategori)
                            <option value="{{ $altkategori->id }}">{{ $altkategori->alt_kategori_ad }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Kriter Adı -->
                <div class="mb-3">
                    <label class="form-label">Kriter Adı</label>
                    <input type="text" name="kriter_ad" class="form-control" placeholder="Örn: Renk, Boyut, Kapasite" required>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-success">
                        <i class="bx bx-plus me-1"></i> Kaydet
                    </button>
                    <a href="{{ route('admin.kriterler.index') }}" class="btn btn-secondary">
                        <i class="bx bx-arrow-back me-1"></i> Geri
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
