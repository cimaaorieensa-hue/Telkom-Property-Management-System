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
            $table->string('nama_perusahaan')->default('Telkom Property');
            $table->text('alamat_perusahaan')->nullable();
            $table->string('no_whatsapp')->nullable();
            $table->string('no_telepon')->nullable();
            $table->string('email_perusahaan')->nullable();
            $table->string('logo')->nullable();
            $table->text('tentang')->nullable()->comment('Deskripsi singkat perusahaan');
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
