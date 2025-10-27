<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;

class VendingMachineStateModel extends Model
{
    protected $table = 'vending_machine_states';
    protected $fillable = ['inserted_money'];
}
