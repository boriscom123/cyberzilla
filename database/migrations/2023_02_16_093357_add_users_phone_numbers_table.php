<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $data = [
        'user_contact_status' => [
            'Рабочий',
            'Домашний',
            'Дополнительный',
            'Личный',
            'Корпоративный',
        ],
    ];
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_contact_status', function (Blueprint $table) {
            $table->string('text', 32)->comment('Статус контакта пользователя');
        });

//        foreach ($this->data['user_contact_status'] as $status) {
//            $dbStatus = new Status();
//            $dbStatus->text = $status;
//            $dbStatus->save();
//        }

        Schema::create('users_phone_numbers', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->comment('users->id');
            $table->string('phone_number', 20)->comment('Номер телефона пользователя');
            $table->unsignedBigInteger('status_id')->default(1)->comment('Статус контакта пользователя');
            $table->boolean('is_confirmed')->default(false)->comment('Статус подтверждения телефона');

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
        Schema::dropIfExists('users_phone_numbers');
        Schema::dropIfExists('user_contact_status');
    }
};
