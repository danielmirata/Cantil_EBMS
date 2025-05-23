<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('records', function (Blueprint $table) {
            $table->id();
            $table->string('resident_name');
            $table->string('request_type');
            $table->text('purpose');
            $table->string('status');
            $table->string('processed_by');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('records');
    }
}; 