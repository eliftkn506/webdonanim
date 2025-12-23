@extends('layouts.app')
@section('title', $page->title . ' - Avantaj Bilişim')

@section('content')
<style>
    /* --- HAKKIMIZDA MODERN DÜZENLEME --- */
    .about-hero-section {
        background-color: #1e293b; /* Koyu Lacivert Arkaplan (Logona Uygun) */
        color: #ffffff; /* Yazılar Tam Beyaz */
        padding: 80px 0 120px;
        text-align: center;
        position: relative;
    }

    /* Görünmeyen Başlık Sorunu Çözümü */
    .about-title {
        font-size: 3.5rem;
        font-weight: 800;
        margin-bottom: 20px;
        color: #ffffff !important; /* Görünürlük Garantisi */
        text-transform: capitalize;
    }

    .about-subtitle {
        font-size: 1.2rem;
        color: #cbd5e1 !important; /* Açık gri/mavi tonu - okunurluk için */
        max-width: 800px;
        margin: 0 auto;
        line-height: 1.6;
    }

    /* İstatistikler (Görseldeki Kutular) */
    .stats-wrapper {
        margin-top: -60px;
        position: relative;
        z-index: 10;
    }

    .stats-container {
        background: #ffffff;
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        border: 1px solid #e2e8f0;
    }

    .stat-box h2 {
        font-size: 2.5rem;
        font-weight: 800;
        color: #3b82f6; /* Senin marka mavini buraya koydum */
        margin-bottom: 5px;
    }

    .stat-box p {
        color: #64748b;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 1px;
        margin: 0;
    }

    /* Ana İçerik Kartı */
    .about-main-card {
        background: #ffffff;
        border-radius: 25px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        margin: 80px 0;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
    }

    .about-text-area {
        padding: 60px;
    }

    .content-badge {
        display: inline-block;
        padding: 6px 16px;
        background: #eff6ff;
        color: #3b82f6;
        border-radius: 50px;
        font-weight: 700;
        font-size: 0.8rem;
        margin-bottom: 20px;
    }

    .content-heading {
        font-size: 2.2rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 25px;
    }

    .content-body {
        font-size: 1.1rem;
        line-height: 1.8;
        color: #475569;
    }

    .about-img-side {
        background-image: url('https://images.unsplash.com/photo-1550751827-4bd374c3f58b?q=80&w=1200'); /* Teknoloji temalı görsel */
        background-size: cover;
        background-position: center;
        min-height: 450px;
    }

    @media (max-width: 991px) {
        .about-title { font-size: 2.5rem; }
        .about-text-area { padding: 30px; }
        .about-img-side { min-height: 300px; }
    }
</style>

<section class="about-hero-section">
    <div class="container">
        <h1 class="about-title">{{ $page->title }}</h1>
        <p class="about-subtitle">
            Teknoloji dünyasındaki 10 yılı aşkın tecrübemizle, hayalinizdeki sistemleri en avantajlı çözümlerle gerçeğe dönüştürüyoruz.
        </p>
    </div>
</section>

<section class="stats-wrapper">
    <div class="container">
        <div class="stats-container">
            <div class="row text-center g-4">
                <div class="col-md-3 col-6 border-end">
                    <div class="stat-box">
                        <h2>10+</h2>
                        <p>Yıllık Tecrübe</p>
                    </div>
                </div>
                <div class="col-md-3 col-6 border-end">
                    <div class="stat-box">
                        <h2>50K+</h2>
                        <p>Mutlu Müşteri</p>
                    </div>
                </div>
                <div class="col-md-3 col-6 border-end">
                    <div class="stat-box">
                        <h2>5000+</h2>
                        <p>Ürün Çeşidi</p>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-box">
                        <h2>%99</h2>
                        <p>Memnuniyet</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="container">
    <div class="about-main-card">
        <div class="row g-0 align-items-center">
            <div class="col-lg-7">
                <div class="about-text-area">
                    <span class="content-badge">Hikayemiz</span>
                    <h2 class="content-heading">Teknolojide Güvenin ve Avantajın Merkezi</h2>
                    <div class="content-body">
                        {!! $page->content !!}
                    </div>
                </div>
            </div>
            <div class="col-lg-5 about-img-side d-none d-lg-block">
                </div>
        </div>
    </div>
</section>
@endsection