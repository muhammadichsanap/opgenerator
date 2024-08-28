<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRemindersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reminders', function (Blueprint $table) {
            $table->id(); // BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT
            $table->time('waktu_mulai'); // TIME NOT NULL
            $table->time('waktu_selesai'); // TIME NOT NULL
            $table->time('durasi'); // TIME NOT NULL
            $table->string('no_pc')->default('')->collate('utf8mb4_unicode_ci'); // VARCHAR(255) NOT NULL DEFAULT ''
            $table->string('paket')->collate('utf8mb4_unicode_ci'); // VARCHAR(255) NOT NULL
            $table->string('kelas_pc')->collate('utf8mb4_unicode_ci'); // VARCHAR(255) NOT NULL
            $table->integer('harga'); // INT(10) NOT NULL
            $table->string('tambahan')->nullable()->collate('utf8mb4_unicode_ci'); // VARCHAR(255) NULL DEFAULT NULL
            $table->integer('belum_bayar')->default(0); // INT(10) NULL DEFAULT '0'
            $table->integer('dompet_digital')->default(0); // INT(10) NULL DEFAULT '0'
            $table->integer('total')->default(0); // INT(10) NOT NULL DEFAULT '0'
            $table->timestamps(); // created_at & updated_at

            $table->primary('id'); // PRIMARY KEY (`id`) USING BTREE
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reminders');
    }
}