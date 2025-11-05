@extends('layouts.admin')

@section('title', 'Alt Kategori Düzenle - Admin Panel')

@section('content')
<div class="container-xxl flex-grow-1 container-py-4">
    <h4 class="fw-bold py-3 mb-4">Alt Kategori Düzenle</h4>

    <!-- Hata Mesajları -->
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.altkategoriler.update', $altkategoriler->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Kategori Seç -->
                <div class="mb-3">
                    <label for="kategori_id" class="form-label">Kategori Seç</label>
                    <select name="kategori_id" id="kategori_id" class="form-select" required>
                        @foreach($kategoriler as $kategori)
                            <option value="{{ $kategori->id }}" @if($kategori->id == $altkategoriler->kategori_id) selected @endif>
                                {{ $kategori->kategori_ad }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Alt Kategori Adı -->
                <div class="mb-3">
                    <label for="alt_kategori_ad" class="form-label">Alt Kategori Adı</label>
                    <input type="text" name="alt_kategori_ad" id="alt_kategori_ad" class="form-control" placeholder="Alt kategori adını giriniz" value="{{ $altkategoriler->alt_kategori_ad }}" required>
                </div>

                <!-- Butonlar -->
                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-save me-1"></i> Güncelle
                    </button>
                    <a href="{{ route('admin.altkategoriler.index') }}" class="btn btn-secondary">
                        <i class="bx bx-arrow-back me-1"></i> Geri
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
