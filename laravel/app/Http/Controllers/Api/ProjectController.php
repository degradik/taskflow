<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Http\Resources\ProjectResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator; 
use Illuminate\Support\Facades\Auth; 

use App\Models\User;

class ProjectController extends BaseController
{


    public function index(){

        $projects = Project::query()->get();
        return $this->sendResponse("OK", ProjectResource::collection($projects));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'nullable|integer',
            'deadline'    => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $input = $request->all();
        $input['owner_id'] = Auth::id();

        $project = Project::create($input);

        return $this->sendResponse($project, 'Project created successfully.');
    }
    
    /**
     * Получить конкретный проект
     */
    public function show($id)
    {
        $project = Project::find($id);

        if (is_null($project)) {
            return $this->sendError('Project not found.');
        }

        if ($project->owner_id !== Auth::id()) {
            return $this->sendError('Unauthorized.', [], 403);
        }

        return $this->sendResponse($project, 'Project retrieved successfully.');
    }

    /**
     * Обновить проект
     */
    public function update(Request $request, $id)
    {
        $project = Project::find($id);

        if (is_null($project)) {
            return $this->sendError('Project not found.');
        }

        if ($project->owner_id !== Auth::id()) {
            return $this->sendError('Unauthorized.', [], 403);
        }

        $validator = Validator::make($request->all(), [
            'title'       => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'nullable|integer',
            'deadline'    => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $project->update($request->all());

        return $this->sendResponse($project, 'Project updated successfully.');
    }

    /**
     * Удалить проект
     */
    public function destroy($id)
    {
        $project = Project::find($id);

        if (is_null($project)) {
            return $this->sendError('Project not found.');
        }

        if ($project->owner_id !== Auth::id()) {
            return $this->sendError('Unauthorized.', [], 403);
        }

        $project->delete();

        return $this->sendResponse([], 'Project deleted successfully.');
    }


    public function addUser(Request $request, Project $project)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'nullable|string'
        ]);

        // Добавляем пользователя с ролью
        $project->users()->attach($validated['user_id']);

        return response()->json([
            'message' => 'Пользователь добавлен в проект',
            'project_id' => $project->id,
            'user_id' => $validated['user_id']
        ]);
    }

    public function removeUser(Project $project, User $user)
    {
        $project->users()->detach($user->id);

        return response()->json([
            'message' => 'Пользователь удалён из проекта',
            'project_id' => $project->id,
            'user_id' => $user->id
        ]);
    }
}
