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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('client_name')->nullable();
            $table->string('client_phone')->nullable();
            $table->string('service_number')->nullable();
            $table->string('address')->nullable();
            $table->integer('compound_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('central_id')->nullable();
            $table->integer('comment_id')->nullable();
            $table->timestamp('task_date')->nullable();
            $table->timestamp('end_date')->nullable();
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
        Schema::dropIfExists('tasks');
    }
};
