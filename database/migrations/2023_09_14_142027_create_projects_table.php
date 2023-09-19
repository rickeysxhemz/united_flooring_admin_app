<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->unsignedbigInteger('user_id')->nullable();
            $table->string('name');
            $table->enum('priority', ['low', 'medium', 'high'])->nullable();
            $table->enum('status', ['in_progress', 'completed', 'cancel'])->nullable();
            $table->text('description')->nullable();
            $table->text('logo')->nullable();
            $table->timestamps();
            $table->timestamp('ended_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects');
    }
}
