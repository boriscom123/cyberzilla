<?php

namespace App\Http\Controllers;


use App\Models\User;
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
        } else {
            $data['user-name'] = 'Гость';
        }


        return view('index', ['data' => $data]);
    }
}
