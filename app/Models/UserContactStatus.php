<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $text
 */
class UserContactStatus extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_contact_status';

    /**
     * @var array
     */
    protected $fillable = ['text'];

    public $timestamps = false;
}
