<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->nullable()->constrained()->onDelete('cascade');
            $table->date('date');
            $table->text('description');
            $table->enum('category', [
                'Infrastructure',
                'Health Services',
                'Education',
                'Social Services',
                'Public Safety',
                'Environmental',
                'Administrative',
                'Events and Programs',
                'Utilities',
                'Maintenance',
                'Other'
            ]);
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['Pending', 'Approved', 'Rejected']);
            $table->string('location')->comment('Specific location where expense was incurred');
            $table->string('beneficiary')->nullable()->comment('Who benefited from this expense');
            $table->string('receipt_number')->nullable();
            $table->string('receipt_file_path')->nullable();
            $table->string('budget_allocation')->nullable()->comment('Budget category this expense belongs to');
            $table->decimal('budget_remaining', 10, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('expenses');
    }
}; 