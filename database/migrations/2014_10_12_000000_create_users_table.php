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
            $table->id();

            $table->string('image')->nullable();
            $table->foreignId('current_team_id')->nullable();
            $table->string('profile_photo_path', 2048)->nullable();

            $table->set('nivel',['administrador','student','teacher']);
            $table->string('local',10);

            $table->string('name',100);
            $table->string('phone',20);

            $table->string('zip_code',9)->nullable();
            $table->string('address',250)->nullable();
            $table->string('number',5)->nullable();
            $table->string('district',50)->nullable();
            $table->string('city',50)->nullable();
            $table->string('state',2)->nullable();

            $table->string('email',100)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            $table->string('rg',30)->nullable();
            $table->string('cpf',14)->nullable();
            $table->string('filiation',100)->nullable();
            $table->set('sexo',['M','F'])->default('M');
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
            $table->set('active',['ativo','inativo'])->default('inativo');

            $table->unsignedBigInteger('polo_id');
            $table->foreign('polo_id')->references('id')->on('polos');

            $table->unsignedBigInteger('course_id');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');

            $table->unsignedBigInteger('subject_id');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
            
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
