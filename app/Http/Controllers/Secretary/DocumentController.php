<?php

namespace App\Http\Controllers\Secretary;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ResidentDocumentRequest;
use App\Models\OfficialDocumentRequest;
use Illuminate\Support\Facades\Cache;

class DocumentController extends Controller
{
    public function printClearance($id)
    {
        // Try to get certificate data from cache (for generated certificates)
        $data = Cache::get($id);
        if ($data) {
            return view('secretary.CertificateType.barangayClearance', [
                'data' => $data
            ]);
        }

        // Fallback: Try to find the request in resident document requests first
        $request = ResidentDocumentRequest::where('request_id', $id)->first();
        if (!$request) {
            $request = OfficialDocumentRequest::where('request_id', $id)->firstOrFail();
        }
        return view('secretary.CertificateType.barangayClearance', [
            'request' => $request
        ]);
    }

    public function printResidency($id)
    {
        $data = Cache::get($id);
        if ($data) {
            return view('secretary.CertificateType.certificateOfResidency', [
                'data' => $data
            ]);
        }
        $request = ResidentDocumentRequest::where('request_id', $id)->first();
        if (!$request) {
            $request = OfficialDocumentRequest::where('request_id', $id)->firstOrFail();
        }
        return view('secretary.CertificateType.certificateOfResidency', [
            'request' => $request
        ]);
    }

    public function printCertification($id)
    {
        $data = Cache::get($id);
        if ($data) {
            return view('secretary.CertificateType.barangayCertification', [
                'data' => $data
            ]);
        }
        $request = ResidentDocumentRequest::where('request_id', $id)->first();
        if (!$request) {
            $request = OfficialDocumentRequest::where('request_id', $id)->firstOrFail();
        }
        return view('secretary.CertificateType.barangayCertification', [
            'request' => $request
        ]);
    }
} 