<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books',function(Blueprint $table){
            $table->increments('id');
            $table->integer('publication_id')->unsigned();
            $table->foreign('publication_id')
                ->references('id')->on('publications')
                ->onDelete('cascade');
            $table->integer('category_id')->unsigned();
            $table->foreign('category_id')
                ->references('id')->on('categories')
                ->onDelete('cascade');
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('price')->default('0');
            $table->string('file');
            $table->text('description')->nullable();
            $table->longText('text')->nullable();
            $table->string('image')->nullable();
            $table->text('other_images')->nullable();
            $table->string('isbn')->nullable();
            $table->string('publish_date')->nullable();
            $table->text('params')->nullable();
            $table->boolean('active')->default(1);
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
        Schema::drop('books');
    }
}
