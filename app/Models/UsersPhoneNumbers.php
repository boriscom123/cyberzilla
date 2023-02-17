<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $user_id
 * @property string $phone_number
 * @property integer $status_id
 * @property boolean $is_confirmed
 */
class UsersPhoneNumbers extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users_phone_numbers';

    /**
     * @var array
     */
    protected $fillable = ['user_id', 'phone_number', 'status_id', 'is_confirmed'];

    public $timestamps = false;
}
