<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nibbles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gourmet_id')->constrained('gourmets')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('delight_id')->constrained('delights')->onDelete('cascade')->onUpdate('cascade');
            $table->text('content');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nibbles');
    }
};
