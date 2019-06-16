<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthorBookTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("_writer_book",function(Blueprint $table){
            $table->integer('author_id')->unsigned();
            $table->foreign('author_id')
                ->references('id')->on('authors')
                ->onDelete('cascade');
            $table->integer('book_id')->unsigned();
            $table->foreign('book_id')
                ->references('id')->on('books')
                ->onDelete('cascade');
        });

        Schema::create("_translator_book",function(Blueprint $table){
            $table->integer('author_id')->unsigned();
            $table->foreign('author_id')
                ->references('id')->on('authors')
                ->onDelete('cascade');
            $table->integer('book_id')->unsigned();
            $table->foreign('book_id')
                ->references('id')->on('books')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('_writer_book');
        Schema::drop('_translator_book');
    }
}
