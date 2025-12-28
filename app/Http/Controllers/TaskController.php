<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        return response()->json(Task::latest()->get(), 200);
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
        ]);
        $task = Task::create([
            'title' => $data['title'],
            'is_completed' => false,
        ]);
        return response()->json($task, 201);
    }

    public function toggleStatus($id)
    {
        $task = Task::findOrFail($id);
        $task->is_completed = !$task->is_completed;
        $task->save();
        return response()->json($task, 200);
    }
}
