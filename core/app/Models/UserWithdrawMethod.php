<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserWithdrawMethod extends Model
{
    protected $guarded = ['id'];

    public function method()
    {
        return $this->belongsTo(WithdrawMethod::class, 'method_id');
    }
}