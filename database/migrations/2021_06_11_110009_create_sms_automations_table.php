<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmsAutomationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_automations', function (Blueprint $table) {
            $table->id();
            //$table->increments('id');
            $table->bigInteger('user_id')->unsigned()->index()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->bigInteger('campaigns_id')->unsigned()->index()->nullable();
            //$table->foreign('campaigns_id')->references('id')->on('sms_campaigns')->onDelete('cascade');

            $table->string('series_name');

            $table->enum('automation_type',['1','2','3'])->comment('1=Sms,2=Email,3=Whatsapp');
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
        Schema::dropIfExists('sms_automations');
    }
}
