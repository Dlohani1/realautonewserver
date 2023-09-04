<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBulkSmsAutomationMessageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bulk_sms_automation_message', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('series_id')->unsigned();
            $table->integer('segment_id')->unsigned();
            //$table->enum('delivery_type',['initial','scheduled'])->default('initial');
            $table->longText('message');
            //$table->time('delivery_time')->nullable();
            //$table->integer('delivery_day')->nullable();
            $table->string('image')->nullable();
            $table->string('custom_full_name')->nullable();
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
        Schema::dropIfExists('bulk_sms_automation_message');
    }
}
