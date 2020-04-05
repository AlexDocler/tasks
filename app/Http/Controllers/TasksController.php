<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use TaskManager\App\Entity\TaskEntity;
use TaskManager\App\Services\TaskManagerService;

class TasksController extends Controller
{
    public function index(TaskManagerService $taskManagerService): JsonResponse
    {
        $tasks = [];
        /** @var TaskEntity $task */
        foreach ($taskManagerService->find() as $task) {
            $tasks[] = $task->getProperties();
        }

        return response()->json($tasks);
    }

    public function store(TaskManagerService $taskManagerService, Request $request): JsonResponse
    {
        /** @var TaskEntity $task */
        $task = $taskManagerService->create($request->all());
        return response()->json($task->getProperties());
    }

    public function update(int $id, TaskManagerService $taskManagerService, Request $request): JsonResponse
    {
        $tasks = [];
        /** @var TaskEntity $task */
        foreach ($taskManagerService->update($id, $request->all()) as $task) {
            $tasks[] = $task->getProperties();
        }

        return response()->json($tasks);
    }

    public function show(int $id, TaskManagerService $taskManagerService): JsonResponse
    {
        /** @var TaskEntity $task */
        $task = $taskManagerService->first([TaskEntity::PROPERTY_ID => $id]);
        return response()->json($task->getProperties());
    }

    public function destroy(int $id, TaskManagerService $taskManagerService): JsonResponse
    {
        $taskManagerService->delete($id);
        return response()->json([]);
    }
}
