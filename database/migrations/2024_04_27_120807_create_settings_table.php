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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string("appName")->nullable()->default('GestHub');
            $table->string("companyName")->nullable()->default('');
            $table->string('email')->nullable()->default('exemple@gmail.com');
            $table->string('phone')->nullable()->default('06 55 22 33 66');
            $table->string('facebook')->nullable()->default('www.facebook.com');
            $table->string('instagram')->nullable()->default('www.instagram.com');
            $table->string('twitter')->nullable()->default('www.twitter.com');
            $table->string('youtube')->nullable()->default('www.youtube.com');
            $table->string('linkedin')->nullable()->default('www.linkedin.com');
            $table->text('maps')->nullable();
            $table->string('location')->nullable();
            $table->text('aboutDescription')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
