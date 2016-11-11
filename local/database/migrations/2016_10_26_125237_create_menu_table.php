<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus',function(Blueprint $table){

            $table->increments('id');
            $table->string('title');
            $table->tinyInteger('position')->unsigned()->default(0);
            $table->timestamps();
        });

        Schema::create("_menu_category",function(Blueprint $table){
            $table->integer('menu_id')->unsigned();
            $table->foreign('menu_id')
                ->references('id')->on('menus')
                ->onDelete('cascade');
            $table->integer('category_id')->unsigned();
            $table->foreign('category_id')
                ->references('id')->on('categories')
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
        Schema::drop('menus');
        Schema::drop('_menu_category');
    }
}
