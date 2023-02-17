<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $user_id
 * @property string $payment_number
 * @property integer $payment_total
 * @property boolean $payment_status
 * @property string $created_at
 * @property string $updated_at
 */
class UserPayments extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users_payments';

    /**
     * @var array
     */
    protected $fillable = ['user_id', 'payment_number', 'payment_total', 'payment_status', 'created_at', 'updated_at'];

    public $timestamps = true;
}
