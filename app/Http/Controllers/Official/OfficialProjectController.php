<?php

namespace App\Http\Controllers\Official;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\Storage;

class OfficialProjectController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'budget' => 'required|numeric|min:0',
            'status' => 'required|in:Planning,Ongoing,Completed,On Hold',
            'priority' => 'required|in:High,Medium,Low',
            'funding_source' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'documents.*' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
            'progress' => 'nullable|integer|min:0|max:100',
        ]);

        $documents = [];
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $file) {
                $path = $file->store('project-documents', 'public');
                $documents[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'type' => $file->getMimeType(),
                    'size' => $file->getSize()
                ];
            }
        }

        $validated['documents'] = $documents;

        Project::create($validated);

        return redirect()->route('official.projects')
            ->with('success', 'Project created successfully.');
    }
} 