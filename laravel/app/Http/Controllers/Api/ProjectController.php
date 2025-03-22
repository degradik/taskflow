<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = auth()->user()
            ->projects() // Связь projects в модели User
            ->with('tasks') // Подгружаем задачи проекта
            ->get();

        return response()->json($projects);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $project = auth()->user()->projects()->create($validated);

        return response()->json($project, 201);
    }

    public function show($id)
    {
        $project = auth()->user()->projects()
            ->with('tasks.customFieldValues.customField')
            ->findOrFail($id);

        return response()->json($project);
    }

    public function update(Request $request, $id)
    {
        $project = auth()->user()->projects()->findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $project->update($validated);

        return response()->json($project);
    }

    public function destroy($id)
    {
        $project = auth()->user()->projects()->findOrFail($id);

        $project->delete();

        return response()->json(['message' => 'Проект удален']);
    }
}
