<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class ProjectUserController extends Controller
{
    /**
     * Добавить пользователя в проект
     */
    public function addUser(Request $request, $projectId)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $project = Project::findOrFail($projectId);

        // Добавляем пользователя
        $project->users()->attach($validated['user_id']);

        return response()->json([
            'message' => 'Пользователь добавлен в проект',
            'project_id' => $project->id,
            'user_id' => $validated['user_id'],
        ]);
    }

    /**
     * Получить всех пользователей проекта
     */
    public function getUsers($projectId)
    {
        $project = Project::with('users')->findOrFail($projectId);

        return response()->json([
            'project_id' => $project->id,
            'users' => $project->users
        ]);
    }

    /**
     * Удалить пользователя из проекта
     */
    public function removeUser($projectId, $userId)
    {
        $project = Project::findOrFail($projectId);
        $project->users()->detach($userId);

        return response()->json([
            'message' => 'Пользователь удалён из проекта',
            'project_id' => $project->id,
            'user_id' => $userId
        ]);
    }
}
