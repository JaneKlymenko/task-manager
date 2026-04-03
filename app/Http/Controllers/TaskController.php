<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TaskController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tasks = Task::where('user_id', auth()->id())->latest()->get();
        if ($request->filter === 'completed') {
            $tasks = $tasks->where('is_completed', true);
        } elseif ($request->filter === 'pending') {
            $tasks = $tasks->where('is_completed', false);
        }      
        elseif ($request->filter === 'overdue') {
        $tasks = $tasks->where('deadline', '<', now())
              ->where('is_completed', false);
        }
        return view('tasks.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tasks.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {    
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $data = $request->all();
        $data['status'] = $data['status'] ?? 'pending';
        $data['user_id'] = auth()->id();
        $data['deadline'] = $request->deadline;

        Task::create($data);

        return redirect()->route('tasks.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        $this->authorize('view', $task);
        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        $this->authorize('update', $task);
        return view('tasks.edit', compact('task'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task);
        $request->validate([
            'title' => 'required|string|max:255',
            'status' => 'required|in:pending,in_progress,completed',
            'deadline' => 'nullable|date|after_or_equal:today',
        ]);

        $task->update($request->only(['title', 'description', 'status', 'deadline']));

        return redirect()->route('tasks.show', $task)->with('success', 'Task updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);
        $task->delete();

        return redirect()->route('tasks.index');
    }

    public function complete(Task $task)
    {
        $this->authorize('update', $task);
        $task->update([
            'status' => 'completed'
        ]);

        return redirect()->route('tasks.index');
    }

    public function start(Task $task)
    {
        $this->authorize('update', $task);
        $task->update([
            'status' => 'in_progress'
        ]);

        return redirect()->route('tasks.index');
    }

    public function reset(Task $task)
    {
        $this->authorize('update', $task);
        $task->update([
            'status' => 'pending'
        ]);

        return redirect()->route('tasks.index');
    }
}
