<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_type_id',
        'asset_id',
        'user_id',
        'assign_date',
        'return_date',
        'returned',
        'damaged',
        'cost_of_damage'
    ];

    public function users(){
        return $this->hasMany(User::class);
    }

    public function assets(){
        return $this->hasMany(Asset::class);
    }

    public function asset_types(){
        return $this->hasMany(AssetType::class);
    }  
}
