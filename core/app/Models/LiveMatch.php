<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LiveMatch extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'spots_filled' => 'integer',
        'spots_total'  => 'integer',
        'entry_fee'    => 'float',
        'prize'        => 'float',
        'is_live'      => 'boolean',
        'status'       => 'boolean',
        'sort_order'   => 'integer',
    ];
}
