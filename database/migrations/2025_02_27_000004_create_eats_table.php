<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('eats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gourmet_id')->constrained('gourmets')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('delight_id')->constrained('delights')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eats');
    }
};
