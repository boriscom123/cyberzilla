<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $user_id
 * @property integer $user_role_id
 */
class UserOptions extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_options';

    /**
     * @var array
     */
    protected $fillable = ['user_id', 'user_role_id'];

    public $timestamps = false;
}
