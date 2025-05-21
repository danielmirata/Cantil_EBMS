<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::latest()->get();
        $totalProjects = Project::count();
        $ongoingProjects = Project::where('status', 'Ongoing')->count();
        $completedProjects = Project::where('status', 'Completed')->count();
        $pendingProjects = Project::where('status', 'Planning')->count();

        return view('secretary.barangay_project', compact(
            'projects',
            'totalProjects',
            'ongoingProjects',
            'completedProjects',
            'pendingProjects'
        ));
    }

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
            'documents.*' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240'
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

        return redirect()->route('projects.index')
            ->with('success', 'Project created successfully.');
    }

    public function show(Project $project)
    {
        return response()->json(['project' => $project]);
    }

    public function edit(Project $project)
    {
        return response()->json(['project' => $project]);
    }

    public function update(Request $request, Project $project)
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
            'documents.*' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240'
        ]);

        $documents = $project->documents ?? [];
        if ($request->hasFile('documents')) {
            // Delete old documents
            foreach ($documents as $doc) {
                Storage::delete($doc['path']);
            }

            // Store new documents
            $documents = [];
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

        $project->update($validated);

        return redirect()->route('projects.index')
            ->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project)
    {
        // Delete associated documents
        if ($project->documents) {
            foreach ($project->documents as $doc) {
                Storage::delete($doc['path']);
            }
        }

        $project->delete();

        return response()->json(['message' => 'Project deleted successfully']);
    }
} 