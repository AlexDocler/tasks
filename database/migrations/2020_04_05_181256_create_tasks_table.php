<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use TaskManager\App\Entity\TaskEntity;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string(TaskEntity::PROPERTY_TITLE)->default('')->index();
            $table->text(TaskEntity::PROPERTY_DESCRIPTION)->default('');
            $table->string(TaskEntity::PROPERTY_STATUS, 20)->default(TaskEntity::STATUS_ACTIVE);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
