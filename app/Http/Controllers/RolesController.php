<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserOptions;
use App\Models\UserRoles;
use Illuminate\Support\Facades\Auth;

class RolesController extends Controller
{
    public array $data = [];

    public function index()
    {
        $this->data = [
            "title" => "Страница администратора - Права пользователей",
            "description" => "Права пользователей",
        ];

        if (Auth::check()) {
            $this->data['auth'] = true;

            /** @var User $user */
            $this->data['user'] = Auth::user();
            if ($this->checkUserRole()) {
                $this->data['roles'] = $this->reloadRoles();
                return view('roles', ['data' => $this->data]);
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
            if ($this->data['user-role']->roles_list) {
                $is_approved = true;
            }
        }

        return $is_approved;
    }

    public function reloadRoles(): array
    {
        $result = [];

        /** @var UserRoles $dbRoles */
        $dbRoles = UserRoles::all();
        if ($dbRoles) {
            foreach ($dbRoles as $role) {
                $result[$role->id] = $role;
            }
        }

        return $result;
    }
}
