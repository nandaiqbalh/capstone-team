<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    //     Schema::create('jadwal_sidang_t_a_s', function (Blueprint $table) {
    //         $table->id();
    // $table->integer('id_kelompok');
    // $table->integer('siklus_id');
    // $table->date('tanggal');
    // $table->time('waktu');
    // $table->unsignedBigInteger('ruangan_id');
    // $table->timestamps();

    // $table->foreign('id_kelompok')->references('id')->on('kelompok');
    // $table->foreign('siklus_id')->references('id')->on('siklus');
    // $table->foreign('ruangan_id')->references('id')->on('ruang_sidangs');
    //     });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jadwal_sidang_t_a_s');
    }
};
