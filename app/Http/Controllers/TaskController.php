<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use function Laravel\Prompts\alert;

class TaskController extends Controller
{

    public function index()
    {
        $tasks = Task::orderBy('priority', 'asc')->get();
        $projects = Project::all();
        return view('welcome', ['projects' => $projects, 'tasks' => $tasks]);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'task_name' => 'required|string|max:255',
            'project_id' => 'required|exists:projects,id',
            'task_priority' => 'required|numeric|min:1|unique:tasks,priority', // Validate for uniqueness
        ], [
            'task_name.required' => 'Task name is required',
            'project_id.required' => 'Project is required',
            'task_priority.unique' => 'Task with below priority is already set.',

        ]);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();
            Task::create([
                'task_name' => $request->task_name,
                'project_id' => $request->project_id,
                'priority' => $request->task_priority,
            ]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Task added successfully');
    }


    public function updatePriority(Request $request)
    {
        $taskIds = $request->input('taskIds');
        Log::info('Received task IDs: ', $taskIds);

        try {
            DB::beginTransaction();
            Task::query()->update(['priority' => null]);

            foreach ($taskIds as $priority => $id) {
                Log::info("Updating task id $id with priority " . ($priority + 1));
                Task::where('id', $id)->update(['priority' => $priority + 1]);
            }
            DB::commit();
            return response()->json(['message' => 'Task priorities updated successfully']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Error updating task priorities', 'error' => $e->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        // Find the task by ID
        $task = Task::find($id);

        if (!$task) {
            return redirect()->route('tasks.view')->with('error', 'Task not found');
        }
        try {
            // Delete the task
            $task->delete();

            return redirect()->route('tasks.view')->with('success', 'Task deleted successfully');
        } catch (\Exception $e) {
            return redirect()->route('tasks.view')->with('error', 'Error deleting task: ' . $e->getMessage());
        }
    }
}
