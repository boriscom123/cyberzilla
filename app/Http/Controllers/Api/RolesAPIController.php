<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserOptions;
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
            $response['role'] = $dbRole;
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
            $response['role'] = $dbRole;
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
            $response['role'] = $dbRole;
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
        if ($dbRole) {
            if ($dbRole->is_default) {
                $response['message'] = 'Невозможно удалить роль назначенную по умолчанию.';
                return new JsonResponse($response);
            }

            $dbUserOptions = UserOptions::query()->where('user_role_id', $dbRole->id)->count();
            if ($dbUserOptions >= 1) {
                $response['message'] = 'Невозможно удалить роль - Некоторые пользователи используют данную роль.';
                return new JsonResponse($response);
            }
            $dbRole?->delete();
        }

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

        $requestRole = $request->get('role');

        if (!$requestRole['name']) {
            $response['message'] = 'Задано неправильное наименования для роли';
            return new JsonResponse($response);
        }

        /** @var UserRoles $dbRole */
        $dbRole = UserRoles::query()->where('name', $requestRole['name'])->first();
        if (!$dbRole) {
            $dbRole = new UserRoles();
            $dbRole->fill($requestRole);
            $dbRole->save();

            $response['is_created'] = true;
            $response['role'] = $dbRole;

            if ($requestRole['is_default']) {
                $response['roles'] = $this->setDefaultRoleById($dbRole->id);
            }
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

        $requestRole = $request->get('role');

        if ($requestRole['id'] === 1) {
            $response['message'] = 'Нельзя изменить данную роль';
            return new JsonResponse($response);
        }

        /** @var UserRoles $dbRole */
        $dbRole = UserRoles::query()->where('id', $requestRole['id'])->first();
        if ($dbRole) {
            if ($requestRole['is_default'] && $requestRole['is_default'] !== $dbRole->is_default) {
                $dbRole->fill($requestRole);
                $dbRole->save();
                $response['roles'] = $this->setDefaultRoleById($requestRole['id']);
            } else {
                $dbRole->fill($requestRole);
                $dbRole->save();
            }
            $response['is_updated'] = true;
            $response['role'] = $dbRole;
        }

        return new JsonResponse($response);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function roleSetDefault(Request $request): JsonResponse
    {
        $response = [
            'status' => 200,
            'is_changed' => false,
        ];

        $roleId = $request->get('role_id');
        $roles = [];

        /** @var UserRoles $dbRoles */
        $dbRoles = UserRoles::all();
        if ($dbRoles) {
            foreach ($dbRoles as $role) {
                if ($role->id === $roleId) {
                    $role->is_default = true;
                } else {
                    $role->is_default = false;
                }
                $role->save();
                $roles[$role->id] = $role;
            }
            $response['is_changed'] = true;
        }

        $response['roles'] = $roles;

        return new JsonResponse($response);
    }

    public function setDefaultRoleById($role_id)
    {
        $roles = [];

        /** @var UserRoles $dbRoles */
        $dbRoles = UserRoles::all();
        if ($dbRoles) {
            foreach ($dbRoles as $role) {
                if ($role->id === $role_id) {
                    $role->is_default = true;
                } else {
                    $role->is_default = false;
                }
                $role->save();
                $roles[$role->id] = $role;
            }
        }

        return $roles;
    }
}
