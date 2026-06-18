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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('nama_properti');
            $table->text('alamat');
            $table->decimal('luas', 10, 2)->comment('Luas dalam meter persegi');
            $table->decimal('harga_sewa', 15, 2)->comment('Harga sewa Rp/meter/bulan');
            $table->enum('status', ['tersedia', 'disewa'])->default('tersedia');
            $table->text('deskripsi')->nullable();
            $table->string('link_google_maps')->nullable();
            $table->string('thumbnail')->nullable()->comment('Path gambar utama');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
