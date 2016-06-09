<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TableSetup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feeds', function (Blueprint $table) {
            $table->increments('id');
            $table->string('feed_url');
            $table->string('source');
            $table->string('icon_name');
            $table->timestamps();
        });

        Schema::create('articles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('feed_id');
            $table->longText('article_description')->nullable();
            $table->string('article_title')->nullable();
            $table->string('article_img')->nullable();
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
        Schema::drop('feeds');
        Schema::drop('articles');
    }
}
