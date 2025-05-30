<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('map_locations', function (Blueprint $table) {
            $table->unsignedBigInteger('project_id')->nullable()->after('household_id');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('map_locations', function (Blueprint $table) {
            $table->dropForeign(['project_id']);
            $table->dropColumn('project_id');
        });
    }
}; 