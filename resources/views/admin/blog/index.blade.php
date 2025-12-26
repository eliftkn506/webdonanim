@extends('layouts.admin')

@section('title', 'Blog Yönetimi')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    
    <div class="d-flex justify-content-between align-items-center py-3 mb-4">
        <h4 class="fw-bold mb-0">
            <span class="text-muted fw-light">İçerik Yönetimi /</span> Blog Yazıları
        </h4>
        <a href="{{ route('admin.blog.create') }}" class="btn btn-primary">
            <span class="tf-icons bx bx-plus me-1"></span> Yeni Ekle
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <h5 class="card-header">Blog Listesi</h5>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="100">Görsel</th>
                        <th>Başlık</th>
                        <th>Yazar</th>
                        <th>Durum</th>
                        <th>Tarih</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse($blogs as $blog)
                    <tr>
                        <td>
                            <div class="avatar avatar-md">
                                @if($blog->resim)
                                    <img src="{{ asset('storage/' . $blog->resim) }}" alt="Blog" class="rounded-circle object-fit-cover">
                                @else
                                    <span class="avatar-initial rounded-circle bg-label-secondary"><i class='bx bx-image'></i></span>
                                @endif
                            </div>
                        </td>
                        <td><strong class="text-truncate d-inline-block" style="max-width: 200px;">{{ $blog->baslik }}</strong></td>
                        <td>{{ $blog->yazar }}</td>
                        <td>
                            @if($blog->aktif)
                                <span class="badge bg-label-success me-1">Aktif</span>
                            @else
                                <span class="badge bg-label-secondary me-1">Pasif</span>
                            @endif
                        </td>
                        <td>{{ $blog->created_at->format('d.m.Y') }}</td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ route('admin.blog.edit', $blog->id) }}">
                                        <i class="bx bx-edit-alt me-1"></i> Düzenle
                                    </a>
                                    <form action="{{ route('admin.blog.destroy', $blog->id) }}" method="POST" onsubmit="return confirm('Bu yazıyı silmek istediğinize emin misiniz?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bx bx-trash me-1"></i> Sil
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <i class='bx bx-folder-open display-6 text-muted mb-3'></i>
                            <p class="text-muted">Henüz blog yazısı eklenmemiş.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($blogs->hasPages())
        <div class="card-footer d-flex justify-content-end">
            {{ $blogs->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
</div>
@endsection