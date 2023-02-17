<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_emails', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->comment('users->id');
            $table->string('email', 255)->comment('Email пользователя');
            $table->unsignedBigInteger('status_id')->default(1)->comment('Статус контакта пользователя');
            $table->boolean('is_confirmed')->default(false)->comment('Статус подтверждения email');

//            $table->foreign('user_id')
//                ->references('id')
//                ->on('users')
//                ->onUpdate('cascade')
//                ->onDelete('cascade');

//            $table->foreign('status_id')
//                ->references('id')
//                ->on('user_contact_status')
//                ->onUpdate('cascade')
//                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_emails');
    }
};
