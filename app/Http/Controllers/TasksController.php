<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Http\Requests\TaskUpdateRequest;
use App\Http\Requests\TaskUpdateStatusRequest;
use App\Http\Resources\TaskResource;
use App\Models\Tasks;
use Carbon\Carbon;
use Illuminate\Console\View\Components\Task;
use Illuminate\Http\Request;

class TasksController extends Controller
{
    private $tasks;

    public function __construct(Tasks $tasks)
    {
        $this->tasks = $tasks;
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return
            TaskResource::collection(
                Tasks::with('attachments')
                    ->orderBy('created_at', 'desc')
                    ->paginate(10)
            );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskRequest $request)
    {
        $files = $request->file('attachments');

        $task = Tasks::create([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => $request->user_id,
        ]);

        if ($files) {
            $filesUpload = [];
            foreach ($files as $file) {
                $path = $file->store('files', 'public');
                $filesUpload[] =   ["path" => $path, "user_id" => intval($task->user_id)];
            }
            $task->attachments()->createMany($filesUpload);
        }
        $task->load('attachments');

        return response()->json([new TaskResource($task)], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $task = Tasks::find($id);
        if (!$task) {
            return response()->json(["error" => '404 Not Found'], 404);
        }
        return new TaskResource($task);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $task = Tasks::find($id);
        if (!$task) {
            return response()->json(["error" => '404 Not Found'], 404);
        }

        $task->update($request->all());
        return response()->json([new TaskResource($task)], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $task = Tasks::find($id);
        if (!$task) {
            return response()->json(["error" => '404 Not Found'], 404);
        }
        $task->delete();
        return response()->json([], 204);
    }

    public function updateStatus(TaskUpdateStatusRequest $request, $id)
    {
        $task = Tasks::find($id);
        if (!$task) {
            return response()->json(["error" => '404 Not Found'], 404);
        }

        $task->status = $request->status;

        if ($request->status == 'done') {
            $currentDate = Carbon::now();
            $task->date_done =  $currentDate;
        }

        $task->save();
        return response()->json(new TaskResource($task), 200);
    }

    public function getAllTasksDeleted()
    {
        return
            TaskResource::collection(
                Tasks::onlyTrashed()
                    ->with('attachments')
                    ->orderBy('created_at', 'desc')
                    ->paginate(10)
            );
    }

    public function taskDeleted($id)
    {
        return Tasks::onlyTrashed()->find($id);
    }

    public function restoreTask($id)
    {
        $task = Tasks::onlyTrashed()->find($id);
        if (!$task) {
            return response()->json(["error" => '404 Not Found'], 404);
        }
        $task->restore();
        return response()->json([new TaskResource($task)], 200);
    }
}
