@extends('layouts.admin')
@section('title', $page->title . ' Düzenle')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">CMS /</span> {{ $page->title }} Düzenle
    </h4>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('admin.pages.update', $page->slug) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Sayfa Başlığı</label>
                            <input type="text" name="title" class="form-control" value="{{ $page->title }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">İçerik Metni (HTML Kullanılabilir)</label>
                            <textarea name="content" id="editor" class="form-control" rows="15">{{ $page->content }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                @if($page->slug == 'iletisim')
                <div class="card mb-4">
                    <div class="card-header border-bottom">
                        <h5 class="card-title mb-0">İletişim Bilgileri</h5>
                    </div>
                    <div class="card-body mt-3">
                        <div class="mb-3">
                            <label class="form-label">Telefon</label>
                            <input type="text" name="phone" class="form-control" value="{{ $page->phone }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">E-posta</label>
                            <input type="email" name="email" class="form-control" value="{{ $page->email }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Açık Adres</label>
                            <textarea name="address" class="form-control" rows="3">{{ $page->address }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Google Harita Iframe Kodu</label>
                            <textarea name="google_maps" class="form-control" rows="4" placeholder="<iframe src='...'></iframe>">{{ $page->google_maps }}</textarea>
                            <small class="text-muted">Google Haritalar'dan aldığınız paylaşım kodunu buraya yapıştırın.</small>
                        </div>
                    </div>
                </div>
                @endif

                <div class="card">
                    <div class="card-body text-center">
                        <button type="submit" class="btn btn-primary btn-lg w-100 mb-3">
                            <i class="bx bx-save me-1"></i> Değişiklikleri Kaydet
                        </button>
                        <a href="{{ route('admin.pages.index') }}" class="btn btn-outline-secondary w-100">
                            İptal Et
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('editor', {
        height: 400,
        removeButtons: 'About',
        language: 'tr'
    });
</script>
@endsection