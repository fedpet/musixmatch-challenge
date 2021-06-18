<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Init extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        });

        Schema::create('stations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        Schema::create('segments', function (Blueprint $table) {
            $table->unsignedBigInteger('station_id_a');
            $table->unsignedBigInteger('station_id_b');
            $table->foreign('station_id_a')->references('id')->on('stations')->cascadeOnDelete();
            $table->foreign('station_id_b')->references('id')->on('stations')->cascadeOnDelete();
            $table->unique(['station_id_a', 'station_id_b']);
            $table->unsignedDecimal('cost', 8, 2);
        });

        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->constrained()->cascadeOnDelete();
            $table->dateTime('date_enter');
            $table->dateTime('date_exit')->nullable()->default(null);
            $table->unsignedDecimal('cost', 8, 2)->nullable()->default(null);
            $table->unsignedBigInteger('station_id_enter');
            $table->unsignedBigInteger('station_id_exit')->nullable()->default(null);
            $table->foreign('station_id_enter')->references('id')->on('stations')->cascadeOnDelete();
            $table->foreign('station_id_exit')->references('id')->on('stations')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('logs');
        Schema::dropIfExists('segments');
        Schema::dropIfExists('stations');
        Schema::dropIfExists('devices');
        Schema::dropIfExists('users');
    }
}
