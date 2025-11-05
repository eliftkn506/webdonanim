<!doctype html>
<html lang="tr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ config('app.name', 'WebDonanım') }}</title>

<!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- FontAwesome 6 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
<!-- Google Fonts -->
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
    gap: 1rem;
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

.navbar-brand-modern {
    font-weight: 900;
    font-size: 2rem;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    text-decoration: none;
    transition: var(--transition);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.navbar-brand-modern:hover {
    transform: scale(1.05);
    filter: brightness(1.1);
}

.navbar-brand-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
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
    transform: translateY(-1px);
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

/* ========== SEARCH BAR ========== */
.search-wrapper {
    position: relative;
    width: 100%;
    max-width: 450px;
}

.search-form {
    position: relative;
    display: flex;
    align-items: center;
}

.search-input {
    width: 100%;
    padding: 0.875rem 1.25rem 0.875rem 3.5rem;
    border: 2px solid var(--border-color);
    border-radius: 50px;
    background: var(--bg-primary);
    font-size: 0.95rem;
    font-weight: 500;
    transition: var(--transition);
    outline: none;
}

.search-input:focus {
    border-color: var(--accent-color);
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
    transform: translateY(-2px);
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
}

.search-btn:hover {
    background: linear-gradient(135deg, var(--accent-hover), var(--primary-color));
    transform: translateY(-50%) scale(1.05);
    box-shadow: var(--shadow-md);
}

/* ========== ACTION BUTTONS ========== */
.action-buttons {
    display: flex;
    align-items: center;
    gap: 1rem;
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

.cart-button:hover {
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
    0%, 20%, 53%, 80%, 100% {
        transform: scale(1);
    }
    40%, 43% {
        transform: scale(1.1);
    }
    70% {
        transform: scale(1.05);
    }
    90% {
        transform: scale(1.02);
    }
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

.btn-outline {
    background: transparent;
    color: var(--accent-color);
    border: 2px solid var(--accent-color);
}

.btn-outline:hover {
    background: var(--accent-color);
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

.cart-button.show + .cart-dropdown {
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
    display: none;
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
    display: none;
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
    
    .nav-menu {
        display: none;
    }
    
    .mobile-toggle {
        display: block;
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
}

@media (max-width: 576px) {
    .main-navbar {
        padding: 0.75rem 0;
    }
    
    .navbar-brand-modern {
        font-size: 1.5rem;
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
}

/* ========== UTILITIES ========== */
.fade-in {
    animation: fadeIn 0.6s ease-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
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

<!-- Top Bar -->
<div class="top-bar">
    <div class="container">
        <div class="top-bar-content">
            <div class="top-bar-left">
                <a href="tel:+905554443322" class="top-bar-item">
                    <i class="fas fa-phone"></i>
                    <span>tel:+90850 533 3444</span>
                </a>
                <a href="mailto:destek@webdonanim.com" class="top-bar-item">
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
            </div>
        </div>
    </div>
</div>

<!-- Main Navbar -->
<nav class="main-navbar">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between">
            <!-- Brand Logo -->
      <a href="/" class="navbar-brand me-3"> 
    <img src="{{ asset('resimler/logo3.png') }}" alt="WebDonanım Logo" style="height: 35px; width: auto;"> 
</a>         
            <!-- Desktop Navigation -->
            <ul class="nav-menu d-none d-lg-flex">
                <li class="nav-item-modern">
                    <a href="{{ route('home') }}" class="nav-link-modern {{ Route::is('home') ? 'active' : '' }}">
                        <i class="fas fa-home"></i>
                        Anasayfa
                    </a>
                </li>
                
                <!-- Mega Dropdown Menu -->
                <li class="nav-item-modern mega-dropdown">
                    <a href="#" class="nav-link-modern dropdown-toggle-arrow">
                        <i class="fas fa-microchip"></i>
                        Ürünler
                    </a>
                    <div class="mega-menu">
                        <div class="mega-menu-grid">
                            <!-- Tüm Ürünler -->
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
            
            <!-- Search Bar -->
            <div class="search-wrapper d-none d-md-block">
                <form method="GET" action="{{ route('urun.ara') }}" class="search-form">
                    <i class="fas fa-search search-icon"></i>
                    <input type="search" name="q" class="search-input" placeholder="Ürün ara... (örn: RTX 4090)">
                    <button type="submit" class="search-btn">
                        <i class="fas fa-search me-1"></i>Ara
                    </button>
                </form>
            </div>
            
            <!-- Action Buttons -->
            <div class="action-buttons">
                <!-- Shopping Cart -->
                <div class="position-relative">
                    <a href="#" class="cart-button" id="cartToggle">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="cart-badge" id="cartCount">
                            {{ session('sepet') ? count(session('sepet')) : 0 }}
                        </span>
                    </a>
                    
                    <!-- Cart Dropdown -->
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
                
                <!-- User Menu -->
<!-- User Menu -->
@guest
<a href="{{ route('login') }}" class="btn-modern btn-primary">
    <i class="fas fa-sign-in-alt"></i>
    Giriş Yap
</a>
<a href="{{ route('register') }}" class="btn-modern btn-warning">
    <i class="fas fa-user-plus"></i>
    Kayıt Ol
</a>
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
            
            <!-- Mobile Toggle -->
            <button class="mobile-toggle d-lg-none" id="mobileToggle">
                <i class="fas fa-bars"></i>
            </button>
        </div>
        
        <!-- Mobile Menu -->
        <div class="mobile-menu" id="mobileMenu">
            <div class="container">
                <!-- Mobile Search -->
                <div class="mb-4">
                    <form method="GET" action="{{ route('urun.ara') }}" class="search-form">
                        <i class="fas fa-search search-icon"></i>
                        <input type="search" name="q" class="search-input" placeholder="Ürün ara...">
                        <button type="submit" class="search-btn">Ara</button>
                    </form>
                </div>
                
                <!-- Mobile Navigation -->
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
                </ul>
            </div>
        </div>
    </div>
</nav>

<!-- Main Content -->
<main class="main-content fade-in">
    @yield('content')
</main>

<!-- Footer -->
<footer class="footer-modern">
    <div class="container">
        <div class="footer-grid">
            <!-- Brand Column -->
            <div class="footer-brand">
                <div class="footer-logo">
                    <i class="fas fa-microchip"></i>
                    WebDonanım
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
            
            <!-- Quick Links -->
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
            
            <!-- Legal Links -->
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
            
            <!-- Contact Info -->
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
            <p>&copy; {{ date('Y') }} WebDonanım. Tüm hakları saklıdır.</p>
        </div>
    </div>
</footer>
<!-- Bootstrap JS -->
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

// Mega dropdown functionality
document.addEventListener('DOMContentLoaded', function() {
    const megaDropdown = document.querySelector('.mega-dropdown');
    const dropdownToggle = megaDropdown?.querySelector('.dropdown-toggle-arrow');
    
    if (dropdownToggle) {
        dropdownToggle.addEventListener('click', function(e) {
            e.preventDefault();
            megaDropdown.classList.toggle('show');
        });
        
        // Close on outside click
        document.addEventListener('click', function(e) {
            if (!megaDropdown.contains(e.target)) {
                megaDropdown.classList.remove('show');
            }
        });
        
        // Close on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                megaDropdown.classList.remove('show');
            }
        });
    }
});

// Cart dropdown functionality - bu çalışıyor
document.addEventListener('DOMContentLoaded', function() {
    const cartToggle = document.getElementById('cartToggle');
    const cartDropdown = document.getElementById('cartDropdown');
    
    if (cartToggle && cartDropdown) {
        cartToggle.addEventListener('click', function(e) {
            e.preventDefault();
            cartToggle.classList.toggle('show');
        });
        
        document.addEventListener('click', function(e) {
            if (!cartToggle.contains(e.target) && !cartDropdown.contains(e.target)) {
                cartToggle.classList.remove('show');
            }
        });
    }
});

// User dropdown functionality - DÜZELTILMIŞ VERSİYON
document.addEventListener('DOMContentLoaded', function() {
    const userToggle = document.getElementById('userToggle');
    const userDropdown = document.getElementById('userDropdown');
    const userMenu = document.getElementById('userMenu');
    
    if (userToggle && userDropdown && userMenu) {
        userToggle.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('User avatar clicked!'); // Debug için
            userMenu.classList.toggle('show');
        });
        
        // Dışına tıklandığında kapat
        document.addEventListener('click', function(e) {
            if (!userMenu.contains(e.target)) {
                userMenu.classList.remove('show');
            }
        });
        
        // ESC tuşuyla kapat
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                userMenu.classList.remove('show');
            }
        });
    } else {
        console.log('User dropdown elements not found:', {userToggle, userDropdown, userMenu}); // Debug için
    }
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

// Cart functionality
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
            // Update cart count
            document.getElementById('cartCount').textContent = data.cart_count;
            
            // Remove item from dropdown
            const cartItem = document.querySelector(`[data-id="${productId}"]`);
            if (cartItem) {
                cartItem.remove();
            }
            
            // Show empty cart message if no items
            if (data.cart_count === 0) {
                document.getElementById('cartItems').innerHTML = `
                    <div class="cart-empty">
                        <i class="fas fa-shopping-cart fa-2x mb-2"></i>
                        <p>Sepetiniz boş</p>
                    </div>
                `;
            }
            
            showToast('Ürün sepetten kaldırıldı', 'success');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Bir hata oluştu', 'error');
    });
}

// Toast notification system
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = 'toast-notification';
    toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'success' ? 'var(--success-color)' : 'var(--danger-color)'};
        color: white;
        padding: 1rem 1.5rem;
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-lg);
        z-index: 9999;
        transform: translateX(400px);
        transition: transform 0.3s ease;
        font-weight: 600;
    `;
    toast.textContent = message;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.transform = 'translateX(0)';
    }, 100);
    
    setTimeout(() => {
        toast.style.transform = 'translateX(400px)';
        setTimeout(() => {
            if (document.body.contains(toast)) {
                document.body.removeChild(toast);
            }
        }, 300);
    }, 3000);
}

// Search form enhancements
document.addEventListener('DOMContentLoaded', function() {
    const searchInputs = document.querySelectorAll('.search-input');
    
    searchInputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.style.transform = 'scale(1.02)';
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.style.transform = 'scale(1)';
        });
    });
});

// Smooth scrolling for anchor links
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});

// Loading states
function showLoading() {
    document.body.classList.add('loading');
}

function hideLoading() {
    document.body.classList.remove('loading');
}

// Page loading animation
window.addEventListener('load', function() {
    hideLoading();
});

// Debug fonksiyonu - tarayıcı console'ında test etmek için
function testUserDropdown() {
    const userMenu = document.getElementById('userMenu');
    if (userMenu) {
        userMenu.classList.toggle('show');
        console.log('User menu toggled, current classes:', userMenu.className);
    } else {
        console.log('User menu not found!');
    }
}

</script>

</body>
</html>