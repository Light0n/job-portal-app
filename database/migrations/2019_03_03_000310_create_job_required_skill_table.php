<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobRequiredSkillTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_required_skill', function (Blueprint $table) {
            $table->integer('job_id')->unsigned();
            $table->integer('skill_id')->unsigned();

            $table->primary(['job_id', 'skill_id']);
            $table->foreign('job_id')->references('id')->on('job');
            $table->foreign('skill_id')->references('id')->on('skill');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_required_skill');
    }
}
