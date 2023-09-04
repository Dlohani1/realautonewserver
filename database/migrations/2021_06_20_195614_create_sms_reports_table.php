<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmsReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_reports', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('user_id')->unsigned()->index()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->bigInteger('lead_id');
            $table->bigInteger('campaign_id');

            $table->string('name', 191);
            $table->string('image')->nullable();
            $table->string('mail_id', 191);
            $table->string('mobile_no', 191);

            $table->enum('automation_type',['1','2','3'])->comment('1=Sms,2=Email,3=Whatsapp');
            $table->dateTime('delivery_date_time');
            $table->string('message');
            $table->enum('status', ['1', '2'])->comment('1=Send,2=NotSend')->nullable();

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
        Schema::dropIfExists('sms_reports');
    }
}