<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->string('fullname');
            $table->string('contact_number');
            $table->string('email')->nullable();
            $table->string('purok');
            $table->string('complainee_name');
            $table->string('complainee_address');
            $table->string('complaint_type');
            $table->date('incident_date');
            $table->time('incident_time');
            $table->string('incident_location');
            $table->text('complaint_description');
            $table->string('evidence_photo')->nullable();
            $table->boolean('declaration');
            $table->string('status')->default('Pending');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
