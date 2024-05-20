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
            $table->string("appName")->default('gestHub');
            $table->string("companyName")->default('Company Name');
            $table->string('email')->default('exemple@gmail.com');
            $table->string('phone')->default('06 55 22 33 66');
            $table->string('facebook')->default('www.facebook.com');
            $table->string('instagram')->default('www.instagram.com');
            $table->string('twitter')->default('www.twitter.com');
            $table->string('youtube')->default('www.youtube.com');
            $table->string('linkedin')->default('www.linkedin.com');
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
