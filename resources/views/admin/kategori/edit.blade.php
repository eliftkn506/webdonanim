@extends('layouts.admin')

@section('title', 'Kategori Düzenle - Admin Panel')

@section('content')
<div class="container-xxl flex-grow-1 container-py-4">
    <h4 class="fw-bold py-3 mb-4">Kategori Düzenle</h4>

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
            {{-- ÖNEMLİ: enctype eklendi --}}
            <form action="{{ route('admin.kategoriler.update', $kategori->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="form-label">Kategori Adı</label>
                            <input type="text" name="kategori_ad" value="{{ $kategori->kategori_ad }}" class="form-control" required>
                        </div>

                        {{-- Resim Yükleme Alanı --}}
                        <div class="mb-3">
                            <label class="form-label">Kategori Görselini Güncelle</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                            <div class="form-text">Yeni bir resim seçerseniz eskisi silinecektir.</div>
                        </div>
                    </div>

                    {{-- Mevcut Resmi Gösterme Alanı --}}
                    <div class="col-md-4 text-center">
                        <label class="form-label d-block">Mevcut Görsel</label>
                        @if($kategori->image)
                            <img src="{{ asset('storage/' . $kategori->image) }}" class="img-thumbnail rounded" style="max-height: 200px;" alt="{{ $kategori->kategori_ad }}">
                        @else
                            <div class="alert alert-secondary d-flex align-items-center justify-content-center" style="height: 150px;">
                                <span class="text-muted">Görsel Yok</span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="text-end mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-save me-1"></i> Güncelle
                    </button>
                    <a href="{{ route('admin.kategoriler.index') }}" class="btn btn-secondary">
                        <i class="bx bx-arrow-back me-1"></i> Geri
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection