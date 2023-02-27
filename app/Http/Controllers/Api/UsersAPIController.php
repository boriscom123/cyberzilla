<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserContactStatus;
use App\Models\UserOptions;
use App\Models\UserPayments;
use App\Models\UserRoles;
use App\Models\UsersPhoneNumbers;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;


class UsersAPIController extends Controller
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
            $userPayments = [];
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

                $dbUserPayments = UserPayments::query()->where('user_id', $user->id)->get();
                if ($dbUserPayments) {
                    foreach ($dbUserPayments as $payment) {
                        $userPayments[$payment->id] = [
                            'id' => $payment->id,
                            'payment_number' => $payment->payment_number,
                            'payment_total' => $payment->payment_total,
                            'payment_status' => $payment->payment_status,
                            'created_at' => $payment->created_at,
                            'updated_at' => $payment->updated_at,
                        ];
                    }
                }

                $users[$user->id] = [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phones' => $userPhoneNumbers,
                    'payments' => $userPayments,
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
    public function userCreate(Request $request): JsonResponse
    {
        $response = [
            'status' => 200,
            'is_created' => false,
        ];

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $data = $request->all();
        $check = $this->createNewUser($data);

        if ($check) {
            $response['message'] = 'Пользователь успешно создан';
            $response['is_created'] = true;
            $response['user'] = $this->getUserData($check->id);
        }

        return new JsonResponse($response);
    }

    public function createNewUser(array $data)
    {
        $newUser = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password'])
        ]);

        /** @var UserRoles $userRoles */
        $userRoles = UserRoles::query()->where('id', $data['role'])->first();

        $userOptions = new UserOptions();
        $userOptions->user_id = $newUser->id;
        $userOptions->user_role_id = $userRoles->id;
        $userOptions->save();

        return $newUser;
    }

    public function getUserData($user_id)
    {
        $result = [];

        /** @var User $dbUsers */
        $dbUser = User::query()->where('id', $user_id)->first();
        if ($dbUser) {
            $user = [];
            $userPhoneNumbers = [];
            $userPayments = [];
            $userRole = '';
            $dbUserPhones = UsersPhoneNumbers::query()->where('user_id', $dbUser->id)->get();
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

            $dbUserPayments = UserPayments::query()->where('user_id', $dbUser->id)->get();
            if ($dbUserPayments) {
                foreach ($dbUserPayments as $payment) {
                    $userPayments[$payment->id] = [
                        'id' => $payment->id,
                        'payment_number' => $payment->payment_number,
                        'payment_total' => $payment->payment_total,
                        'payment_status' => $payment->payment_status,
                        'created_at' => $payment->created_at,
                        'updated_at' => $payment->updated_at,
                    ];
                }
            }

            $dbUserOptions = UserOptions::query()->where('user_id', $dbUser->id)->first();
            if ($dbUserOptions) {
                $dbUserRole = UserRoles::query()->where('id', $dbUserOptions->user_role_id)->first();
                if ($dbUserRole) {
                    $userRole = $dbUserRole->name;
                }
            }

            $user = [
                'id' => $dbUser->id,
                'name' => $dbUser->name,
                'email' => $dbUser->email,
                'phones' => $userPhoneNumbers,
                'payments' => $userPayments,
                'role' => $userRole,
            ];
            $result = $user;
        }

        return $result;
    }

    public function userDelete(Request $request): JsonResponse
    {
        $response = [
            'status' => 200,
            'is_deleted' => false,
        ];

        $userId = $request->only('user_id');

        $dbUser = User::query()->where('id', $userId)->first();
        $dbUser?->delete();

        $dbUserOptions = UserOptions::query()->where('user_id', $userId)->first();
        $dbUserOptions?->delete();

        $dbUser = User::query()->where('id', $userId)->first();
        $dbUserOptions = UserOptions::query()->where('user_id', $userId)->first();
        if (!$dbUser && !$dbUserOptions) {
            $response['is_deleted'] = true;
        }
        return new JsonResponse($response);
    }

    public function userUpdate(Request $request): JsonResponse
    {
        $response = [
            'status' => 200,
            'is_updated' => false,
        ];

        $userId = $request->get('user_id');
        $userName = $request->get('name');
        $roleId = $request->get('role_id');

        /** @var User $dbUser */
        $dbUser = User::query()->where('id', $userId)->first();

        if ($dbUser) {
            $dbUser->name = $userName;
            $dbUser->save();

            /** @var UserOptions $userOptions */
            $userOptions = UserOptions::query()->where('user_id', $dbUser->id)->first();
            if ($userOptions) {
                $userOptions->user_role_id = $roleId;
                $userOptions->save();

                $response['is_updated'] = true;
            }
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

    public function userPaymentCreate(Request $request): JsonResponse
    {
        $response = [
            'status' => 200,
            'is_created' => false,
        ];

        $userId = $request->get('user_id');
        $payment_number = $request->get('payment_number');
        $payment_status = $request->get('payment_status');
        $payment_total = $request->get('payment_total');

        /** @var User $dbUser */
        $dbUser = User::query()->where('id', $userId)->first();

        if ($dbUser) {
            $dbPayment = new UserPayments();
            $dbPayment->user_id = $dbUser->id;
            $dbPayment->payment_number = $payment_number;
            $dbPayment->payment_status = $payment_status;
            $dbPayment->payment_total = $payment_total;
            $dbPayment->save();
            $response['is_created'] = true;
            $response['payment'] = [
                'id' => $dbPayment->id,
                'payment_number' => $dbPayment->payment_number,
                'payment_status' => $dbPayment->payment_status,
                'payment_total' => $dbPayment->payment_total,
                'created_at' => $dbPayment->created_at,
                'updated_at' => $dbPayment->updated_at,
            ];
        }

        return new JsonResponse($response);
    }

    public function userPaymentUpdate(Request $request): JsonResponse
    {
        $response = [
            'status' => 200,
            'is_updated' => false,
        ];

        $payment_id = $request->get('id');
        $payment_number = $request->get('payment_number');
        $payment_status = $request->get('payment_status');
        $payment_total = $request->get('payment_total');

        /** @var UserPayments $dbPayment */
        $dbPayment = UserPayments::query()->where('id', $payment_id)->first();

        if ($dbPayment) {
            $dbPayment->payment_number = $payment_number;
            $dbPayment->payment_status = $payment_status;
            $dbPayment->payment_total = $payment_total;

            $dbPayment->save();
            $response['is_updated'] = true;
            $response['payment'] = [
                'id' => $dbPayment->id,
                'payment_number' => $dbPayment->payment_number,
                'payment_status' => $dbPayment->payment_status,
                'payment_total' => $dbPayment->payment_total,
                'created_at' => $dbPayment->created_at,
                'updated_at' => $dbPayment->updated_at,
            ];
        }

        return new JsonResponse($response);
    }

    public function userPaymentDelete(Request $request): JsonResponse
    {
        $response = [
            'status' => 200,
            'is_deleted' => false,
        ];

        $payment_id = $request->get('payment_id');

        /** @var UserPayments $dbPayment */
        $dbPayment = UserPayments::query()->where('id', $payment_id)->first();
        $dbPayment?->delete();

        /** @var UserPayments $dbPayment */
        $dbPayment = UserPayments::query()->where('id', $payment_id)->first();

        if (!$dbPayment) {
            $response['is_deleted'] = true;
        }

        return new JsonResponse($response);
    }
}
