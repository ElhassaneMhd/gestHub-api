<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('subject');
            $table->fullText(['subject']);
            $table->text('description');
            $table->date('startDate');    
            $table->date('endDate');  
            $table->string('status');
            $table->string('priority');
            $table->foreignId('supervisor_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('intern_id')->nullable()->constrained()->nullOnDelete();
            $table->softDeletes();
            $table->timestampsTz();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
