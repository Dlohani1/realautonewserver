<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();


            $table->bigInteger('user_id')->unsigned()->index()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->bigInteger('project_id');
            $table->enum('project_type', ['1', '2'])->comment('1=Old,2=New');
            $table->string('project_name', 191)->nullable();

            $table->bigInteger('segment_id');
            $table->enum('segment_type', ['1', '2'])->comment('1=Old,2=New');
            $table->string('segment_name', 191)->nullable();


            $table->bigInteger('campaigns_id');

			$table->string('name', 191)->nullable();
            $table->string('mail_id', 191)->nullable();
			$table->string('mobile_no', 191)->nullable();
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('zipcode')->nullable();
            $table->string('company')->nullable();
            $table->string('position')->nullable();
            $table->longText('address1')->nullable();
            $table->longText('address2')->nullable();

            $table->string('fb_page_name')->nullable();
            $table->string('fb_page_desc')->nullable();
            $table->string('fb_id')->nullable();
            $table->string('whats_your_business_name')->nullable();
            $table->enum('source',['Facebook','Upload','Wordpress','Form HTML','99Acres','Magicbricks','Housing','COMF','Self'])->default('Upload');
            $table->enum('is_assigned',['Yes','No'])->default('No');

			$table->enum('status', ['1', '2'])->comment('1=Active,2=InActive')->nullable();
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
        Schema::dropIfExists('leads');
    }
}
