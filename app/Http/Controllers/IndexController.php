<?php

namespace App\Http\Controllers;


use App\Models\User;
use App\Models\UserOptions;
use App\Models\UserRoles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    public function __invoke(Request $request)
    {
        $data = [
            "title" => "Главная страница",
            "description" => "Описание главной страницы",
            "auth" => false,
        ];


        if (Auth::check()) {
            $data['auth'] = true;
            /** @var User $user */
            $user = Auth::user();
            $data['user-name'] = $user->name;
            $data['user-role'] = $this->getUserRole($user);
        } else {
            $data['user-name'] = 'Гость';
        }


        return view('index', ['data' => $data]);
    }

    public function getUserRole($user)
    {
        /** @var UserOptions $userOptions */
        $userOptions = UserOptions::query()->where('user_id', $user->id)->first();
        /** @var UserRoles $userRoles */
        $userRoles = UserRoles::query()->where('id', $userOptions->user_role_id)->first();

        return $userRoles;
    }
}
