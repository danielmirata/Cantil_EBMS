<?php

namespace App\Http\Controllers\Official;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::latest()->get();
        
        // Calculate stats
        $totalProjects = $projects->count();
        $ongoingProjects = $projects->where('status', 'Ongoing')->count();
        $completedProjects = $projects->where('status', 'Completed')->count();
        $pendingProjects = $projects->where('status', 'Planning')->count();
        
        return view('official.project', compact(
            'projects', 
            'totalProjects', 
            'ongoingProjects', 
            'completedProjects', 
            'pendingProjects'
        ));
    }

    public function show($id)
    {
        $project = Project::findOrFail($id);
        
        // Load documents if they exist
        $documents = [];
        if ($project->documents) {
            $documents = json_decode($project->documents, true) ?: [];
        }
        $project->documents = $documents;
        
        return response()->json(['project' => $project]);
    }

    public function edit($id)
    {
        $project = Project::findOrFail($id);
        return response()->json(['project' => $project]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'budget' => 'required|numeric|min:0',
            'status' => 'required|in:Planning,Ongoing,Completed,On Hold',
            'priority' => 'required|in:High,Medium,Low',
            'funding_source' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'progress' => 'nullable|integer|min:0|max:100',
            'documents.*' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:5120'
        ]);

        // Handle file uploads
        $documents = [];
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $file) {
                $path = $file->store('project-documents', 'public');
                $documents[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path
                ];
            }
        }

        $validated['documents'] = json_encode($documents);
        $validated['progress'] = $validated['progress'] ?? 0;

        Project::create($validated);

        return redirect()->route('official.projects.index')
            ->with('success', 'Project created successfully.');
    }

    public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id);
        
        $validated = $request->validate([
            'project_name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'budget' => 'required|numeric|min:0',
            'status' => 'required|in:Planning,Ongoing,Completed,On Hold',
            'priority' => 'required|in:High,Medium,Low',
            'funding_source' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'progress' => 'nullable|integer|min:0|max:100',
            'documents.*' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:5120'
        ]);

        // Handle file uploads
        $existingDocuments = json_decode($project->documents, true) ?: [];
        
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $file) {
                $path = $file->store('project-documents', 'public');
                $existingDocuments[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path
                ];
            }
        }

        $validated['documents'] = json_encode($existingDocuments);
        $validated['progress'] = $validated['progress'] ?? $project->progress ?? 0;

        $project->update($validated);

        return redirect()->route('official.projects.index')
            ->with('success', 'Project updated successfully.');
    }

    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        
        // Delete associated files
        $documents = json_decode($project->documents, true) ?: [];
        foreach ($documents as $document) {
            if (isset($document['path'])) {
                Storage::disk('public')->delete($document['path']);
            }
        }
        
        $project->delete();
        
        return response()->json(['message' => 'Project deleted successfully']);
    }
}