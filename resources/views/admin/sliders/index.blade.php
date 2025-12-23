@extends('layouts.admin')
@section('title', 'Slider Yönetimi')
@section('content')
<div class="container-xxl flex-grow-1 container-py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">Slider Yönetimi</h4>
        <a href="{{ route('admin.sliders.create') }}" class="btn btn-primary">Yeni Slider Ekle</a>
    </div>

    <div class="card">
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Görsel</th>
                        <th>Başlık</th>
                        <th>Badge</th>
                        <th>Sıra</th>
                        <th>Durum</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sliders as $slider)
                    <tr>
                        <td>
                            <img src="{{ asset('storage/' . $slider->image) }}" width="100" class="rounded">
                        </td>
                        <td>{!! $slider->title !!}</td>
                        <td><span class="badge bg-{{ $slider->badge_color }}">{{ $slider->badge_text }}</span></td>
                        <td>{{ $slider->order }}</td>
                        <td>
                            @if($slider->status) <span class="badge bg-success">Aktif</span> @else <span class="badge bg-danger">Pasif</span> @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.sliders.edit', $slider->id) }}" class="btn btn-sm btn-warning"><i class="bx bx-edit"></i></a>
                            <form action="{{ route('admin.sliders.destroy', $slider->id) }}" method="POST" class="d-inline-block">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Silmek istediğine emin misin?')"><i class="bx bx-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection