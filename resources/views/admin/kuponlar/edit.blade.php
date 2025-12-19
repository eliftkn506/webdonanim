@extends('layouts.admin')

@section('title', 'Kupon Düzenle')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">
            <span class="text-muted fw-light">Kuponlar /</span> Düzenle
        </h4>
        <a href="{{ route('admin.kuponlar.index') }}" class="btn btn-outline-secondary">
            <i class="bx bx-arrow-back me-1"></i> İptal
        </a>
    </div>

    <form action="{{ route('admin.kuponlar.update', $kupon->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        @include('admin.kuponlar.form', ['kupon' => $kupon])
        
        <div class="d-flex justify-content-end gap-2 mb-5">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="bx bx-check me-1"></i> Güncelle
            </button>
        </div>
    </form>
</div>
@endsection