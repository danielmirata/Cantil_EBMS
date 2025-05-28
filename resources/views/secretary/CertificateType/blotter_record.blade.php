<!DOCTYPE html>
<html>
<head>
    <title>Barangay Blotter</title>
    <style>
        body { font-family: Times New Roman, Times, serif; }
        .header { text-align: center; }
        .logo { width: 100px; }
        .section-title { font-weight: bold; margin-top: 20px; }
        .underline { border-bottom: 1px solid #000; display: inline-block; width: 200px; }
        .facts { margin-top: 20px; }
        .facts-line { border-bottom: 1px solid #000; margin-bottom: 10px; }
        .footer { margin-top: 40px; }
        .signature { margin-top: 60px; }
        @media print {
            body { font-family: Times New Roman, Times, serif; }
            .header { text-align: center; }
            .logo { width: 100px; }
            .section-title { font-weight: bold; margin-top: 20px; }
            .underline { border-bottom: 1px solid #000; display: inline-block; width: 200px; }
            .facts { margin-top: 20px; }
            .facts-line { border-bottom: 1px solid #000; margin-bottom: 10px; }
            .footer { margin-top: 40px; }
            .signature { margin-top: 60px; }
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ asset('img/cantil-e-logo.png') }}" alt="Barangay Logo" class="logo"><br>
        <div>Republic of the Philippines</div>
        <div>Province of Negros Oriental</div>
        <div>City of Dumaguete</div>
        <div><b>Barangay Cantil-E</b></div>
        <div><b>OFFICE OF THE SANGGUNIANG BARANGAY</b></div>
        <hr>
        <h2>BARANGAY BLOTTER</h2>
    </div>
    <div>
        <b>INCIDENT REPORT:</b>
        <p>
            As per report and records of events available in the official barangay blotter of this office, there appears the following data, to wit:
        </p>
        <table>
            <tr>
                <td>Barangay Blotter Series of</td>
                <td>: <span class="underline"></span></td>
            </tr>
            <tr>
                <td>Blotter Page Number</td>
                <td>: <span class="underline"></span></td>
            </tr>
            <tr>
                <td>Blotter Entry Number</td>
                <td>: <span class="underline">{{ $blotter->id }}</span></td>
            </tr>
            <tr>
                <td>Date & Time Entered</td>
                <td>: <span class="underline">{{ $incident_date }} {{ $incident_time }}</span></td>
            </tr>
        </table>
    </div>
    <div class="section-title">FACTS OF THE CASE:</div>
    <div class="facts">
        <p>{{ $what_happened }}</p>
        <p><strong>Who was involved:</strong> {{ $who_was_involved }}</p>
        <p><strong>How it happened:</strong> {{ $how_it_happened }}</p>
    </div>
    <div class="footer">
        Entered this {{ date('j') }} day of {{ date('F') }}, {{ date('Y') }} at Barangay Cantil-E, Dumaguete City, Negros Oriental.
        <div class="signature">
            Prepared by:<br><br>
            <b></b><br>
            Barangay Secretary
        </div>
        <div class="signature">
            Certified by:<br><br>
            <b>{{ $barangay_captain_name }}</b><br>
            Punong Barangay
        </div>
    </div>
</body>
</html>
