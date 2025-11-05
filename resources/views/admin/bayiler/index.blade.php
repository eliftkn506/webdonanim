@extends('layouts.admin')

@section('title', 'Bayilerimiz - Admin Panel')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Bayilerimiz (Onaylanmış)</h5>
        <a href="{{ route('admin.bayiler.basvurular') }}" class="btn btn-warning btn-sm">
            <i class="fas fa-clock me-1"></i> Bekleyen Başvurular
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>#ID</th>
                        <th>Firma Adı</th>
                        <th>Yetkili</th>
                        <th>Email</th>
                        <th>Telefon</th>
                        <th>Onaylanma Tarihi</th>
                        <th class="text-center">İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bayiler as $bayi)
                    <tr>
                        <td>{{ $bayi->id }}</td>
                        <td>{{ $bayi->firma_adi ?? '-' }}</td>
                        <td>{{ $bayi->yetkili_ad }} {{ $bayi->yetkili_soyad }}</td>
                        <td>{{ $bayi->email }}</td>
                        <td>{{ $bayi->telefon ?? '-' }}</td>
                        <td>{{ $bayi->updated_at->format('d.m.Y H:i') }}</td>
                        <td class="text-center">
                            <a href="{{ route('admin.bayiler.show', $bayi->id) }}" 
                               class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i> Detay
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                            Henüz onaylanmış bayi bulunmamaktadır.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection