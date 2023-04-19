<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubjectContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subject_contents', function (Blueprint $table) {
            $table->id();
            $table->string('subjectContentName');
            $table->string('subjectContentimage');
            $table->string('file');
            $table->string('video_url');
            $table->float('price');
            $table->string('description');
            $table->unsignedBigInteger('teacherId');
            $table->foreign('teacherId')->references('id')->on('teachers')->onDelete('cascade');
            $table->unsignedBigInteger('subjectId');
            $table->foreign('subjectId')->references('id')->on('subjects')->onDelete('cascade');
            $table->unsignedBigInteger('subSubjectId');
            $table->foreign('subSubjectId')->references('id')->on('sub_subjects')->onDelete('cascade');
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
        Schema::dropIfExists('subject_contents');
    }
}
