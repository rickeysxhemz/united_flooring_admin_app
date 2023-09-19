<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Comments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('project_id')->nullable();
        $table->unsignedBigInteger('sender_id')->nullable();
        $table->unsignedBigInteger('receiver_id')->nullable();
        $table->text('comment')->nullable();
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
        Schema::dropIfExists(comments);
    }
}
