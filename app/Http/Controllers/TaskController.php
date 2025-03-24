<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TaskController extends Controller
{
   
    /**
     * Retrieve all tasks with pagination, filtering, and sorting.
     */
   /**
     * Retrieve all tasks with pagination, filtering, and sorting.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            Log::info('Fetching tasks list.');

            // Get filters
            $email = $request->query('email');
            $search = $request->query('search');
            $sortField = $request->query('sort_field', 'created_at');
            $sortOrder = $request->query('sort_order', 'desc');
            $priority = $request->query('priority');
            $dueDate = $request->query('due_date');

            // Log filter values
            Log::info('Filters: email=' . $email . ', search=' . $search . ', sortField=' . $sortField . ', sortOrder=' . $sortOrder . ', priority=' . $priority . ', dueDate=' . $dueDate);

            // Ensure only allowed sort fields are used
            $allowedSortFields = ['created_at', 'title', 'status']; // Add valid fields here
            if (!in_array($sortField, $allowedSortFields)) {
                $sortField = 'created_at'; // Default if invalid field is given
                Log::warning('Invalid sort field provided. Using default: created_at');
            }

            // Fetch tasks with filters
            $tasks = Task::when($email, fn($query) => $query->where('user_email', $email))
                ->when($search, fn($query) => $query->where('title', 'like', "%$search%"))
                ->when($priority, fn($query) => $query->where('priority', $priority))
                ->when($dueDate, fn($query) => $query->whereDate('due_date', $dueDate))
                ->orderBy($sortField, $sortOrder)
                ->paginate(10);

            // Ensure API returns 'email' field correctly mapped from 'user_email'
            $tasks->getCollection()->transform(function ($task) {
                $task->email = $task->user_email; // ✅ Ensure correct mapping
                return $task;
            });

            Log::info('Tasks fetched successfully. Total tasks: ' . $tasks->total());

            return response()->json([
                'status' => 'success',
                'data' => $tasks,
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching tasks: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch tasks'], 500);
        }
    }


    /**
     * Retrieve a single task.
     */
    public function show($id): JsonResponse
    {
        $task = Task::find($id);
        if (!$task) {
            Log::warning("Task not found: $id");
            return response()->json(['error' => 'Task not found'], 404);
        }
        Log::info("Fetching task details for ID: $id");
        return response()->json(['status' => 'success', 'data' => $task]);
    }
    

    /**
     * Store a new task.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            Log::info('Validating and storing new task.');
    
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'priority' => 'required|in:low,medium,high',
                'due_date' => 'nullable|date',
                'user_email' => 'required|email|max:255' // Track user by email
            ]);
    
            $task = Task::create($validated);
    
            Log::info('Task created successfully.', ['task_id' => $task->id]);
    
            return response()->json(['status' => 'success', 'data' => $task], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Task validation failed.', ['errors' => $e->errors()]);
            return response()->json(['error' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Error creating task: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to create task'], 500);
        }
    }
    

    /**
     * Update a task.
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            // Retrieve the task manually
            $task = Task::withTrashed()->find($id);
    
            if (!$task) {
                return response()->json(['error' => 'Task not found'], 404);
            }
    
            // Log before update
            Log::info('Task before update:', ['task' => $task]);
    
            // Validate incoming request
            $validated = $request->validate([
                'title'       => 'sometimes|required|string|max:255',
                'description' => 'nullable|string',
                'priority'    => 'in:low,medium,high',
                'due_date'    => 'nullable|date',
            ]);
    
            // Update the task
            $task->update($validated);
    
            // Fetch the latest updated task
            $updatedTask = Task::withTrashed()->where('id', $task->id)->first();
    
            // Log after update
            Log::info('Task after update:', ['task' => $updatedTask]);
    
            return response()->json([
                'status'  => 'success',
                'message' => 'Task updated successfully',
                'data'    => $updatedTask ? $updatedTask->toArray() : null,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Task update failed.', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to update task'], 500);
        }
    }
    

    

    /**
     * Soft delete a task.
     */
    public function destroy(Task $task): JsonResponse
    {
        try {
            // Soft delete the task
            $task->delete();

            // Log the deletion
            Log::info('Task deleted successfully.', ['task_id' => $task->id]);

            // Return success response
            return response()->json(['message' => 'Task deleted successfully']);
        } catch (\Exception $e) {
            // Log the error and return an error response
            Log::error('Failed to delete task.', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to delete task'], 500);
        }
    }
    

    /**
     * Retrieve soft-deleted tasks.
     */
    public function trashed(): JsonResponse
    {
        try {
            Log::info('Fetching soft-deleted tasks.');

            $tasks = Task::onlyTrashed()->orderBy('deleted_at', 'desc')->paginate(10);

            return response()->json(['status' => 'success', 'data' => $tasks]);
        } catch (\Exception $e) {
            Log::error('Error fetching soft-deleted tasks: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch soft-deleted tasks'], 500);
        }
    }

    /**
     * Restore a soft-deleted task.
     */
    public function restore($id): JsonResponse
    {
        try {
            Log::info('Restoring soft-deleted task.', ['task_id' => $id]);

            $task = Task::onlyTrashed()->findOrFail($id);
            $task->restore();

            return response()->json(['message' => 'Task restored successfully']);
        } catch (ModelNotFoundException $e) {
            Log::warning('Task not found for restoration.', ['task_id' => $id]);
            return response()->json(['error' => 'Task not found'], 404);
        } catch (\Exception $e) {
            Log::error('Error restoring task: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to restore task'], 500);
        }
    }
}
