@extends('layouts.admin')

@section('title', 'Blog Düzenle')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Bloglar /</span> Düzenle
    </h4>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <h5 class="card-header">İçerik Düzenle</h5>
                <div class="card-body">
                    <form action="{{ route('admin.blog.update', $blog->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label class="form-label" for="baslik">Blog Başlığı <span class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="bx bx-pen"></i></span>
                                <input type="text" class="form-control" id="baslik" name="baslik" value="{{ $blog->baslik }}" required />
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="ozet">Kısa Özet</label>
                            <textarea id="ozet" name="ozet" class="form-control" rows="2">{{ $blog->ozet }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="icerik">İçerik <span class="text-danger">*</span></label>
                            <textarea id="icerik" name="icerik" class="form-control" rows="12" required>{{ $blog->icerik }}</textarea>
                        </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <h5 class="card-header">Yayın & Görsel</h5>
                <div class="card-body">
                    
                    <div class="mb-3">
                        <label class="form-label" for="aktif">Durum</label>
                        <select id="aktif" name="aktif" class="form-select">
                            <option value="1" {{ $blog->aktif == 1 ? 'selected' : '' }}>Yayında (Aktif)</option>
                            <option value="0" {{ $blog->aktif == 0 ? 'selected' : '' }}>Taslak (Pasif)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mevcut Görsel</label>
                        <div class="d-block mb-2 border rounded p-1">
                            @if($blog->resim)
                                <img src="{{ asset('storage/' . $blog->resim) }}" class="img-fluid rounded" alt="Blog Görseli">
                            @else
                                <div class="text-center p-3 text-muted bg-lighter rounded">
                                    <i class='bx bx-image-alt fs-1'></i>
                                    <p class="small mb-0">Görsel Yok</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="resim">Görseli Değiştir</label>
                        <input class="form-control" type="file" id="resim" name="resim" accept="image/*" />
                    </div>

                    <hr class="my-4">

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <span class="tf-icons bx bx-sync me-1"></span> Güncelle
                        </button>
                        <a href="{{ route('admin.blog.index') }}" class="btn btn-label-secondary">İptal</a>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection