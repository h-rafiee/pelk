<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserMagazine extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('_user_magazine',function(Blueprint $table){

            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')
                ->references('id')->on('users');
            $table->integer('magazine_id')->unsigned();
            $table->foreign('magazine_id')
                ->references('id')->on('magazines');
            $table->boolean('demo')->default(0);
            $table->string('original_key')->nullable();
            $table->string('key')->nullable();
            $table->string('public')->nullable();
            $table->string('private')->nullable();
            $table->integer('page')->unsigned()->default(0);
            $table->text('params')->nullable();
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
        Schema::drop('_user_magazine');
    }
}
