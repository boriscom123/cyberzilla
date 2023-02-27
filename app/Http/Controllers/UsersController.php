<?php

namespace App\Http\Controllers;

use App\Models\PaymentsStatus;
use App\Models\User;
use App\Models\UserOptions;
use App\Models\UserPayments;
use App\Models\UserRoles;
use App\Models\UsersPhoneNumbers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    public array $data = [];

    public function index()
    {
        $this->data = [
            "title" => "Пользователи",
            "description" => "Пользователи",
        ];

        if (Auth::check()) {
            /** @var User $user */
            $this->data['auth'] = true;

            /** @var User $user */
            $this->data['user'] = Auth::user();
            if ($this->checkUserRole()) {
                $this->data['users'] = $this->reloadUsers();
                $this->data['roles'] = $this->reloadRoles();
                $this->data['payments_status'] = $this->reloadPaymentsStatus();
                return view('users', ['data' => $this->data]);
            }

        }
        return redirect("/");
    }

    private function checkUserRole(): bool
    {
        $is_approved = false;

        $this->data['user-options'] = UserOptions::query()->where('user_id', $this->data['user']->id)->first();
        if ($this->data['user-options']) {
            $this->data['user-role'] = UserRoles::query()->where('id', $this->data['user-options']->user_role_id)->first();
            if ($this->data['user-role']->users_list) {
                $is_approved = true;
            }
        }

        return $is_approved;
    }

    public function reloadUsers(): array
    {
        $result = [];

        /** @var User $dbUsers */
        $dbUsers = User::all();
        if ($dbUsers) {
            $users = [];

            foreach ($dbUsers as $user) {
                $userPhoneNumbers = [];
                $userPayments = [];
                $userRole = '';

                $dbUserPhones = UsersPhoneNumbers::query()->where('user_id', $user->id)->get();
                if ($dbUserPhones) {
                    foreach ($dbUserPhones as $number) {
                        $userPhoneNumbers[$number->id] = [
                            'id' => $number->id,
                            'phone_number' => $number->phone_number,
                            'status_id' => $number->status_id,
                            'is_confirmed' => $number->is_confirmed,
                        ];
                    }
                }

                $dbUserPayments = UserPayments::query()->where('user_id', $user->id)->get();
                if ($dbUserPayments) {
                    foreach ($dbUserPayments as $payment) {
                        $userPayments[$payment->id] = [
                            'id' => $payment->id,
                            'payment_number' => $payment->payment_number,
                            'payment_total' => $payment->payment_total,
                            'payment_status' => $payment->payment_status,
                            'created_at' => $payment->created_at,
                            'updated_at' => $payment->updated_at,
                        ];
                    }
                }

                $dbUserOptions = UserOptions::query()->where('user_id', $user->id)->first();
                if ($dbUserOptions) {
                    $dbUserRole = UserRoles::query()->where('id', $dbUserOptions->user_role_id)->first();
                    if ($dbUserRole) {
                        $userRole = $dbUserRole->name;
                    }
                }

                $users[$user->id] = [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phones' => $userPhoneNumbers,
                    'payments' => $userPayments,
                    'role' => $userRole,
                ];
            }
            $result = $users;
        }

        return $result;
    }

    public function reloadRoles(): array
    {
        $result = [];

        /** @var UserRoles $dbUsers */
        $dbRoles = UserRoles::query()->get();
        if ($dbRoles) {
            $roles = [];
            foreach ($dbRoles as $role) {
                $roles[$role->id] = [
                    'id' => $role->id,
                    'name' => $role->name,
                ];
            }
            $result = $roles;
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
}
