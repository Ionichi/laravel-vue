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
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary()->nullable(false);
            $table->string('username', 50)->unique();
            $table->string('fullname', 200);
            $table->enum('gender', ['L', 'P']);
            $table->string('email', 200);
            $table->string('password', 255);
            $table->enum('role', ['A', 'T', 'M']);
            $table->enum('status', ['A', 'N']);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
