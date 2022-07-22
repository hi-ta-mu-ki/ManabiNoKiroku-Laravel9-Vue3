<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateESettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('e_settings', function (Blueprint $table) {
          $table->Increments('id');
          $table->unsignedInteger('e_classes_id');
          $table->foreign('e_classes_id')->references('id')->on('e_classes')->onDelete('cascade');
          $table->integer('no');
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
        Schema::dropIfExists('e_settings');
    }
}
