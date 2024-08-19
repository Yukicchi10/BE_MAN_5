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
        Schema::create('tugas_murids', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_tugas');
            $table->unsignedBigInteger('id_mahasiswa');
            $table->string('file');
            $table->string('filename');
            $table->string('nilai');
            $table->timestamps();
        });

        Schema::table('tugas_murids', function (Blueprint $table) {
            $table->foreign('id_tugas')->references('id')->on('tugas');
            $table->foreign('id_mahasiswa')->references('id')->on('mahasiswas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tugas_murids');
    }
};
