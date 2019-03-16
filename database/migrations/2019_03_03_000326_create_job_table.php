<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('employer_id')->unsigned();
            $table->text('title');
            $table->text('description')->nullable();
            $table->decimal('estimated_budget', 8, 2);
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('country')->nullable();
            $table->string('status', 50)->default('open');
            $table->string('employer_status', 50)->nullable();
            $table->integer('jobseeker_id')->unsigned()->nullable();
            $table->string('jobseeker_status', 50)->nullable();
            $table->integer('employer_review_id')->unsigned()->nullable();
            $table->integer('jobseeker_review_id')->unsigned()->nullable();
            $table->timestamps();
            
            $table->foreign('employer_id')->references('id')->on('user');
            $table->foreign('jobseeker_id')->references('id')->on('job_application');
            $table->foreign('id')->references('id')->on('job_application');
            $table->foreign('jobseeker_review_id')->references('id')->on('jobseeker_review');
            $table->foreign('employer_review_id')->references('id')->on('employer_review');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job');
    }
}
