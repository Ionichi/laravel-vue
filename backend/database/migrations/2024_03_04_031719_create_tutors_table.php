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
        Schema::create('tutor', function (Blueprint $table) {
            $table->id();
            $table->uuid('user_id')->nullable(false);
            $table->string('nama_tutor', 200);
            $table->string('no_wa', 15);
            $table->string('provinsi', 50);
            $table->string('kota', 50);
            $table->string('kode_pos', 10);
            $table->text('alamat_lengkap');
            $table->date('tgl_lahir');
            $table->enum('status', ['A', 'N']);

            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tutors');
    }
};
