<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComicTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       
        Schema::create('comics', function(Blueprint $table) {
            $table->increments('id');
            $table->string('title', 255);
            $table->string('cover_url', 255);
            $table->string('thumb_url', 255);
            $table->integer('comic_categories_id');
            $table->text('description');
            $table->integer('author_id')->unsigned();
            $table->integer('bundling_id');
            $table->string('comic_tags',255);
            $table->integer('custom_prop');
            $table->boolean('published');
            $table->timestamps();
        });

        Schema::create('bundling',function(Blueprint $table){
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('prop_relation',function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('comic_id');
            $table->integer('comic_properties_id');
            $table->timestamps();
        });

        Schema::create('comic_proerties',function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('prop_name',255)->unique();
            $table->timestamps();
        });

        Schema::create('comic_categories',function(Blueprint $table){
            $table->increments('id');
            $table->string('category',255)->unique();
            $table->timestamps();
        });

      

        Schema::create('comic_tags',function(Blueprint $table){
            $table->increments('id');
            $table->string('tag',255);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::drop('comics');
        Schema::drop('prop_relation');
        Schema::drop('comic_proerties');
        Schema::drop('comic_categories');
        Schema::drop('comic_tags');
        Schema::drop('bundling');
    }
}
