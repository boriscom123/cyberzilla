<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserContactStatus;
use App\Models\UsersPhoneNumbers;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class UsersController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getUsers(Request $request): JsonResponse
    {
        $response = [
            'status' => 200,
        ];

        /** @var  $dbUsers User */
        $dbUsers = User::all();
        if ($dbUsers) {
            $users = [];
            $userPhoneNumbers = [];
            foreach ($dbUsers as $user) {

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
                $users[$user->id] = [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phones' => $userPhoneNumbers,
                ];
            }
            $response['users'] = $users;
        }

        return new JsonResponse($response);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function addUser(Request $request): JsonResponse
    {
        $response = [
            'status' => 200,
            'is_added' => false,
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
                $response['message'] = 'Пользователь успешно добавлен';
                $response['is_added'] = true;
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

    public function removeUser(Request $request): JsonResponse
    {
        $response = [
            'status' => 200,
            'is_removed' => false,
        ];

        $userId = $request->only('user_id');

        $dbUser = User::query()->where('id', $userId)->first();
        $dbUser?->delete();

        $dbUser = User::query()->where('id', $userId)->first();
        if (!$dbUser) {
            $response['is_removed'] = true;
        }
        return new JsonResponse($response);
    }

    public function changeUserName(Request $request): JsonResponse
    {
        $response = [
            'status' => 200,
            'is_changed' => false,
        ];

        $userId = $request->get('user_id');
        $userName = $request->get('name');

        /** @var User $dbUser */
        $dbUser = User::query()->where('id', $userId)->first();

        if ($dbUser) {
            $dbUser->name = $userName;
            $dbUser->save();
            $response['is_changed'] = true;
        }
        return new JsonResponse($response);
    }

    public function addUserPhone(Request $request): JsonResponse
    {
        $response = [
            'status' => 200,
            'is_added' => false,
        ];

        $userId = $request->get('user_id');
        $phone = $request->get('phone');
        $status = $request->get('status');
        $confirmation = $request->get('confirmation');

        /** @var User $dbUser */
        $dbUser = User::query()->where('id', $userId)->first();

        if ($dbUser) {
            $dbPhone = new UsersPhoneNumbers();
            $dbPhone->user_id = $dbUser->id;
            $dbPhone->phone_number = $phone;
            $dbPhone->status_id = $status;
            $dbPhone->is_confirmed = $confirmation;
            $dbPhone->save();
            $response['is_added'] = true;
        }

        return new JsonResponse($response);
    }

    public function changeUserPhone(Request $request): JsonResponse
    {
        $response = [
            'status' => 200,
            'is_changed' => false,
        ];

        $userId = $request->get('user_id');
        $numberId = $request->get('number_id');
        $data = $request->get('data');

        /** @var UsersPhoneNumbers $dbPhone */
        $dbPhone = UsersPhoneNumbers::query()->where('id', $numberId)->first();

        if ($dbPhone) {
            $dbPhone->phone_number = $data['phone_number'];
            $dbPhone->status_id = $data['status_id'];
            $dbPhone->is_confirmed = $data['is_confirmed'];
            $dbPhone->save();
            $response['is_changed'] = true;
        }

        return new JsonResponse($response);
    }

    public function removeUserPhone(Request $request): JsonResponse
    {
        $response = [
            'status' => 200,
            'is_removed' => false,
        ];

        $numberId = $request->get('number_id');

        /** @var UsersPhoneNumbers $dbPhone */
        $dbPhone = UsersPhoneNumbers::query()->where('id', $numberId)->first();

        if ($dbPhone) {
            $dbPhone->delete();
        }

        /** @var UsersPhoneNumbers $dbPhone */
        $dbPhone = UsersPhoneNumbers::query()->where('id', $numberId)->first();

        if (!$dbPhone) {
            $response['is_removed'] = true;
        }

        return new JsonResponse($response);
    }

}
