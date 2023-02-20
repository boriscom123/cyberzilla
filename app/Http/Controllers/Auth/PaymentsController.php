<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserOptions;
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
            /** @var User $user */
            $this->data['user'] = Auth::user();;
            if ($this->checkUserRole()) {
                $this->data['roles'] = UserRoles::all();
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

}
