<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay Certification</title>
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
            min-height: 11in;
            margin: 0 auto;
            padding: 1in;
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
            height: 80px;
            width: 80px;
            object-fit: contain;
            margin: 0 auto;
        }
        .bagong-pilipinas {
            height: 80px;
            width: 80px;
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
        .clearance-content {
            font-size: 14px;
            text-align: justify;
            padding: 10px 20px;
        }
        .underline {
            border-bottom: 1px solid #000;
            display: inline-block;
            min-width: 200px;
            margin: 0 5px;
        }
        .footer-info {
            font-size: 12px;
            margin-top: 10px;
        }
        .signature-line {
            border-top: 1px solid #000;
            margin-top: 30px;
            width: 250px;
            float: right;
            margin-right: 20px;
        }
        .official-name {
            font-weight: bold;
            text-decoration: underline;
            text-align: right;
        }
        .official-title {
            font-size: 12px;
            text-align: right;
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
        .divider {
            height: 1px;
            background-color: #000;
            margin: 15px 0;
        }
        .content-text {
            font-size: 14px;
            text-align: justify;
            line-height: 1.4;
        }
        .purpose-field {
            margin-top: 15px;
            margin-bottom: 15px;
        }
        .issuance-info {
            margin-top: 15px;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
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
                <div class="divider"></div>
                <h3><strong>CERTIFICATE OF RESIDENCY</strong></h3>
            </div>
            
            <!-- Main Content -->
            <div class="content-text">
                <p>TO WHOM IT MAY CONCERN:</p>
                
                <p>THIS IS TO CERTIFY THAT, <span class="underline">{{ $request->first_name . ' ' . $request->last_name }}</span> of legal age, single/married, Filipino, is a <strong>resident of Address</strong> <span class="underline">{{ $request->address }}</span> Barangay Cantil-e Dumaguete City, Negros Oriental. And according to our record he/she is a person with good moral character with no pending case against him/her in the barangay.</p>
                
                <p><strong>Further certifies that <span class="underline">{{ $request->first_name . ' ' . $request->last_name }}</span> is an indigent person and belongs to an indigent family.</strong></p>
                
                <p>This certification is issued upon request of the above-named person for whatever legal purpose this may serve best.</p>
                
                <div class="purpose-field">
                    <p><strong>PURPOSE: <span class="underline">{{ $request->purpose }}</span></strong></p>
                </div>
                
                <div class="issuance-info">
                    <p>Issued this <span class="underline">{{ \Carbon\Carbon::now()->format('F d, Y') }}</span> Office of the Punong Barangay, Barangay Cantil-e, Dumaguete City, Negros oriental, Philippines.</p>
                </div>
                
                <div class="mt-5">
                    <div class="signature-line">
                        <div class="official-name">HON. DEMOSTHENES P. PATINGA</div>
                        <div class="official-title">Punong Barangay</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>