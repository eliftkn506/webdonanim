@extends('layouts.app')
@section('title', 'Şifre Sıfırla - Avantaj Bilişim')

@section('content')
<style>
    :root {
        --primary-turq: #00d4aa;
        --secondary-navy: #1e293b;
        --bg-soft: #f8fafc;
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .reset-section {
        min-height: 80vh;
        display: flex;
        align-items: center;
        padding: 60px 0;
        background-color: var(--bg-soft);
    }

    .reset-card {
        background: white;
        border-radius: 24px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 20px 40px -10px rgba(30, 41, 59, 0.05);
        overflow: hidden;
    }

    .reset-header {
        background: var(--secondary-navy);
        padding: 40px 30px;
        text-align: center;
        color: white;
        position: relative;
    }

    .reset-header::after {
        content: '';
        position: absolute;
        inset: 0;
        background-image: radial-gradient(var(--primary-turq) 1px, transparent 1px);
        background-size: 20px 20px;
        opacity: 0.1;
    }

    .reset-header h2 {
        font-weight: 800;
        font-size: 1.75rem;
        margin-bottom: 10px;
        position: relative;
        z-index: 1;
    }

    .reset-header p {
        color: #94a3b8;
        font-size: 0.95rem;
        margin: 0;
        position: relative;
        z-index: 1;
    }

    .reset-body {
        padding: 40px;
    }

    .form-label {
        font-weight: 700;
        color: var(--secondary-navy);
        font-size: 0.85rem;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
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

    .reset-btn {
        background: var(--primary-turq);
        color: var(--secondary-navy) !important;
        border: none;
        padding: 15px;
        border-radius: 12px;
        font-weight: 800;
        font-size: 1rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: var(--transition);
        margin-top: 10px;
    }

    .reset-btn:hover {
        background: #00bf9a;
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(0, 212, 170, 0.2);
    }

    .fade-up {
        animation: fadeUp 0.6s ease-out;
    }

    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<section class="reset-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-8 fade-up">
                
                <div class="reset-card shadow-lg">
                    <div class="reset-header">
                        <h2>{{ __('Şifreyi Sıfırla') }}</h2>
                        <p>Yeni şifrenizi belirleyerek güvenliğinizi sağlayın.</p>
                    </div>

                    <div class="reset-body">
                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf

                            <input type="hidden" name="token" value="{{ $token }}">

                            <div class="mb-4">
                                <label for="email" class="form-label">{{ __('E-Posta Adresi') }}</label>
                                <input id="email" type="email" class="form-control custom-input @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus placeholder="ornek@mail.com">
                                @error('email')
                                    <span class="invalid-feedback d-block mt-2" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label">{{ __('Yeni Şifre') }}</label>
                                <input id="password" type="password" class="form-control custom-input @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="••••••••">
                                @error('password')
                                    <span class="invalid-feedback d-block mt-2" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="password-confirm" class="form-label">{{ __('Şifreyi Onayla') }}</label>
                                <input id="password-confirm" type="password" class="form-control custom-input" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••">
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="reset-btn">
                                    {{ __('Şifreyi Güncelle') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <a href="{{ route('login') }}" class="text-muted text-decoration-none small fw-bold">
                        <i class="fas fa-arrow-left me-1"></i> {{ __('Giriş Sayfasına Dön') }}
                    </a>
                </div>

            </div>
        </div>
    </div>
</section>
@endsection