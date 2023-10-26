<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->unsignedInteger('label_id')->nullable();
            $table->integer('ver_index');
            $table->string('name')->unique();
            $table->string('version')->nullable();
            $table->date('start_date')->index();
            $table->date('end_date')->index()->nullable();
            $table->date('due_date')->index();
            $table->unsignedInteger('progress')->nullable();
            $table->decimal('points', 5, 2)->default(0);
            $table->longtext('initial_condition')->nullable();
            $table->longtext('procedure')->nullable();
            $table->longtext('expected_result')->nullable();
            $table->timestamps();

            //Relationship
            $table->foreign('label_id')->references('id')->on('labels');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
