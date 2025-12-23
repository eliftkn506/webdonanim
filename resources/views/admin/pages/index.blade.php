@extends('layouts.admin')
@section('title', 'Sayfa Yönetimi')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0"><span class="text-muted fw-light">CMS /</span> Sayfa Yönetimi</h4>
        <a href="{{ route('admin.pages.create') }}" class="btn btn-primary">
            <i class="bx bx-plus me-1"></i> Yeni Sayfa Ekle
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Sayfa Adı</th>
                        <th>Slug (URL)</th>
                        <th>Son Güncelleme</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse($pages as $page)
                    <tr>
                        <td><strong>{{ $page->title }}</strong></td>
                        <td><code>/{{ $page->slug }}</code></td>
                        <td>{{ $page->updated_at->format('d.m.Y H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.pages.edit', $page->slug) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bx bx-edit-alt me-1"></i> Düzenle
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-muted">Henüz hiç sayfa eklenmemiş. Lütfen "Yeni Sayfa Ekle" butonuna basın.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection