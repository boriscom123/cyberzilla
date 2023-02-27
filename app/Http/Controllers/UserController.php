<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserOptions;
use App\Models\UserRoles;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public array $data = [];

    public function __invoke(Request $request, string $id)
    {
        if (Auth::check()) {
            $this->data['auth'] = true;
            $this->data['user'] = Auth::user();
            $this->data['user-name'] = $this->data['user']->name;
            $this->getUserRole();

            $user_id = $id;
            $this->data['data-user'] = $this->getUserInfoById($user_id);

            if (count($this->data['data-user']) > 0) {
                if ($this->data['user-options']['user_role_id'] > $this->data['data-user']['user-options']['user_role_id']) {
                    // недостаточно прав
                    return new RedirectResponse("/");
                }

                $this->data['title'] = "Информация о пользователе: " . $this->data['data-user']['user']['mane'];
                $this->data['description'] = "Информация о пользователе: " . $this->data['data-user']['user']['mane'];

                return view('user', ['data' => $this->data]);
            }

            return new RedirectResponse("/");

        } else {
            return new RedirectResponse("/");
        }
    }

    public function getUserRole()
    {
        $this->data['user-options'] = UserOptions::query()->where('user_id', $this->data['user']->id)->first();
        $this->data['user-role'] = UserRoles::query()->where('id', $this->data['user-options']->user_role_id)->first();
    }

    public function getUserInfoById($user_id): array
    {
        $result = [];

        $dbUser = User::query()->where('id', $user_id)->first();
        if ($dbUser) {
            $result['user'] = $dbUser;
            $dbUserOptions = UserOptions::query()->where('user_id', $dbUser->id)->first();
            if ($dbUserOptions) {
                $result['user-options'] = $dbUserOptions;
                $dbUserRole = UserRoles::query()->where('id', $dbUserOptions->user_role_id)->first();
                if ($dbUserRole) {
                    $result['user-role'] = $dbUserRole;
                }
            }
        }

        return $result;
    }
}
