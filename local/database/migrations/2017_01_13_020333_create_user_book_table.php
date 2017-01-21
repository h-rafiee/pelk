<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserBookTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('_user_book',function(Blueprint $table){

            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')
                ->references('id')->on('users');
            $table->integer('book_id')->unsigned();
            $table->foreign('book_id')
                ->references('id')->on('books');
            $table->boolean('demo')->default(0);
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
        Schema::drop('_user_book');
    }
}
