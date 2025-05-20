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
        Schema::create('residents_information', function (Blueprint $table) {
            $table->id();
            
            // Status Information
            $table->string('profile_picture')->nullable();
            $table->enum('residency_status', ['Permanent', 'Temporary']);
            $table->enum('voters', ['Yes', 'No']);
            $table->enum('pwd', ['Yes', 'No']);
            $table->string('pwd_type')->nullable();
            $table->enum('single_parent', ['Yes', 'No']);

            // Personal Information
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('suffix')->nullable();
            $table->date('date_of_birth');
            $table->string('place_of_birth');
            $table->enum('gender', ['Male', 'Female']);
            $table->enum('civil_status', ['Single', 'Married', 'Widowed', 'Divorced']);
            $table->string('nationality')->default('Filipino');
            $table->string('religion');

            // Contact Information
            $table->string('email')->nullable();
            $table->string('contact_number');

            // Address Information
            $table->string('house_number');
            $table->string('street');
            $table->string('barangay')->default('Cantil-E');
            $table->string('municipality');
            $table->string('zip');

            // Family Information
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('guardian_name');
            $table->string('guardian_contact');
            $table->enum('guardian_relation', ['Parent', 'Spouse', 'Sibling', 'Relative', 'Friend']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('residents_information');
    }
};
