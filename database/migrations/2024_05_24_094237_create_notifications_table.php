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
        Schema::disableForeignKeyConstraints();
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('initiator')->nullable();
            $table->foreign('initiator')->nullable()->references('id')->on('profiles');
            $table->unsignedBigInteger('receiver')->nullable();
            $table->foreign('receiver')->references('id')->on('profiles');
            $table->string('activity');
            $table->string('object')->nullable();;
            $table->string('action');
            $table->enum('isRead',['false','true'])->default('false');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
