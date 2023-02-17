<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        $data = [
            "title" => "Страница администратора",
            "description" => "Описание страницы администратора",
        ];

        if (Auth::check()) {
            /** @var User $user */
            $user = Auth::user();
            $data['user-name'] = $user->name;
        } else {
            return redirect("/");
        }

        return view('admin', ['data' => $data]);
    }
}
