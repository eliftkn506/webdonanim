<!doctype html>
<html lang="tr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', config('app.name', 'Avantaj'))</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

<style>
:root {
    --primary-color: #1a365d;
    --primary-light: #2b77cb;
    --secondary-color: #22987e;
    --accent-color: #3182ce;
    --accent-hover: #2563eb;
    --success-color: #10b981;
    --warning-color: #f59e0b;
    --danger-color: #ef4444;
    --text-primary: #1f2937;
    --text-secondary: #6b7280;
    --text-light: #9ca3af;
    --bg-primary: #ffffff;
    --bg-secondary: #f8fafc;
    --bg-tertiary: #f1f5f9;
    --border-color: #e5e7eb;
    --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
    --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
    --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1);
    --radius-sm: 0.375rem;
    --radius-md: 0.5rem;
    --radius-lg: 0.75rem;
    --radius-xl: 1rem;
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    background: var(--bg-secondary);
    color: var(--text-primary);
    line-height: 1.6;
    font-weight: 400;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

/* DÜZELTME: Arama kutusundaki 'x' butonu gizlendi */
.search-input::-webkit-search-decoration,
.search-input::-webkit-search-cancel-button,
.search-input::-webkit-search-results-button,
.search-input::-webkit-search-results-decoration {
    -webkit-appearance: none;
    appearance: none;
}

/* ========== HERO CAROUSEL (YÜKSEKLİK VE BOŞLUK DÜZELTMESİ) ========== */
.hero-carousel {
    position: relative;
    height: 500px;
    color: white;
    margin-top: 1.5rem;
    border-radius: var(--radius-xl);
    overflow: hidden;
    box-shadow: var(--shadow-lg);
}
.hero-carousel .carousel,
.hero-carousel .carousel-inner,
.hero-carousel .carousel-item {
    height: 100%;
}
.hero-carousel .carousel-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.hero-carousel .carousel-item::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, 
        rgba(26, 54, 93, 0.7) 0%, 
        rgba(34, 152, 126, 0.6) 100%
    );
}

.hero-carousel .hero-content-wrapper {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 10;
    padding-left: 5%;
    padding-right: 5%;
}
.hero-carousel .hero-content {
    position: relative;
    top: 50%;
    transform: translateY(-50%);
    max-width: 600px;
}
.hero-badge {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    padding: 0.75rem 1.25rem;
    border-radius: 50px;
    font-weight: 600;
    display: inline-block;
    margin-bottom: 1.5rem;
}
.hero-title {
    font-size: 3.5rem;
    font-weight: 900;
    line-height: 1.2;
    margin-bottom: 1.5rem;
    text-shadow: 0 2px 4px rgba(0,0,0,0.2);
}
.hero-subtitle {
    font-size: 1.15rem;
    font-weight: 400;
    margin-bottom: 2.5rem;
    max-width: 500px;
    color: rgba(255, 255, 255, 0.9);
}
.btn-hero {
    padding: 0.875rem 2rem;
    font-weight: 700;
    border-radius: 50px;
    text-decoration: none;
    transition: var(--transition);
    border: none;
    font-size: 1rem;
    box-shadow: var(--shadow-md);
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}
.btn-primary-hero {
    background: var(--warning-color);
    color: white;
}
.btn-primary-hero:hover {
    background: #ffbe33;
    color: white;
    transform: translateY(-3px);
    box-shadow: var(--shadow-lg);
}
.btn-secondary-hero {
    background: rgba(255, 255, 255, 0.1);
    border: 2px solid white;
    color: white;
    margin-left: 1rem;
}
.btn-secondary-hero:hover {
    background: white;
    color: var(--primary-color);
    transform: translateY(-3px);
    box-shadow: var(--shadow-lg);
}
.hero-carousel .carousel-control-prev,
.hero-carousel .carousel-control-next,
.hero-carousel .carousel-indicators {
    z-index: 11;
}
.hero-carousel .carousel-indicators [data-bs-target] {
    background-color: var(--warning-color);
    height: 4px;
    width: 30px;
    opacity: 0.8;
    border: 0;
    margin: 0 5px;
}
.hero-carousel .carousel-indicators .active {
    opacity: 1;
}

/* ========== GENEL SEKSİYON BAŞLIKLARI ========== */
.section-header {
    text-align: center;
    margin-bottom: 4rem;
}
.section-badge {
    display: inline-block;
    padding: 0.5rem 1rem;
    background: rgba(59, 130, 246, 0.1);
    color: var(--accent-color);
    border-radius: 50px;
    font-weight: 600;
    font-size: 0.9rem;
    margin-bottom: 1rem;
}
.section-title {
    font-weight: 800;
    font-size: 2.5rem;
    color: var(--text-primary);
    margin-bottom: 1rem;
}
.section-subtitle {
    font-size: 1.1rem;
    color: var(--text-secondary);
    max-width: 700px;
    margin: 0 auto;
}
.products-section, .features-section, .stats-section, .categories-section, .newsletter-section {
    padding: 5rem 0;
}

/* ========== FEATURES (ÖZELLİKLER) SEKSİYONU ========== */
.features-section {
    background: var(--bg-primary);
}
.feature-card {
    background: var(--bg-primary);
    padding: 2rem;
    border-radius: var(--radius-lg);
    text-align: center;
    box-shadow: var(--shadow-md);
    border: 1px solid var(--border-color);
    transition: var(--transition);
    height: 100%;
}
.feature-card:hover {
    transform: translateY(-8px);
    box-shadow: var(--shadow-lg);
}
.feature-icon {
    width: 70px;
    height: 70px;
    line-height: 70px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: white;
    font-size: 1.75rem;
    margin: 0 auto 1.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
}
.feature-title {
    font-size: 1.25rem;
    font-weight: 700;
    margin-bottom: 0.75rem;
}
.feature-description {
    color: var(--text-secondary);
    font-size: 0.95rem;
}

/* ========== STATS (İSTATİSTİK) SEKSİYONU ========== */
.stats-section {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: white;
    padding: 5rem 0;
}
.stat-item {
    text-align: center;
}
.stat-number {
    font-size: 3.5rem;
    font-weight: 900;
    margin-bottom: 0.5rem;
}
.stat-label {
    font-size: 1.1rem;
    font-weight: 500;
    color: rgba(255, 255, 255, 0.8);
}

/* ========== MODERN ÜRÜN KARTLARI ========== */
.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 2rem;
    margin-bottom: 3rem;
}
.product-card {
    background: var(--bg-primary);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--border-color);
    overflow: hidden;
    transition: var(--transition);
    display: flex;
    flex-direction: column;
    position: relative;
}
.product-card:hover {
    transform: translateY(-10px);
    box-shadow: var(--shadow-xl);
}
.product-badge {
    position: absolute;
    top: 1rem;
    left: 1rem;
    background: linear-gradient(135deg, var(--warning-color), #d97706);
    color: white;
    font-weight: 700;
    font-size: 0.8rem;
    padding: 0.35rem 0.75rem;
    border-radius: 50px;
    z-index: 2;
}
.product-image {
    height: 220px;
    background: var(--bg-tertiary);
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    padding: 1rem;
}
.product-image img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    transition: var(--transition);
}
.product-card:hover .product-image img {
    transform: scale(1.05);
}
.product-info {
    padding: 1.5rem;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}
.product-brand {
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--text-light);
    margin-bottom: 0.5rem;
    text-transform: uppercase;
}
.product-name {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 1rem;
    line-height: 1.4;
    height: 44px; 
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}
.product-name a {
    color: inherit;
    text-decoration: none;
}
.product-name a:hover {
    color: var(--accent-color);
}
.product-price {
    font-size: 1.5rem;
    font-weight: 800;
    color: var(--accent-color);
    margin-bottom: 1.5rem;
    margin-top: auto;
}
.product-actions {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.75rem;
}
.btn-product {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0.75rem;
    font-weight: 600;
    font-size: 0.9rem;
    border-radius: var(--radius-md);
    transition: var(--transition);
    text-decoration: none;
    cursor: pointer;
    border: 1px solid transparent;
}
.btn-outline-product {
    background: var(--bg-tertiary);
    color: var(--text-primary);
    border-color: var(--border-color);
}
.btn-outline-product:hover {
    background: var(--bg-primary);
    border-color: var(--accent-color);
    color: var(--accent-color);
}
.btn-primary-product {
    background: var(--accent-color);
    color: white;
    border-color: var(--accent-color);
}
.btn-primary-product:hover {
    background: var(--accent-hover);
    border-color: var(--accent-hover);
    transform: scale(1.05);
}
.loading-shimmer {
    background: #f0f0f0;
    background-image: linear-gradient(to right, #f0f0f0 0%, #e8e8e8 20%, #f0f0f0 40%, #f0f0f0 100%);
    background-repeat: no-repeat;
    background-size: 800px 100%;
    animation: shimmer-anim 1s infinite linear;
}
@keyframes shimmer-anim {
    0% { background-position: -468px 0; }
    100% { background-position: 468px 0; }
}

/* ========== KATEGORİ SEKSİYONU ========== */
.categories-section {
    background: var(--bg-primary);
}
.categories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
}
.category-card {
    position: relative;
    border-radius: var(--radius-lg);
    overflow: hidden;
    height: 280px;
    cursor: pointer;
    transition: var(--transition);
    box-shadow: var(--shadow-md);
}
.category-card:hover {
    transform: scale(1.03);
    box-shadow: var(--shadow-xl);
}
.category-background {
    position: absolute;
    top: 0; left: 0; width: 100%; height: 100%;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    opacity: 0.8;
    transition: var(--transition);
}
.category-card:hover .category-background {
    opacity: 1;
}
.category-content {
    position: relative;
    z-index: 2;
    padding: 2rem;
    color: white;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;
}
.category-icon {
    font-size: 3rem;
    margin-bottom: 1.5rem;
    opacity: 0.9;
}
.category-name {
    font-size: 1.3rem;
    font-weight: 700;
}
.category-count {
    font-size: 0.9rem;
    color: rgba(255,255,255,0.8);
}

/* ========== HABER BÜLTENİ SEKSİYONU ========== */
.newsletter-section {
    background: var(--bg-tertiary);
}
.newsletter-content {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    border-radius: var(--radius-xl);
    padding: 4rem;
    text-align: center;
    box-shadow: var(--shadow-lg);
    color: white;
}
.newsletter-title {
    font-size: 2rem;
    font-weight: 800;
    margin-bottom: 1rem;
}
.newsletter-subtitle {
    font-size: 1.1rem;
    color: rgba(255, 255, 255, 0.8);
    max-width: 600px;
    margin: 0 auto 2.5rem;
}
.newsletter-form {
    display: flex;
    max-width: 500px;
    margin: 0 auto;
    background: var(--bg-primary);
    border: 2px solid var(--border-color);
    border-radius: 50px;
    overflow: hidden;
}
.newsletter-input {
    flex: 1;
    border: none;
    padding: 1rem 1.5rem;
    font-size: 1rem;
    outline: none;
    color: var(--text-primary);
}
.newsletter-button {
    border: none;
    background: var(--warning-color);
    color: white;
    padding: 1rem 1.5rem;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
}
.newsletter-button:hover {
    background: #ffbe33;
}

/* ========== TOP BAR ========== */
.top-bar {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: white;
    padding: 0.5rem 0;
    font-size: 0.875rem;
    position: relative;
    overflow: hidden;
}
.top-bar::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
    animation: shimmer 3s infinite;
}
@keyframes shimmer {
    0% { left: -100%; }
    100% { left: 100%; }
}
.top-bar-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.top-bar-left {
    display: flex;
    gap: 2rem;
    align-items: center;
}
.top-bar-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: rgba(255, 255, 255, 0.9);
    text-decoration: none;
    transition: var(--transition);
}
.top-bar-item:hover {
    color: white;
    transform: translateY(-1px);
}
.top-bar-right {
    display: flex;
    gap: 0.75rem; /* Sosyal ikonlar ve auth arası boşluk */
    align-items: center;
}
.social-link {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    background: rgba(255, 255, 255, 0.1);
    color: white;
    border-radius: 50%;
    text-decoration: none;
    font-size: 0.9rem;
    transition: var(--transition);
}
.social-link:hover {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    transform: scale(1.1);
}

/* YENİ: Top Bar Auth Linkleri */
.top-bar-divider {
    width: 1px;
    height: 20px;
    background: rgba(255, 255, 255, 0.2);
    margin: 0 0.5rem;
}
.top-bar-auth-link {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: white;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.875rem;
    padding: 0.25rem 0.75rem;
    border-radius: var(--radius-sm);
    transition: var(--transition);
    border: 1px solid transparent;
}
.top-bar-auth-link:hover {
    background: rgba(255, 255, 255, 0.1);
    border-color: rgba(255, 255, 255, 0.3);
    color: white;
}
.top-bar-auth-link.top-bar-auth-register {
     background: var(--warning-color);
     border-color: var(--warning-color);
}
.top-bar-auth-link.top-bar-auth-register:hover {
     background: #ffbe33;
     border-color: #ffbe33;
}


/* ========== MAIN NAVBAR ========== */
.main-navbar {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-bottom: 1px solid var(--border-color);
    padding: 1rem 0;
    position: sticky;
    top: 0;
    z-index: 1030;
    transition: var(--transition);
    box-shadow: var(--shadow-sm);
}
.main-navbar.scrolled {
    padding: 0.75rem 0;
    background: rgba(255, 255, 255, 0.98);
    box-shadow: var(--shadow-md);
}

/* ========== NAVIGATION MENU ========== */
.nav-menu {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    list-style: none;
    margin: 0;
    padding: 0;
}
.nav-item-modern {
    position: relative;
}
.nav-link-modern {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1rem;
    color: var(--text-primary);
    text-decoration: none;
    font-weight: 500;
    font-size: 0.95rem;
    border-radius: var(--radius-md);
    transition: var(--transition);
    position: relative;
    white-space: nowrap;
}
.nav-link-modern:hover {
    color: var(--accent-color);
    background: rgba(59, 130, 246, 0.05);
}
.nav-link-modern::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    width: 0;
    height: 2px;
    background: var(--accent-color);
    transition: var(--transition);
    transform: translateX(-50%);
}
.nav-link-modern:hover::after {
    width: 70%;
}
.nav-link-modern.active {
    color: var(--accent-color);
    background: rgba(59, 130, 246, 0.1);
}
.nav-link-modern.active::after {
    width: 70%;
}
.nav-link-modern.btn-outline {
    border-width: 2px;
    font-weight: 600;
    padding: 0.6rem 1.2rem;
}

/* ========== DROPDOWN MEGA MENU ========== */
.mega-dropdown {
    position: relative;
}
.dropdown-toggle-arrow::after {
    content: '\f107';
    font-family: 'Font Awesome 6 Free';
    font-weight: 900;
    margin-left: 0.5rem;
    transition: var(--transition);
    border: none;
    font-size: 0.8rem;
}
.mega-dropdown.show .dropdown-toggle-arrow::after {
    transform: rotate(180deg);
}
.mega-menu {
    position: absolute;
    top: calc(100% + 1rem);
    left: 50%;
    transform: translateX(-50%);
    min-width: 900px;
    background: var(--bg-primary);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-xl);
    padding: 2.5rem;
    opacity: 0;
    visibility: hidden;
    transform: translateX(-50%) translateY(-20px) scale(0.95);
    transition: var(--transition);
    z-index: 1000;
}
.mega-dropdown.show .mega-menu {
    opacity: 1;
    visibility: visible;
    transform: translateX(-50%) translateY(0) scale(1);
}
.mega-menu-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 2rem;
}
.category-column {
    position: relative;
}
.category-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-weight: 700;
    font-size: 1.1rem;
    color: var(--primary-color);
    margin-bottom: 1.5rem;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid var(--accent-color);
    text-decoration: none;
    transition: var(--transition);
}
.category-header:hover {
    color: var(--accent-color);
    transform: translateX(5px);
}
.category-icon {
    width: 35px;
    height: 35px;
    background: linear-gradient(135deg, var(--accent-color), var(--secondary-color));
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 0.9rem;
}
.subcategory-list {
    list-style: none;
    margin: 0;
    padding: 0;
}
.subcategory-item {
    margin-bottom: 0.75rem;
}
.subcategory-link {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--text-secondary);
    text-decoration: none;
    font-weight: 500;
    font-size: 0.9rem;
    padding: 0.5rem 0.75rem;
    border-radius: var(--radius-sm);
    transition: var(--transition);
    position: relative;
}
.subcategory-link::before {
    content: '';
    position: absolute;
    left: 0;
    top: 50%;
    width: 0;
    height: 70%;
    background: var(--accent-color);
    transform: translateY(-50%);
    transition: var(--transition);
    border-radius: 0 2px 2px 0;
}
.subcategory-link:hover {
    color: var(--accent-color);
    background: rgba(59, 130, 246, 0.05);
    transform: translateX(8px);
}
.subcategory-link:hover::before {
    width: 3px;
}

/* ========== SEARCH BAR (DÜZENLENDİ) ========== */
.search-wrapper {
    position: relative;
    width: 100%;
    max-width: 600px; /* Genişletildi */
}
.search-form {
    position: relative;
    display: flex;
    align-items: center;
}
.search-input {
    width: 100%;
    padding: 0.875rem 7.5rem 0.875rem 3.5rem;
    border: 2px solid var(--border-color);
    border-radius: 50px;
    background: var(--bg-tertiary);
    font-size: 0.95rem;
    font-weight: 500;
    transition: var(--transition);
    outline: none;
}
.search-input:focus {
    border-color: var(--accent-color);
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
    background: var(--bg-primary);
}
.search-input::placeholder {
    color: var(--text-light);
    font-weight: 400;
}
.search-icon {
    position: absolute;
    left: 1.25rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-light);
    font-size: 1.1rem;
    z-index: 2;
    pointer-events: none;
}
.search-btn {
    position: absolute;
    right: 6px;
    top: 50%;
    transform: translateY(-50%);
    background: linear-gradient(135deg, var(--accent-color), var(--primary-light));
    color: white;
    border: none;
    border-radius: 50px;
    padding: 0.625rem 1.5rem;
    font-weight: 600;
    font-size: 0.9rem;
    cursor: pointer;
    transition: var(--transition);
    box-shadow: var(--shadow-sm);
    z-index: 2;
}
.search-btn:hover {
    background: linear-gradient(135deg, var(--accent-hover), var(--primary-color));
    transform: translateY(-50%) scale(1.05);
    box-shadow: var(--shadow-md);
}

/* ========== ACTION BUTTONS (BOŞLUK EKLENDİ) ========== */
.action-buttons {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}
.cart-button {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 48px;
    height: 48px;
    background: var(--bg-primary);
    border: 2px solid var(--border-color);
    border-radius: 50%;
    color: var(--text-primary);
    text-decoration: none;
    font-size: 1.2rem;
    transition: var(--transition);
    box-shadow: var(--shadow-sm);
}
.cart-button:hover, .cart-button.show {
    color: var(--accent-color);
    border-color: var(--accent-color);
    background: rgba(59, 130, 246, 0.05);
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}
.cart-badge {
    position: absolute;
    top: -8px;
    right: -8px;
    background: linear-gradient(135deg, var(--danger-color), #dc2626);
    color: white;
    font-size: 0.75rem;
    font-weight: 700;
    padding: 0.25rem 0.5rem;
    border-radius: 50px;
    min-width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: bounce 2s infinite;
    box-shadow: var(--shadow-md);
}
@keyframes bounce {
    0%, 20%, 53%, 80%, 100% { transform: scale(1); }
    40%, 43% { transform: scale(1.1); }
    70% { transform: scale(1.05); }
    90% { transform: scale(1.02); }
}

.btn-modern {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    font-size: 0.9rem;
    text-decoration: none;
    border-radius: 50px;
    transition: var(--transition);
    border: none;
    cursor: pointer;
    white-space: nowrap;
    box-shadow: var(--shadow-sm);
}
.btn-primary {
    background: linear-gradient(135deg, var(--accent-color), var(--primary-light));
    color: white;
}
.btn-primary:hover {
    background: linear-gradient(135deg, var(--accent-hover), var(--primary-color));
    color: white;
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}
.btn-warning {
    background: linear-gradient(135deg, var(--warning-color), #d97706);
    color: white;
}
.btn-warning:hover {
    background: linear-gradient(135deg, #d97706, #b45309);
    color: white;
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

/* ========== USER PROFILE ========== */
.user-menu {
    position: relative;
}
.user-avatar {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, var(--accent-color), var(--secondary-color));
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1.1rem;
    cursor: pointer;
    transition: var(--transition);
    box-shadow: var(--shadow-sm);
    border: 3px solid var(--bg-primary);
}
.user-avatar:hover {
    transform: scale(1.1);
    box-shadow: var(--shadow-md);
}
.user-dropdown {
    position: absolute;
    top: calc(100% + 1rem);
    right: 0;
    min-width: 280px;
    background: var(--bg-primary);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-xl);
    padding: 1.5rem;
    opacity: 0;
    visibility: hidden;
    transform: translateY(-20px) scale(0.95);
    transition: var(--transition);
    z-index: 1000;
}
.user-menu.show .user-dropdown {
    opacity: 1;
    visibility: visible;
    transform: translateY(0) scale(1);
}
.user-info {
    text-align: center;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid var(--border-color);
}
.user-name {
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 0.25rem;
}
.user-email {
    font-size: 0.85rem;
    color: var(--text-secondary);
}
.user-menu-link {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 1rem;
    color: var(--text-secondary);
    text-decoration: none;
    font-weight: 500;
    border-radius: var(--radius-md);
    transition: var(--transition);
    margin-bottom: 0.5rem;
}
.user-menu-link:hover {
    color: var(--accent-color);
    background: rgba(59, 130, 246, 0.05);
    transform: translateX(5px);
}
.user-menu-link.danger:hover {
    color: var(--danger-color);
    background: rgba(239, 68, 68, 0.05);
}

/* ========== CART DROPDOWN ========== */
.cart-menu {
    position: relative;
}
.cart-dropdown {
    position: absolute;
    top: calc(100% + 1rem);
    right: 0;
    min-width: 350px;
    max-width: 400px;
    background: var(--bg-primary);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-xl);
    opacity: 0;
    visibility: hidden;
    transform: translateY(-20px) scale(0.95);
    transition: var(--transition);
    z-index: 1000;
}
.cart-menu.show .cart-dropdown {
    opacity: 1;
    visibility: visible;
    transform: translateY(0) scale(1);
}
.cart-header {
    padding: 1.5rem;
    border-bottom: 1px solid var(--border-color);
    text-align: center;
}
.cart-title {
    font-weight: 700;
    color: var(--text-primary);
    margin: 0;
}
.cart-items {
    max-height: 300px;
    overflow-y: auto;
    padding: 1rem;
}
.cart-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    border-radius: var(--radius-md);
    transition: var(--transition);
    margin-bottom: 0.5rem;
}
.cart-item:hover {
    background: var(--bg-tertiary);
}
.cart-item-image {
    width: 50px;
    height: 50px;
    border-radius: var(--radius-md);
    background: var(--bg-tertiary);
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid var(--border-color);
}
.cart-item-info {
    flex: 1;
}
.cart-item-name {
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.25rem;
    font-size: 0.9rem;
}
.cart-item-details {
    font-size: 0.8rem;
    color: var(--text-secondary);
}
.cart-item-remove {
    background: none;
    border: none;
    color: var(--danger-color);
    cursor: pointer;
    padding: 0.5rem;
    border-radius: var(--radius-sm);
    transition: var(--transition);
}
.cart-item-remove:hover {
    background: rgba(239, 68, 68, 0.1);
}
.cart-footer {
    padding: 1.5rem;
    border-top: 1px solid var(--border-color);
    text-align: center;
}
.cart-empty {
    padding: 2rem;
    text-align: center;
    color: var(--text-light);
}

/* ========== MOBILE NAVBAR ========== */
.mobile-toggle {
    background: none;
    border: none;
    color: var(--text-primary);
    font-size: 1.5rem;
    cursor: pointer;
    padding: 0.5rem;
    border-radius: var(--radius-md);
    transition: var(--transition);
}
.mobile-toggle:hover {
    background: var(--bg-tertiary);
    color: var(--accent-color);
}
.mobile-menu {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: var(--bg-primary);
    border-bottom: 1px solid var(--border-color);
    box-shadow: var(--shadow-lg);
    max-height: 0;
    overflow: hidden;
    transition: var(--transition);
}
.mobile-menu.show {
    max-height: 500px;
    padding: 2rem 0;
}
.mobile-nav {
    list-style: none;
    margin: 0;
    padding: 0 2rem;
}
.mobile-nav-item {
    margin-bottom: 1rem;
}
.mobile-nav-link {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem;
    color: var(--text-primary);
    text-decoration: none;
    font-weight: 500;
    border-radius: var(--radius-md);
    transition: var(--transition);
}
.mobile-nav-link:hover {
    color: var(--accent-color);
    background: rgba(59, 130, 246, 0.05);
}

/* ========== MAIN CONTENT ========== */
.main-content {
    min-height: calc(100vh - 200px);
    padding: 0;
}

/* ========== FOOTER ========== */
.footer-modern {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: white;
    padding: 4rem 0 2rem;
    margin-top: 5rem;
    position: relative;
    overflow: hidden;
}
.footer-modern::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--accent-color), var(--warning-color), var(--success-color));
}
.footer-grid {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr;
    gap: 3rem;
    margin-bottom: 2rem;
}
.footer-brand {
    max-width: 400px;
}
.footer-logo {
    font-size: 2rem;
    font-weight: 900;
    color: white;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.footer-description {
    color: rgba(255, 255, 255, 0.8);
    line-height: 1.6;
    margin-bottom: 2rem;
}
.footer-social {
    display: flex;
    gap: 1rem;
}
.footer-social-link {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 45px;
    height: 45px;
    background: rgba(255, 255, 255, 0.1);
    color: white;
    border-radius: 50%;
    text-decoration: none;
    font-size: 1.2rem;
    transition: var(--transition);
}
.footer-social-link:hover {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    transform: translateY(-3px);
}
.footer-column h5 {
    font-weight: 700;
    margin-bottom: 1.5rem;
    color: white;
}
.footer-links {
    list-style: none;
    margin: 0;
    padding: 0;
}
.footer-links li {
    margin-bottom: 0.75rem;
}
.footer-links a {
    color: rgba(255, 255, 255, 0.8);
    text-decoration: none;
    transition: var(--transition);
    font-weight: 500;
}
.footer-links a:hover {
    color: white;
    transform: translateX(5px);
}
.footer-contact p {
    color: rgba(255, 255, 255, 0.8);
    margin-bottom: 0.75rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}
.footer-bottom {
    text-align: center;
    padding-top: 2rem;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    color: rgba(255, 255, 255, 0.7);
}

/* ========== RESPONSIVE DESIGN ========== */
@media (max-width: 1200px) {
    .mega-menu {
        min-width: 800px;
    }
    .mega-menu-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}
@media (max-width: 991.98px) {
    .top-bar {
        display: none;
    }
    .main-navbar {
        position: relative;
    }
    .mobile-menu {
        display: block;
    }
    .search-wrapper {
        max-width: 100%;
        margin: 1rem 0;
    }
    .action-buttons {
        gap: 0.75rem;
    }
    .mega-menu {
        position: static;
        min-width: 100%;
        transform: none;
        margin-top: 1rem;
        padding: 1.5rem;
    }
    .mega-menu-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    .footer-grid {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    .hero-carousel {
        height: 500px;
    }
    .hero-title {
        font-size: 2.5rem;
    }
    .hero-subtitle {
        font-size: 1.1rem;
    }
}
@media (max-width: 576px) {
    .main-navbar {
        padding: 0.75rem 0;
    }
    .search-input {
        padding: 0.75rem 1rem 0.75rem 3rem;
        font-size: 0.9rem;
    }
    .search-btn {
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
    }
    .btn-modern {
        padding: 0.6rem 1.2rem;
        font-size: 0.85rem;
    }
    .cart-button,
    .user-avatar {
        width: 42px;
        height: 42px;
        font-size: 1rem;
    }
    .cart-dropdown,
    .user-dropdown {
        min-width: 280px;
        right: -1rem;
    }
    .hero-carousel {
        height: 450px;
    }
    .hero-title {
        font-size: 2.2rem;
    }
    .hero-subtitle {
        font-size: 1rem;
        margin-bottom: 2rem;
    }
    .btn-hero {
        font-size: 0.9rem;
        padding: 0.75rem 1.5rem;
    }
    .section-title {
        font-size: 2rem;
    }
    .newsletter-content {
        padding: 2.5rem;
    }
}

/* ========== UTILITIES & ANIMATIONS ========== */
.fade-in {
    animation: fadeIn 0.6s ease-out;
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.fade-in-up {
    opacity: 0;
    animation: fadeInUp 0.8s ease-out forwards;
}
@keyframes fadeInUp {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
.fade-in-up {
    transform: translateY(30px);
}

.loading {
    opacity: 0.6;
    pointer-events: none;
}
.text-gradient {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
</style>
</head>
<body>

<div class="top-bar d-none d-lg-block">
    <div class="container">
        <div class="top-bar-content">
            <div class="top-bar-left">
                <a href="tel:+908505333444" class="top-bar-item">
                    <i class="fas fa-phone"></i>
                    <span>tel:+90850 533 3444</span>
                </a>
                <a href="mailto:bilgi@avantajbilisim.com" class="top-bar-item">
                    <i class="fas fa-envelope"></i>
                    <span>bilgi@avantajbilisim.com</span>
                </a>
                <span class="top-bar-item">
                    <i class="fas fa-clock"></i>
                    <span>7/24 Müşteri Hizmetleri</span>
                </span>
            </div>
            <div class="top-bar-right">
                <a href="#" class="social-link" aria-label="Facebook">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="#" class="social-link" aria-label="Instagram">
                    <i class="fab fa-instagram"></i>
                </a>
                <a href="#" class="social-link" aria-label="Twitter">
                    <i class="fab fa-twitter"></i>
                </a>
                <a href="#" class="social-link" aria-label="YouTube">
                    <i class="fab fa-youtube"></i>
                </a>
                
                @guest
                <span class="top-bar-divider"></span>
                <a href="{{ route('login') }}" class="top-bar-auth-link">
                    <i class="fas fa-sign-in-alt"></i> Giriş Yap
                </a>
                <a href="{{ route('register') }}" class="top-bar-auth-link top-bar-auth-register">
                    <i class="fas fa-user-plus"></i> Kayıt Ol
                </a>
                @endguest
                </div>
        </div>
    </div>
</div>

<nav class="main-navbar">
    <div class="container">
        <div class="d-flex align-items-center">
            
            <div class="d-flex align-items-center">
                <a href="/" class="navbar-brand">
                    <img src="{{ asset('resimler/logo3.png') }}" alt="WebDonanım Logo" style="height: 35px; width: auto;">
                </a>
                
                <ul class="nav-menu d-none d-lg-flex ms-3">
                    <li class="nav-item-modern">
                        <a href="{{ route('home') }}" class="nav-link-modern {{ Route::is('home') ? 'active' : '' }}">
                            <i class="fas fa-home"></i>
                            Anasayfa
                        </a>
                    </li>
                    
                    <li class="nav-item-modern mega-dropdown">
                        <a href="#" class="nav-link-modern dropdown-toggle-arrow">
                            <i class="fas fa-microchip"></i>
                            Ürünler
                        </a>
                        <div class="mega-menu">
                            <div class="mega-menu-grid">
                                <div class="category-column">
                                    <a href="{{ route('urun.index') }}" class="category-header">
                                        <div class="category-icon">
                                            <i class="fas fa-th-large"></i>
                                        </div>
                                        Tüm Ürünler
                                    </a>
                                </div>
                                
                                @php
                                    $kategoriler = \App\Models\Kategori::with('altKategoriler')->get();
                                @endphp
                                
                                @foreach($kategoriler->take(3) as $kategori)
                                <div class="category-column">
                                    <a href="{{ route('urun.kategori', $kategori->id) }}" class="category-header">
                                        <div class="category-icon">
                                            <i class="fas fa-{{ $kategori->icon ?? 'microchip' }}"></i>
                                        </div>
                                        {{ $kategori->kategori_ad }}
                                    </a>
                                    <ul class="subcategory-list">
                                        @foreach($kategori->altKategoriler->take(6) as $alt)
                                        <li class="subcategory-item">
                                            <a href="{{ route('urun.altkategori', $alt->id) }}" class="subcategory-link">
                                                <i class="fas fa-angle-right"></i>
                                                {{ $alt->alt_kategori_ad }}
                                            </a>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </li>
                    
                    <li class="nav-item-modern">
                        <a href="{{ route('hakkimizda') }}" class="nav-link-modern {{ Route::is('hakkimizda') ? 'active' : '' }}">
                            <i class="fas fa-info-circle"></i>
                            Hakkımızda
                        </a>
                    </li>
                    
                    <li class="nav-item-modern">
                        <a href="{{ route('iletisim') }}" class="nav-link-modern {{ Route::is('iletisim') ? 'active' : '' }}">
                            <i class="fas fa-envelope"></i>
                            İletişim
                        </a>
                    </li>
                    
                    @auth
                    <li class="nav-item-modern">
                        <a href="{{ route('wizard.index') }}" class="nav-link-modern btn-outline">
                            <i class="fas fa-magic"></i>
                            PC Toplama
                        </a>
                    </li>
                    @endauth
                </ul>
            </div>
            
            <div class="search-wrapper d-none d-lg-block mx-4" style="flex-grow: 1;">
                <form method="GET" action="{{ route('urun.ara') }}" class="search-form">
                    <i class="fas fa-search search-icon"></i>
                    <input type="search" name="q" class="search-input" placeholder="Ürün ara...">
                    <button type="submit" class="search-btn">
                        <i class="fas fa-search me-1"></i>Ara
                    </button>
                </form>
            </div>
            
            <div class="d-flex align-items-center gap-2 ms-auto">
                <div class="action-buttons">
                    <div class="cart-menu" id="cartMenu">
                        <a href="#" class="cart-button" id="cartToggle">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="cart-badge" id="cartCount">
                                {{ session('sepet') ? count(session('sepet')) : 0 }}
                            </span>
                        </a>
                        
                        <div class="cart-dropdown" id="cartDropdown">
                            <div class="cart-header">
                                <h6 class="cart-title">Alışveriş Sepeti</h6>
                            </div>
                            <div class="cart-items" id="cartItems">
                                @if(session('sepet') && count(session('sepet')) > 0)
                                    @foreach(session('sepet') as $urunId => $urun)
                                    <div class="cart-item" data-id="{{ $urunId }}">
                                        <div class="cart-item-image">
                                            <i class="fas fa-microchip"></i>
                                        </div>
                                        <div class="cart-item-info">
                                            <div class="cart-item-name">{{ $urun['urun_ad'] }}</div>
                                            <div class="cart-item-details">
                                                {{ $urun['adet'] }} adet × ₺{{ number_format($urun['fiyat'], 2, ',', '.') }}
                                            </div>
                                        </div>
                                        <button class="cart-item-remove" onclick="removeFromCart({{ $urunId }})">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                    @endforeach
                                @else
                                    <div class="cart-empty">
                                        <i class="fas fa-shopping-cart fa-2x mb-2"></i>
                                        <p>Sepetiniz boş</p>
                                    </div>
                                @endif
                            </div>
                            @if(session('sepet') && count(session('sepet')) > 0)
                            <div class="cart-footer">
                                <a href="{{ route('sepet.index') }}" class="btn-modern btn-primary w-100">
                                    <i class="fas fa-shopping-bag me-2"></i>Sepete Git
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    @guest
                    @else
                    <div class="user-menu" id="userMenu">
                        <div class="user-avatar" id="userToggle">
                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                        </div>
                        
                        <div class="user-dropdown" id="userDropdown">
                            <div class="user-info">
                                <div class="user-name">{{ Auth::user()->name }}</div>
                                <div class="user-email">{{ Auth::user()->email }}</div>
                            </div>
                            
                            @if(Auth::user()->role === 'admin')
                                <a href="{{ route('admin.dashboard') }}" class="user-menu-link">
                                    <i class="fas fa-tachometer-alt"></i>
                                    Admin Paneli
                                </a>
                            @else
                                <a href="{{ route('profil') }}" class="user-menu-link">
                                    <i class="fas fa-user"></i>
                                    Profilim
                                </a>
                            @endif
                            
                            <a href="{{ route('sepet.index') }}" class="user-menu-link">
                                <i class="fas fa-shopping-bag"></i>
                                Sepetim
                            </a>
                            
                            @auth
                            <a href="{{ route('wizard.index') }}" class="user-menu-link">
                                <i class="fas fa-magic"></i>
                                PC Toplama
                            </a>
                            @endauth
                            
                            <hr class="my-2">
                            <a href="{{ route('logout') }}" class="user-menu-link danger"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt"></i>
                                Çıkış Yap
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </div>
                    @endguest
                </div>
                
                <button class="mobile-toggle d-lg-none ms-2" id="mobileToggle">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
        
        <div class="mobile-menu" id="mobileMenu">
            <div class="container">
                <div class="mb-4">
                    <form method="GET" action="{{ route('urun.ara') }}" class="search-form">
                        <i class="fas fa-search search-icon"></i>
                        <input type="search" name="q" class="search-input" placeholder="Ürün ara...">
                        <button type="submit" class="search-btn">Ara</button>
                    </form>
                </div>
                
                <ul class="mobile-nav">
                    <li class="mobile-nav-item">
                        <a href="{{ route('home') }}" class="mobile-nav-link">
                            <i class="fas fa-home"></i>
                            Anasayfa
                        </a>
                    </li>
                    <li class="mobile-nav-item">
                        <a href="{{ route('urun.index') }}" class="mobile-nav-link">
                            <i class="fas fa-microchip"></i>
                            Tüm Ürünler
                        </a>
                    </li>
                    <li class="mobile-nav-item">
                        <a href="{{ route('hakkimizda') }}" class="mobile-nav-link">
                            <i class="fas fa-info-circle"></i>
                            Hakkımızda
                        </a>
                    </li>
                    <li class="mobile-nav-item">
                        <a href="{{ route('iletisim') }}" class="mobile-nav-link">
                            <i class="fas fa-envelope"></i>
                            İletişim
                        </a>
                    </li>
                    @auth
                    <li class="mobile-nav-item">
                        <a href="{{ route('wizard.index') }}" class="mobile-nav-link">
                            <i class="fas fa-magic"></i>
                            PC Toplama Sihirbazı
                        </a>
                    </li>
                    @endauth
                    @guest
                    <li class="mobile-nav-item mt-3">
                         <a href="{{ route('login') }}" class="btn-modern btn-primary w-100">
                            <i class="fas fa-sign-in-alt"></i>
                            Giriş Yap
                        </a>
                    </li>
                     <li class="mobile-nav-item">
                        <a href="{{ route('register') }}" class="btn-modern btn-warning w-100">
                            <i class="fas fa-user-plus"></i>
                            Kayıt Ol
                        </a>
                    </li>
                    @endguest
                </ul>
            </div>
        </div>
    </div>
</nav>

<main class="main-content">
    @yield('content')
</main>

<footer class="footer-modern">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-brand">
                <div class="footer-logo">
                    <i class="fas fa-microchip"></i>
                    Avantaj Bilişim
                </div>
                <p class="footer-description">
                    Türkiye'nin önde gelen bilgisayar donanım ve teknoloji mağazası.
                    Kaliteli ürünler, güvenilir hizmet ve rekabetçi fiyatlarla
                    teknoloji ihtiyaçlarınızı karşılıyoruz.
                </p>
                <div class="footer-social">
                    <a href="#" class="footer-social-link" aria-label="Facebook">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="footer-social-link" aria-label="Instagram">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="footer-social-link" aria-label="Twitter">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="footer-social-link" aria-label="YouTube">
                        <i class="fab fa-youtube"></i>
                    </a>
                    <a href="#" class="footer-social-link" aria-label="LinkedIn">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                </div>
            </div>
            
            <div class="footer-column">
                <h5>Hızlı Erişim</h5>
                <ul class="footer-links">
                    <li><a href="{{ route('home') }}">Anasayfa</a></li>
                    <li><a href="{{ route('urun.index') }}">Tüm Ürünler</a></li>
                    <li><a href="{{ route('hakkimizda') }}">Hakkımızda</a></li>
                    <li><a href="{{ route('iletisim') }}">İletişim</a></li>
                    @auth
                    <li><a href="{{ route('wizard.index') }}">PC Toplama</a></li>
                    <li><a href="{{ route('profil') }}">Profilim</a></li>
                    @endauth
                </ul>
            </div>
            
            <div class="footer-column">
                <h5>Yasal Bilgiler</h5>
                <ul class="footer-links">
                    <li><a href="#">Gizlilik Politikası</a></li>
                    <li><a href="#">Kullanım Şartları</a></li>
                    <li><a href="#">KVKK Metni</a></li>
                    <li><a href="#">İptal & İade</a></li>
                    <li><a href="#">Mesafeli Satış</a></li>
                    <li><a href="#">Güvenli Alışveriş</a></li>
                </ul>
            </div>
            
            <div class="footer-column footer-contact">
                <h5>İletişim</h5>
                <p>
                    <i class="fas fa-map-marker-alt"></i>
                    İstanbul, Türkiye
                </p>
                <p>
                    <i class="fas fa-phone"></i>
                    tel:+90850 533 3444
                </p>
                <p>
                    <i class="fas fa-envelope"></i>
                    bilgi@avantajbilisim.com
                </p>
                <p>
                    <i class="fas fa-clock"></i>
                    7/24 Müşteri Hizmetleri
                </p>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} Avantaj Bilişim. Tüm hakları saklıdır.</p>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Navbar scroll effect
window.addEventListener('scroll', function() {
    const navbar = document.querySelector('.main-navbar');
    if (window.scrollY > 20) {
        navbar.classList.add('scrolled');
    } else {
        navbar.classList.remove('scrolled');
    }
});

// Dropdown (Mega, Cart, User) için evrensel fonksiyon
function initializeDropdown(menuId, toggleId) {
    const menu = document.getElementById(menuId);
    const toggle = document.getElementById(toggleId);
    
    if (!menu || !toggle) return;

    toggle.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        closeAllDropdowns(menuId);
        menu.classList.toggle('show');
    });
}

function closeAllDropdowns(exceptMenuId = null) {
    const dropdowns = document.querySelectorAll('.mega-dropdown, .cart-menu, .user-menu');
    dropdowns.forEach(dropdown => {
        let currentId = dropdown.id;
        if (dropdown.classList.contains('mega-dropdown') && !currentId) {
             if (exceptMenuId && dropdown.contains(document.getElementById(exceptMenuId))) {
                 return;
             }
        }
        
        if (currentId !== exceptMenuId) {
            dropdown.classList.remove('show');
        }
    });
}

document.addEventListener('click', function(e) {
    if (!e.target.closest('.mega-dropdown') && !e.target.closest('.cart-menu') && !e.target.closest('.user-menu')) {
        closeAllDropdowns();
    }
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeAllDropdowns();
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const megaDropdown = document.querySelector('.mega-dropdown');
    if (megaDropdown) {
        const megaToggle = megaDropdown.querySelector('.dropdown-toggle-arrow');
        megaDropdown.id = megaDropdown.id || 'megaMenuMain'; // ID yoksa ata
        
        megaToggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            closeAllDropdowns(megaDropdown.id);
            megaDropdown.classList.toggle('show');
        });
    }
    
    initializeDropdown('cartMenu', 'cartToggle');
    initializeDropdown('userMenu', 'userToggle');
});


// Mobile menu toggle
document.addEventListener('DOMContentLoaded', function() {
    const mobileToggle = document.getElementById('mobileToggle');
    const mobileMenu = document.getElementById('mobileMenu');
    
    if (mobileToggle && mobileMenu) {
        mobileToggle.addEventListener('click', function() {
            mobileMenu.classList.toggle('show');
            const icon = mobileToggle.querySelector('i');
            if (mobileMenu.classList.contains('show')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            } else {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        });
    }
});

// Cart functionality (Sepetten Silme)
function removeFromCart(productId) {
    fetch(`/sepet/sil/${productId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('cartCount').textContent = data.cart_count;
            
            const cartItem = document.querySelector(`.cart-item[data-id="${productId}"]`);
            if (cartItem) {
                cartItem.remove();
            }
            
            if (data.cart_count === 0) {
                document.getElementById('cartItems').innerHTML = `
                    <div class="cart-empty">
                        <i class="fas fa-shopping-cart fa-2x mb-2"></i>
                        <p>Sepetiniz boş</p>
                    </div>
                `;
                const cartFooter = document.querySelector('.cart-dropdown .cart-footer');
                if(cartFooter) cartFooter.remove();
            }
            
            showToast('Ürün sepetten kaldırıldı', 'success');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Bir hata oluştu', 'error');
    });
}

// Toast (bildirim) sistemi
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = 'toast-notification';
    toast.style.cssText = `
        position: fixed;
        bottom: 20px;
        right: 20px;
        background: ${type === 'success' ? 'var(--success-color)' : 'var(--danger-color)'};
        color: white;
        padding: 1rem 1.5rem;
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-lg);
        z-index: 9999;
        transform: translateY(100px);
        opacity: 0;
        transition: all 0.4s cubic-bezier(0.21, 1.02, 0.73, 1);
        font-weight: 600;
    `;
    toast.textContent = message;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.transform = 'translateY(0)';
        toast.style.opacity = '1';
    }, 100);
    
    setTimeout(() => {
        toast.style.transform = 'translateY(100px)';
        toast.style.opacity = '0';
        setTimeout(() => {
            if (document.body.contains(toast)) {
                document.body.removeChild(toast);
            }
        }, 400);
    }, 3000);
}

// Scroll ile tetiklenen animasyonlar
document.addEventListener('DOMContentLoaded', function() {
    const animatedElements = document.querySelectorAll('.fade-in-up');
    
    if ("IntersectionObserver" in window) {
        const observer = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.animationPlayState = 'running';
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });
        
        animatedElements.forEach(el => {
            el.style.animationPlayState = 'paused';
            observer.observe(el);
        });
    } else {
        animatedElements.forEach(el => {
            el.style.animationPlayState = 'running';
        });
    }
});

// İstatistik Sayacı
document.addEventListener('DOMContentLoaded', function() {
    const statNumbers = document.querySelectorAll('.stat-number');
    
    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const el = entry.target;
                const countTo = parseInt(el.getAttribute('data-count'), 10);
                let current = 0;
                const duration = 2000;
                const increment = Math.max(1, Math.ceil(countTo / (duration / 16)));
                
                const timer = setInterval(() => {
                    current += increment;
                    if (current >= countTo) {
                        clearInterval(timer);
                        el.textContent = countTo.toLocaleString('tr-TR');
                    } else {
                        el.textContent = current.toLocaleString('tr-TR');
                    }
                }, 16);
                
                observer.unobserve(el);
            }
        });
    }, { threshold: 0.5 });

    statNumbers.forEach(el => {
        observer.observe(el);
    });
});

</script>

@stack('scripts') </body>
</html>