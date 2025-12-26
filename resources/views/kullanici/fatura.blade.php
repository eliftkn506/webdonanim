@php
    // FONKSİYONU EN ÜSTE TANIMLIYORUZ (HATA ALMAMAK İÇİN)
    if (!function_exists('yaziyla')) {
        function yaziyla($tutar) {
            $tutar = number_format($tutar, 2, '.', '');
            $yazi = "";
            
            $birler = ["", "Bir", "İki", "Üç", "Dört", "Beş", "Altı", "Yedi", "Sekiz", "Dokuz"];
            $onlar  = ["", "On", "Yirmi", "Otuz", "Kırk", "Elli", "Altmış", "Yetmiş", "Seksen", "Doksan"];
            $binler = ["", "Bin", "Milyon", "Milyar"];

            list($lira, $kurus) = explode('.', $tutar);

            // Lira Kısmı
            $liraStr = (string) intval($lira);
            $liraLen = strlen($liraStr);
            
            // Basit bir çevirici (Milyara kadar destekli)
            $gruplar = array_reverse(str_split(str_pad($liraStr, ceil($liraLen/3)*3, "0", STR_PAD_LEFT), 3));
            
            foreach ($gruplar as $i => $grup) {
                $grupInt = intval($grup);
                if ($grupInt == 0) continue;
                
                $yuzler = floor($grupInt / 100);
                $onlarBas = floor(($grupInt % 100) / 10);
                $birlerBas = $grupInt % 10;
                
                $grupYazi = "";
                if ($yuzler > 0) {
                    $grupYazi .= ($yuzler > 1 ? $birler[$yuzler] : "") . " Yüz ";
                }
                if ($onlarBas > 0) {
                    $grupYazi .= $onlar[$onlarBas] . " ";
                }
                if ($birlerBas > 0) {
                    if (!($i == 1 && $grupInt == 1)) { // "Bir Bin" denmez, "Bin" denir.
                        $grupYazi .= $birler[$birlerBas] . " ";
                    }
                }
                
                $yazi = $grupYazi . $binler[$i] . " " . $yazi;
            }
            
            if (empty($yazi)) $yazi = "Sıfır ";
            $yazi .= "Türk Lirası";

            // Kuruş Kısmı
            if (intval($kurus) > 0) {
                $yazi .= ", " . intval($kurus) . " Kuruş";
            }

            return trim($yazi);
        }
    }
@endphp

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fatura - {{ $fatura->fatura_no }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* === GENEL AYARLAR === */
        :root {
            --primary: #1e293b;
            --accent: #00d4aa;
            --text: #334155;
            --border: #e2e8f0;
            --bg: #f1f5f9;
        }

        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg);
            color: var(--text);
            margin: 0;
            padding: 20px;
            font-size: 14px;
        }

        /* === FATURA KAĞIDI (A4) === */
        .invoice-container {
            background: white;
            max-width: 210mm;
            margin: 0 auto;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            position: relative;
        }

        /* === HEADER === */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid var(--border);
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .logo-area h1 {
            margin: 0;
            color: var(--primary);
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -1px;
        }
        
        .logo-area span { color: var(--accent); }

        .invoice-details { text-align: right; }

        .invoice-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--primary);
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .invoice-meta {
            font-size: 13px;
            color: #64748b;
            line-height: 1.5;
        }

        /* === BİLGİ ALANLARI === */
        .info-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
            gap: 20px;
        }

        .info-box { flex: 1; }

        .info-title {
            font-size: 11px;
            text-transform: uppercase;
            color: #94a3b8;
            font-weight: 700;
            margin-bottom: 8px;
            letter-spacing: 0.5px;
        }

        .company-name {
            font-size: 15px;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 5px;
        }

        .address-text {
            font-size: 13px;
            color: #64748b;
            line-height: 1.6;
        }

        /* === TABLO === */
        .table-wrapper { margin-bottom: 30px; overflow-x: auto; }

        table { width: 100%; border-collapse: collapse; font-size: 13px; }

        th {
            background-color: #f8fafc;
            color: var(--primary);
            font-weight: 700;
            text-align: left;
            padding: 12px 15px;
            border-bottom: 2px solid var(--border);
            text-transform: uppercase;
            font-size: 11px;
        }

        td {
            padding: 12px 15px;
            border-bottom: 1px solid var(--border);
            color: var(--text);
        }

        tr:last-child td { border-bottom: none; }

        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .fw-bold { font-weight: 700; }

        /* === TOPLAMLAR === */
        .totals-section {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 40px;
        }

        .totals-table { width: 300px; border-collapse: collapse; }

        .totals-table td {
            padding: 8px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .totals-table tr:last-child td {
            border-bottom: none;
            padding-top: 15px;
            font-size: 16px;
            font-weight: 800;
            color: var(--primary);
        }

        .total-label { color: #64748b; font-weight: 600; }

        /* === FOOTER === */
        .footer {
            border-top: 2px solid var(--border);
            padding-top: 20px;
            font-size: 11px;
            color: #94a3b8;
            text-align: center;
            line-height: 1.6;
        }

        /* === AKSİYON BUTONLARI === */
        .actions {
            max-width: 210mm;
            margin: 0 auto 20px;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .btn {
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            font-size: 13px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
        }

        .btn-print { background-color: var(--primary); color: white; }
        .btn-print:hover { background-color: #0f172a; }

        .btn-back { background-color: white; color: var(--text); border: 1px solid var(--border); }
        .btn-back:hover { background-color: #f8fafc; }

        /* === RESPONSIVE & PRINT === */
        @media screen and (max-width: 768px) {
            body { padding: 10px; }
            .invoice-container { padding: 20px; }
            .header, .info-section { flex-direction: column; gap: 20px; text-align: center; align-items: center; }
            .invoice-details, .info-box.text-right { text-align: center !important; }
            .totals-section { justify-content: center; }
            .totals-table { width: 100%; }
            .actions { justify-content: center; }
        }

        @media print {
            body { background: none; padding: 0; margin: 0; }
            .invoice-container { box-shadow: none; margin: 0; padding: 0; width: 100%; max-width: 100%; }
            .actions { display: none !important; }
            @page { margin: 1cm; }
        }
    </style>
</head>
<body>

    <div class="actions">
        <a href="{{ route('siparis.basarili', $siparis->id) }}" class="btn btn-back">
            <i class="fas fa-arrow-left"></i> Geri Dön
        </a>
        <button onclick="window.print()" class="btn btn-print">
            <i class="fas fa-print"></i> Yazdır / PDF
        </button>
    </div>

    <div class="invoice-container">
        
        <div class="header">
            <div class="logo-area">
                <h1>AVANTAJ<span>BİLİŞİM</span></h1>
                <p style="font-size: 12px; color: #64748b; margin-top: 5px;">Teknolojinin Adresi</p>
            </div>
            <div class="invoice-details">
                <div class="invoice-title">E-ARŞİV FATURA</div>
                <div class="invoice-meta">
                    Fatura No: <strong>{{ $fatura->fatura_no }}</strong><br>
                    Sipariş No: <strong>{{ $siparis->siparis_no }}</strong><br>
                    Düzenleme Tarihi: <strong>{{ $fatura->created_at->format('d.m.Y') }}</strong>
                </div>
            </div>
        </div>

        <div class="info-section">
            <div class="info-box">
                <div class="info-title">SAYIN / ALICI</div>
                <div class="company-name">{{ $fatura->unvan }}</div>
                <div class="address-text">
                    {{ $fatura->fatura_adresi }}<br>
                    @if($fatura->vergi_no)
                        <strong>V.D:</strong> {{ $fatura->vergi_dairesi }} | <strong>V.No:</strong> {{ $fatura->vergi_no }}
                    @else
                        <strong>TCKN:</strong> {{ $fatura->tc_kimlik_no }}
                    @endif
                    <br>
                    <strong>Tel:</strong> {{ Auth::user()->telefon ?? 'Belirtilmedi' }}
                </div>
            </div>
            <div class="info-box text-right" style="text-align: right;">
                <div class="info-title">SATICI</div>
                <div class="company-name">Avantaj Bilişim Teknoloji A.Ş.</div>
                <div class="address-text">
                    Teknokent Bilişim Vadisi No: 10<br>
                    Selçuklu / KONYA<br>
                    <strong>V.D:</strong> Meram | <strong>V.No:</strong> 1234567890<br>
                    <strong>Mersis:</strong> 012345678900001
                </div>
            </div>
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 45%;">Ürün / Hizmet Adı</th>
                        <th class="text-center" style="width: 15%;">Miktar</th>
                        <th class="text-right" style="width: 15%;">Birim Fiyat</th>
                        <th class="text-right" style="width: 20%;">Toplam</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($siparis->urunler as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <strong>{{ $item->urun->urun_ad ?? 'Ürün Silinmiş' }}</strong>
                        </td>
                        <td class="text-center">{{ $item->adet }}</td>
                        <td class="text-right">{{ number_format($item->birim_fiyat, 2, ',', '.') }} ₺</td>
                        <td class="text-right fw-bold">{{ number_format($item->toplam_fiyat, 2, ',', '.') }} ₺</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="totals-section">
            <table class="totals-table">
                <tr>
                    <td class="total-label">Ara Toplam</td>
                    <td class="text-right">{{ number_format($fatura->ara_toplam, 2, ',', '.') }} ₺</td>
                </tr>
                
                @if($siparis->indirim_tutari > 0)
                <tr>
                    <td class="total-label" style="color: #ef4444;">İndirim</td>
                    <td class="text-right" style="color: #ef4444;">-{{ number_format($siparis->indirim_tutari, 2, ',', '.') }} ₺</td>
                </tr>
                @endif

                <tr>
                    <td class="total-label">KDV Toplam</td>
                    <td class="text-right">{{ number_format($fatura->kdv_tutari, 2, ',', '.') }} ₺</td>
                </tr>
                
                <tr>
                    <td>GENEL TOPLAM</td>
                    <td class="text-right">{{ number_format($fatura->genel_toplam, 2, ',', '.') }} ₺</td>
                </tr>
            </table>
        </div>

        <div class="footer">
            <p>
                <strong>Yalnız:</strong> {{ yaziyla($fatura->genel_toplam) }} tahsil edilmiştir.<br>
                Bu belge 213 sayılı V.U.K. hükümlerine göre düzenlenmiştir. İşbu fatura, e-arşiv uygulaması kapsamında elektronik ortamda oluşturulmuştur.
                Kağıt nüsha olarak teslim edilmesi halinde "İrsaliye yerine geçer" ibaresiyle birlikte geçerlidir.
            </p>
            <div style="margin-top: 15px; font-size: 10px; color: #cbd5e1;">
                Sistem tarafından oluşturulmuştur. {{ now()->format('d.m.Y H:i:s') }}
            </div>
        </div>

    </div>

</body>
</html>