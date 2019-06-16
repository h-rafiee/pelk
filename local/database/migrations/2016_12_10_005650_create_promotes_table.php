<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePromotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promotes',function(Blueprint $table){

            $table->increments('id');
            $table->string('image');
            $table->string('content')->nullable();
            $table->string('link')->nullable();
            $table->smallInteger('position')->default(0);
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
        Schema::drop('promotes');
    }
}
