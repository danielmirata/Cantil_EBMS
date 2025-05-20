<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Summons</title>
    <style>
        @page {
            size: A4;
            margin: 0.3in;
        }
        html, body {
            width: 100%;
            min-height: 100vh;
            margin: 0;
            padding: 0;
            background: #fff;
        }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0 1vw;
            box-sizing: border-box;
            font-size: 14pt;
            min-height: 100vh;
            margin-left: auto;
            margin-right: auto;
        }
        .header-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
        }
        .logo {
            width: 80px;
            height: 80px;
            margin-right: 20px;
            flex-shrink: 0;
        }
        .header-center {
            flex: 1;
            text-align: center;
        }
        .header-center h4, .header-center h5 {
            margin: 2px 0;
        }
        .header-case-info {
            text-align: right;
            min-width: 180px;
            margin-bottom: 8px;
        }
        .header-line {
            position: absolute;
            left: 90px;
            right: 0;
            top: 100%;
            border: none;
            border-top: 2px solid #000;
            width: auto;
            margin: 0;
            z-index: 1;
        }
        .complainant-section, .respondent-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 0;
        }
        .complainant-label, .respondent-label {
            border-bottom: 1.5px dashed #000;
            flex: 1;
            margin-right: 10px;
            height: 1.2em;
        }
        .case-label {
            border-bottom: 1.5px dashed #000;
            flex: 1;
            margin-left: 10px;
            height: 1.2em;
        }
        .clear {
            clear: both;
        }
        .brgy-case {
            text-align: right;
            margin-top: 5px;
            margin-bottom: 15px;
        }
        .details {
            margin: 20px 0 5px 0;
            font-size: 1em;
        }
        .details p {
            margin: 1px 0;
        }
        .summons-title {
            text-align: center;
            letter-spacing: 0.2em;
            margin: 30px 0 15px 0;
        }
        .summons-title h2 {
            margin: 0;
            font-size: 1.3em;
        }
        .content {
            margin: 15px 0 30px 0;
            font-size: 1em;
            line-height: 1.4;
        }
        .content p {
            margin: 8px 0;
        }
        .underline {
            border-bottom: 1px solid #000;
            display: inline-block;
            min-width: 80px;
            padding-bottom: 2px;
        }
        .signature {
            margin-top: 40px;
            text-align: right;
        }
        .duplication {
            margin-top: 40px;
            border-top: 1px dashed #000;
            padding-top: 15px;
            page-break-inside: avoid;
        }
        .duplication-title {
            text-align: center;
            margin-bottom: 8px;
        }
        .duplication-title h4 {
            margin: 0;
            font-size: 1.1em;
        }
        .row {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            margin-bottom: 8px;
        }
        .col {
            width: 48%;
            font-size: 1em;
        }
        .col p {
            margin: 5px 0;
        }
        .received {
            margin-top: 20px;
        }
        @media (max-width: 700px) {
            body {
                padding: 0 1vw;
                font-size: 11pt;
            }
            .header-center {
                margin-left: 0;
            }
            .logo {
                position: static;
                display: block;
                margin: 0 auto 10px auto;
            }
            .row {
                flex-direction: column;
            }
            .col {
                width: 100%;
            }
        }
        @media print {
            html, body {
                width: 8.5in;
                min-height: 13in;
                margin: 0;
                padding: 0 0.75in;
            }
            body {
                max-width: none;
                min-height: 0;
            }
            .header, .header-center, .logo {
                page-break-inside: avoid;
            }
        }
        .office-underline {
            border: none;
            border-top: 2px solid #000;
            width: 100%;
            margin: 6px 0 0 0;
        }
    </style>
</head>
<body>
    <div class="header-row">
        <img src="{{  asset('img/cantil-e-logo.png') }}" alt="Logo" class="logo">
        <div class="header-center">
            <h4>Province of Negros Oriental</h4>
            <h4>City of Dumaguete</h4>
            <h4>BARANGAY CANTIL-E</h4>
            <h5>Tel. No. (035) 402-1801</h5>
            <h5>OFFICE OF THE PUNONG BARANGAY</h5>
        </div>
        <div>
            
        </div>
        <hr class="header-line">
    </div>
   

    <div class="details">
        <div class="header-case-info">
            <div>Brgy. Case No.{{ $blotter->blotters_id }}</div>
            <div>For: {{ $blotter->complaint_type }}</div>
        </div>
        <p>{{ $blotter->first_name }} {{ $blotter->last_name }}</p>
        <p><strong>- Complainant/s -</strong></p>
        <br>
        <p><strong>- Against -</strong></p>
        <br>
        <p>{{ $blotter->respondent_first_name }} {{ $blotter->respondent_last_name }}</p>
        <p><strong>- Respondent/s -</strong></p>
    </div>

    <div class="summons-title">
        <h2>SUMMONS</h2>
    </div>

    <div class="content">
        <p>To: MS/MR.<br>
        Barangay Cantil-e<br>
        Dumaguete City</p>

        <p>You are hereby summoned to appear before me in person, together with your witnesses on the <span class="underline">{{ $date }}</span> ( <span class="underline">{{ $day }}</span> ) at <span class="underline">{{ $time }}</span> in the morning/afternoon then and there to answer to a complaint made before me in, copy of which is attached hereto, for mediation/conciliation of your dispute with complainant.</p>

        <p>You are hereby warned that if you refuse or willfully fail to appear in obedience to this summons, you may be barred from filing any counterclaim arising from said complaint.</p>
        <p>FAIL NOT or else faces punishment as for contempt of court.</p>
        <p>This <span class="underline">{{ $issued_day }}</span> day of <span class="underline">{{ $issued_month }}</span>, {{ date('Y') }}</p>
    </div>

    <div class="signature">
        <p><strong>HON. DEMOSTHENES P. PATINGA</strong><br>Punong Barangay</p>
    </div>

    <div class="duplication">
        <div class="duplication-title">
            <h4>DUPLICATION</h4>
        </div>
        <div class="row">
            <div class="col">
                <p>Date/Time of hearing: <span class="underline">{{ $date }} at {{ $time }}</span></p>
                <p>Venue: Barangay Hall, Barangay Cantil-e, Dumaguete City</p>
                <p><strong>Complainant/s:</strong></p>
                <p>{{ $blotter->first_name }} {{ $blotter->last_name }}</p>
                <p>Barangay ____________, Dumaguete City</p>
            </div>
            <div class="col">
                <p>Case No.: <span class="underline">{{ $blotter->id }}</span></p>
                <p>For: <span class="underline">{{ $blotter->complaint_type }}</span></p>
                <p><strong>Respondent/s:</strong></p>
                <p>{{ $blotter->respondent_first_name }} {{ $blotter->respondent_last_name }}</p>
                <p>Barangay Cantil-e, Dumaguete City</p>
            </div>
        </div>
        <div class="received">
            <p>Received by: ____________________ this ____ of ________, {{ date('Y') }}</p>
        </div>
    </div>
</body>
</html>


