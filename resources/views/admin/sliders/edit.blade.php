@extends('layouts.admin')
@section('title', 'Slider Düzenle')
@section('content')
<div class="container-xxl flex-grow-1 container-py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">Slider Düzenle</h4>
        <a href="{{ route('admin.sliders.index') }}" class="btn btn-secondary">Geri Dön</a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.sliders.update', $slider->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-12 mb-4">
                        <label class="form-label d-block">Mevcut Görsel</label>
                        <img src="{{ asset('storage/' . $slider->image) }}" width="200" class="rounded border shadow-sm">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Görseli Değiştir (Opsiyonel)</label>
                        <input type="file" name="image" class="form-control">
                        <small class="text-muted">Değiştirmek istemiyorsanız boş bırakın.</small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Başlık (HTML kullanabilirsin)</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title', $slider->title) }}" placeholder="Örn: Sınırları Zorlayan <br> <span>Performans</span>">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Açıklama</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description', $slider->description) }}</textarea>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Badge Metni</label>
                        <input type="text" name="badge_text" class="form-control" value="{{ old('badge_text', $slider->badge_text) }}" placeholder="Örn: OYUN CANAVARI">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Badge Rengi</label>
                        <select name="badge_color" class="form-control">
                            <option value="danger" {{ $slider->badge_color == 'danger' ? 'selected' : '' }}>Kırmızı (Danger)</option>
                            <option value="primary" {{ $slider->badge_color == 'primary' ? 'selected' : '' }}>Mavi (Primary)</option>
                            <option value="success" {{ $slider->badge_color == 'success' ? 'selected' : '' }}>Yeşil (Success)</option>
                            <option value="warning" {{ $slider->badge_color == 'warning' ? 'selected' : '' }}>Sarı (Warning)</option>
                            <option value="info" {{ $slider->badge_color == 'info' ? 'selected' : '' }}>Açık Mavi (Info)</option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Sıralama</label>
                        <input type="number" name="order" class="form-control" value="{{ old('order', $slider->order) }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Buton Yazısı</label>
                        <input type="text" name="button_text" class="form-control" value="{{ old('button_text', $slider->button_text) }}" placeholder="Hemen İncele">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Buton Linki</label>
                        <input type="text" name="button_link" class="form-control" value="{{ old('button_link', $slider->button_link) }}" placeholder="/urunler veya https://...">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Durum</label>
                        <select name="status" class="form-control">
                            <option value="1" {{ $slider->status == 1 ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ $slider->status == 0 ? 'selected' : '' }}>Pasif</option>
                        </select>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-warning">Güncelle</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection