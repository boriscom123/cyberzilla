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
        Schema::create('payments_status', function (Blueprint $table) {
            $table->string('text', 32)->comment('Статус платежа пользователя');
        });

        Schema::create('users_payments', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->comment('users->id');
            $table->timestamps();
            $table->string('payment_number', 36)->nullable()->comment('Номер платежа пользователя');
            $table->decimal('payment_total', $precision = 8, $scale = 2)->default(0)->comment('Итоговая сумма платежа пользователя');
            $table->unsignedBigInteger('payment_status')->default(1)->comment('Статус платежа пользователя');


//            $table->foreign('user_id')
//                ->references('id')
//                ->on('users')
//                ->onUpdate('cascade')
//                ->onDelete('cascade');

//            $table->foreign('payment_status')
//                ->references('id')
//                ->on('payments_status')
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
        Schema::dropIfExists('payments_status');
        Schema::dropIfExists('users_payments');
    }
};
