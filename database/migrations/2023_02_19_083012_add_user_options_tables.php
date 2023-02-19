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
        Schema::create('user_options', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->comment('users->id');
            $table->unsignedBigInteger('user_role_id')->comment('user_roles->id');
        });

        Schema::create('user_roles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->comment('Наименование роли');

            $table->boolean('roles_list')->default(false)->comment('Просмотр списка ролей (прав доступа) пользователей');
            $table->boolean('roles_view')->default(false)->comment('Просмотр роли (прав доступа)');
            $table->boolean('roles_add')->default(false)->comment('Добавление роли (прав доступа)');
            $table->boolean('roles_edit')->default(false)->comment('Изменение роли (прав доступа)');
            $table->boolean('roles_delete')->default(false)->comment('Удаление роли (прав доступа)');

            $table->boolean('users_list')->default(false)->comment('Просмотр списка пользователей');
            $table->boolean('users_view')->default(false)->comment('Просмотр пользователей');
            $table->boolean('users_add')->default(false)->comment('Добавление пользователей');
            $table->boolean('users_edit')->default(false)->comment('Изменение пользователей');
            $table->boolean('users_delete')->default(false)->comment('Удаление пользователей');

            $table->boolean('payments_list')->default(false)->comment('Просмотр списка платежей');
            $table->boolean('payments_view')->default(false)->comment('Просмотр платежей');
            $table->boolean('payments_add')->default(false)->comment('Добавление платежей');
            $table->boolean('payments_edit')->default(false)->comment('Изменение платежей');
            $table->boolean('payments_delete')->default(false)->comment('Удаление платежей');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_options');
        Schema::dropIfExists('user_roles');
    }
};
