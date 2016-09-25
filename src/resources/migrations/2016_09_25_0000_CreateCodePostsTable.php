<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCodeCategoriesTable extends Migration
{
    public function up()
    {
        Schema::create('codepress_posts', function (Blueprint $table) {
            $table->increments('id');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('codepress_categories');
    }
}