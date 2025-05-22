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
            $table->string('type');
            $table->text('coordinates');
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('household_id')->nullable()->constrained()->onDelete('set null');
            $table->string('color')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('map_locations');
    }
}; 