<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $name
 * @property boolean $is_default
 * @property boolean $roles_list
 * @property boolean $roles_view
 * @property boolean $roles_add
 * @property boolean $roles_edit
 * @property boolean $roles_delete
 * @property boolean $users_list
 * @property boolean $users_view
 * @property boolean $users_add
 * @property boolean $users_edit
 * @property boolean $users_delete
 * @property boolean $payments_list
 * @property boolean $payments_view
 * @property boolean $payments_add
 * @property boolean $payments_edit
 * @property boolean $payments_delete
 */
class UserRoles extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_roles';

    /**
     * @var array
     */
    protected $fillable = [
        'name', 'is_default',
        'roles_list', 'roles_view', 'roles_add', 'roles_edit', 'roles_delete',
        'users_list', 'users_view', 'users_add', 'users_edit', 'users_delete',
        'payments_list', 'payments_view', 'payments_add', 'payments_edit', 'payments_delete',
        ];

    public $timestamps = false;
}
