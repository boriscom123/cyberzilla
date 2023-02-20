<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserRoles;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RolesAPIController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function changeRoles(Request $request): JsonResponse
    {
        $response = [
            'status' => 200,
            'is_changed' => false,
        ];

        $roleId = $request->get('role_id');
        if ($roleId === 1) {
            $response['message'] = 'Нельзя изменять данные права';
            return new JsonResponse($response);
        }

        /** @var UserRoles $dbRole */
        $dbRole = UserRoles::query()->where('id', $roleId)->first();
        if ($dbRole) {
            $dbRole->roles_list = !(bool)$dbRole->roles_list;
            $dbRole->save();
            $response['is_changed'] = true;
        }

        return new JsonResponse($response);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function changeUsers(Request $request): JsonResponse
    {
        $response = [
            'status' => 200,
            'is_changed' => false,
        ];

        $roleId = $request->get('role_id');
        if ($roleId === 1) {
            $response['message'] = 'Нельзя изменять данные права';
            return new JsonResponse($response);
        }

        /** @var UserRoles $dbRole */
        $dbRole = UserRoles::query()->where('id', $roleId)->first();
        if ($dbRole) {
            $dbRole->users_list = !(bool)$dbRole->users_list;
            $dbRole->save();
            $response['is_changed'] = true;
        }

        return new JsonResponse($response);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function changePayments(Request $request): JsonResponse
    {
        $response = [
            'status' => 200,
            'is_changed' => false,
        ];

        $roleId = $request->get('role_id');
        if ($roleId === 1) {
            $response['message'] = 'Нельзя изменять данные права';
            return new JsonResponse($response);
        }

        /** @var UserRoles $dbRole */
        $dbRole = UserRoles::query()->where('id', $roleId)->first();
        if ($dbRole) {
            $dbRole->payments_list = !(bool)$dbRole->payments_list;
            $dbRole->save();
            $response['is_changed'] = true;
        }

        return new JsonResponse($response);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function roleDelete(Request $request): JsonResponse
    {
        $response = [
            'status' => 200,
            'is_deleted' => false,
        ];

        $roleId = $request->get('role_id');
        if ($roleId === 1) {
            $response['message'] = 'Нельзя удалить данную роль';
            return new JsonResponse($response);
        }

        /** @var UserRoles $dbRole */
        $dbRole = UserRoles::query()->where('id', $roleId)->first();
        $dbRole?->delete();

        /** @var UserRoles $dbRole */
        $dbRole = UserRoles::query()->where('id', $roleId)->first();
        if (!$dbRole) {
            $response['is_deleted'] = true;
        }

        return new JsonResponse($response);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function roleCreate(Request $request): JsonResponse
    {
        $response = [
            'status' => 200,
            'is_created' => false,
        ];

        $request->validate([
            'name' => 'required',
        ]);

        $roleName = $request->get('name');

        /** @var UserRoles $dbRole */
        $dbRole = UserRoles::query()->where('name', $roleName)->first();
        if (!$dbRole) {
            $dbRole = new UserRoles();
            $dbRole->name = $roleName;
            $dbRole->roles_list = $request->get('roles_list');
            $dbRole->users_list = $request->get('users_list');
            $dbRole->payments_list = $request->get('payments_list');
            $dbRole->save();
            $response['is_created'] = true;
            $response['role'] = [
                'id' => $dbRole->id,
                'name' => $dbRole->name,
                'roles_list' => $dbRole->roles_list,
                'users_list' => $dbRole->users_list,
                'payments_list' => $dbRole->payments_list,
            ];
        }

        return new JsonResponse($response);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function roleUpdate(Request $request): JsonResponse
    {
        $response = [
            'status' => 200,
            'is_updated' => false,
        ];

        $roleId = $request->get('id');
        if ($roleId === 1) {
            $response['message'] = 'Нельзя изменить данную роль';
            return new JsonResponse($response);
        }

        /** @var UserRoles $dbRole */
        $dbRole = UserRoles::query()->where('id', $roleId)->first();
        if ($dbRole) {
            $dbRole->name = $request->get('name');;
            $dbRole->roles_list = $request->get('roles_list');
            $dbRole->users_list = $request->get('users_list');
            $dbRole->payments_list = $request->get('payments_list');
            $dbRole->save();
            $response['is_updated'] = true;
            $response['role'] = [
                'id' => $dbRole->id,
                'name' => $dbRole->name,
                'roles_list' => $dbRole->roles_list,
                'users_list' => $dbRole->users_list,
                'payments_list' => $dbRole->payments_list,
            ];
        }

        return new JsonResponse($response);
    }
}
