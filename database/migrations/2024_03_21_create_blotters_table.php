<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('blotters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('complaint_id')->nullable();
            $table->foreign('complaint_id')->references('id')->on('complaints')->onDelete('set null');
            // Complainant Details
            $table->string('blotters_id')->unique()->default(DB::raw('CONCAT("REQ-", DATE_FORMAT(NOW(), "%Y%m%d"), "-", LPAD(FLOOR(RAND() * 10000), 4, "0"))'));
            $table->string('first_name');
            $table->string('last_name');
            $table->integer('age');
            $table->string('sex');
            $table->string('civil_status');
            $table->string('complete_address');
            $table->string('contact_number');
            $table->string('occupation');
            $table->string('relationship_to_respondent')->nullable();
            
            // Respondent Details
            $table->string('respondent_first_name');
            $table->string('respondent_last_name');
            $table->integer('respondent_age');
            $table->string('respondent_sex');
            $table->string('respondent_civil_status');
            $table->string('respondent_address');
            $table->string('respondent_contact_number');
            $table->string('respondent_occupation');
            
            // Incident Details
            $table->string('complaint_type');
            $table->date('incident_date');
            $table->timestamp('incident_time');
            $table->string('incident_location');
            $table->text('complaint_description');
            $table->text('what_happened');
            $table->text('who_was_involved');
            $table->text('how_it_happened');
            
            // Witness Details
            $table->string('witness_name')->nullable();
            $table->string('witness_address')->nullable();
            $table->string('witness_contact')->nullable();
            
            // Action Taken by Barangay
            $table->string('initial_action_taken');
            $table->string('handling_officer_name');
            $table->string('handling_officer_position');
            $table->date('mediation_date')->nullable();
            $table->string('action_result');
            $table->text('remarks')->nullable();
            
            // Additional Fields
            $table->string('email')->nullable();
            $table->string('evidence_photo')->nullable();
            $table->boolean('declaration')->default(false);
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('blotters');
    }
}; 

