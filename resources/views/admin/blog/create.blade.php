@extends('layouts.admin')

@section('title', 'Yeni Blog Ekle')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Bloglar /</span> Yeni Ekle
    </h4>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <h5 class="card-header">İçerik Detayları</h5>
                <div class="card-body">
                    <form action="{{ route('admin.blog.store') }}" method="POST" enctype="multipart/form-data" id="blogForm">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label" for="baslik">Blog Başlığı <span class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="bx bx-pen"></i></span>
                                <input type="text" class="form-control" id="baslik" name="baslik" placeholder="Örn: Yeni Nesil İşlemciler" value="{{ old('baslik') }}" required />
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="ozet">Kısa Özet</label>
                            <textarea id="ozet" name="ozet" class="form-control" rows="2" placeholder="Anasayfada görünecek kısa açıklama...">{{ old('ozet') }}</textarea>
                            <div class="form-text">SEO ve kart görünümleri için önemlidir.</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="icerik">İçerik <span class="text-danger">*</span></label>
                            <textarea id="icerik" name="icerik" class="form-control" rows="10" required>{{ old('icerik') }}</textarea>
                        </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <h5 class="card-header">Yayın Ayarları</h5>
                <div class="card-body">
                    
                    <div class="mb-3">
                        <label class="form-label" for="aktif">Durum</label>
                        <select id="aktif" name="aktif" class="form-select">
                            <option value="1">Yayında (Aktif)</option>
                            <option value="0">Taslak (Pasif)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="resim">Kapak Görseli</label>
                        <input class="form-control" type="file" id="resim" name="resim" accept="image/*" />
                        <div class="form-text">İdeal boyut: 800x600px</div>
                    </div>

                    <hr class="my-4">

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <span class="tf-icons bx bx-save me-1"></span> Kaydet ve Yayınla
                        </button>
                        <a href="{{ route('admin.blog.index') }}" class="btn btn-label-secondary">Vazgeç</a>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection