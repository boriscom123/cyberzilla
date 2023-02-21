<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserOptions;
use App\Models\UserRoles;
use Illuminate\Http\JsonResponse;
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
        $response = [
            'status' => 200,
            'is_user_login' => false,
        ];

        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $remember = $request->only('remember') ? true : false;

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials, $remember)) {
            $response['is_user_login'] = true;
        }

        return new JsonResponse($response);
    }


    public function logout(Request $request) {
        Auth::logout();
        Session::flush();
        return Redirect('/');
    }

    public function registration(Request $request)
    {
        $response = [
            'status' => 200,
            'is_user_registered' => false,
        ];

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
                $response['is_user_registered'] = true;
                /** @var User $user */
                $user = Auth::user();
                /** @var UserRoles $userRoles */
                $userRoles = UserRoles::query()->where('id', '>=', 1)->latest();

                $userOptions = new UserOptions();
                $userOptions->user_id = $user->id;
                $userOptions->user_role_id = $userRoles->id;
                $userOptions->save();
            }
        }

        return new JsonResponse($response);
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
