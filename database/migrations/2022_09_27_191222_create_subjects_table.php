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
        Schema::create('subjects', function (Blueprint $table) {

            $table->id();
            $table->set('type',['setbal','ead'])->default('setbal');
            $table->string('title',100);
            $table->string('slug',120);
            $table->integer('year');
            $table->integer('semester');
            $table->string('workload',50)->nullable();
            $table->string('period',50)->nullable();
            $table->string('credits',50)->nullable();
            $table->text('description');
            $table->set('quiz',['liberado','bloqueado'])->default('bloqueado');
            $table->set('status',['sim','nao'])->default('nao');
            
            $table->unsignedBigInteger('course_id');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            
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
        Schema::dropIfExists('subjects');
    }
};
