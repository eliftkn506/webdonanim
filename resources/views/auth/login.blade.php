@extends('layouts.app')
@section('title', 'Giriş Yap ')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-primary text-white text-center fw-bold fs-4 rounded-top">
                    Giriş Yap
                </div>

                <div class="card-body p-4">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- E-posta -->
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">E-posta Adresi</label>
                            <input id="email" type="email" 
                                class="form-control @error('email') is-invalid @enderror" 
                                name="email" value="{{ old('email') }}" required autofocus>
                            @error('email')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Şifre -->
                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">Şifre</label>
                            <input id="password" type="password" 
                                class="form-control @error('password') is-invalid @enderror" 
                                name="password" required>
                            @error('password')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Beni Hatırla -->
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">
                                Beni Hatırla
                            </label>
                        </div>

                        <!-- Butonlar -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill">
                                Giriş Yap
                            </button>

                            @if (Route::has('password.request'))
                                <a class="btn btn-link text-decoration-none text-center" href="{{ route('password.request') }}">
                                    Şifremi Unuttum?
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                <div class="card-footer text-center text-muted">
                    Hesabınız yok mu? <a href="{{ route('register') }}" class="text-decoration-none">Kayıt Olun</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
