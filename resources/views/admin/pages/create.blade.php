@extends('layouts.admin')
@section('title', 'Yeni Sayfa Ekle')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">CMS /</span> Yeni Sayfa Oluştur</h4>

    <form action="{{ route('admin.pages.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Sayfa Başlığı</label>
                            <input type="text" name="title" class="form-control" placeholder="Örn: Hakkımızda" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">İçerik</label>
                            <textarea name="content" id="editor" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-4 text-center p-3">
                    <button type="submit" class="btn btn-primary btn-lg w-100">Kaydet ve Yayınla</button>
                </div>
                <div class="card mb-4 p-3">
                    <h6>Ek Bilgiler (İletişim için)</h6>
                    <div class="mb-2">
                        <label class="small">Telefon</label>
                        <input type="text" name="phone" class="form-control form-control-sm">
                    </div>
                    <div class="mb-2">
                        <label class="small">E-posta</label>
                        <input type="email" name="email" class="form-control form-control-sm">
                    </div>
                    <div class="mb-2">
                        <label class="small">Adres</label>
                        <textarea name="address" class="form-control form-control-sm" rows="2"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<script>CKEDITOR.replace('editor');</script>
@endsection