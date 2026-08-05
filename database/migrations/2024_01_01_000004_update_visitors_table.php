<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('visitors', function (Blueprint $table) {
            $table->dropColumn('address');
            $table->dropColumn('phone');
            $table->string('rw', 3)->nullable()->after('name');
            $table->string('rt', 3)->nullable()->after('rw');
            $table->string('alamat')->nullable()->after('rt');
            $table->unsignedTinyInteger('umur')->nullable()->after('alamat');
            $table->string('desa')->nullable()->after('umur');
        });
    }

    public function down(): void
    {
        Schema::table('visitors', function (Blueprint $table) {
            $table->dropColumn(['rw', 'rt', 'alamat', 'umur', 'desa']);
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
        });
    }
};
