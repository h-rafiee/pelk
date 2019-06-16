<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('authors',function(Blueprint $table){
            $table->increments('id');
            $table->enum('type',['writer','translator'])->default('writer');
            $table->string('name');
            $table->text('description')->nullable();
            $table->longText('text')->nullable();
            $table->string('image')->nullable();
            $table->text('other_images')->nullable();
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
        Schema::drop('authors');
    }
}
