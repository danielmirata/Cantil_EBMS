<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('map_locations', function (Blueprint $table) {
            // Check if the foreign key exists before dropping it
            if (Schema::hasColumn('map_locations', 'resident_id')) {
                $table->dropForeign(['resident_id']);
                $table->dropColumn('resident_id');
            }

            // Add the new column and foreign key if it doesn't exist
            if (!Schema::hasColumn('map_locations', 'household_id')) {
                $table->unsignedBigInteger('household_id')->nullable();
                $table->foreign('household_id')
                      ->references('id')
                      ->on('households')
                      ->onDelete('set null');
            }
        });
    }

    public function down()
    {
        Schema::table('map_locations', function (Blueprint $table) {
            // Check if the foreign key exists before dropping it
            if (Schema::hasColumn('map_locations', 'household_id')) {
                $table->dropForeign(['household_id']);
                $table->dropColumn('household_id');
            }

            // Add back the old column and foreign key if it doesn't exist
            if (!Schema::hasColumn('map_locations', 'resident_id')) {
                $table->unsignedBigInteger('resident_id')->nullable();
                $table->foreign('resident_id')
                      ->references('id')
                      ->on('residents_information')
                      ->onDelete('set null');
            }
        });
    }
}; 