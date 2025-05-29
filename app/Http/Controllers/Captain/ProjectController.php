<?php

namespace App\Http\Controllers\Captain;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    /**
     * Display a listing of the projects.
     */
    public function index()
    {
        $projects = Project::latest()->paginate(10);
        return view('captain.project', compact('projects'));
    }

    /**
     * Show the form for creating a new project.
     */
    public function create()
    {
        return view('captain.project');
    }

    /**
     * Store a newly created project in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_name' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'budget' => 'required|numeric',
            'status' => 'required|in:planned,ongoing,completed,cancelled',
            'location' => 'required|string',
            'priority' => 'required|in:low,medium,high',
            'funding_source' => 'required|string',
            'notes' => 'nullable|string',
            'documents' => 'nullable|array',
            'progress' => 'nullable|numeric|min:0|max:100'
        ]);

        $project = Project::create($validated);

        return redirect()->route('captain.projects.index')
            ->with('success', 'Project created successfully.');
    }

    /**
     * Display the specified project.
     */
    public function show(Project $project)
    {
        return view('captain.project', compact('project'));
    }

    /**
     * Show the form for editing the specified project.
     */
    public function edit(Project $project)
    {
        return view('captain.project', compact('project'));
    }

    /**
     * Update the specified project in storage.
     */
    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'project_name' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'budget' => 'required|numeric',
            'status' => 'required|in:planned,ongoing,completed,cancelled',
            'location' => 'required|string',
            'priority' => 'required|in:low,medium,high',
            'funding_source' => 'required|string',
            'notes' => 'nullable|string',
            'documents' => 'nullable|array',
            'progress' => 'nullable|numeric|min:0|max:100'
        ]);

        $project->update($validated);

        return redirect()->route('captain.projects.index')
            ->with('success', 'Project updated successfully.');
    }

    /**
     * Remove the specified project from storage.
     */
    public function destroy(Project $project)
    {
        $project->delete();

        return redirect()->route('captain.projects.index')
            ->with('success', 'Project deleted successfully.');
    }
} 