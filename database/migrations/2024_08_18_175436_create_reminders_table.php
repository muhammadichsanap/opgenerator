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
        Schema::create('reminders', function (Blueprint $table) {
            $table->id();
            $table->time('waktu_mulai'); // Waktu mulai
            $table->time('waktu_selesai'); // Waktu selesai
            $table->time('durasi'); // Durasi
            $table->integer('no_pc'); // Nomor PC
            $table->string('paket'); // Paket
            $table->string('kelas_pc'); // Kelas PC
            $table->integer('harga'); // Harga
            $table->string('tambahan')->nullable(); // Tambahan
            $table->integer('belum_bayar')->default(0); // Belum bayar
            $table->integer('dompet_digital')->default(0); // Dompet digital
            $table->integer('total')->default(0); // Total
            $table->timestamps(); // Timestamps untuk created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reminders');
    }
};