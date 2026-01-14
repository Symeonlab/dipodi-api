<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interest extends Model
{
    use HasFactory;

    // This table does not need 'created_at' or 'updated_at' fields
    public $timestamps = false;

    protected $fillable = [
        'key',
        'icon',
        'name_en',
        'name_fr',
        'name_ar',
    ];
}
