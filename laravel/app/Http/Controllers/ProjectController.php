<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Project;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Project::with('users')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'integer',
            'deadline' => 'nullable|date',
        ]);

        $project = Project::create([
            ...$validated,
            'owner_id' => auth()->id(),
        ]);

        // добавить владельца в project_user как owner (роль 1)
        $project->users()->attach(auth()->id(), ['role' => 1]);

        return response()->json($project, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        return $project->load('users');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        $this->authorize('update', $project);

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'status' => 'integer',
            'deadline' => 'nullable|date',
        ]);

        $project->update($validated);

        return response()->json($project);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);

        $project->delete();

        return response()->json(['message' => 'Deleted']);
    }
}
