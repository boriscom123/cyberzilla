<?php

namespace App\Http\Controllers;

use App\Models\PaymentsStatus;
use App\Models\User;
use App\Models\UserOptions;
use App\Models\UserPayments;
use App\Models\UserRoles;
use Illuminate\Support\Facades\Auth;

class PaymentsController extends Controller
{
    public array $data = [];

    public function index()
    {
        $this->data = [
            "title" => "Платежи",
            "description" => "Описание страницы администратора - Платежи",
        ];

        if (Auth::check()) {
            $this->data['auth'] = true;

            /** @var User $user */
            $this->data['user'] = Auth::user();;
            if ($this->checkUserRole()) {
                $this->data['payments'] = $this->reloadPayments();
                $this->data['payments_status'] = $this->reloadPaymentsStatus();
                $this->data['users'] = $this->reloadUsers();
                return view('payments', ['data' => $this->data]);
            }
        }

        return redirect("/");
    }

    private function checkUserRole(): bool
    {
        $is_approved = false;

        $this->data['user-options'] = UserOptions::query()->where('user_id', $this->data['user']->id)->first();
        $this->data['user-role'] = UserRoles::query()->where('id', $this->data['user-options']->user_role_id)->first();

        if ($this->data['user-role']->payments_list) {
            $is_approved = true;
        }

        return $is_approved;
    }

    public function reloadPayments()
    {
        $result = [];

        /** @var UserPayments $dbUserPayments */
        $dbUserPayments = UserPayments::all();
        if ($dbUserPayments) {
            $payments = [];

            foreach ($dbUserPayments as $payment) {
                $payments[$payment->id] = $payment;
            }

            $result = $payments;
        }

        return $result;
    }

    public function reloadPaymentsStatus(): array
    {
        $result = [];

        /** @var PaymentsStatus $dbPaymentStatus */
        $dbPaymentStatus = PaymentsStatus::query()->get();
        if ($dbPaymentStatus) {
            $status_list = [];
            foreach ($dbPaymentStatus as $status) {
                $status_list[$status->id] = [
                    'id' => $status->id,
                    'name' => $status->text,
                ];
            }
            $result = $status_list;
        }

        return $result;
    }

    public function reloadUsers()
    {
        $result = [];

        /** @var User $dbUsers */
        $dbUsers = User::all();
        if ($dbUsers) {
            $users = [];

            foreach ($dbUsers as $user) {
                $users[$user->id] = $user;
            }

            $result = $users;
        }

        return $result;
    }
}
