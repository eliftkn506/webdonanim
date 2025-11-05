@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-primary text-white text-center fw-bold fs-4 rounded-top">
                    E-posta Doğrulama
                </div>

                <div class="card-body p-4 text-center">
                    @if (session('resent'))
                        <div class="alert alert-success">
                            Yeni doğrulama bağlantısı e-posta adresinize gönderildi.
                        </div>
                    @endif

                    <p class="mb-3">
                        Devam etmeden önce, lütfen e-posta adresinize gönderilen doğrulama bağlantısını kontrol edin.
                    </p>
                    <p class="mb-3">
                        E-posta almadıysanız,
                    </p>

                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">yeni bir bağlantı göndermek için tıklayın</button>.
                    </form>
                </div>

                <div class="card-footer text-center text-muted">
                    Hesabınızın güvenliği için e-posta doğrulaması gereklidir.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
