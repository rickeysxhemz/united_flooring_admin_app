<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->string('image');
            $table->timestamps();
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade'); // This is the foreign key that references the id column on the projects table. The onDelete('cascade') method means that if a project is deleted, all of its images will be deleted as well.
            $table->index('project_id'); // This is an index on the project_id column. This is not required, but it will speed up queries that filter by project_id.
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('project_images');
    }
}
