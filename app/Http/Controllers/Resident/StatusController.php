<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Complaint;
use App\Models\ResidentDocumentRequest;
use Illuminate\Support\Facades\Auth;

class StatusController extends Controller
{
    public function complaintStatus()
    {
        $user = Auth::user();
        $complaints = Complaint::where('first_name', $user->first_name)
            ->where('last_name', $user->last_name)
            ->where('contact_number', $user->contact_number)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('resident.s_complain', compact('complaints'));
    }

    public function documentStatus()
    {
        $documents = ResidentDocumentRequest::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('resident.s_document', compact('documents'));
    }
} 