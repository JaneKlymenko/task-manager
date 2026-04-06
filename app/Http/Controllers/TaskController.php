<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TaskController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Task::where('user_id', Auth::id());

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filter === 'completed') {
            $query->where('status', 'completed');
        } elseif ($request->filter === 'pending') {
            $query->whereIn('status', ['pending', 'in_progress']);
        } elseif ($request->filter === 'overdue') {
            $query->where('deadline', '<', now())
                  ->where('status', '!=', 'completed');
        }

        $tasks = $query->latest()->get();

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
        $data['user_id'] = Auth::id();
        $data['deadline'] = $request->deadline;

        Task::create($data);

        Session::flash('success', 'Task created successfully!');
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

        Session::flash('success', 'Task updated successfully.');
        return redirect()->route('tasks.show', $task);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);
        $task->delete();

        Session::flash('success', 'Task deleted successfully!');
        return redirect()->route('tasks.index');
    }
    public function complete(Task $task)
    {
        $this->authorize('update', $task);
        $task->update([
            'status' => 'completed'
        ]);

        Session::flash('success', 'Task marked as completed!');
        return redirect()->route('tasks.index');
    }

    public function start(Task $task)
    {
        $this->authorize('update', $task);
        $task->update([
            'status' => 'in_progress'
        ]);

        Session::flash('success', 'Task started!');
        return redirect()->route('tasks.index');
    }

    public function reset(Task $task)
    {
        $this->authorize('update', $task);
        $task->update([
            'status' => 'pending'
        ]);

        return redirect()->route('tasks.index')->with('success', 'Task reset to pending!');
    }
}
    


