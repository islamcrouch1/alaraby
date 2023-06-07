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
        Schema::table('tasks', function (Blueprint $table) {
            $table->string('status')->default('inactive');
            $table->string('db')->nullable();
            $table->string('box')->nullable();
            $table->string('cab')->nullable();
            $table->string('cable_type')->nullable();
            $table->string('cable_length')->nullable();
            $table->string('type')->nullable();
            $table->string('connectors')->nullable();
            $table->string('face_split')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tasks', function (Blueprint $table) {
            Schema::dropIfExists('status');
            Schema::dropIfExists('db');
            Schema::dropIfExists('box');
            Schema::dropIfExists('cab');
            Schema::dropIfExists('cable_type');
            Schema::dropIfExists('cable_length');
            Schema::dropIfExists('type');
            Schema::dropIfExists('connectors');
            Schema::dropIfExists('face_split');
        });
    }
};
