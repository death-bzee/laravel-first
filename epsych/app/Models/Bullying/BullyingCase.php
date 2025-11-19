<?php

namespace App\Models\Bullying;

use App\Enums\Bullying\BullyingCaseStatusEnum;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Permission\Models\Role;

class BullyingCase extends Model
{
    protected $fillable = [
        'victim',
        'aggressor',
        'description',
        'incident_date',
        'status',
        'organization_id',
        'role_id',
    ];

    protected $casts = [
        'incident_date' => 'date',
        'status' => BullyingCaseStatusEnum::class,
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
}
