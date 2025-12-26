@extends('layouts.app')

@section('title', $blog->baslik . ' - Blog')

@section('content')

<style>
    /* --- BLOG DETAY STİLLERİ --- */
    .blog-header-section {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        padding: 80px 0 60px;
        color: white;
        margin-bottom: 3rem;
        position: relative;
        overflow: hidden;
    }
    .blog-header-section::after {
        content: ''; position: absolute; bottom: 0; left: 0; width: 100%; height: 100%;
        background: url('https://www.transparenttextures.com/patterns/cubes.png'); opacity: 0.1;
    }

    /* İçerik Alanı */
    .blog-content-area {
        font-size: 1.1rem;
        line-height: 1.8;
        color: #334155;
    }
    .blog-content-area p { margin-bottom: 1.5rem; }
    .blog-content-area h2, .blog-content-area h3 {
        color: #0f172a; font-weight: 800; margin-top: 2.5rem; margin-bottom: 1rem;
    }
    .blog-content-area img {
        max-width: 100%; height: auto; border-radius: 12px; margin: 20px 0;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    .blog-content-area ul, .blog-content-area ol { margin-bottom: 1.5rem; padding-left: 1.5rem; }
    .blog-content-area li { margin-bottom: 0.5rem; }

    /* Yazar Kartı */
    .author-card {
        background: #f8fafc; border-radius: 12px; padding: 20px;
        display: flex; align-items: center; gap: 15px; margin-top: 3rem;
        border-left: 4px solid var(--primary-color, #3b82f6);
    }

    /* Sidebar Widget */
    .sidebar-widget {
        background: white; border: 1px solid #e2e8f0; border-radius: 16px;
        padding: 25px; margin-bottom: 30px;
    }
    .widget-title {
        font-size: 1.1rem; font-weight: 700; color: #0f172a;
        margin-bottom: 20px; padding-bottom: 15px;
        border-bottom: 2px solid #f1f5f9; position: relative;
    }
    .widget-title::after {
        content: ''; position: absolute; bottom: -2px; left: 0; width: 50px;
        height: 2px; background: var(--primary-color, #3b82f6);
    }

    /* Mini Post Listesi */
    .mini-post-list .mini-post-item {
        display: flex; gap: 15px; margin-bottom: 20px; text-decoration: none;
    }
    .mini-post-list .mini-post-item:last-child { margin-bottom: 0; }
    .mini-post-img {
        width: 80px; height: 80px; border-radius: 10px; object-fit: cover; flex-shrink: 0;
    }
    .mini-post-content h6 {
        font-size: 0.95rem; font-weight: 600; color: #334155;
        line-height: 1.4; margin-bottom: 5px; transition: 0.2s;
        display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
    }
    .mini-post-item:hover h6 { color: var(--primary-color, #3b82f6); }
    .mini-post-date { font-size: 0.75rem; color: #94a3b8; }

    /* Ürün Widget */
    .widget-product-card {
        border: 1px solid #f1f5f9; border-radius: 10px; padding: 10px;
        margin-bottom: 15px; transition: 0.2s;
    }
    .widget-product-card:hover { border-color: var(--primary-color, #3b82f6); transform: translateY(-3px); }
</style>

<section class="blog-header-section">
    <div class="container position-relative z-1">
        <div class="row justify-content-center">
            <div class="col-lg-10 text-center">
                <nav aria-label="breadcrumb" class="d-flex justify-content-center mb-4">
                    <ol class="breadcrumb mb-0 px-3 py-2 bg-white bg-opacity-10 rounded-pill">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white text-decoration-none opacity-75">Anasayfa</a></li>
                        <li class="breadcrumb-item"><a href="#" class="text-white text-decoration-none opacity-75">Blog</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Detay</li>
                    </ol>
                </nav>

                <h1 class="display-4 fw-bold mb-4">{{ $blog->baslik }}</h1>
                
                <div class="d-flex justify-content-center align-items-center gap-4 text-white opacity-75">
                    <span><i class="fas fa-user me-2"></i>{{ $blog->yazar }}</span>
                    <span><i class="fas fa-calendar-alt me-2"></i>{{ $blog->created_at->isoFormat('D MMMM YYYY') }}</span>
                    <span><i class="fas fa-clock me-2"></i>{{ ceil(str_word_count(strip_tags($blog->icerik)) / 200) }} dk okuma</span>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="container mb-5">
    <div class="row">
        <div class="col-lg-8">
            <article class="bg-white rounded-4 shadow-sm p-4 p-md-5 border border-light">
                
                @if($blog->resim)
                <div class="mb-5 rounded-4 overflow-hidden shadow-lg">
                    <img src="{{ asset('storage/' . $blog->resim) }}" alt="{{ $blog->baslik }}" class="w-100 object-fit-cover" style="max-height: 500px;">
                </div>
                @endif

                <div class="blog-content-area">
                    {{-- Editörden gelen HTML içeriği olduğu gibi basıyoruz --}}
                    {!! $blog->icerik !!}
                </div>

                <div class="border-top mt-5 pt-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                        <h6 class="mb-0 fw-bold">Bu yazıyı paylaş:</h6>
                        <div class="d-flex gap-2">
                            <a href="#" class="btn btn-outline-primary btn-sm rounded-pill px-3"><i class="fab fa-facebook-f me-2"></i>Facebook</a>
                            <a href="#" class="btn btn-outline-info btn-sm rounded-pill px-3"><i class="fab fa-twitter me-2"></i>Twitter</a>
                            <a href="#" class="btn btn-outline-success btn-sm rounded-pill px-3"><i class="fab fa-whatsapp me-2"></i>WhatsApp</a>
                        </div>
                    </div>
                </div>

                <div class="author-card">
                    <div class="flex-shrink-0">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fs-4 fw-bold" style="width: 60px; height: 60px;">
                            {{ strtoupper(substr($blog->yazar, 0, 1)) }}
                        </div>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">{{ $blog->yazar }}</h6>
                        <p class="mb-0 text-muted small">Teknoloji editörü ve içerik üreticisi. Yeni nesil donanımlar ve yazılımlar üzerine incelemeler yapıyor.</p>
                    </div>
                </div>

            </article>
        </div>

        <div class="col-lg-4 mt-5 mt-lg-0">
            <div class="sticky-top" style="top: 20px; z-index: 1;">
                
                <div class="sidebar-widget">
                    <h5 class="widget-title">Blogda Ara</h5>
                    <form action="#" class="position-relative">
                        <input type="text" class="form-control rounded-pill ps-4" placeholder="Bir şeyler arayın...">
                        <button class="btn btn-link position-absolute top-50 end-0 translate-middle-y text-muted" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>

                <div class="sidebar-widget">
                    <h5 class="widget-title">Son Eklenenler</h5>
                    <div class="mini-post-list">
                        @forelse($sonYazilar as $yazi)
                        <a href="{{ route('blog.detay', $yazi->slug) }}" class="mini-post-item">
                            <img src="{{ asset('storage/' . $yazi->resim) }}" class="mini-post-img" alt="Blog">
                            <div class="mini-post-content">
                                <h6>{{ $yazi->baslik }}</h6>
                                <span class="mini-post-date"><i class="far fa-calendar-alt me-1"></i> {{ $yazi->created_at->format('d.m.Y') }}</span>
                            </div>
                        </a>
                        @empty
                        <p class="text-muted small">Başka yazı bulunamadı.</p>
                        @endforelse
                    </div>
                </div>

                @if(isset($onerilenUrunler) && $onerilenUrunler->count() > 0)
                <div class="sidebar-widget bg-light border-0">
                    <h5 class="widget-title">Sizin İçin Seçtiklerimiz</h5>
                    @foreach($onerilenUrunler as $urun)
                        @php
                            // Fiyat Hesaplama Mantığı
                            $user = auth()->user();
                            $satisFiyati = $urun->getFiyatForUser($user) ?? 0;
                            $standartFiyat = $urun->getStandartFiyat() ?? 0;
                            
                            // Eğer satış fiyatı yoksa veya 0 ise standart fiyatı baz alalım, o da yoksa 0 kalır.
                            $gosterilecekFiyat = $satisFiyati > 0 ? $satisFiyati : $standartFiyat;
                        @endphp

                    <div class="widget-product-card bg-white d-flex align-items-center gap-3">
                        <div class="flex-shrink-0" style="width: 60px; height: 60px;">
                            <img src="{{ $urun->resim_url ? asset($urun->resim_url) : 'https://via.placeholder.com/60' }}" class="w-100 h-100 object-fit-contain">
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1" style="font-size: 0.9rem; line-height: 1.3;">
                                <a href="{{ route('urun.incele', $urun->id) }}" class="text-dark text-decoration-none">{{ $urun->urun_ad }}</a>
                            </h6>
                            
                            @if($gosterilecekFiyat > 0)
                                <div class="fw-bold text-primary">{{ number_format($gosterilecekFiyat, 2, ',', '.') }} ₺</div>
                            @else
                                <div class="fw-bold text-muted small" style="font-size: 0.8rem;">Fiyat İçin Arayınız</div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                    <a href="{{ route('urun.index') }}" class="btn btn-primary w-100 rounded-pill btn-sm mt-2">Mağazaya Git</a>
                </div>
                @endif

            </div>
        </div>
    </div>
</div>

@endsection