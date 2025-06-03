<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->string('name');            $table->decimal('amount', 10, 2);
            $table->decimal('allocated', 10, 2)->default(0);
            $table->decimal('remaining_amount', 10, 2)->virtualAs('amount - allocated');
            $table->string('period');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('budgets');
    }
}; 