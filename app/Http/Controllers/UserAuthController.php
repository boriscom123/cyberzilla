<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;


class UserAuthController extends Controller
{
    public function index()
    {
        $data = [
            "title" => "Страница входа",
            "description" => "Описание страницы входа",
        ];

        if (Auth::check()) {
            /** @var User $user */
            $user = Auth::user();
            $data['user-name'] = $user->name;
        } else {
            $data['user-name'] = 'Гость';
        }

        return view('login', ['data' => $data]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $remember = $request->only('remember') ? true : false;

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials, $remember)) {
            return Redirect('/admin');
        }

        return redirect("/");
    }

    public function logout(Request $request) {
        Auth::logout();
        Session::flush();
        return Redirect('/');
    }

    public function registration(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $data = $request->all();
        $check = $this->createNewUser($data);

        if ($check) {
            $credentials = $request->only('email', 'password');
            if (Auth::attempt($credentials)) {
                return Redirect('/admin');
            }
        }
        return redirect('/');
    }

    public function createNewUser(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password'])
        ]);
    }
}
