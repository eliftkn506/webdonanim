@extends('layouts.app')
@section('title', 'Pc Toplama Sihirbazı ')

@section('content')
<style>
:root {
    /* === YENİ RENK PALETİ (Logonuza Göre) === */
    --avantaj-primary: #008D85; /* Logonuzdaki ana teal rengi */
    --avantaj-primary-light: #F0F9F9; /* Teal'in çok açık tonu (vurgu için) */
    --avantaj-dark: #1a1a1a;    /* Logonuzdaki siyah */
    --avantaj-text: #333;       /* Ana metin rengi */
    --avantaj-text-light: #757575; /* İkincil metin rengi */
    --avantaj-bg: #f9f9f9;      /* Ferah bir sayfa arka planı */
    --avantaj-card-bg: #ffffff; /* Kartların arka planı */
    --avantaj-border: #e0e0e0;   /* İnce kenarlıklar */
    --avantaj-success: #10b981;
    --avantaj-danger: #ef4444;
    --avantaj-shadow: 0 4px 15px rgba(0,0,0,0.05);
    --avantaj-shadow-lg: 0 10px 30px rgba(0,0,0,0.08);
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Inter', 'Segoe UI', system-ui, -apple-system, sans-serif;
    /* Koyu gradient yerine ferah, açık renk */
    background-color: var(--avantaj-bg); 
    color: var(--avantaj-text);
    min-height: 100vh;
    overflow-x: hidden;
}

.wizard-container {
    max-width: 1600px;
    margin: 0 auto;
    padding: 2rem 1.5rem;
}

/* === Başlık === */
.wizard-header {
    text-align: center;
    margin-bottom: 2.5rem;
    animation: fadeInDown 0.6s ease;
}

.wizard-header h1 {
    font-size: 2.25rem; /* Biraz daha zarif */
    font-weight: 800;
    color: var(--avantaj-dark); /* Arka plan açık, yazı koyu */
    margin-bottom: 0.5rem;
}

.wizard-header p {
    font-size: 1.1rem;
    color: var(--avantaj-text-light);
    font-weight: 500;
}

/* === Stepper (Adım Göstergesi) === */
.stepper-wrapper {
    background: var(--avantaj-card-bg);
    border-radius: 16px; /* Daha modern */
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: var(--avantaj-shadow);
    animation: fadeInUp 0.6s ease 0.1s both;
}

.stepper {
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: relative;
    gap: 1rem;
}

.stepper-item {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    cursor: pointer;
    transition: all 0.3s ease;
}

.stepper-item:not(:last-child)::after {
    content: '';
    position: absolute;
    top: 25px; /* Küçüldü */
    left: 60%;
    right: -40%;
    height: 3px;
    background: var(--avantaj-border);
    z-index: 0;
    transition: all 0.4s ease;
}

.stepper-item.completed:not(:last-child)::after,
.stepper-item.active:not(:last-child)::after {
    background: var(--avantaj-primary); /* Ana renk */
}

.step-circle {
    width: 50px; /* Küçüldü */
    height: 50px; /* Küçüldü */
    border-radius: 50%;
    background: var(--avantaj-bg);
    color: var(--avantaj-text-light);
    border: 2px solid var(--avantaj-border);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    font-weight: 700;
    position: relative;
    z-index: 1;
    transition: all 0.4s ease;
}

.stepper-item.active .step-circle {
    background: var(--avantaj-primary);
    border-color: var(--avantaj-primary);
    color: white;
    transform: scale(1.1);
    box-shadow: 0 4px 15px rgba(0, 141, 133, 0.3);
}

.stepper-item.completed .step-circle {
    background: var(--avantaj-primary-light);
    border-color: var(--avantaj-primary);
    color: var(--avantaj-primary);
}

.stepper-item.completed .step-circle::before {
    content: '✓';
    font-size: 1.5rem;
}

.step-label {
    margin-top: 0.75rem;
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--avantaj-text-light);
    text-align: center;
    transition: all 0.3s ease;
}

.stepper-item.active .step-label {
    color: var(--avantaj-primary);
}

/* === Ana İçerik Alanı === */
.wizard-content {
    display: grid;
    /* Sidebar'ı daralttık, ürüne yer açtık */
    grid-template-columns: 1fr 400px; 
    gap: 2rem;
    animation: fadeInUp 0.6s ease 0.2s both;
}

/* === Ürünlerin Olduğu Ana Alan === */
.products-section {
    background: var(--avantaj-card-bg);
    border-radius: 16px;
    padding: 2rem;
    box-shadow: var(--avantaj-shadow);
    min-height: 600px;
}

.section-title {
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--avantaj-dark);
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    border-bottom: 2px solid var(--avantaj-border);
    padding-bottom: 1rem;
}

.section-title i {
    width: 40px;
    height: 40px;
    background: var(--avantaj-primary);
    color: white;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

/* === YENİ TASARIM: Alt Kategori Başlığı === */
/* Artık "Intel" ve "AMD" yan yana sütunlar değil,
   daha okunaklı alt başlıklar */
.subcategory-title {
    font-size: 1.4rem;
    font-weight: 700;
    color: var(--avantaj-dark);
    padding-bottom: 0.5rem;
    margin-bottom: 1.5rem;
    margin-top: 2rem;
    border-bottom: 1px solid var(--avantaj-border);
}
.wizard-step > .subcategory-title:first-of-type {
    margin-top: 0;
}

/* === YENİ TASARIM: Ürün Kartı Grid'i === */
/* Artık kategorileri değil, direkt ürünleri listeliyor */
.products-grid {
    display: grid;
    /* Daha küçük kartlar, daha ferah aralıklar */
    grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    gap: 1.5rem;
}

/* === YENİ TASARIM: Ürün Kartı === */
/* Eski hantal "category-card" ve "product-item" yapısı GİTTİ */
.product-item {
    background: var(--avantaj-card-bg);
    border-radius: 12px;
    border: 1px solid var(--avantaj-border);
    box-shadow: var(--avantaj-shadow);
    overflow: hidden;
    display: flex;
    flex-direction: column;
    transition: all 0.3s ease;
}
.product-item:hover {
    transform: translateY(-5px);
    box-shadow: var(--avantaj-shadow-lg);
    border-color: var(--avantaj-primary);
}

.product-image-wrapper {
    /* Görüntü boyutlarını sabitlemek için */
    height: 200px; 
    padding: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #fff;
}
.product-image {
    width: 100%;
    height: 100%;
    object-fit: contain; /* Görüntünün bozulmasını engeller */
}

.product-info {
    padding: 1rem 1.25rem;
    display: flex;
    flex-direction: column;
    flex-grow: 1; /* Kart boyunu eşitler */
}

.product-info h6 {
    font-size: 1rem;
    font-weight: 600;
    color: var(--avantaj-dark);
    margin-bottom: 0.25rem;
    line-height: 1.4;
    min-height: 45px; /* 2 satırlık yer ayırır */
}

.product-info .product-meta {
    font-size: 0.85rem;
    color: var(--avantaj-text-light);
    margin-bottom: 1rem;
    flex-grow: 1;
}

.product-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 1.25rem;
    border-top: 1px solid var(--avantaj-border);
    background-color: var(--avantaj-bg);
}

.product-price {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--avantaj-primary);
}

.btn-select {
    padding: 0.6rem 1rem;
    background: var(--avantaj-primary);
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.3s ease;
}
.btn-select:hover {
    background: #006a64; /* Teal'in koyu tonu */
    transform: scale(1.05);
}

/* === Sidebar === */
.sidebar {
    position: sticky;
    top: 2rem;
    height: fit-content;
}

.selected-card {
    background: var(--avantaj-card-bg);
    border-radius: 16px;
    box-shadow: var(--avantaj-shadow-lg);
    overflow: hidden;
}

.card-header {
    /* Ağır gradient gitti, temiz başlık geldi */
    background: none;
    border-bottom: 2px solid var(--avantaj-border);
    color: var(--avantaj-dark);
    padding: 1.5rem;
    font-size: 1.25rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}
.card-header i {
    color: var(--avantaj-primary);
}

.card-body {
    padding: 1.5rem;
    max-height: 450px; /* Azaltıldı */
    overflow-y: auto;
}

.card-body::-webkit-scrollbar { width: 6px; }
.card-body::-webkit-scrollbar-track { background: var(--avantaj-bg); border-radius: 10px; }
.card-body::-webkit-scrollbar-thumb { background: var(--avantaj-primary); border-radius: 10px; }

.selected-item {
    background: var(--avantaj-bg);
    border-radius: 12px;
    padding: 1rem;
    margin-bottom: 1rem;
    display: grid;
    grid-template-columns: 60px 1fr auto;
    gap: 1rem;
    align-items: center;
    animation: slideIn 0.3s ease;
}

@keyframes slideIn {
    from { opacity: 0; transform: translateX(-20px); }
    to { opacity: 1; transform: translateX(0); }
}

.selected-item img {
    width: 60px;
    height: 60px;
    border-radius: 8px;
    object-fit: contain;
    background: white;
    border: 1px solid var(--avantaj-border);
}

.selected-info h6 {
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--avantaj-dark);
    margin-bottom: 0.25rem;
}
.selected-info .meta {
    font-size: 0.8rem;
    color: var(--avantaj-text-light);
    margin-bottom: 0.5rem;
}

.badge-quantity {
    background: var(--avantaj-text-light);
    color: white;
}
.badge-price {
    background: var(--avantaj-success);
    color: white;
}
.badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
}

.btn-control {
    width: 30px;
    height: 30px;
    border: 2px solid var(--avantaj-border);
    background: white;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: 600;
}
.btn-control:hover {
    border-color: var(--avantaj-primary);
    color: var(--avantaj-primary);
    transform: scale(1.1);
}
.btn-remove { border-color: var(--avantaj-danger); color: var(--avantaj-danger); }
.btn-remove:hover { background: var(--avantaj-danger); color: white; border-color: var(--avantaj-danger); }

.card-footer {
    padding: 1.5rem;
    background: var(--avantaj-bg);
    display: flex;
    flex-direction: column;
    gap: 1rem;
    border-top: 1px solid var(--avantaj-border);
}

.config-name-input {
    width: 100%;
    padding: 1rem;
    border: 2px solid var(--avantaj-border);
    border-radius: 12px;
    font-size: 1rem;
    transition: all 0.3s ease;
}
.config-name-input:focus {
    outline: none;
    border-color: var(--avantaj-primary);
    box-shadow: 0 0 0 3px rgba(0, 141, 133, 0.1);
}

.btn {
    padding: 1rem;
    border-radius: 12px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

/* Temizle Butonu (Daha az dikkat çekici) */
.btn-clear {
    background: none;
    color: var(--avantaj-text-light);
    border: 2px solid var(--avantaj-border);
}
.btn-clear:hover {
    background: var(--avantaj-card-bg);
    color: var(--avantaj-danger);
    border-color: var(--avantaj-danger);
}

/* Kaydet Butonu (Ana Eylem) */
.btn-save {
    background: var(--avantaj-primary);
    color: white;
    border: none;
    box-shadow: 0 6px 20px rgba(0, 141, 133, 0.3);
}
.btn-save:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0, 141, 133, 0.4);
}
.btn-save:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

.empty-state {
    text-align: center;
    padding: 2rem 1rem; /* Daha az yer kaplasın */
    color: var(--avantaj-text-light);
}
.empty-state i {
    font-size: 3rem;
    color: var(--avantaj-border);
    margin-bottom: 1rem;
}

.loading-spinner {
    width: 20px; height: 20px;
    border: 3px solid rgba(255,255,255,0.3);
    border-radius: 50%;
    border-top-color: white;
    animation: spin 0.8s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }

.wizard-step { display: none; }
.wizard-step.active { display: block; animation: fadeInUp 0.4s ease; }

@keyframes fadeInDown {
    from { opacity: 0; transform: translateY(-30px); }
    to { opacity: 1; transform: translateY(0); }
}
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}

/* === RESPONSIVE DÜZENLEMELER === */
@media (max-width: 1200px) {
    .wizard-content {
        grid-template-columns: 1fr 360px; /* Sidebar daha da dar */
    }
}

@media (max-width: 992px) {
    /* Sidebar'ı alta at */
    .wizard-content {
        grid-template-columns: 1fr;
    }
    .sidebar {
        position: relative;
        top: 0;
        margin-top: 2rem;
    }
    .stepper {
        overflow-x: auto;
        padding-bottom: 1rem;
    }
    .stepper-item {
        min-width: 100px;
    }
}

@media (max-width: 768px) {
    .wizard-header h1 { font-size: 1.8rem; }
    .products-grid {
        /* Mobilde tek sütun */
        grid-template-columns: 1fr;
    }
    .products-section, .stepper-wrapper { padding: 1.5rem; }
    .step-circle { width: 45px; height: 45px; font-size: 1.1rem; }
    .step-label { font-size: 0.8rem; }
}

</style>

<div class="wizard-container">
    <div class="wizard-header">
        <h1>PC Toplama Sihirbazı</h1>
        <p>Hayalinizdeki bilgisayarı adım adım oluşturun</p>
    </div>

    <div class="stepper-wrapper">
        <div class="stepper" id="wizard-stepper">
            @foreach($kategoriler as $index => $kategori)
                <div class="stepper-item {{ $index == 0 ? 'active' : '' }}" data-step="{{ $index }}">
                    <div class="step-circle">{{ $index + 1 }}</div>
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
                        <div class="section-title">
                            <i class="fas fa-microchip"></i>
                            <span>{{ $kategori->kategori_ad }} Seçiniz</span>
                        </div>

                        @foreach($kategori->altKategoriler as $alt)
                            <h3 class="subcategory-title">{{ $alt->alt_kategori_ad }}</h3>

                            <div class="category-body urun-list products-grid" 
                                 data-altkategoriid="{{ $alt->id }}" 
                                 data-step="{{ $index }}">
                                <div class="empty-state">
                                    <i class="fas fa-spinner fa-spin"></i>
                                    <p>Ürünler yükleniyor...</p>
                                </div>
                            </div>
                        @endforeach
                        </div>
                @endforeach
            </div>
        </div>

        <div class="sidebar">
            <div class="selected-card">
                <div class="card-header">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Seçilen Ürünler</span>
                </div>
                
                <div class="card-body" id="selected-products">
                    <div class="empty-state">
                        <i class="fas fa-box-open"></i>
                        <p>Henüz ürün seçilmedi</p>
                    </div>
                </div>
                
                <div class="card-footer">
                    <input type="text" 
                           id="konfig-isim" 
                           class="config-name-input" 
                           placeholder="Konfigürasyonunuza bir isim verin..."
                           required>
                    
                    <button class="btn btn-clear" id="clear-selection">
                        <i class="fas fa-trash"></i>
                        <span>Seçimi Temizle</span>
                    </button>
                    
                    <button class="btn btn-save" id="save-progress">
                        <i class="fas fa-save"></i>
                        <span>Kaydet ve Profil'e Git</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// === JAVASCRIPT ===
// Fonksiyonların %99'u aynı, sadece 'loadUrunler' içindeki
// HTML oluşturma kısmı yeni tasarıma göre güncellendi.

let currentStep = 0;
const singleSelectCategories = ['İşlemci','Anakart','RAM','Ekran Kartı'];
let selectedUrun = {};

function updateStepper(){
    document.querySelectorAll('.stepper-item').forEach((item, i)=>{
        item.classList.remove('active', 'completed');
        if(i < currentStep) item.classList.add('completed');
        if(i === currentStep) item.classList.add('active');
    });
}

function goToStep(stepIndex){
    // Son adımı geçerse (örn. 10 adım varken 11'e gitmeye çalışırsa)
    const totalSteps = document.querySelectorAll('.stepper-item').length;
    if (stepIndex >= totalSteps) {
        // Son adıma git ve orada kal
        stepIndex = totalSteps - 1; 
        
        // Opsiyonel: "Kaydet" butonuna odaklan
        document.getElementById('konfig-isim').focus();
    }
    
    document.querySelectorAll('.wizard-step').forEach((s,i)=>{
        s.classList.remove('active');
        if(i === stepIndex) s.classList.add('active');
    });
    currentStep = stepIndex;
    updateStepper();
    
    // Sadece stepper'a değil, sayfa başına odaklansın
    document.querySelector('.wizard-container').scrollIntoView({ behavior: 'smooth', block: 'start' });
}

document.querySelectorAll('.stepper-item').forEach(stepEl=>{
    stepEl.addEventListener('click', ()=> goToStep(parseInt(stepEl.dataset.step)));
});

function updateSelectedBox(){
    const container = document.getElementById('selected-products');
    container.innerHTML = '';
    const steps = Object.keys(selectedUrun);

    if(steps.length===0){
        container.innerHTML = `
            <div class="empty-state">
                <i class="fas fa-box-open"></i>
                <p>Henüz ürün seçilmedi</p>
            </div>
        `;
        return;
    }

    steps.sort((a, b) => a - b).forEach(step=>{ // Adımları sırayla göster
        const urunler = Array.isArray(selectedUrun[step]) ? selectedUrun[step] : [selectedUrun[step]];
        
        urunler.forEach(urun=>{
            const div = document.createElement('div');
            div.className = 'selected-item';
            div.innerHTML = `
                <img src="${urun.resim || 'https://via.placeholder.com/60'}" alt="${urun.urun_ad}">
                <div class="selected-info">
                    <h6>${urun.urun_ad}</h6>
                    <div class="meta">${urun.marka} - ${urun.model}</div>
                    <div class="selected-badges">
                        <span class="badge badge-quantity">Adet: ${urun.adet || 1}</span>
                        <span class="badge badge-price">${urun.fiyat || 0} ₺</span>
                    </div>
                </div>
                <div class="item-controls">
                    <div class="control-group">
                        <button class="btn-control decrease-adet" ${singleSelectCategories.includes(urun.kategoriAd) ? 'disabled style="display:none;"' : ''}>-</button>
                        <button class="btn-control increase-adet" ${singleSelectCategories.includes(urun.kategoriAd) ? 'disabled style="display:none;"' : ''}>+</button>
                    </div>
                    <button class="btn-control btn-remove remove-urun">×</button>
                </div>
            `;

            const decreaseBtn = div.querySelector('.decrease-adet');
            const increaseBtn = div.querySelector('.increase-adet');
            const removeBtn = div.querySelector('.remove-urun');

            if (decreaseBtn) {
                decreaseBtn.addEventListener('click', ()=>{ 
                    if(urun.adet > 1){ urun.adet--; updateSelectedBox(); } 
                });
            }

            if (increaseBtn) {
                increaseBtn.addEventListener('click', ()=>{ 
                    urun.adet++; updateSelectedBox(); 
                });
            }
            
            removeBtn.addEventListener('click', ()=>{
                if(Array.isArray(selectedUrun[step])){
                    selectedUrun[step] = selectedUrun[step].filter(u=>u.id!==urun.id);
                    if(selectedUrun[step].length===0) delete selectedUrun[step];
                } else {
                    delete selectedUrun[step];
                }
                updateSelectedBox();
                // Ürün listelerini yeniden yükle (uyumluluk için)
                document.querySelectorAll('.urun-list').forEach(d=> d.dispatchEvent(new Event('reloadUrun')));
            });

            container.appendChild(div);
        });
    });
}

document.getElementById('clear-selection').addEventListener('click', ()=>{
    if(Object.keys(selectedUrun).length === 0) return;
    if(!confirm('Tüm seçimleri temizlemek istediğinize emin misiniz?')) return;
    
    selectedUrun = {};
    updateSelectedBox();
    document.querySelectorAll('.urun-list').forEach(div=> div.dispatchEvent(new Event('reloadUrun')));
    goToStep(0); // İlk adıma dön
});

document.getElementById('save-progress').addEventListener('click', ()=>{
    let isim = document.getElementById('konfig-isim').value.trim();
    
    if(!isim) {
        alert("Lütfen konfigürasyonunuza bir isim verin!");
        document.getElementById('konfig-isim').focus();
        return;
    }

    let urunler = [];
    Object.keys(selectedUrun).forEach(step=>{
        const urunlerArr = Array.isArray(selectedUrun[step]) ? selectedUrun[step] : [selectedUrun[step]];
        urunlerArr.forEach(u=> urunler.push({id:u.id, adet:u.adet, fiyat:u.fiyat||0}));
    });

    if(urunler.length===0){ 
        alert("Lütfen önce ürün seçin!"); 
        return; 
    }

    const saveBtn = document.getElementById('save-progress');
    saveBtn.disabled = true;
    saveBtn.innerHTML = '<span class="loading-spinner"></span><span>Kaydediliyor...</span>';

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
        if (!response.ok) {
            return response.json().then(err => {
                throw new Error(err.message || 'Bir hata oluştu');
            });
        }
        return response.json();
    })
    .then(data=>{
        if(data.success && data.redirect_url){
            alert(data.message);
            selectedUrun = {};
            updateSelectedBox();
            document.getElementById('konfig-isim').value = '';
            window.location.href = data.redirect_url;
        } else {
            alert(data.message || "Hata oluştu!");
            saveBtn.disabled = false;
            saveBtn.innerHTML = '<i class="fas fa-save"></i><span>Kaydet ve Profil\'e Git</span>';
        }
    })
    .catch(error => {
        console.error('Hata:', error);
        alert("Konfigürasyon kaydedilirken bir hata oluştu: " + error.message);
        saveBtn.disabled = false;
        saveBtn.innerHTML = '<i class="fas fa-save"></i><span>Kaydet ve Profil\'e Git</span>';
    });
});

document.querySelectorAll('.urun-list').forEach(div=>{
    const altKategoriId = div.dataset.altkategoriid;
    const step = div.dataset.step;

    function loadUrunler(){
        const kategoriAd = div.closest('.wizard-step').querySelector('.section-title span').innerText.replace(' Seçiniz','');
        const prevSteps = Object.keys(selectedUrun).filter(s=>parseInt(s) < parseInt(step));
        let uyumlulukParams = '';
        if(prevSteps.length>0){
            const prevIds = prevSteps.map(s=>{ 
                const arr = Array.isArray(selectedUrun[s]) ? selectedUrun[s] : [selectedUrun[s]]; 
                return arr.map(u=>u.id); 
            }).flat();
            if(prevIds.length>0) uyumlulukParams = '?selected_urun_id='+prevIds.join(',');
        }

        // Başlangıçta spinner'ı göster
        div.innerHTML = `
            <div class="empty-state" style="padding: 1rem;">
                <i class="fas fa-spinner fa-spin"></i>
            </div>
        `;

        fetch(`/wizard/urunler/${altKategoriId}${uyumlulukParams}`)
        .then(res=>res.json())
        .then(data=>{
            div.innerHTML=''; // Spinner'ı temizle
            if(data.length===0){ 
                div.innerHTML = `
                    <div class="empty-state" style="grid-column: 1 / -1;">
                        <i class="fas fa-inbox"></i>
                        <p style="font-size: 0.9rem;">Uyumlu ürün bulunamadı</p>
                    </div>
                `;
                return; 
            }

            data.forEach(urun=>{
                const item = document.createElement('div');
                item.className='product-item';
                
                // === YENİ KART HTML'i ===
                item.innerHTML=`
                    <div class="product-image-wrapper">
                        <img src="${urun.resim || 'https://via.placeholder.com/200'}"
                             class="product-image" 
                             alt="${urun.urun_ad}"
                             loading="lazy"> 
                    </div>
                    <div class="product-info">
                        <h6>${urun.urun_ad}</h6>
                        <div class="product-meta">${urun.marka} - ${urun.model}</div>
                    </div>
                    <div class="product-footer">
                        <div class="product-price">${urun.fiyat || 0} ₺</div>
                        <button class="btn-select">
                            <span>Seç</span>
                        </button>
                    </div>
                `;
                
                item.querySelector('button').addEventListener('click', ()=>{
                    // Kategori adını da ürüne ekleyelim (opsiyonel, adet +/- için)
                    urun.kategoriAd = kategoriAd; 

                    if(singleSelectCategories.includes(kategoriAd)){
                        selectedUrun[step] = {...urun, adet:1};
                        goToStep(parseInt(step)+1);
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
                    // Uyumlu ürünleri filtrelemek için sonraki adımları yeniden yükle
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
            console.error('Ürün yükleme hatası:', error);
            div.innerHTML = `
                <div class="empty-state" style="grid-column: 1 / -1;">
                    <i class="fas fa-exclamation-triangle" style="color: var(--avantaj-danger);"></i>
                    <p style="color: var(--avantaj-danger);">Ürünler yüklenirken hata oluştu</p>
                </div>
            `;
        });
    }

    div.addEventListener('reloadUrun', loadUrunler);
    // Sadece aktif olan adımdaki ürünleri yükle
    if (div.closest('.wizard-step').classList.contains('active')) {
        loadUrunler();
    }
    
    // Adıma tıklandığında da ürünleri yükle
    document.querySelector(`.stepper-item[data-step="${step}"]`).addEventListener('click', () => {
        // Eğer zaten yüklenmişse tekrar fetch atma (isteğe bağlı)
        // if (div.innerHTML.includes('product-item')) return; 
        loadUrunler();
    });
});
</script>
@endsection