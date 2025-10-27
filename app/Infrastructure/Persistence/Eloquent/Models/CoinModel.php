<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;

class CoinModel extends Model
{
    protected $table = 'coins';
    protected $fillable = ['value', 'count'];
}
