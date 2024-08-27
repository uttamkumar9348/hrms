<?php

namespace App\Models;

use App\Helpers\AppHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasPermissions;

class Role extends Model
{
    use HasFactory, HasPermissions;

    const RECORDS_PER_PAGE = 10;

    protected $table = 'roles';

    protected $fillable = [
        'name',
        'slug',
        'is_active',
        'backend_login_authorize',
        'created_by',
        'updated_by'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            //            $model->created_by = Auth::user()->id;
        });

        static::updating(function ($model) {
            $model->updated_by = Auth::user()?->id ?? AppHelper::findAdminUserAuthId();
        });
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    /**
     * A role may be given various permissions.
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            config('permission.models.permission'),
            config('permission.table_names.role_has_permissions'),
            'role_id',
            'permission_id'
        );
    }
}
