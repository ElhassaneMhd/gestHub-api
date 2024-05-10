<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('offer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('intern_id')->nullable()->constrained()->nullOnDelete();
            $table->date('startDate');
            $table->date('endDate');
            $table->string('status')->default('Pending');
            $table->string('isRead')->default('false');
            $table->text('motivationLetter')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });           
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
