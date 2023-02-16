<?php

namespace Database\Seeders;

use App\Models\PaymentsStatus;
use App\Models\UserContactStatus;
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
    }
}
