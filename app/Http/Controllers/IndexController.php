<?php

namespace App\Http\Controllers;


use App\Models\User;
use App\Models\UserOptions;
use App\Models\UserRoles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    public array $data = [];

    public function __invoke(Request $request)
    {
        $this->data = [
            "title" => "Главная страница",
            "description" => "Описание главной страницы",
            "auth" => false,
        ];

        if (Auth::check()) {
            $this->data['auth'] = true;
            $this->data['user'] = Auth::user();
            $this->data['user-name'] = $this->data['user']->name;
            $this->getUserRole();
        } else {
            $this->data['user-name'] = 'Гость';
        }

        return view('index', ['data' => $this->data]);
    }

    public function getUserRole()
    {
        $this->data['user-options'] = UserOptions::query()->where('user_id', $this->data['user']->id)->first();
        $this->data['user-role'] = UserRoles::query()->where('id', $this->data['user-options']->user_role_id)->first();
    }
}
