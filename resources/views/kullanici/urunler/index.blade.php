@extends('layouts.app')
@section('title', 'Avantaj Bilişim - Fırsatlar Dünyası')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
/* --- RENK PALETİ VE DEĞİŞKENLER --- */
:root {
    --primary: #0066FF;
    --primary-dark: #004ec2;
    --primary-light: #3385ff;
    --accent: #FF3366;
    --accent-light: #ff5c85;
    --success: #10B981;
    --warning: #f59e0b;
    --dark: #0f172a;
    --light: #f8fafc;
    --white: #ffffff;
    --border: #e2e8f0;
    --shadow-sm: 0 1px 3px rgba(0,0,0,0.05);
    --shadow-md: 0 4px 12px rgba(0,0,0,0.08);
    --shadow-lg: 0 10px 30px rgba(0,0,0,0.15);
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    background: linear-gradient(135deg, #f5f7fa 0%, #e8eef5 100%);
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    color: var(--dark);
    overflow-x: hidden;
}

/* --- HERO KAMPANYA SLIDER --- */
.hero-wrapper {
    background: linear-gradient(135deg, #0a0e27 0%, #1a1f3a 50%, #2d1b3d 100%);
    padding: 4rem 0 5rem;
    margin-bottom: 4rem;
    position: relative;
    overflow: hidden;
}

.hero-wrapper::before {
    content: '';
    position: absolute;
    top: -30%;
    left: -10%;
    width: 600px;
    height: 600px;
    background: radial-gradient(circle, rgba(255,51,102,0.15) 0%, transparent 70%);
    border-radius: 50%;
    animation: float 8s ease-in-out infinite;
}

.hero-wrapper::after {
    content: '';
    position: absolute;
    bottom: -20%;
    right: -10%;
    width: 500px;
    height: 500px;
    background: radial-gradient(circle, rgba(0,102,255,0.15) 0%, transparent 70%);
    border-radius: 50%;
    animation: float 10s ease-in-out infinite reverse;
}

@keyframes float {
    0%, 100% { transform: translate(0, 0) scale(1); }
    50% { transform: translate(30px, -30px) scale(1.1); }
}

/* Parlayan Yıldızlar Efekti */
.stars {
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    pointer-events: none;
}

.star {
    position: absolute;
    width: 2px;
    height: 2px;
    background: white;
    border-radius: 50%;
    animation: twinkle 3s infinite;
}

@keyframes twinkle {
    0%, 100% { opacity: 0; transform: scale(0); }
    50% { opacity: 1; transform: scale(1.5); }
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 3rem;
    position: relative;
    z-index: 2;
    padding-bottom: 1.5rem;
    border-bottom: 2px solid rgba(255,255,255,0.1);
}

.section-title-wrapper {
    display: flex;
    align-items: center;
    gap: 16px;
    animation: slideInLeft 0.8s ease-out;
}

@keyframes slideInLeft {
    from { opacity: 0; transform: translateX(-50px); }
    to { opacity: 1; transform: translateX(0); }
}

.section-title-text {
    font-size: 2.5rem;
    font-weight: 900;
    background: linear-gradient(135deg, #fff 0%, #ffd700 50%, #ff6b6b 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    text-transform: uppercase;
    letter-spacing: 2px;
    position: relative;
    text-shadow: 0 0 30px rgba(255,215,0,0.3);
}

/* Geliştirilmiş Alev İkonu */
.fire-icon {
    font-size: 2.8rem;
    color: #ff6b00;
    filter: drop-shadow(0 0 20px rgba(255,107,0,0.8));
    animation: fireFlicker 1.5s infinite alternate, fireFloat 3s ease-in-out infinite;
}

@keyframes fireFlicker {
    0% { 
        opacity: 1; 
        transform: scale(1) rotate(-5deg);
        filter: drop-shadow(0 0 20px rgba(255,107,0,0.8));
    }
    50% {
        opacity: 0.85;
        transform: scale(1.15) rotate(5deg);
        filter: drop-shadow(0 0 30px rgba(255,215,0,1));
    }
    100% { 
        opacity: 1; 
        transform: scale(1.05) rotate(-3deg);
        filter: drop-shadow(0 0 25px rgba(255,51,102,0.9));
    }
}

@keyframes fireFloat {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-8px); }
}

.pulse-badge {
    background: linear-gradient(135deg, #ff3366 0%, #ff6b6b 100%);
    color: white;
    font-size: 0.9rem;
    padding: 6px 16px;
    border-radius: 25px;
    box-shadow: 0 0 20px rgba(255,51,102,0.6), 0 0 40px rgba(255,51,102,0.4);
    animation: pulseGlow 2s infinite, bounce 1s ease-in-out infinite;
    font-weight: 700;
    position: relative;
    text-transform: uppercase;
    letter-spacing: 1px;
}

@keyframes pulseGlow {
    0%, 100% { 
        box-shadow: 0 0 20px rgba(255,51,102,0.6), 0 0 40px rgba(255,51,102,0.4);
        transform: scale(1);
    }
    50% { 
        box-shadow: 0 0 30px rgba(255,51,102,0.9), 0 0 60px rgba(255,51,102,0.6);
        transform: scale(1.05);
    }
}

@keyframes bounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-5px); }
}

.hero-subtitle {
    color: rgba(255,255,255,0.8);
    font-size: 1rem;
    font-weight: 500;
    animation: slideInRight 0.8s ease-out;
    display: flex;
    align-items: center;
    gap: 8px;
}

@keyframes slideInRight {
    from { opacity: 0; transform: translateX(50px); }
    to { opacity: 1; transform: translateX(0); }
}

/* Slider Kartları - Geliştirilmiş */
.deal-card {
    background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
    backdrop-filter: blur(15px);
    border: 1px solid rgba(255,255,255,0.2);
    border-radius: 20px;
    padding: 2rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    height: 380px;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    position: relative;
    overflow: hidden;
    cursor: pointer;
}

.deal-card::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
    transform: rotate(45deg);
    transition: 0.6s;
}

.deal-card:hover::before {
    left: 100%;
}

.deal-card:hover {
    transform: translateY(-15px) scale(1.02);
    background: linear-gradient(135deg, rgba(255,255,255,0.15) 0%, rgba(255,255,255,0.08) 100%);
    border-color: var(--accent);
    box-shadow: 
        0 20px 60px rgba(255,51,102,0.3),
        0 0 40px rgba(0,102,255,0.2),
        inset 0 0 20px rgba(255,255,255,0.1);
}

.deal-badge {
    position: absolute;
    top: 20px;
    right: 20px;
    background: linear-gradient(135deg, #ff3366 0%, #ff6b6b 100%);
    color: white;
    font-weight: 900;
    font-size: 1.1rem;
    padding: 8px 16px;
    border-radius: 12px;
    z-index: 2;
    box-shadow: 0 4px 15px rgba(255,51,102,0.4);
    animation: rotateBadge 3s ease-in-out infinite;
}

@keyframes rotateBadge {
    0%, 100% { transform: rotate(-3deg) scale(1); }
    50% { transform: rotate(3deg) scale(1.1); }
}

.deal-img-wrap {
    width: 200px;
    height: 200px;
    background: white;
    border-radius: 16px;
    padding: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    margin-bottom: 1.5rem;
    position: relative;
    overflow: hidden;
    transition: 0.4s;
}

.deal-img-wrap::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(45deg, transparent 30%, rgba(255,255,255,0.5) 50%, transparent 70%);
    transform: translateX(-100%);
    transition: 0.6s;
}

.deal-card:hover .deal-img-wrap::before {
    transform: translateX(100%);
}

.deal-card:hover .deal-img-wrap {
    box-shadow: 0 15px 40px rgba(0,0,0,0.25);
}

.deal-img-wrap img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
    transition: transform 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

.deal-card:hover .deal-img-wrap img {
    transform: scale(1.15) rotate(5deg);
}

.deal-content {
    text-align: center;
    width: 100%;
}

.deal-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: white;
    margin-bottom: 1rem;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    min-height: 2.8em;
}

.deal-prices {
    margin-bottom: 1rem;
}

.deal-price-old {
    font-size: 1rem;
    color: #94a3b8;
    text-decoration: line-through;
    margin-bottom: 4px;
}

.deal-price-new {
    font-size: 2rem;
    font-weight: 900;
    background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    filter: drop-shadow(0 2px 8px rgba(16,185,129,0.4));
}

.deal-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    background: linear-gradient(135deg, rgba(255,255,255,0.2) 0%, rgba(255,255,255,0.1) 100%);
    color: white;
    border-radius: 12px;
    text-decoration: none;
    font-size: 0.95rem;
    font-weight: 700;
    transition: all 0.3s;
    border: 2px solid rgba(255,255,255,0.3);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.deal-card:hover .deal-btn {
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
    border-color: var(--primary);
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0,102,255,0.4);
}

/* --- OWL CAROUSEL ÖZELLEŞTİRME --- */
.owl-theme .owl-dots .owl-dot span {
    width: 12px;
    height: 12px;
    background: rgba(255,255,255,0.3);
    transition: 0.3s;
}

.owl-theme .owl-dots .owl-dot.active span {
    width: 30px;
    border-radius: 6px;
    background: linear-gradient(135deg, var(--accent) 0%, var(--accent-light) 100%);
}

.owl-theme .owl-dots .owl-dot:hover span {
    background: rgba(255,255,255,0.6);
}

/* --- ANA LAYOUT & GRID --- */
.main-layout {
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 2.5rem;
    max-width: 1440px;
    margin: 0 auto;
    padding: 0 2rem 5rem;
}

/* SIDEBAR - Modernize */
.sidebar {
    position: sticky;
    top: 20px;
    height: fit-content;
    background: white;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    border: 1px solid var(--border);
}

.sidebar-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    padding-bottom: 1.5rem;
    border-bottom: 2px solid #f1f5f9;
}

.sidebar-title { 
    font-weight: 800; 
    color: var(--dark); 
    display: flex; 
    align-items: center; 
    gap: 10px;
    font-size: 1.1rem;
}

.clear-filter { 
    font-size: 0.85rem; 
    color: var(--accent); 
    font-weight: 700; 
    text-decoration: none;
    transition: 0.2s;
}

.clear-filter:hover {
    color: var(--primary);
    transform: translateX(-3px);
}

.filter-group { 
    margin-bottom: 2rem; 
}

.filter-label { 
    font-size: 0.9rem; 
    font-weight: 700; 
    color: var(--dark); 
    margin-bottom: 1rem; 
    display: flex;
    align-items: center;
    gap: 8px;
}

.filter-label::before {
    content: '';
    width: 4px;
    height: 16px;
    background: linear-gradient(135deg, var(--primary), var(--accent));
    border-radius: 2px;
}

.custom-select {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    background: #f8fafc;
    color: var(--dark);
    font-size: 0.95rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%230f172a' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 12px center;
}

.custom-select:hover {
    border-color: var(--primary);
    background: white;
}

.custom-select:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 4px rgba(0,102,255,0.1);
}

.checkbox-wrap {
    max-height: 220px;
    overflow-y: auto;
    padding-right: 8px;
}

.checkbox-wrap::-webkit-scrollbar { 
    width: 6px; 
}

.checkbox-wrap::-webkit-scrollbar-track { 
    background: #f1f5f9; 
    border-radius: 10px;
}

.checkbox-wrap::-webkit-scrollbar-thumb { 
    background: linear-gradient(135deg, var(--primary), var(--accent)); 
    border-radius: 10px; 
}

.check-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 10px;
    font-size: 0.95rem;
    color: #475569;
    cursor: pointer;
    padding: 10px 12px;
    border-radius: 10px;
    transition: all 0.2s;
    font-weight: 500;
}

.check-item:hover {
    background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
    color: var(--primary);
    transform: translateX(5px);
}

.check-item input {
    margin-right: 12px;
    accent-color: var(--primary);
    width: 18px;
    height: 18px;
    cursor: pointer;
}

.count-badge {
    font-size: 0.75rem;
    background: linear-gradient(135deg, var(--primary), var(--accent));
    color: white;
    padding: 4px 8px;
    border-radius: 12px;
    font-weight: 700;
    min-width: 24px;
    text-align: center;
}

.price-inputs {
    display: flex;
    gap: 12px;
}

.price-inputs input {
    flex: 1;
}

.btn-filter {
    width: 100%;
    padding: 14px;
    background: linear-gradient(135deg, var(--dark) 0%, #1e293b 100%);
    color: white;
    border: none;
    border-radius: 12px;
    font-weight: 700;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.3s;
    box-shadow: 0 6px 20px rgba(15,23,42,0.3);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.btn-filter:hover {
    background: linear-gradient(135deg, #000 0%, var(--dark) 100%);
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(15,23,42,0.4);
}

/* ÜRÜN KARTLARI */
.product-top-bar {
    background: white;
    padding: 1.5rem 2rem;
    border-radius: 16px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    border: 1px solid var(--border);
    box-shadow: 0 2px 12px rgba(0,0,0,0.05);
}

.grid-wrapper {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 1.5rem;
}

.prod-card {
    background: white;
    border-radius: 16px;
    border: 1px solid transparent;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    position: relative;
    display: flex;
    flex-direction: column;
    height: 100%;
    overflow: hidden;
    box-shadow: 0 2px 12px rgba(0,0,0,0.06);
}

/* HOVER GÖLGE EFEKTİ - GÜÇLENDİRİLDİ */
.prod-card:hover {
    transform: translateY(-12px);
    box-shadow: 0 25px 50px rgba(0,0,0,0.15); /* Daha belirgin gölge */
    border-color: var(--primary);
    z-index: 5;
}

.prod-badges {
    position: absolute;
    top: 15px;
    left: 15px;
    display: flex;
    flex-direction: column;
    gap: 8px;
    z-index: 5;
}

.badge-item {
    font-size: 0.75rem;
    font-weight: 800;
    padding: 6px 12px;
    border-radius: 8px;
    color: white;
    text-transform: uppercase;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    backdrop-filter: blur(10px);
}

.bg-sale { 
    background: linear-gradient(135deg, var(--accent) 0%, var(--accent-light) 100%);
}

.bg-new { 
    background: linear-gradient(135deg, var(--success) 0%, #34d399 100%);
}

.bg-dealer { 
    background: linear-gradient(135deg, var(--warning) 0%, #fbbf24 100%); 
    color: var(--dark); 
}

.prod-actions {
    position: absolute;
    top: 15px;
    right: 15px;
    display: flex;
    flex-direction: column;
    gap: 10px;
    opacity: 0;
    transform: translateX(30px);
    transition: all 0.4s cubic-bezier(0.68, -0.55, 0.27, 1.55);
    z-index: 10;
}

.prod-card:hover .prod-actions {
    opacity: 1;
    transform: translateX(0);
}

.act-btn {
    width: 42px;
    height: 42px;
    background: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #64748b;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    cursor: pointer;
    transition: all 0.3s;
    border: none;
}

.act-btn:hover {
    background: var(--primary);
    color: white;
    transform: scale(1.1);
}

.act-btn.fav-active {
    color: var(--accent);
}

.act-btn.fav-active:hover {
    background: var(--accent);
    color: white;
}

.prod-img-box {
    height: 200px;
    padding: 2rem;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #fafbfc;
    border-bottom: 1px solid #f1f5f9;
    position: relative;
    overflow: hidden;
}

.prod-img-box::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(45deg, transparent 30%, rgba(255,255,255,0.6) 50%, transparent 70%);
    transform: translateX(-100%);
    transition: 0.6s;
}

.prod-card:hover .prod-img-box::before {
    transform: translateX(100%);
}

.prod-img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
    transition: transform 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

.prod-card:hover .prod-img {
    transform: scale(1.12) rotate(-2deg);
}

.prod-details {
    padding: 1.5rem;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.prod-brand {
    font-size: 0.8rem;
    font-weight: 800;
    color: var(--primary);
    text-transform: uppercase;
    margin-bottom: 6px;
    letter-spacing: 0.5px;
}

.prod-title {
    font-size: 1rem;
    font-weight: 600;
    color: var(--dark);
    margin-bottom: 1rem;
    line-height: 1.5;
    height: 3em;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}

.prod-title a {
    color: inherit;
    text-decoration: none;
    transition: 0.2s;
}

.prod-title a:hover {
    color: var(--primary);
}

/* YENİ EKLENEN KRİTERLER İÇİN CSS */
.prod-specs {
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
    margin-bottom: 10px;
    min-height: 25px; /* Boşluk oluşmaması için */
}

.spec-tag {
    font-size: 0.7rem;
    background-color: #f1f5f9;
    color: #475569;
    padding: 3px 8px;
    border-radius: 6px;
    font-weight: 600;
    border: 1px solid #e2e8f0;
}

.prod-footer {
    margin-top: auto;
    padding-top: 1rem;
    border-top: 1px solid #f1f5f9;
}

.price-row {
    min-height: 60px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    margin-bottom: 1rem;
}

.price-current {
    font-size: 1.6rem;
    font-weight: 900;
    color: var(--dark);
    line-height: 1;
}

.price-current.has-discount {
    color: var(--accent);
}

.price-old {
    font-size: 0.9rem;
    text-decoration: line-through;
    color: #94a3b8;
    margin-top: 4px;
}

.cart-group {
    display: flex;
    gap: 10px;
}

.qty-control {
    display: flex;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    overflow: hidden;
    width: 100px;
}

.qty-btn {
    width: 32px;
    border: none;
    background: #f8fafc;
    font-weight: bold;
    font-size: 1rem;
    color: #64748b;
    cursor: pointer;
    transition: 0.2s;
}

.qty-btn:hover {
    background: #e2e8f0;
    color: var(--dark);
}

.qty-input {
    width: 100%;
    border: none;
    text-align: center;
    font-weight: 700;
    color: var(--dark);
    font-size: 1rem;
    background: white;
}

.btn-add {
    flex: 1;
    height: 44px;
    border: none;
    border-radius: 10px;
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
    color: white;
    font-weight: 700;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: all 0.3s;
    box-shadow: 0 6px 20px rgba(0,102,255,0.3);
}

.btn-add:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(0,102,255,0.4);
}

.btn-add:disabled {
    background: #cbd5e1;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

/* Loading Overlay */
.loading-overlay {
    position: fixed;
    inset: 0;
    background: rgba(15,23,42,0.8);
    backdrop-filter: blur(5px);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 9999;
}

.loading-overlay.show {
    display: flex;
}

.loading-spinner {
    width: 60px;
    height: 60px;
    border: 4px solid rgba(255,255,255,0.2);
    border-top-color: var(--primary);
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Responsive */
@media (max-width: 1200px) {
    .main-layout {
        grid-template-columns: 260px 1fr;
        padding: 0 1.5rem 4rem;
    }
    
    .grid-wrapper {
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    }
}

@media (max-width: 1024px) {
    .main-layout {
        grid-template-columns: 240px 1fr;
        padding: 0 1rem 3rem;
    }
    
    .grid-wrapper {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .section-title-text {
        font-size: 2rem;
    }
}

@media (max-width: 768px) {
    .main-layout {
        grid-template-columns: 1fr;
    }
    
    .sidebar {
        display: none;
    }
    
    .hero-wrapper {
        padding: 2rem 0 3rem;
        margin-bottom: 2rem;
    }
    
    .deal-card {
        height: auto;
        padding: 1.5rem;
    }
    
    .deal-img-wrap {
        width: 160px;
        height: 160px;
        margin-bottom: 1rem;
    }
    
    .section-title-text {
        font-size: 1.5rem;
    }
    
    .fire-icon {
        font-size: 2rem;
    }
    
    .grid-wrapper {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
}
</style>

@php
    // SORGULAR: Duplicate engelleme ve filtreleme
    $firsatUrunleri = \App\Models\Urun::query()
    ->whereHas('kampanyalar', function($q) {
        $q->where('aktif', 1)
          ->where('baslangic_tarihi', '<=', now())
          ->where('bitis_tarihi', '>=', now())
          ->where('indirim_orani', '>', 0);
    })
    ->with(['kampanyalar' => function($q) {
        $q->where('aktif', 1)
          ->where('baslangic_tarihi', '<=', now())
          ->where('bitis_tarihi', '>=', now())
          ->orderBy('indirim_orani', 'desc')
          ->limit(1);
    }])
    ->inRandomOrder()
    ->get()
    ->unique('id')
    ->filter(function($urun) {
        return ($urun->getStandartFiyat() ?? 0) > 0;
    })
    ->take(10);
@endphp

@if($firsatUrunleri->count() > 0)
<div class="hero-wrapper">
    <div class="stars">
        @for($i = 0; $i < 30; $i++)
            <div class="star" style="
                left: {{ rand(0, 100) }}%; 
                top: {{ rand(0, 100) }}%; 
                animation-delay: {{ rand(0, 3000) }}ms;
            "></div>
        @endfor
    </div>

    <div class="container" style="max-width: 1440px; padding: 0 2rem; position: relative; z-index: 2;">
        <div class="section-header">
            <div class="section-title-wrapper">
                <i class="fas fa-fire fire-icon"></i>
                <span class="section-title-text">GÜNÜN YILDIZLARI</span>
                <span class="pulse-badge">⚡ Sınırlı Süre</span>
            </div>
            <div class="hero-subtitle">
                <i class="fas fa-bolt"></i>
                <span>En popüler ürünlerde dev indirimler!</span>
            </div>
        </div>

        <div class="owl-carousel owl-theme" id="heroCarousel">
            @foreach($firsatUrunleri as $fUrun)
                @php
                    $fKampanya = $fUrun->kampanyalar->first();
                    $fStandart = $fUrun->getStandartFiyat() ?? 0;
                    
                    if($fStandart <= 0 || !$fKampanya) continue;

                    $fIndirimli = $fStandart * (1 - ($fKampanya->indirim_orani / 100));
                @endphp
                <div class="item">
                    <div class="deal-card">
                        <div class="deal-badge">-%{{ $fKampanya->indirim_orani }}</div>
                        <div class="deal-img-wrap">
                            <img src="{{ $fUrun->resim_url ? asset($fUrun->resim_url) : 'https://via.placeholder.com/200' }}" 
                                 alt="{{ $fUrun->urun_ad }}"
                                 loading="lazy">
                        </div>
                        <div class="deal-content">
                            <div class="deal-title">{{ $fUrun->urun_ad }}</div>
                            <div class="deal-prices">
                                <div class="deal-price-old">₺{{ number_format($fStandart, 2, ',', '.') }}</div>
                                <div class="deal-price-new">₺{{ number_format($fIndirimli, 2, ',', '.') }}</div>
                            </div>
                            <a href="{{ route('urun.incele', $fUrun->id) }}" class="deal-btn">
                                <span>Hemen İncele</span>
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif

<div class="main-layout">
    <aside class="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-title">
                <i class="fas fa-sliders-h" style="color: var(--primary);"></i> 
                Filtreler
            </div>
            <a href="{{ route('urun.index') }}" class="clear-filter">
                <i class="fas fa-redo"></i> Temizle
            </a>
        </div>

        <form method="GET" id="filterForm">
            @if(request('q'))
                <input type="hidden" name="q" value="{{ request('q') }}">
            @endif
            <input type="hidden" name="sort" value="{{ request('sort') }}" id="sort_hidden">

            <div class="filter-group">
                <label class="filter-label">Kategori</label>
                <select name="kategori_id" class="custom-select auto-submit">
                    <option value="">Tüm Kategoriler</option>
                    @foreach($kategoriler as $kat)
                        <option value="{{ $kat->id }}" {{ request('kategori_id') == $kat->id ? 'selected' : '' }}>
                            {{ $kat->kategori_ad }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group" id="sub_cat_box" style="{{ (!empty($altKategoriler) && $altKategoriler->count()) ? '' : 'display:none' }}">
                <label class="filter-label">Alt Kategori</label>
                <select name="alt_kategori_id" class="custom-select auto-submit">
                    <option value="">Tüm Alt Kategoriler</option>
                    @foreach($altKategoriler ?? [] as $alt)
                        <option value="{{ $alt->id }}" {{ request('alt_kategori_id') == $alt->id ? 'selected' : '' }}>
                            {{ $alt->alt_kategori_ad }}
                        </option>
                    @endforeach
                </select>
            </div>

            @if(!empty($markalar))
            <div class="filter-group">
                <label class="filter-label">Marka</label>
                <div class="checkbox-wrap">
                    @foreach($markalar as $marka)
                        <label class="check-item">
                            <div style="display:flex; align-items:center;">
                                <input type="checkbox" 
                                       name="marka[]" 
                                       value="{{ $marka }}" 
                                       {{ (is_array(request('marka')) && in_array($marka, request('marka'))) ? 'checked' : '' }} 
                                       class="auto-submit">
                                {{ $marka }}
                            </div>
                            <span class="count-badge">{{ $markaCounts[$marka] ?? 0 }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
            @endif

            @if(!empty($kriterler) && $kriterler->count() > 0)
                @foreach($kriterler as $kriter)
                    <div class="filter-group">
                        <label class="filter-label">{{ $kriter->kriter_ad }}</label>
                        <div class="checkbox-wrap">
                            @foreach($kriter->degerler as $deger)
                                @if($deger->urun_count > 0)
                                    <label class="check-item">
                                        <div style="display:flex; align-items:center;">
                                            <input type="checkbox" 
                                                   name="kriterler[{{ $kriter->id }}][]" 
                                                   value="{{ $deger->id }}" 
                                                   {{ (request()->has("kriterler.{$kriter->id}") && in_array($deger->id, request("kriterler.{$kriter->id}"))) ? 'checked' : '' }} 
                                                   class="auto-submit">
                                            {{ $deger->deger }}
                                        </div>
                                        <span class="count-badge">{{ $deger->urun_count }}</span>
                                    </label>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endforeach
            @endif

            <div class="filter-group">
                <label class="filter-label">Fiyat Aralığı</label>
                <div class="price-inputs">
                    <input type="number" 
                           name="min_fiyat" 
                           class="custom-select" 
                           placeholder="Min ₺" 
                           value="{{ request('min_fiyat') }}">
                    <input type="number" 
                           name="max_fiyat" 
                           class="custom-select" 
                           placeholder="Max ₺" 
                           value="{{ request('max_fiyat') }}">
                </div>
            </div>

            <button type="submit" class="btn-filter">
                <i class="fas fa-check-circle"></i> Sonuçları Göster
            </button>
        </form>
    </aside>

    <section class="products-area">
        <div class="product-top-bar">
            <div style="font-size:1rem; color:#64748b; font-weight: 600;">
                <i class="fas fa-box-open" style="color: var(--primary); margin-right: 8px;"></i>
                <strong style="color: var(--dark);">{{ $urunler->total() }}</strong> ürün listeleniyor
            </div>
            <select class="custom-select" 
                    style="width:auto; min-width: 180px;" 
                    onchange="document.getElementById('sort_hidden').value=this.value; document.getElementById('filterForm').submit();">
                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>
                    <i class="fas fa-sparkles"></i> En Yeniler
                </option>
                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>
                    💰 Fiyat (Artan)
                </option>
                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>
                    💎 Fiyat (Azalan)
                </option>
            </select>
        </div>

        <div class="grid-wrapper">
            @forelse($urunler as $urun)
                @php
                    $user = auth()->user();
                    $satisFiyati = $urun->getFiyatForUser($user) ?? 0;
                    $standartFiyat = $urun->getStandartFiyat() ?? 0;
                    $isBayi = $user && $user->isBayi();
                    $bayiFiyat = $isBayi ? $urun->getBayiFiyat() : null;

                    $kampanya = DB::table('kampanya_indirim')
                        ->where('urun_id', $urun->id)
                        ->where('aktif', 1)
                        ->where('baslangic_tarihi', '<=', now())
                        ->where('bitis_tarihi', '>=', now())
                        ->first();
                    
                    $indirimliFiyat = $satisFiyati;
                    if($kampanya && $satisFiyati > 0) {
                        $indirimliFiyat = $satisFiyati * (1 - $kampanya->indirim_orani / 100);
                    }

                    $isFav = $user ? DB::table('favoriUrunler')
                        ->where('user_id', $user->id)
                        ->where('urun_id', $urun->id)
                        ->exists() : false;
                @endphp

                <div class="prod-card">
                    <div class="prod-badges">
                        @if($kampanya)
                            <span class="badge-item bg-sale">-%{{ $kampanya->indirim_orani }}</span>
                        @endif
                        @if($isBayi && $bayiFiyat)
                            <span class="badge-item bg-dealer">Bayi</span>
                        @endif
                        @if($urun->created_at->diffInDays() < 7)
                            <span class="badge-item bg-new">Yeni</span>
                        @endif
                    </div>

                    <div class="prod-actions">
                        <button class="act-btn {{ $isFav ? 'fav-active' : '' }}" 
                                onclick="toggleFavorite({{ $urun->id }}, this)"
                                title="Favorilere Ekle">
                            <i class="{{ $isFav ? 'fas' : 'far' }} fa-heart"></i>
                        </button>
                        <a href="{{ route('urun.incele', $urun->id) }}" 
                           class="act-btn"
                           title="Ürünü İncele">
                            <i class="far fa-eye"></i>
                        </a>
                    </div>

                    <div class="prod-img-box">
                        <a href="{{ route('urun.incele', $urun->id) }}">
                            <img src="{{ $urun->resim_url ? asset($urun->resim_url) : 'https://via.placeholder.com/300x300?text=No+Image' }}" 
                                 class="prod-img" 
                                 alt="{{ $urun->urun_ad }}"
                                 loading="lazy">
                        </a>
                    </div>

                    <div class="prod-details">
                        @if($urun->marka)
                            <div class="prod-brand">{{ $urun->marka }}</div>
                        @endif
                        <h3 class="prod-title">
                            <a href="{{ route('urun.incele', $urun->id) }}">{{ $urun->urun_ad }}</a>
                        </h3>

                        <div class="prod-specs">
                            @if($urun->urunKriterDegerleri && $urun->urunKriterDegerleri->isNotEmpty())
                                @foreach($urun->urunKriterDegerleri->take(3) as $ukd)
                                    @if($ukd->kriterDeger)
                                        <span class="spec-tag">
                                            {{-- GÜNCELLENEN KISIM BURASI: Kriter adını ekledik --}}
                                            @if($ukd->kriterDeger->kriter)
                                                <strong style="color:var(--dark); font-weight:700;">{{ $ukd->kriterDeger->kriter->kriter_ad }}:</strong>
                                            @endif
                                            {{ $ukd->kriterDeger->deger }}
                                        </span>
                                    @endif
                                @endforeach
                                @if($urun->urunKriterDegerleri->count() > 3)
                                    <span class="spec-tag">+{{ $urun->urunKriterDegerleri->count() - 3 }}</span>
                                @endif
                            @endif
                        </div>
                        
                        <div class="prod-footer">
                            <div class="price-row">
                                @if($satisFiyati > 0)
                                    @if($kampanya)
                                        <div class="price-current has-discount">
                                            ₺{{ number_format($indirimliFiyat, 2, ',', '.') }}
                                        </div>
                                        <div class="price-old">
                                            ₺{{ number_format($satisFiyati, 2, ',', '.') }}
                                        </div>
                                    @elseif($isBayi && $bayiFiyat)
                                        <div class="price-current has-discount">
                                            ₺{{ number_format($bayiFiyat, 2, ',', '.') }}
                                        </div>
                                        <div class="price-old">
                                            ₺{{ number_format($standartFiyat, 2, ',', '.') }}
                                        </div>
                                    @else
                                        <div class="price-current">
                                            ₺{{ number_format($satisFiyati, 2, ',', '.') }}
                                        </div>
                                    @endif
                                @else
                                    <div class="text-danger fw-bold" style="font-size:0.95rem;">
                                        <i class="fas fa-phone"></i> Fiyat Sorunuz
                                    </div>
                                @endif
                            </div>

                            <div class="cart-group">
                                @if($urun->stok > 0)
                                    <div class="qty-control">
                                        <button class="qty-btn" onclick="decreaseQty({{ $urun->id }})">-</button>
                                        <input type="number" 
                                               id="qty_{{ $urun->id }}" 
                                               class="qty-input" 
                                               value="1" 
                                               min="1" 
                                               max="{{ $urun->stok }}" 
                                               readonly>
                                        <button class="qty-btn" onclick="increaseQty({{ $urun->id }})">+</button>
                                    </div>
                                    <button class="btn-add" onclick="addToCart({{ $urun->id }}, this)">
                                        <i class="fas fa-shopping-basket"></i>
                                        <span>Ekle</span>
                                    </button>
                                @else
                                    <button class="btn-add" disabled style="background:#ef4444; width:100%;">
                                        <i class="fas fa-times-circle"></i> Tükendi
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div style="grid-column: 1/-1; text-align: center; padding: 5rem 2rem;">
                    <i class="fas fa-ghost" style="font-size: 5rem; color: #cbd5e1; margin-bottom: 1.5rem;"></i>
                    <h3 style="color: #64748b; font-weight: 700; margin-bottom: 0.5rem;">Ürün Bulunamadı</h3>
                    <p style="color: #94a3b8;">Arama kriterlerinizi değiştirerek tekrar deneyebilirsiniz.</p>
                    <a href="{{ route('urun.index') }}" style="display: inline-block; margin-top: 1.5rem; padding: 12px 24px; background: var(--primary); color: white; border-radius: 10px; text-decoration: none; font-weight: 600;">
                        <i class="fas fa-arrow-left"></i> Tüm Ürünlere Dön
                    </a>
                </div>
            @endforelse
        </div>

        @if($urunler->hasPages())
            <div style="margin-top: 3rem; display: flex; justify-content: center;">
                {{ $urunler->appends(request()->all())->links('pagination::bootstrap-4') }}
            </div>
        @endif
    </section>
</div>

<div class="modal fade" id="loginModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 p-4 text-center">
            <div class="mb-3" style="color: var(--primary);">
                <i class="fas fa-lock fa-3x"></i>
            </div>
            <h4 class="fw-bold mb-2">Giriş Yapmalısınız</h4>
            <p class="text-muted mb-4">Bu özelliği kullanmak için lütfen giriş yapın.</p>
            <div class="d-grid gap-2">
                <a href="{{ route('login') }}" class="btn btn-primary fw-bold">
                    <i class="fas fa-sign-in-alt"></i> Giriş Yap
                </a>
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    Vazgeç
                </button>
            </div>
        </div>
    </div>
</div>

<div class="loading-overlay" id="loadingOverlay">
    <div class="loading-spinner"></div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

<script>
$(document).ready(function(){
    // DÜZELTME: Carousel eleman sayısını kontrol et
    // Eğer az ürün varsa döngü yaparken klonlayıp görsel duplicate yaratmaması için kontrol.
    var itemCount = $('.owl-carousel .item').length;
    var shouldLoop = itemCount > 4; // 4'ten fazla ürün varsa loop olsun

    $('#heroCarousel').owlCarousel({
        loop: shouldLoop, 
        margin: 25,
        nav: false,
        dots: true,
        autoplay: true,
        autoplayTimeout: 4500,
        autoplayHoverPause: true,
        smartSpeed: 1000,
        animateOut: 'fadeOut',
        animateIn: 'fadeIn',
        responsive: {
            0: { items: 1 },
            600: { items: 2 },
            1000: { items: 3 },
            1200: { items: 4 }
        }
    });

    // Otomatik filtre submit
    $('.auto-submit').on('change', function() {
        $('#loadingOverlay').addClass('show');
        $('#filterForm').submit();
    });

    // Smooth scroll for anchor links
    $('a[href^="#"]').on('click', function(e) {
        e.preventDefault();
        const target = $(this.getAttribute('href'));
        if(target.length) {
            $('html, body').stop().animate({
                scrollTop: target.offset().top - 100
            }, 800);
        }
    });
});

// Adet Artır
function increaseQty(id) {
    const input = document.getElementById('qty_' + id);
    const val = parseInt(input.value);
    const max = parseInt(input.getAttribute('max'));
    if(val < max) {
        input.value = val + 1;
    }
}

// Adet Azalt
function decreaseQty(id) {
    const input = document.getElementById('qty_' + id);
    const val = parseInt(input.value);
    if(val > 1) {
        input.value = val - 1;
    }
}

// Sepete Ekle
function addToCart(urunId, btn) {
    const qtyInput = document.getElementById('qty_' + urunId);
    const adet = qtyInput ? qtyInput.value : 1;
    const originalContent = btn.innerHTML;

    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

    fetch('{{ route("sepet.ekle") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ id: urunId, adet: adet })
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            // Sepet sayısını güncelle
            if (typeof window.updateAllCartCounts === 'function') {
                window.updateAllCartCounts(data.sepet_count || data.sepetCount);
            }
            
            // Başarılı animasyon
            btn.innerHTML = '<i class="fas fa-check"></i> Eklendi';
            btn.style.background = 'linear-gradient(135deg, var(--success) 0%, #34d399 100%)';
            
            if(window.showToast) {
                window.showToast('Ürün sepete eklendi!', 'success');
            }

            setTimeout(() => {
                btn.innerHTML = originalContent;
                btn.style.background = '';
                btn.disabled = false;
            }, 2000);
        } else {
            alert(data.message || 'Hata oluştu');
            btn.innerHTML = originalContent;
            btn.disabled = false;
        }
    })
    .catch(err => {
        console.error(err);
        btn.innerHTML = originalContent;
        btn.disabled = false;
        alert('Bir hata oluştu. Lütfen tekrar deneyin.');
    });
}

// Favori Toggle
function toggleFavorite(id, btn) {
    @guest
        new bootstrap.Modal(document.getElementById('loginModal')).show();
        return;
    @endguest

    const icon = btn.querySelector('i');
    
    fetch('{{ route("favori.toggle") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ urun_id: id })
    })
    .then(res => res.json())
    .then(data => {
        if(data.action === 'added') {
            icon.classList.replace('far', 'fas');
            btn.classList.add('fav-active');
            if(window.showToast) {
                window.showToast('Favorilere eklendi!', 'success');
            }
        } else {
            icon.classList.replace('fas', 'far');
            btn.classList.remove('fav-active');
            if(window.showToast) {
                window.showToast('Favorilerden kaldırıldı.', 'info');
            }
        }
    })
    .catch(err => {
        console.error('Favori hatası:', err);
    });
}

// Sayfa yüklendiğinde animasyonları tetikle
window.addEventListener('load', function() {
    document.querySelectorAll('.prod-card').forEach((card, index) => {
        setTimeout(() => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            setTimeout(() => {
                card.style.transition = 'all 0.6s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 50);
        }, index * 50);
    });
});
</script>
@endsection