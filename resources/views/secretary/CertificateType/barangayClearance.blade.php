<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay Clearance Certificate</title>
    <!-- Bootstrap CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            @page {
                size: letter;
                margin: 0.3in;
            }
            body {
                margin: 0;
                padding: 0;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .container {
                padding: 0 !important;
                margin: 0 !important;
                max-width: none !important;
            }
            img {
                display: block !important;
                break-inside: avoid;
                max-width: 100% !important;
                visibility: visible !important;
            }
            .header-logo, .bagong-pilipinas {
                height: 55px !important;
                width: 55px !important;
                print-color-adjust: exact !important;
                -webkit-print-color-adjust: exact !important;
            }
            .certificate {
                border: 2px solid #000 !important;
                break-inside: avoid;
            }
        }
        body {
            font-family: 'Arial';
            line-height: 1.3;
        }
        .certificate {
            width: 8.5in;
            height: 11in;
            margin: 0 auto;
            padding: 0.4in;
            border: 2px solid #000;
            position: relative;
            background-color: #fff;
            box-sizing: border-box;
        }
        .container {
            position: relative;
            overflow: visible;
        }
        .certificate {
            position: relative;
        }
        
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1;
            background-color: white;
        }
        
        .watermark img {
            width: 80%;
            height: auto;
            opacity: 0.3;
            filter: grayscale(100%);
        }
        
        .certificate-content {
            position: relative;
            z-index: 2;
        }
        
        .header-logo {
            height: 55px;
            width: 55px;
            object-fit: contain;
            margin: 0 auto;
        }
        .bagong-pilipinas {
            height: 55px;
            width: 55px;
            object-fit: contain;
            margin: 0 auto;
        }
        .title {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            margin-top: 10px;
        }
        .subtitle {
            font-size: 18px;
            text-align: center;
            margin-bottom: 20px;
        }
        .committee-title {
            font-size: 9px;
            font-weight: bold;
            margin-bottom: 0;
            line-height: 1.2;
            text-align: center;
        }
        .committee-desc {
            font-size: 8px;
            font-style: italic;
            margin-bottom: 2px;
            line-height: 1.2;
            text-align: center;
        }
        .clearance-content {
            font-size: 11px;
            text-align: justify;
            padding: 4px 10px;
            line-height: 1.3;
        }
        .certificate-box {
            border: 1px solid #000;
            padding: 0;
        }
        .officials-column {
            border-right: 1px solid #000;
            padding: 4px;
            text-align: center;
        }
        .clearance-column {
            padding: 8px;
        }
        .underline {
            border-bottom: 1px solid #000;
            display: inline-block;
            min-width: 150px;
            margin: 0 5px;
        }
        .checkbox {
            display: inline-block;
            width: 15px;
            height: 15px;
            border: 1px solid #000;
            margin-right: 5px;
        }
        .footer-info {
            font-size: 10px;
            margin-top: 6px;
        }
        .signature-line {
            border-top: 1px solid #000;
            margin-top: 15px;
            width: 200px;
            text-align: center;
            margin-left: auto;
            margin-right: 20px;
        }
        .official-name {
            font-size: 10px;
            font-weight: bold;
            text-decoration: underline;
            text-align: center;
        }
        .official-title {
            font-size: 9px;
            text-align: center;
        }
        .row.align-items-center {
            display: flex;
            justify-content: space-between; /* Ensures equal spacing between logos */
            align-items: center; /* Vertically aligns logos */
        }
        .certificate {
            position: relative;
            overflow: hidden;
        }
        .certificate-watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 70%;
            height: auto;
            opacity: 0.2;
            z-index: 1;
            pointer-events: none;
        }
        .certificate > *:not(.certificate-watermark) {
            position: relative;
            z-index: 2;
        }
        .certificate {
            overflow: hidden;
            background: white;
        }
        .certificate > *:not(img) {
            position: relative;
            z-index: 1;
        }
        .mb-3 {
            margin-bottom: 0.4rem !important;
        }
        .mt-4 {
            margin-top: 0.8rem !important;
        }
        .mt-5 {
            margin-top: 1rem !important;
        }
        h3 {
            font-size: 1.4rem;
            margin-bottom: 0.5rem;
        }
        h4 {
            font-size: 1.1rem;
            margin-bottom: 0.3rem;
        }
    </style>
</head>
<body>
    @php
        // Use $data if available, otherwise fallback to $request
        $cert = $data ?? $request ?? null;
        if (is_array($cert)) $cert = (object) $cert;
    @endphp
    <div class="container mt-4 mb-4">
        <div class="certificate position-relative">
            <img src="{{ asset('img/cantil-e-logo.png') }}" class="position-absolute start-50 top-50 translate-middle" style="width: 80%; height: auto; opacity: 0.2; z-index: 0; pointer-events: none;" alt="Watermark">
            <div class="row align-items-center mb-3">
                <div class="col-3 text-center">
                    <img src="{{ asset('img/cantil-e-logo.png') }}" alt="Barangay Seal" class="header-logo">
                </div>
                <div class="col-6 text-center">
                    <img src="{{ asset('img/bagong-pilipinas-logo.png') }}" alt="Bagong Pilipinas" class="bagong-pilipinas">
                    <div class="mt-2">
                        <div><strong>Republic of the Philippines</strong></div>
                        <div><strong>Province of Negros Oriental</strong></div>
                        <div><strong>City of Dumaguete</strong></div>
                        <div><strong>BARANGAY CANTIL-E</strong></div>
                        <div>Tel. No. (035) 421-0458</div>
                    </div>
                </div>
                <div class="col-3 text-center">
                    <img src="{{ asset('img/Dumaguete-logo.png') }}" alt="City Seal" class="header-logo">
                </div>
            </div>
            
            <div class="text-center mb-3">
                <h4><strong>OFFICE OF THE PUNONG BARANGAY</strong></h4>
                <h3><strong>BARANGAY CLEARANCE</strong></h3>
            </div>
            
            <!-- Main Content -->
            <div class="row certificate-box mx-0">
                <!-- Left Column - Officials -->
                <div class="col-4 officials-column">
                    <div class="mb-3">
                        <div class="committee-title">HON. RENATO A. PATINGA</div>
                        <div class="committee-desc">Infrastructure & Finance Committee</div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="committee-title">HON. GODOFREDO S. ANSOC</div>
                        <div class="committee-desc">Health & Nutrition Committee</div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="committee-title">HON. JOSEFINA B. CATAYLO</div>
                        <div class="committee-desc">Social Services & Waste Material Management Committee</div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="committee-title">HON. ALEX A. ENQUIG</div>
                        <div class="committee-desc">Environment & Agriculture Committee</div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="committee-title">HON. KIM L. AWID</div>
                        <div class="committee-desc">Education and Cultural Activities Committee</div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="committee-title">HON. MARGIE I. ANSOK</div>
                        <div class="committee-desc">Women and Family Committee</div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="committee-title">HON. JOVANNY S. ABEQUIBEL</div>
                        <div class="committee-desc">Sports, Peace and Order Committee</div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="committee-title">HON. JEMAR S. TUBOG</div>
                        <div class="committee-desc">Youth Development</div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="committee-title">MARITES S. TUBOG</div>
                        <div class="committee-desc">Barangay Treasurer</div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="committee-title">GAVINA B. DALES</div>
                        <div class="committee-desc">Barangay Secretary</div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="committee-title">HON. DEMOSTHENES P. PATINGA</div>
                        <div class="committee-desc">Punong Barangay</div>
                    </div>
                </div>
                
                <!-- Right Column - Clearance Content -->
                <div class="col-8 clearance-column">
                    <p>This is to certify that per official records on this barangay to date: <strong>MR./MRS./MS.</strong> <span class="underline">{{ $cert->first_name . ' ' . $cert->last_name }}</span> of legal age, single/married, Filipino, is a registered resident of <strong>Address</strong> <span class="underline">{{ $cert->address }}</span> Barangay Cantil-e, Dumaguete City Negros Oriental. And according to our record she is a person with good moral character in our barangay.</p>
                    
                    <p>This clearance is issued upon request of <strong>MR / MRS. / MS.</strong> <span class="underline">{{ $cert->first_name . ' ' . $cert->last_name }}</span></p>
                    
                    <p class="mt-4">For:</p>
                    <div class="row">
                        <div class="col-6">
                            <div><span class="checkbox"></span> Student</div>
                            <div><span class="checkbox"></span> Employment</div>
                            <div><span class="checkbox"></span> Others (Specify)<span class="underline">{{ $cert->purpose }}</span></div>
                        </div>
                        <div class="col-6">
                            <div><span class="checkbox"></span> Business</div>
                            <div><span class="checkbox"></span> Assistance</div>
                        </div>
                    </div>
                    
                    <p class="mt-4">Done this <span class="underline">{{ \Carbon\Carbon::now()->format('F d, Y') }}</span> at Barangay Cantil-e Dumaguete City, Negros Oriental, Philippines.</p>
                    
                    <div class="mt-5 text-end">
                        <div class="signature-line">
                            <div class="official-name">HON. DEMOSTHENES P. PATINGA</div>
                            <div class="official-title">Punong Barangay</div>
                        </div>
                    </div>
                    
                    <div class="footer-info mt-5">
                        <div>Res. Cert. No. <span class="underline">{{ $cert->request_id ?? 'N/A' }}</span></div>
                        <div>Issued on: <span class="underline">{{ \Carbon\Carbon::now()->format('F d, Y') }}</span></div>
                        <div>Issued at: <span class="underline">Barangay Cantil-e, Dumaguete City</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>