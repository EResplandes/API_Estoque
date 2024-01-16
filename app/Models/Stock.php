<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $table = 'stock';

    protected $fillable = [
        'name',
        'description',
        'amount',
        'dt_validity',
        'fk_companie'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
