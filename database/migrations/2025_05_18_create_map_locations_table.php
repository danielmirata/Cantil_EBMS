<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('map_locations', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // rectangle, polygon, marker, or purok
            $table->json('coordinates'); // Store coordinates as JSON
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('household_id')->nullable();
            $table->string('color')->nullable();
            $table->timestamps();
            
            $table->foreign('household_id')
                  ->references('id')
                  ->on('households')
                  ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('map_locations');
    }
}; 