<?php

declare(strict_types=1);

namespace App\Repository;

use App\Task;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use TaskManager\App\Contract\Entity;
use TaskManager\App\Contract\Repository;
use TaskManager\App\Entity\TaskEntity;
use TaskManager\App\Exceptions\InconsistentDataException;

class LaravelTaskRepository implements Repository
{
    /**
     * @param Task $task
     * @return TaskEntity
     * @throws InconsistentDataException
     */
    private function modelToEntity(Task $task): TaskEntity
    {
        return new TaskEntity([
            TaskEntity::PROPERTY_ID => $task[TaskEntity::PROPERTY_ID] ?? 0,
            TaskEntity::PROPERTY_TITLE => $task[TaskEntity::PROPERTY_TITLE] ?? '',
            TaskEntity::PROPERTY_DESCRIPTION => $task[TaskEntity::PROPERTY_DESCRIPTION] ?? '',
            TaskEntity::PROPERTY_STATUS => $task[TaskEntity::PROPERTY_STATUS] ?? TaskEntity::STATUS_ACTIVE,
        ]);
    }

    /**
     * @param Collection $tasks
     * @return array
     * @throws InconsistentDataException
     */
    private function modelsToEntities(Collection $tasks): array
    {
        $entities = [];
        /** @var Task $task */
        foreach ($tasks as $task) {
            $entities[] = $this->modelToEntity($task);
        }
        return $entities;
    }

    private function getQuery(array $filters = []): Builder
    {
        $query = Task::query();
        foreach ($filters as $key => $val) {
            $query->where($key, $val);
        }
        return $query;
    }

    private function findModels(array $filters = []): Collection
    {
        $query = $this->getQuery($filters);
        return $query->get();
    }

    /**
     * @param array $data
     * @return Entity
     * @throws InconsistentDataException
     */
    public function create(array $data = []): Entity
    {
        if (array_key_exists(TaskEntity::PROPERTY_ID, $data)) {
            $id = (int) $data[TaskEntity::PROPERTY_ID];
            if ($id) {
                throw new InconsistentDataException('Entity can not be created with unique identifier');
            }

            unset($data[TaskEntity::PROPERTY_ID]);
        }
        /** @var Task $task */
        $task = $this->getQuery()->create([
            TaskEntity::PROPERTY_TITLE => $data[TaskEntity::PROPERTY_TITLE] ?? '',
            TaskEntity::PROPERTY_DESCRIPTION => $data[TaskEntity::PROPERTY_DESCRIPTION] ?? '',
            TaskEntity::PROPERTY_STATUS => $data[TaskEntity::PROPERTY_STATUS] ?? TaskEntity::STATUS_ACTIVE,
        ]);
        return $this->modelToEntity($task);
    }

    public function delete(array $filters = []): bool
    {
        return (bool) $this->getQuery($filters)->delete();
    }

    /**
     * @param array $filters
     * @return array
     * @throws InconsistentDataException
     */
    public function filter(array $filters = []): array
    {
        $tasks = $this->findModels($filters);
        return $this->modelsToEntities($tasks);
    }

    /**
     * @param array $filters
     * @return Entity|null
     * @throws InconsistentDataException
     */
    public function first(array $filters = []): ?Entity
    {
        /** @var Task $task */
        $task = $this->getQuery($filters)->first();
        return $this->modelToEntity($task);
    }

    /**
     * @param array $filters
     * @param array $data
     * @return array
     * @throws InconsistentDataException
     */
    public function update(array $filters, array $data): array
    {
        $tasks = $this->getQuery($filters)->get();
        foreach ($tasks as $task) {
            $task->update($data);
        }
        return $this->modelsToEntities($tasks);
    }
}
