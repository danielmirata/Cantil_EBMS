<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('officials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('position_id')->constrained()->onDelete('cascade');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('suffix')->nullable();
            $table->date('date_of_birth');
            $table->string('place_of_birth');
            $table->enum('gender', ['Male', 'Female']);
            $table->enum('civil_status', ['Single', 'Married', 'Widowed', 'Divorced']);
            $table->string('nationality');
            $table->string('religion');
            $table->string('email')->nullable();
            $table->string('contact_number');
            $table->string('house_number');
            $table->string('street');
            $table->string('barangay');
            $table->string('municipality');
            $table->string('zip');
            $table->string('father_name');
            $table->string('mother_name');
            $table->string('guardian_name');
            $table->string('guardian_contact');
            $table->string('guardian_relation');
            $table->date('term_start');
            $table->date('term_end');
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->string('profile_picture')->nullable();
            $table->timestamp('archived_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('officials');
    }
}; 