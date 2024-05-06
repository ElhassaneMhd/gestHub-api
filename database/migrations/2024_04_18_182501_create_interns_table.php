<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {        
        Schema::disableForeignKeyConstraints();
        Schema::create('interns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('profile_id')->constrained()->cascadeOnDelete();
            $table->string('projectLink')->nullable();
            $table->string('academicLevel');
            $table->string('establishment');    
            $table->string('gender')->nullable();    
            $table->string('specialty')->nullable();    
            $table->date('startDate');    
            $table->date('endDate');  
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interns');
    }
};
