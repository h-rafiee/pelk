<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagMagazineTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("_tag_magazine",function(Blueprint $table){
            $table->integer('tag_id')->unsigned();
            $table->foreign('tag_id')
                ->references('id')->on('tags')
                ->onDelete('cascade');
            $table->integer('magazine_id')->unsigned();
            $table->foreign('magazine_id')
                ->references('id')->on('magazines')
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
        Schema::drop("_tag_magazine");

    }
}
