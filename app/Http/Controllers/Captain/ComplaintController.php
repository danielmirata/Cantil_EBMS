<?php

namespace App\Http\Controllers\Captain;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Complaint;
use Illuminate\Support\Facades\DB;

class ComplaintController extends Controller
{
    public function index()
    {
        // Get all complaints
        $complaints = Complaint::orderBy('created_at', 'desc')->get();

        // Get complaint statistics
        $stats = [
            'total_complaints' => Complaint::count(),
            'pending_complaints' => Complaint::where('status', 'Pending')->count(),
            'resolved_complaints' => Complaint::where('status', 'Resolved')->count(),
            'rejected_complaints' => Complaint::where('status', 'Rejected')->count(),
        ];

        return view('captain.complain', compact('complaints', 'stats'));
    }
} 