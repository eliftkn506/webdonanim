@extends('layouts.app')
@section('title', 'Giriş Yap - Avantaj Bilişim')

@section('content')
<style>
    :root {
        --primary-turq: #00d4aa;
        --secondary-navy: #1e293b;
        --bg-soft: #f8fafc;
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Giriş Sayfası Özel Konteynır */
    .login-section {
        min-height: 80vh;
        display: flex;
        align-items: center;
        padding: 40px 0;
    }

    .login-card {
        background: white;
        border-radius: 24px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 20px 40px -10px rgba(30, 41, 59, 0.05);
        overflow: hidden;
    }

    .login-header {
        background: var(--secondary-navy);
        padding: 40px 30px;
        text-align: center;
        color: white;
        position: relative;
    }

    .login-header::after {
        content: '';
        position: absolute;
        inset: 0;
        background-image: radial-gradient(var(--primary-turq) 1px, transparent 1px);
        background-size: 20px 20px;
        opacity: 0.1;
    }

    .login-header h2 {
        font-weight: 800;
        font-size: 1.75rem;
        margin-bottom: 10px;
        position: relative;
        z-index: 1;
    }

    .login-header p {
        color: #94a3b8;
        font-size: 0.95rem;
        margin: 0;
        position: relative;
        z-index: 1;
    }

    .login-body {
        padding: 40px;
    }

    /* Form Elemanları */
    .form-label {
        font-weight: 700;
        color: var(--secondary-navy);
        font-size: 0.9rem;
        margin-bottom: 8px;
    }

    .custom-input {
        background-color: #f1f5f9 !important;
        border: 2px solid transparent !important;
        border-radius: 12px !important;
        padding: 12px 18px !important;
        font-weight: 500;
        transition: var(--transition);
    }

    .custom-input:focus {
        background-color: #fff !important;
        border-color: var(--primary-turq) !important;
        box-shadow: 0 0 0 4px rgba(0, 212, 170, 0.1) !important;
    }

    .login-btn {
        background: var(--primary-turq);
        color: var(--secondary-navy) !important;
        border: none;
        padding: 14px;
        border-radius: 12px;
        font-weight: 800;
        font-size: 1rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: var(--transition);
        margin-top: 10px;
    }

    .login-btn:hover {
        background: #00bf9a;
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(0, 212, 170, 0.2);
    }

    .remember-me .form-check-input:checked {
        background-color: var(--primary-turq);
        border-color: var(--primary-turq);
    }

    .forgot-pass {
        color: var(--text-muted);
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        transition: 0.2s;
    }

    .forgot-pass:hover {
        color: var(--primary-turq);
    }

    .login-footer {
        padding: 25px;
        background: #f8fafc;
        text-align: center;
        border-top: 1px solid #e2e8f0;
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--text-muted);
    }

    .login-footer a {
        color: var(--primary-turq);
        text-decoration: none;
        font-weight: 800;
    }

    /* Animasyon */
    .fade-up {
        animation: fadeUp 0.6s ease-out;
    }

    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<section class="login-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-8 fade-up">
                
                <div class="login-card shadow-lg">
                    <div class="login-header">
                        <h2>Hoş Geldiniz</h2>
                        <p>Hesabınıza erişmek için bilgilerinizi girin.</p>
                    </div>

                    <div class="login-body">
                        @if(session('error'))
                            <div class="alert alert-danger border-0 rounded-3 mb-4">
                                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="mb-4">
                                <label for="email" class="form-label">E-POSTA ADRESİ</label>
                                <input id="email" type="email" 
                                    class="form-control custom-input @error('email') is-invalid @enderror" 
                                    name="email" value="{{ old('email') }}" 
                                    placeholder="ornek@mail.com" required autofocus>
                                @error('email')
                                    <span class="invalid-feedback d-block mt-2" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <div class="d-flex justify-content-between">
                                    <label for="password" class="form-label">ŞİFRE</label>
                                    @if (Route::has('password.request'))
                                        <a class="forgot-pass" href="{{ route('password.request') }}">
                                            Şifremi Unuttum?
                                        </a>
                                    @endif
                                </div>
                                <input id="password" type="password" 
                                    class="form-control custom-input @error('password') is-invalid @enderror" 
                                    name="password" placeholder="••••••••" required>
                                @error('password')
                                    <span class="invalid-feedback d-block mt-2" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-check remember-me mb-4">
                                <input class="form-check-input shadow-none" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label small fw-bold text-muted" for="remember">
                                    Oturumu açık tut
                                </label>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="login-btn">
                                    <i class="fas fa-sign-in-alt me-2"></i> GİRİŞ YAP
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="login-footer">
                        Hesabınız yok mu? <a href="{{ route('register') }}">Ücretsiz Kayıt Ol</a>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <a href="{{ route('home') }}" class="text-muted text-decoration-none small fw-bold">
                        <i class="fas fa-arrow-left me-1"></i> Anasayfaya Dön
                    </a>
                </div>

            </div>
        </div>
    </div>
</section>
@endsection