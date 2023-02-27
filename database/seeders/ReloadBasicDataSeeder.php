<?php

namespace Database\Seeders;

use App\Models\PaymentsStatus;
use App\Models\UserContactStatus;
use App\Models\UserOptions;
use App\Models\UserRoles;
use Illuminate\Database\Seeder;

class ReloadBasicDataSeeder extends Seeder
{
    private array $data = [
        'user_contact_status' => [
            'Рабочий',
            'Домашний',
            'Дополнительный',
            'Личный',
            'Корпоративный',
        ],
        'payments_status' => [
            'В обработке',
            'Завершен',
        ],
        'roles' => [
            [
                'name' => 'Супер-Пупер Глобальный Повелитель Данных',
                'is_default' => false,
                'roles_list' => true,
                'roles_view' => true,
                'roles_add' => true,
                'roles_edit' => true,
                'roles_delete' => true,
                'users_list' => true,
                'users_view' => true,
                'users_add' => true,
                'users_edit' => true,
                'users_delete' => true,
                'payments_list' => true,
                'payments_view' => true,
                'payments_add' => true,
                'payments_edit' => true,
                'payments_delete' => true,
            ],
            [
                'name' => 'Администратор',
                'is_default' => false,
                'roles_list' => true,
                'roles_view' => true,
                'roles_add' => true,
                'roles_edit' => true,
                'roles_delete' => true,
                'users_list' => true,
                'users_view' => true,
                'users_add' => true,
                'users_edit' => true,
                'users_delete' => true,
                'payments_list' => true,
                'payments_view' => true,
                'payments_add' => true,
                'payments_edit' => true,
                'payments_delete' => true,
            ],
            [
                'name' => 'Клиент',
                'is_default' => true,
                'roles_list' => false,
                'roles_view' => false,
                'roles_add' => false,
                'roles_edit' => false,
                'roles_delete' => false,
                'users_list' => false,
                'users_view' => false,
                'users_add' => false,
                'users_edit' => false,
                'users_delete' => false,
                'payments_list' => false,
                'payments_view' => false,
                'payments_add' => false,
                'payments_edit' => false,
                'payments_delete' => false,
            ],
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->data['user_contact_status'] as $status) {
            $dbStatus = UserContactStatus::query()->where('text', $status)->first();
            if (!$dbStatus) {
                $dbStatus = new UserContactStatus();
                $dbStatus->text = $status;
                $dbStatus->save();
            }
        }

        foreach ($this->data['payments_status'] as $status) {
            $dbPaymentsStatus = PaymentsStatus::query()->where('text', $status)->first();
            if (!$dbPaymentsStatus) {
                $dbPaymentsStatus = new PaymentsStatus();
                $dbPaymentsStatus->text = $status;
                $dbPaymentsStatus->save();
            }
        }

        foreach ($this->data['roles'] as $role) {
            $dbUserRole = UserRoles::query()->where('name', $role['name'])->first();
            if (!$dbUserRole) {
                $dbUserRole = new UserRoles();
                $dbUserRole->fill($role);
                $dbUserRole->save();
            }
        }

        $dbUserOptions = UserOptions::query()->where('id', 1)->first();
        if (!$dbUserOptions) {
            $dbUserOptions = new UserOptions();
            $dbUserOptions->user_id = 1;
            $dbUserOptions->user_role_id = 1;
            $dbUserOptions->save();
        }
    }
}
