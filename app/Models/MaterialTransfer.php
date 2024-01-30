<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialTransfer extends Model
{
    use HasFactory;

    protected $table = 'material_transfer';

    protected $fillable = [
        'fk_material',
        'fk_request',
        'quantity_request',
        'dt_validity',
        'fk_companie'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
