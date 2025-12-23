@extends('layouts.admin')
@section('title', 'Yeni Slider Ekle')
@section('content')
<div class="container-xxl flex-grow-1 container-py-4">
    <h4 class="fw-bold py-3 mb-4">Yeni Slider Ekle</h4>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.sliders.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Görsel (Zorunlu)</label>
                        <input type="file" name="image" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Başlık (HTML kullanabilirsin)</label>
                        <input type="text" name="title" class="form-control" placeholder="Örn: Sınırları Zorlayan <br> <span>Performans</span>">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Açıklama</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Badge Metni</label>
                        <input type="text" name="badge_text" class="form-control" placeholder="Örn: OYUN CANAVARI">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Badge Rengi</label>
                        <select name="badge_color" class="form-control">
                            <option value="danger">Kırmızı (Danger)</option>
                            <option value="primary">Mavi (Primary)</option>
                            <option value="success">Yeşil (Success)</option>
                            <option value="warning">Sarı (Warning)</option>
                            <option value="info">Açık Mavi (Info)</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Sıralama</label>
                        <input type="number" name="order" class="form-control" value="0">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Buton Yazısı</label>
                        <input type="text" name="button_text" class="form-control" placeholder="Hemen İncele">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Buton Linki</label>
                        <input type="text" name="button_link" class="form-control" placeholder="/urunler veya https://...">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Durum</label>
                        <select name="status" class="form-control">
                            <option value="1">Aktif</option>
                            <option value="0">Pasif</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Kaydet</button>
            </form>
        </div>
    </div>
</div>
@endsection