<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fatura - {{ $fatura->fatura_no }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            background: #fff;
        }

        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            display: table;
            width: 100%;
            margin-bottom: 30px;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 20px;
        }

        .company-info {
            display: table-cell;
            width: 60%;
            vertical-align: top;
        }

        .invoice-info {
            display: table-cell;
            width: 40%;
            vertical-align: top;
            text-align: right;
        }

        .company-logo {
            font-size: 24px;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 10px;
        }

        .company-details {
            color: #666;
            line-height: 1.6;
        }

        .invoice-title {
            font-size: 28px;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 10px;
        }

        .invoice-details {
            background: #f8fafc;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .invoice-details table {
            width: 100%;
        }

        .invoice-details td {
            padding: 5px;
            border: none;
        }

        .invoice-details .label {
            font-weight: bold;
            color: #475569;
        }

        .addresses {
            display: table;
            width: 100%;
            margin: 30px 0;
        }

        .address-section {
            display: table-cell;
            width: 48%;
            vertical-align: top;
            padding: 20px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            background: #f8fafc;
        }

        .address-section:first-child {
            margin-right: 4%;
        }

        .address-title {
            font-size: 14px;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 10px;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 5px;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 30px 0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .items-table th {
            background: #2563eb;
            color: white;
            padding: 12px 10px;
            text-align: left;
            font-weight: bold;
            border: none;
        }

        .items-table td {
            padding: 12px 10px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: top;
        }

        .items-table tr:nth-child(even) {
            background: #f8fafc;
        }

        .items-table tr:hover {
            background: #e2e8f0;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .summary-table {
            width: 50%;
            margin-left: auto;
            margin-top: 20px;
            border-collapse: collapse;
        }

        .summary-table td {
            padding: 8px 15px;
            border: 1px solid #e2e8f0;
        }

        .summary-table .label {
            font-weight: bold;
            background: #f8fafc;
            color: #475569;
        }

        .summary-table .total-row {
            background: #2563eb;
            color: white;
            font-weight: bold;
            font-size: 16px;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            color: #666;
            font-size: 11px;
        }

        .payment-info {
            background: #fef3c7;
            border: 1px solid #fbbf24;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
        }

        .payment-info h4 {
            color: #92400e;
            margin-bottom: 10px;
        }

        .bank-details {
            display: table;
            width: 100%;
        }

        .bank-details div {
            display: table-cell;
            width: 33.33%;
            padding: 5px;
        }

        .stamp-area {
            float: right;
            width: 150px;
            height: 80px;
            border: 2px dashed #cbd5e1;
            text-align: center;
            padding-top: 25px;
            color: #9ca3af;
            font-size: 11px;
            margin-top: 30px;
        }

        .qr-code {
            float: left;
            margin-top: 20px;
        }

        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .container {
                padding: 0;
            }
        }

        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 100px;
            color: rgba(37, 99, 235, 0.1);
            z-index: -1;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Watermark -->
        <div class="watermark">{{ strtoupper($siparis->durum) }}</div>

        <!-- Header -->
        <div class="header">
            <div class="company-info">
                <div class="company-logo">
                    TechnoMarket A.Ş.
                </div>
                <div class="company-details">
                    Teknoloji Caddesi No: 123/A<br>
                    Çankaya/ANKARA<br>
                    Telefon: +90 312 456 78 90<br>
                    E-posta: info@technomarket.com<br>
                    Web: www.technomarket.com
                </div>
            </div>
            <div class="invoice-info">
                <div class="invoice-title">FATURA</div>
                <div class="invoice-details">
                    <table>
                        <tr>
                            <td class="label">Fatura No:</td>
                            <td>{{ $fatura->fatura_no }}</td>
                        </tr>
                        <tr>
                            <td class="label">Sipariş No:</td>
                            <td>{{ $siparis->siparis_no }}</td>
                        </tr>
                        <tr>
                            <td class="label">Tarih:</td>
                            <td>{{ $siparis->created_at->format('d.m.Y') }}</td>
                        </tr>
                        <tr>
                            <td class="label">Saat:</td>
                            <td>{{ $siparis->created_at->format('H:i') }}</td>
                        </tr>
                        <tr>
                            <td class="label">Durum:</td>
                            <td style="color: 
                                @if($siparis->durum == 'onaylandi') #22c55e
                                @elseif($siparis->durum == 'beklemede') #f59e0b
                                @else #ef4444 @endif
                                ; font-weight: bold;">
                                {{ ucfirst($siparis->durum) }}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Addresses -->
        <div class="addresses">
            <div class="address-section">
                <div class="address-title">
                    <i>📍</i> TESLİMAT ADRESİ
                </div>
                <strong>{{ $siparis->ad_soyad }}</strong><br>
                Telefon: {{ $siparis->telefon }}<br><br>
                {{ $siparis->kargo_adresi }}
            </div>

            <div class="address-section">
                <div class="address-title">
                    <i>🧾</i> FATURA ADRESİ
                </div>
                <strong>{{ $fatura->unvan }}</strong><br>
                
                @if($fatura->tc_kimlik_no)
                    TC Kimlik No: {{ $fatura->tc_kimlik_no }}<br>
                @endif
                
                @if($fatura->vergi_dairesi && $fatura->vergi_no)
                    {{ $fatura->vergi_dairesi }} - {{ $fatura->vergi_no }}<br>
                @endif
                
                <br>{{ $fatura->fatura_adresi }}
            </div>
        </div>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 10%">#</th>
                    <th style="width: 40%">Ürün Adı</th>
                    <th style="width: 10%" class="text-center">Adet</th>
                    <th style="width: 15%" class="text-right">Birim Fiyat</th>
                    <th style="width: 10%" class="text-right">KDV (%)</th>
                    <th style="width: 15%" class="text-right">Toplam</th>
                </tr>
            </thead>
            <tbody>
                @foreach($siparis->urunler as $index => $siparisUrun)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $siparisUrun->urun->urun_ad ?? 'Ürün Bulunamadı' }}</strong>
                        @if($siparisUrun->urun && $siparisUrun->urun->marka)
                            <br><small style="color: #666;">{{ $siparisUrun->urun->marka }} - {{ $siparisUrun->urun->model }}</small>
                        @endif
                    </td>
                    <td class="text-center">{{ $siparisUrun->adet }}</td>
                    <td class="text-right">{{ number_format($siparisUrun->birim_fiyat, 2, ',', '.') }} ₺</td>
                    <td class="text-right">%{{ $siparisUrun->kdv_orani }}</td>
                    <td class="text-right">{{ number_format($siparisUrun->toplam_fiyat + $siparisUrun->kdv_tutari, 2, ',', '.') }} ₺</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Summary -->
        <table class="summary-table">
            <tr>
                <td class="label">Ara Toplam:</td>
                <td class="text-right">{{ number_format($fatura->ara_toplam, 2, ',', '.') }} ₺</td>
            </tr>
            <tr>
                <td class="label">KDV Tutarı:</td>
                <td class="text-right">{{ number_format($fatura->kdv_tutari, 2, ',', '.') }} ₺</td>
            </tr>
            @if($siparis->indirim_tutari > 0)
            <tr>
                <td class="label" style="color: #22c55e;">İndirim:</td>
                <td class="text-right" style="color: #22c55e;">-{{ number_format($siparis->indirim_tutari, 2, ',', '.') }} ₺</td>
            </tr>
            @endif
            @if($siparis->kargo_ucreti > 0)
            <tr>
                <td class="label">Kargo Ücreti:</td>
                <td class="text-right">{{ number_format($siparis->kargo_ucreti, 2, ',', '.') }} ₺</td>
            </tr>
            @endif
            <tr class="total-row">
                <td>GENEL TOPLAM:</td>
                <td class="text-right">{{ number_format($fatura->genel_toplam, 2, ',', '.') }} ₺</td>
            </tr>
        </table>

        <!-- Payment Information -->
        @if($siparis->odeme_tipi == 'havale')
        <div class="payment-info">
            <h4>💳 Ödeme Bilgileri - Havale/EFT</h4>
            <div class="bank-details">
                <div>
                    <strong>Ziraat Bankası</strong><br>
                    IBAN: TR12 0001 0000 1234 5678 9012 34<br>
                    Hesap Adı: TechnoMarket A.Ş.
                </div>
                <div>
                    <strong>Garanti BBVA</strong><br>
                    IBAN: TR98 0062 0000 5678 9012 3456 78<br>
                    Hesap Adı: TechnoMarket A.Ş.
                </div>
                <div>
                    <strong>İş Bankası</strong><br>
                    IBAN: TR45 0006 4000 9876 5432 1098 76<br>
                    Hesap Adı: TechnoMarket A.Ş.
                </div>
            </div>
            <p style="margin-top: 10px; color: #92400e; font-size: 11px;">
                <strong>Önemli:</strong> Havale/EFT yaparken açıklama kısmına mutlaka sipariş numaranızı ({{ $siparis->siparis_no }}) yazınız. 
                Ödeme onaylandıktan sonra siparişiniz kargoya verilecektir.
            </p>
        </div>
        @elseif($siparis->odeme_tipi == 'kredi_karti')
        <div class="payment-info" style="background: #dcfce7; border-color: #22c55e;">
            <h4 style="color: #166534;">✅ Kredi Kartı ile Ödendi</h4>
            <p style="color: #166534;">Ödemeniz başarıyla alınmıştır. Siparişiniz en kısa sürede hazırlanacaktır.</p>
        </div>
        @elseif($siparis->odeme_tipi == 'kapida_odeme')
        <div class="payment-info" style="background: #fef3c7; border-color: #f59e0b;">
            <h4 style="color: #92400e;">🚚 Kapıda Ödeme</h4>
            <p style="color: #92400e;">Ürününüz teslim edilirken <strong>{{ number_format($fatura->genel_toplam, 2, ',', '.') }} ₺</strong> tutarındaki ödemeyi nakit olarak yapabilirsiniz.</p>
        </div>
        @endif

        <!-- Notes -->
        @if($siparis->notlar)
        <div style="margin: 20px 0; padding: 15px; background: #f8fafc; border-left: 4px solid #2563eb; border-radius: 0 8px 8px 0;">
            <h4 style="color: #2563eb; margin-bottom: 10px;">📝 Sipariş Notları</h4>
            <p style="color: #475569;">{{ $siparis->notlar }}</p>
        </div>
        @endif

        <!-- QR Code and Stamp Area -->
        <div style="overflow: hidden; margin-top: 40px;">
            <div class="qr-code">
                <div style="width: 80px; height: 80px; border: 1px solid #cbd5e1; display: flex; align-items: center; justify-content: center; font-size: 10px; color: #9ca3af;">
                    QR Kod<br>
                    (E-Fatura)
                </div>
                <div style="text-align: center; font-size: 10px; color: #6b7280; margin-top: 5px;">
                    Fatura No: {{ $fatura->fatura_no }}
                </div>
            </div>

            <div class="stamp-area">
                FİRMA<br>
                KAŞE VE İMZA
            </div>
        </div>

        <!-- Footer -->
        <div class="footer" style="clear: both;">
            <div style="border-top: 2px solid #e2e8f0; padding-top: 20px; margin-top: 40px;">
                <table style="width: 100%; font-size: 10px;">
                    <tr>
                        <td style="width: 33%; text-align: left;">
                            <strong>TechnoMarket A.Ş.</strong><br>
                            Vergi Dairesi: Çankaya V.D.<br>
                            Vergi No: 1234567890
                        </td>
                        <td style="width: 34%; text-align: center;">
                            <strong>İletişim</strong><br>
                            📞 0312 456 78 90<br>
                            📧 info@technomarket.com
                        </td>
                        <td style="width: 33%; text-align: right;">
                            <strong>Çalışma Saatleri</strong><br>
                            Pazartesi - Cumartesi: 09:00 - 18:00<br>
                            Pazar: 10:00 - 16:00
                        </td>
                    </tr>
                </table>
            </div>
            
            <div style="margin-top: 20px; padding-top: 10px; border-top: 1px solid #e2e8f0; color: #9ca3af;">
                Bu fatura elektronik ortamda oluşturulmuş olup, kaşe ve imza yerine geçer. <br>
                Fatura tarihi: {{ now()->format('d.m.Y H:i:s') }} | Sayfa: 1/1
            </div>
        </div>
    </div>
</body>
</html>