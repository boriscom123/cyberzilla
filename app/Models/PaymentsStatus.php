<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $text
 */
class PaymentsStatus extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'payments_status';

    /**
     * @var array
     */
    protected $fillable = ['text'];

    public $timestamps = false;
}
