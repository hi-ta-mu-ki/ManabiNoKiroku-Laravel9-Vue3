<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExercisesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exercises', function (Blueprint $table) {
          $table->bigIncrements('id');
          $table->unsignedInteger('e_groups_id');
          $table->foreign('e_groups_id')->references('id')->on('e_groups')->onDelete('cascade');
          $table->integer('no');
          $table->integer('q_no');
          $table->string('quest', 400);
          $table->string('ans1', 256);
          $table->string('ans2', 256);
          $table->string('ans3', 256);
          $table->string('ans4', 256);
          $table->integer('ans');
          $table->string('exp1', 256);
          $table->string('exp2', 256);
          $table->string('exp3', 256);
          $table->string('exp4', 256);
          $table->string('exp0', 256);
          $table->timestamps();        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exercises');
    }
}
