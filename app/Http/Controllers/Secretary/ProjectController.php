<?php

namespace App\Http\Controllers\Secretary;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project; // Import the Project model
use Illuminate\Support\Facades\Storage; // Import Storage facade

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $projects = Project::all();
        // Assuming the secretary view for projects is 'secretary.projects.index'
        return view('secretary.barangay_project', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Assuming the secretary view for creating a project is 'secretary.projects.create'
        return view('secretary.projects.create');
    }

    /**
     * Store a newly created resource in storage.
     */
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

        return redirect()->route('secretary.projects.index')
            ->with('success', 'Project created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $project = Project::findOrFail($id);
        return view('secretary.projects.show', compact('project')); // Assuming a show view
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $project = Project::findOrFail($id);
        return view('secretary.projects.edit', compact('project')); // Assuming an edit view
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $project = Project::findOrFail($id);

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

        return redirect()->route('secretary.projects.index')
            ->with('success', 'Project updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $project = Project::findOrFail($id);
        // Delete associated documents
        if ($project->documents) {
            foreach ($project->documents as $doc) {
                Storage::delete($doc['path']);
            }
        }

        $project->delete();

        return redirect()->route('secretary.projects.index')->with('success', 'Project deleted successfully.');
    }
} 