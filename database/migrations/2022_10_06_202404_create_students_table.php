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
        Schema::create('students', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('polo_id');
            $table->foreign('polo_id')->references('id')->on('polos');

            $table->string('photo',100);
            $table->string('local',10);
            $table->string('name',100);
            $table->string('rg',30);
            $table->string('cpf',14);
            $table->string('filiation',100)->nullable();
            $table->string('email',100);
            $table->string('phone',16)->nullable();
            $table->string('cell',16);
            $table->set('sexo',['M','F'])->default('M');
            $table->string('zip_code',9);
            $table->string('address',250);
            $table->string('district',50);
            $table->string('city',50);
            $table->char('state',2);

            $table->string('conclusion',50)->nullable();
            $table->date('date_entry')->nullable();
            $table->date('date_exit')->nullable();
            $table->integer('year')->nullable();
            $table->string('registration',50)->nullable();
            $table->string('igreja',100)->nullable();
            $table->date('birth_date')->nullable();
            $table->string('birthplace',100)->nullable();
            $table->string('country',100)->nullable();
            $table->string('naturalness', 100)->nullable();
            $table->string('marital_status',50)->nullable();
            $table->string('login',50);
            $table->string('password');
            $table->set('active',['ativo','inativo'])->default('inativo');
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
        Schema::dropIfExists('students');
    }
};
