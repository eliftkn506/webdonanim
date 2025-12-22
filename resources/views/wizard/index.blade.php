@extends('layouts.app')
@section('title', 'PC Toplama Sihirbazı')

@section('content')
<style>
/* === KURUMSAL RENK PALETİ (LOGO İLE UYUMLU) === */
:root {
    /* Logo Turkuazı / Teal */
    --primary: #00897B; 
    --primary-hover: #00695C;
    --primary-light: #E0F2F1;
    
    /* Kurumsal Koyu Renk */
    --dark: #1E293B; 
    --dark-header: #0F172A;
    
    /* Metin Renkleri */
    --text-main: #334155;
    --text-muted: #64748B;
    
    /* Zemin Renkleri */
    --bg-body: #F8FAFC; 
    --bg-card: #FFFFFF;
    
    /* Yardımcı Renkler */
    --border: #E2E8F0;
    --danger: #EF4444;
    --success: #10B981;
    
    /* Gölgeler & Yuvarlatma */
    --shadow-card: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
    --shadow-hover: 0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -2px rgba(0, 0, 0, 0.04);
    --radius: 10px;
}

/* === GENEL AYARLAR === */
body {
    background-color: var(--bg-body);
    font-family: 'Inter', system-ui, -apple-system, sans-serif;
    color: var(--text-main);
}

.wizard-container {
    max-width: 1600px;
    width: 96%;
    margin: 2rem auto 4rem;
}

/* === HEADER BÖLÜMÜ === */
.wizard-header {
    text-align: center;
    margin-bottom: 2.5rem;
}

.wizard-header h1 {
    font-size: 2.2rem;
    font-weight: 800;
    color: var(--dark-header);
    margin-bottom: 0.5rem;
    letter-spacing: -0.02em;
}

.wizard-header h1 span {
    color: var(--primary);
}

.wizard-header p {
    font-size: 1rem;
    color: var(--text-muted);
}

/* === STEPPER (ADIM ÇUBUĞU) === */
.stepper-wrapper {
    background: var(--bg-card);
    border-radius: var(--radius);
    padding: 1.5rem;
    margin-bottom: 2rem;
    box-shadow: var(--shadow-card);
    border: 1px solid var(--border);
    overflow-x: auto;
}

.stepper {
    display: flex;
    justify-content: space-between;
    min-width: 800px; 
    position: relative;
}

.stepper::before {
    content: '';
    position: absolute;
    top: 18px;
    left: 40px;
    right: 40px;
    height: 2px;
    background: var(--border);
    z-index: 0;
}

.stepper-item {
    position: relative;
    z-index: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    cursor: pointer;
    flex: 1;
}

.step-circle {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: var(--bg-card);
    border: 2px solid var(--border);
    color: var(--text-muted);
    font-weight: 700;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.step-label {
    margin-top: 0.5rem;
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--text-muted);
    transition: color 0.3s ease;
}

/* Aktif ve Tamamlanan Adımlar */
.stepper-item.active .step-circle {
    border-color: var(--primary);
    background: var(--primary);
    color: white;
    box-shadow: 0 0 0 4px var(--primary-light);
    transform: scale(1.1);
}
.stepper-item.active .step-label { color: var(--primary); }

.stepper-item.completed .step-circle {
    background: var(--primary);
    border-color: var(--primary);
    color: white;
}
.stepper-item.completed .step-circle::after { content: '✓'; }
.stepper-item.completed .step-circle span { display: none; }

/* === ANA İÇERİK YAPISI (GRID) === */
.wizard-content {
    display: grid;
    grid-template-columns: 1fr 360px;
    gap: 1.5rem;
    align-items: start;
}

/* === SOL TARAF: ÜRÜN SEÇİM ALANI === */
.products-section {
    background: var(--bg-card);
    border-radius: var(--radius);
    padding: 1.5rem;
    box-shadow: var(--shadow-card);
    border: 1px solid var(--border);
    min-height: 600px;
}

.step-title {
    font-size: 1.4rem;
    font-weight: 700;
    color: var(--dark);
    padding-bottom: 1rem;
    margin-bottom: 1.5rem;
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: center;
    gap: 0.75rem;
}
.step-title i { color: var(--primary); }

/* === ALT KATEGORİLER (YAN YANA SÜTUNLAR) === */
.subcategories-wrapper {
    display: flex;
    gap: 1.5rem;
    width: 100%;
}

.subcategory-col {
    flex: 1; /* Eşit genişlik */
    display: flex;
    flex-direction: column;
    min-width: 0;
}

.subcategory-col:not(:last-child) {
    border-right: 1px dashed var(--border);
    padding-right: 1.5rem;
}

.subcat-header {
    background: var(--primary-light);
    color: var(--primary-hover);
    padding: 0.6rem 1rem;
    border-radius: 6px;
    font-weight: 700;
    font-size: 1.1rem; /* Başlık büyütüldü */
    margin-bottom: 1rem;
    border-left: 4px solid var(--primary);
    text-align: center; /* Ortalandı */
}

/* === MİNİMAL ÜRÜN GRID === */
.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(170px, 1fr)); 
    gap: 1rem;
    padding-bottom: 1rem;
}

/* === MİNİMAL ÜRÜN KARTI === */
.product-card {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 8px;
    overflow: hidden;
    transition: all 0.2s ease;
    display: flex;
    flex-direction: column;
    position: relative;
    height: 100%;
}

.product-card:hover {
    border-color: var(--primary);
    box-shadow: var(--shadow-hover);
    transform: translateY(-3px);
}

.product-img-area {
    height: 130px;
    padding: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #fff;
    border-bottom: 1px solid #f1f5f9;
}

.product-img-area img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}

.product-details {
    padding: 0.75rem;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.product-brand {
    font-size: 0.7rem;
    color: var(--text-muted);
    font-weight: 600;
    text-transform: uppercase;
    margin-bottom: 0.25rem;
}

.product-title {
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--dark);
    margin-bottom: 0.5rem;
    line-height: 1.3;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    min-height: 2.6em;
}

.product-bottom {
    margin-top: auto;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 0.5rem;
    border-top: 1px solid #f1f5f9;
}

.price-tag {
    font-size: 1rem;
    font-weight: 800;
    color: var(--primary);
}

.btn-select {
    background-color: #F1F5F9;
    color: var(--dark);
    border: 1px solid var(--border);
    padding: 0.3rem 0.8rem;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.btn-select:hover, .product-card:hover .btn-select {
    background-color: var(--primary);
    color: white;
    border-color: var(--primary);
}

/* === SAĞ TARAF: SIDEBAR === */
.sidebar {
    position: sticky;
    top: 1.5rem;
}

.summary-card {
    background: var(--bg-card);
    border-radius: var(--radius);
    border: 1px solid var(--border);
    box-shadow: var(--shadow-card);
    overflow: hidden;
}

.summary-header {
    background: var(--dark);
    color: white;
    padding: 1rem 1.25rem;
    font-weight: 700;
    font-size: 1rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.summary-body {
    padding: 1rem;
    max-height: 550px;
    overflow-y: auto;
    background: #fff;
}

/* Seçilen Ürün */
.selected-item {
    display: flex;
    gap: 0.75rem;
    align-items: center;
    padding: 0.75rem;
    border: 1px solid var(--border);
    border-radius: 8px;
    margin-bottom: 0.75rem;
    background: #f8fafc;
    transition: all 0.2s;
}

.selected-item:hover {
    border-color: var(--primary);
    background: #fff;
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
}

.selected-img {
    width: 40px;
    height: 40px;
    object-fit: contain;
    background: white;
    border-radius: 4px;
    border: 1px solid var(--border);
    padding: 2px;
}

.selected-meta { flex: 1; }

.selected-name {
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--dark);
    line-height: 1.2;
    margin-bottom: 0.2rem;
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.selected-price {
    font-size: 0.85rem;
    font-weight: 700;
    color: var(--primary);
}

.btn-remove-sm {
    width: 22px;
    height: 22px;
    border-radius: 50%;
    border: 1px solid var(--danger);
    color: var(--danger);
    background: white;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 1rem;
    line-height: 1;
    transition: all 0.2s;
}
.btn-remove-sm:hover { background: var(--danger); color: white; }

.summary-footer {
    padding: 1.25rem;
    background: #f8fafc;
    border-top: 1px solid var(--border);
}

.total-price-area {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}
.total-label { font-weight: 600; color: var(--text-muted); font-size: 0.95rem; }
.total-amount { font-weight: 800; color: var(--dark); font-size: 1.3rem; }

.config-input {
    width: 100%;
    padding: 0.7rem;
    border: 1px solid var(--border);
    border-radius: 6px;
    margin-bottom: 0.75rem;
    font-family: inherit;
    font-size: 0.9rem;
}
.config-input:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px var(--primary-light); }

.btn-save-config {
    width: 100%;
    padding: 0.8rem;
    background: var(--dark);
    color: white;
    border: none;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s;
    font-size: 0.95rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}
.btn-save-config:hover { background: var(--primary); }

.btn-reset {
    width: 100%;
    margin-top: 0.5rem;
    padding: 0.6rem;
    background: transparent;
    border: 1px solid transparent;
    color: var(--text-muted);
    cursor: pointer;
    font-size: 0.85rem;
    text-decoration: underline;
}
.btn-reset:hover { color: var(--danger); }

/* === BOŞ DURUM === */
.empty-state {
    text-align: center;
    padding: 2rem 1rem;
    color: var(--text-muted);
}
.empty-state i { font-size: 2rem; margin-bottom: 0.5rem; opacity: 0.4; }
.empty-state p { font-size: 0.9rem; margin: 0; }

/* === RESPONSIVE === */
@media (max-width: 1200px) {
    .wizard-content { grid-template-columns: 1fr; }
    .subcategories-wrapper { flex-direction: column; }
    .subcategory-col:not(:last-child) {
        border-right: none;
        border-bottom: 1px dashed var(--border);
        padding-right: 0;
        padding-bottom: 1.5rem;
        margin-bottom: 1.5rem;
    }
}

.wizard-step { display: none; }
.wizard-step.active { display: block; animation: fadeIn 0.4s; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

</style>

<div class="wizard-container">
    <div class="wizard-header">
        <h1>PC Toplama <span>Sihirbazı</span></h1>
        <p>İhtiyaçlarınıza en uygun sistemi adım adım tasarlayın.</p>
    </div>

    <div class="stepper-wrapper">
        <div class="stepper" id="wizard-stepper">
            @foreach($kategoriler as $index => $kategori)
                <div class="stepper-item {{ $index == 0 ? 'active' : '' }}" data-step="{{ $index }}">
                    <div class="step-circle">
                        <span>{{ $index + 1 }}</span>
                    </div>
                    <div class="step-label">{{ $kategori->kategori_ad }}</div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="wizard-content">
        
        <div class="products-section">
            <div id="wizard-steps">
                @foreach($kategoriler as $index => $kategori)
                    <div class="wizard-step {{ $index == 0 ? 'active' : '' }}" data-step="{{ $index }}">
                        
                        <div class="step-title">
                            <i class="fas fa-layer-group"></i>
                            <span>{{ $kategori->kategori_ad }} Seçimi</span>
                        </div>

                        <div class="subcategories-wrapper">
                            @foreach($kategori->altKategoriler as $alt)
                                
                                @php
                                    // TEKRARLI İSİMLERİ SİLME MANTIĞI
                                    // Örneğin: "Intel İşlemci" -> "Intel"
                                    // Ana kategori ismini alt kategoriden siler.
                                    $temizAd = trim(str_ireplace($kategori->kategori_ad, '', $alt->alt_kategori_ad));
                                    
                                    // Eğer silince isim boş kalırsa (örn: "RAM" - "DDR4 RAM") orijinali kullan
                                    if(empty($temizAd) || strlen($temizAd) < 2) {
                                        $temizAd = $alt->alt_kategori_ad;
                                    }
                                @endphp

                                <div class="subcategory-col">
                                    <div class="subcat-header">
                                        {{ $temizAd }}
                                    </div>

                                    <div class="category-body urun-list products-grid" 
                                         data-altkategoriid="{{ $alt->id }}" 
                                         data-step="{{ $index }}">
                                        <div class="empty-state">
                                            <i class="fas fa-spinner fa-spin"></i>
                                            <p>Yükleniyor...</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>
                @endforeach
            </div>
        </div>

        <div class="sidebar">
            <div class="summary-card">
                <div class="summary-header">
                    <i class="fas fa-receipt"></i>
                    <span>Sistem Özeti</span>
                </div>
                
                <div class="summary-body" id="selected-products">
                    <div class="empty-state">
                        <i class="fas fa-box-open"></i>
                        <p>Henüz parça eklenmedi.</p>
                    </div>
                </div>
                
                <div class="summary-footer">
                    <div class="total-price-area">
                        <span class="total-label">Toplam Tutar:</span>
                        <span class="total-amount" id="grand-total">0 ₺</span>
                    </div>

                    <input type="text" 
                           id="konfig-isim" 
                           class="config-input" 
                           placeholder="Sistem Adı (Örn: Oyun PC)"
                           required>
                    
                    <button class="btn-save-config" id="save-progress">
                        <i class="fas fa-check-circle"></i>
                        <span>Kaydet ve Bitir</span>
                    </button>
                    
                    <button class="btn-reset" id="clear-selection">
                        Temizle
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
// --- JavaScript ---
let currentStep = 0;
const singleSelectCategories = ['İşlemci','Anakart','RAM','Ekran Kartı', 'Kasa', 'Güç Kaynağı (PSU)', 'Soğutucu'];
let selectedUrun = {};

// Stepper
function updateStepper(){
    document.querySelectorAll('.stepper-item').forEach((item, i)=>{
        item.classList.remove('active', 'completed');
        if(i < currentStep) item.classList.add('completed');
        if(i === currentStep) item.classList.add('active');
    });
}

function goToStep(stepIndex){
    const totalSteps = document.querySelectorAll('.stepper-item').length;
    if (stepIndex >= totalSteps) {
        stepIndex = totalSteps - 1;
        document.getElementById('konfig-isim').focus();
    }
    
    document.querySelectorAll('.wizard-step').forEach((s,i)=>{
        s.classList.remove('active');
        if(i === stepIndex) s.classList.add('active');
    });
    currentStep = stepIndex;
    updateStepper();
    document.querySelector('.wizard-container').scrollIntoView({ behavior: 'smooth', block: 'start' });
}

document.querySelectorAll('.stepper-item').forEach(stepEl=>{
    stepEl.addEventListener('click', ()=> goToStep(parseInt(stepEl.dataset.step)));
});

// Toplam Tutar
function calculateTotal() {
    let total = 0;
    Object.values(selectedUrun).forEach(group => {
        const items = Array.isArray(group) ? group : [group];
        items.forEach(item => {
            let price = parseFloat(String(item.fiyat).replace(/[^0-9.-]+/g,""));
            if(isNaN(price)) price = 0;
            total += price * (item.adet || 1);
        });
    });
    document.getElementById('grand-total').innerText = new Intl.NumberFormat('tr-TR', { style: 'currency', currency: 'TRY' }).format(total);
}

// Sidebar Güncelle
function updateSelectedBox(){
    const container = document.getElementById('selected-products');
    container.innerHTML = '';
    const steps = Object.keys(selectedUrun);

    if(steps.length===0){
        container.innerHTML = `<div class="empty-state"><i class="fas fa-box-open"></i><p>Henüz parça eklenmedi.</p></div>`;
        calculateTotal();
        return;
    }

    steps.sort((a, b) => a - b).forEach(step=>{
        const urunler = Array.isArray(selectedUrun[step]) ? selectedUrun[step] : [selectedUrun[step]];
        
        urunler.forEach(urun=>{
            const div = document.createElement('div');
            div.className = 'selected-item';
            div.innerHTML = `
                <img src="${urun.resim || 'https://via.placeholder.com/40'}" class="selected-img">
                <div class="selected-meta">
                    <div class="selected-name">${urun.urun_ad}</div>
                    <div class="selected-price">${urun.fiyat} ₺ ${urun.adet > 1 ? 'x'+urun.adet : ''}</div>
                </div>
                <div class="selected-actions">
                    <button class="btn-remove-sm remove-urun" title="Çıkar">×</button>
                </div>
            `;
            
            div.querySelector('.remove-urun').addEventListener('click', ()=>{
                if(Array.isArray(selectedUrun[step])){
                    selectedUrun[step] = selectedUrun[step].filter(u=>u.id!==urun.id);
                    if(selectedUrun[step].length===0) delete selectedUrun[step];
                } else {
                    delete selectedUrun[step];
                }
                updateSelectedBox();
                document.querySelectorAll('.urun-list').forEach(d=> d.dispatchEvent(new Event('reloadUrun')));
            });

            container.appendChild(div);
        });
    });
    calculateTotal();
}

document.getElementById('clear-selection').addEventListener('click', ()=>{
    if(Object.keys(selectedUrun).length === 0) return;
    if(!confirm('Tüm sistemi sıfırlamak istiyor musunuz?')) return;
    
    selectedUrun = {};
    updateSelectedBox();
    document.querySelectorAll('.urun-list').forEach(div=> div.dispatchEvent(new Event('reloadUrun')));
    goToStep(0);
});

// Kaydet
document.getElementById('save-progress').addEventListener('click', ()=>{
    let isim = document.getElementById('konfig-isim').value.trim();
    if(!isim) {
        alert("Lütfen sisteminize bir isim verin!");
        document.getElementById('konfig-isim').focus();
        return;
    }

    let urunler = [];
    Object.keys(selectedUrun).forEach(step=>{
        const urunlerArr = Array.isArray(selectedUrun[step]) ? selectedUrun[step] : [selectedUrun[step]];
        urunlerArr.forEach(u=> {
            let price = parseFloat(String(u.fiyat).replace(/[^0-9.-]+/g,""));
            urunler.push({id:u.id, adet:u.adet, fiyat: price});
        });
    });

    if(urunler.length===0){ 
        alert("Lütfen en az bir parça seçin!"); 
        return; 
    }

    const saveBtn = document.getElementById('save-progress');
    saveBtn.disabled = true;
    saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Kaydediliyor...';

    fetch('/wizard/konfigurasyon-kaydet', {
        method:'POST',
        headers: {
            'Content-Type':'application/json',
            'X-CSRF-TOKEN':'{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({isim, urunler})
    })
    .then(response => {
        if (!response.ok) return response.json().then(err => { throw new Error(err.message); });
        return response.json();
    })
    .then(data=>{
        if(data.success && data.redirect_url){
            alert("Sistem başarıyla kaydedildi!");
            window.location.href = data.redirect_url;
        } else {
            alert(data.message || "Hata oluştu!");
            saveBtn.disabled = false;
            saveBtn.innerHTML = '<i class="fas fa-check-circle"></i> Kaydet ve Bitir';
        }
    })
    .catch(error => {
        console.error('Hata:', error);
        alert("Hata oluştu: " + error.message);
        saveBtn.disabled = false;
        saveBtn.innerHTML = '<i class="fas fa-check-circle"></i> Kaydet ve Bitir';
    });
});

// Ürün Yükleme ve Kart Oluşturma
document.querySelectorAll('.urun-list').forEach(div=>{
    const altKategoriId = div.dataset.altkategoriid;
    const step = div.dataset.step;

    function loadUrunler(){
        const kategoriAd = div.closest('.wizard-step').querySelector('.step-title span').innerText.replace(' Seçimi','');
        
        const prevSteps = Object.keys(selectedUrun).filter(s=>parseInt(s) < parseInt(step));
        let uyumlulukParams = '';
        if(prevSteps.length>0){
            const prevIds = prevSteps.map(s=>{ 
                const arr = Array.isArray(selectedUrun[s]) ? selectedUrun[s] : [selectedUrun[s]]; 
                return arr.map(u=>u.id); 
            }).flat();
            if(prevIds.length>0) uyumlulukParams = '?selected_urun_id='+prevIds.join(',');
        }

        div.innerHTML = `<div class="empty-state" style="grid-column: 1 / -1;"><i class="fas fa-spinner fa-spin"></i></div>`;

        fetch(`/wizard/urunler/${altKategoriId}${uyumlulukParams}`)
        .then(res=>res.json())
        .then(data=>{
            div.innerHTML='';
            if(data.length===0){ 
                div.innerHTML = `<div class="empty-state" style="grid-column: 1 / -1; padding: 1rem;"><i class="fas fa-exclamation-circle"></i><p>Uyumlu parça bulunamadı.</p></div>`;
                return; 
            }

            data.forEach(urun=>{
                const item = document.createElement('div');
                item.className='product-card';
                
                // MİNİMAL KART HTML
                item.innerHTML=`
                    <div class="product-img-area">
                        <img src="${urun.resim || 'https://via.placeholder.com/150'}" alt="${urun.urun_ad}" loading="lazy">
                    </div>
                    <div class="product-details">
                        <div class="product-brand">${urun.marka}</div>
                        <div class="product-title" title="${urun.urun_ad}">${urun.urun_ad}</div>
                        
                        <div class="product-bottom">
                            <div class="price-tag">${urun.fiyat} ₺</div>
                            <button class="btn-select">
                                <i class="fas fa-plus"></i> Ekle
                            </button>
                        </div>
                    </div>
                `;
                
                item.querySelector('button').addEventListener('click', ()=>{
                    urun.kategoriAd = kategoriAd; 

                    if(singleSelectCategories.includes(kategoriAd)){
                        selectedUrun[step] = {...urun, adet:1};
                        setTimeout(() => goToStep(parseInt(step)+1), 150);
                    } else {
                        if(!Array.isArray(selectedUrun[step])) selectedUrun[step] = [];
                        const existing = selectedUrun[step].find(u=>u.id===urun.id);
                        if(existing) {
                            existing.adet++;
                        } else {
                            selectedUrun[step].push({...urun, adet:1});
                        }
                    }
                    updateSelectedBox();
                    document.querySelectorAll('.urun-list').forEach(d=> {
                        if (d.dataset.step > step) {
                            d.dispatchEvent(new Event('reloadUrun'));
                        }
                    });
                });
                div.appendChild(item);
            });
        })
        .catch(error => {
            console.error(error);
            div.innerHTML = `<div class="empty-state" style="grid-column: 1 / -1;"><p class="text-danger">Yükleme hatası!</p></div>`;
        });
    }

    div.addEventListener('reloadUrun', loadUrunler);
    if (div.closest('.wizard-step').classList.contains('active')) {
        loadUrunler();
    }
});

document.querySelectorAll('.stepper-item').forEach(stepItem => {
    stepItem.addEventListener('click', () => {
        const stepIndex = stepItem.dataset.step;
        document.querySelectorAll(`.urun-list[data-step="${stepIndex}"]`).forEach(list => {
            list.dispatchEvent(new Event('reloadUrun'));
        });
    });
});
</script>
@endsection