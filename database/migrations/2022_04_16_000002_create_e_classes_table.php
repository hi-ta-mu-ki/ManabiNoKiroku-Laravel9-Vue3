<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('e_classes', function (Blueprint $table) {
          $table->Increments('id');
          $table->unsignedInteger('e_groups_id');
          $table->foreign('e_groups_id')->references('id')->on('e_groups')->onDelete('cascade');
          $table->string('name');
          $table->string('pass_code')->nullable();
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
        Schema::dropIfExists('e_classes');
    }
}
