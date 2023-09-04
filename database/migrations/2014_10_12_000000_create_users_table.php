<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
			$table->bigInteger('admin_id')->nullable();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone_no');
            $table->string('password');
            $table->enum('status', ['1', '2'])->comment('1=Active,2=InActive');
            $table->enum('usertype', ['1', '2', '3'])->comment('1=SuperAdmin,2=Admin,3=SubAdmin');
            $table->string('msg_server_header')->nullable();
            $table->string('instanceId')->nullable();
            $table->string('sending_email')->nullable();
            $table->string('sending_name')->nullable();
            $table->string('whatsapp_api_key')->nullable();
            $table->string('whatsapp_username')->nullable();
            $table->enum('whatsapp_api_key_lock', ['1', '2'])->comment('1=Unlock,2=Lock');
            $table->string('email_api_key')->nullable();
            $table->enum('email_api_key_lock', ['1', '2'])->comment('1=Unlock,2=Lock');
            $table->string('sms_api_key')->nullable();
            $table->string('sms_from_name')->nullable();
            $table->enum('sms_api_key_lock', ['1', '2'])->comment('1=Unlock,2=Lock');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
