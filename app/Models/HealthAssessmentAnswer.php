<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HealthAssessmentAnswer extends Model
{
    protected $fillable = [
        'session_id',
        'question_id',
        'user_id',
        'answer_value',
    ];

    // Relationships
    public function session(): BelongsTo
    {
        return $this->belongsTo(HealthAssessmentSession::class, 'session_id');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(HealthAssessmentQuestion::class, 'question_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Helpers
    public function isPositive(): bool
    {
        $value = strtolower($this->answer_value);
        return in_array($value, ['oui', 'yes', '1', 'true']);
    }
}
