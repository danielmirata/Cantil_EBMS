<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('resident_document_requests', function (Blueprint $table) {
            $table->id();
            $table->string('request_id')->unique()->default(DB::raw('CONCAT("REQ-", DATE_FORMAT(NOW(), "%Y%m%d"), "-", LPAD(FLOOR(RAND() * 10000), 4, "0"))'));
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->string('document_type');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('contact_number');
            $table->string('email')->nullable();
            $table->string('address');
            $table->date('date_needed');
            $table->string('purpose');
            $table->text('notes')->nullable();
            $table->string('id_type');
            $table->string('id_photo');
            $table->boolean('declaration')->default(true);
            $table->string('status')->default('pending');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resident_document_requests');
    }
};
