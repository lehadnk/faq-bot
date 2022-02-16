<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Initial extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('channels', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('discord_server_id');
            $table->string('discord_server_name');
            $table->string('discord_channel_id');
            $table->string('discord_channel_name');
        });

        Schema::create('revisions', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('channel_id');

            $table->foreign(['channel_id'])->references('id')->on('channels')->onDelete('cascade');
        });

        Schema::create('questions', function(Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('revision_id');
            $table->text('value');
            $table->integer('order')->default(0);

            $table->foreign(['revision_id'])->references('id')->on('revisions')->onDelete('cascade');
        });

        Schema::create('messages', function(Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('question_id');
            $table->text('content');
            $table->integer('order')->default(0);

            $table->foreign(['question_id'])->references('id')->on('questions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('messages');
        Schema::drop('questions');
        Schema::drop('revisions');
        Schema::drop('channels');
    }
}
