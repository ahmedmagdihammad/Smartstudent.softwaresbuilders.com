<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use function PHPUnit\Framework\once;

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
            $table->bigIncrements('subjectContentId');
            $table->string('subjectContentName');
            $table->string('subjectContentimage');
            $table->string('file')->nullable();
            $table->string('video_url');
            $table->float('price');
            $table->string('description');
            $table->unsignedBigInteger('teacherId');
            $table->foreign('teacherId')->references('teacherId')->on('teachers')->onDelete('cascade');
            $table->unsignedBigInteger('beforSubjectContentId');
            $table->foreign('beforSubjectContentId')->references('beforSubjectContentId')->on('befor_subject_contents')->onDelete('cascade');
            $table->unsignedBigInteger('SubSubjectId');
            $table->foreign('SubSubjectId')->references('SubSubjectId')->on('sub_subjects')->onDelete('cascade');
            $table->unsignedBigInteger('subjectId');
            $table->foreign('subjectId')->references('subjectId')->on('subjects')->onDelete('cascade');
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
