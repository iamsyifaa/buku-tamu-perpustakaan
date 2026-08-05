<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visitor_id')->constrained('visitors')->onDelete('cascade');
            $table->boolean('baca_buku')->default(false);
            $table->boolean('pinjam_buku')->default(false);
            $table->boolean('belajar_komputer')->default(false);
            $table->timestamp('visited_at')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};
