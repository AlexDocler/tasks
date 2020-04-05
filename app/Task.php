<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use TaskManager\App\Entity\TaskEntity;

class Task extends Model
{
    protected $fillable = [TaskEntity::PROPERTY_TITLE, TaskEntity::PROPERTY_DESCRIPTION, TaskEntity::PROPERTY_STATUS];
}
