<?php

namespace App\Http\Controllers\Secretary;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ResidentDocumentRequest;
use App\Models\OfficialDocumentRequest;

class DocumentController extends Controller
{
    public function printClearance($id)
    {
        // Try to find the request in resident document requests first
        $request = ResidentDocumentRequest::where('request_id', $id)->first();
        
        // If not found in resident requests, try official document requests
        if (!$request) {
            $request = OfficialDocumentRequest::where('request_id', $id)->firstOrFail();
        }
        
        return view('secretary.CertificateType.barangayClearance', [
            'request' => $request
        ]);
    }

    public function printResidency($id)
    {
        // Try to find the request in resident document requests first
        $request = ResidentDocumentRequest::where('request_id', $id)->first();
        
        // If not found in resident requests, try official document requests
        if (!$request) {
            $request = OfficialDocumentRequest::where('request_id', $id)->firstOrFail();
        }
        
        return view('secretary.CertificateType.certificateOfResidency', [
            'request' => $request
        ]);
    }

    public function printCertification($id)
    {
        // Try to find the request in resident document requests first
        $request = ResidentDocumentRequest::where('request_id', $id)->first();
        
        // If not found in resident requests, try official document requests
        if (!$request) {
            $request = OfficialDocumentRequest::where('request_id', $id)->firstOrFail();
        }
        
        return view('secretary.CertificateType.barangayCertification', [
            'request' => $request
        ]);
    }
} 