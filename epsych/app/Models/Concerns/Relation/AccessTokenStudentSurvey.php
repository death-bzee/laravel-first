<?php

namespace App\Models\Concerns\Relation;

use App\Models\AccessToken;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccessTokenStudentSurvey extends Model
{
    protected $fillable = [
        'access_token_id',
        'survey_assignment_id',
        'student_id',
        'access_code',
    ];

    public function accessToken(): BelongsTo
    {
        return $this->belongsTo(AccessToken::class);
    }
}
