<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobApplicationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_application', function (Blueprint $table) {
            $table->integer('job_id')->unsigned();
            $table->integer('jobseeker_id')->unsigned();
            $table->decimal('bid_value', 8, 2);
            $table->tinyInteger('bid_completion_day')->unsigned();
            $table->timestamps();
            
            $table->primary(['job_id', 'jobseeker_id']);
            $table->foreign('job_id')->references('id')->on('job');
            $table->foreign('jobseeker_id')->references('id')->on('user');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_application');
    }
}
