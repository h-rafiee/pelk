<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders',function(Blueprint $table){
            $table->increments('id');
            $table->string('code')->unique();
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')
                ->references('id')->on('users');
            $table->string('price')->default(0);
            $table->text('params')->nullable();
            $table->boolean('pay')->default(0);
            $table->boolean('valid')->default(1);
            $table->timestamps();
        });

        Schema::create('_order_book',function(Blueprint $table){
            $table->increments('id');
            $table->integer('order_id')->unsigned();
            $table->foreign('order_id')
                ->references('id')->on('orders');
            $table->integer('book_id')->unsigned();
            $table->foreign('book_id')
                ->references('id')->on('books');
            $table->string('price')->default(0);
            $table->timestamps();
        });

        Schema::create('_order_magazine',function(Blueprint $table){
            $table->increments('id');
            $table->integer('order_id')->unsigned();
            $table->foreign('order_id')
                ->references('id')->on('orders');
            $table->integer('magazine_id')->unsigned();
            $table->foreign('magazine_id')
                ->references('id')->on('magazines');
            $table->string('price')->default(0);
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
        Schema::drop('orders');
        Schema::drop('order_book');
        Schema::drop('order_magazine');
    }
}
