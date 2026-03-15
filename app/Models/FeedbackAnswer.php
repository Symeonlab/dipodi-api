<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeedbackAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'question_id',
        'user_id',
        'answer_value',
    ];

    /**
     * Get the session this answer belongs to.
     */
    public function session(): BelongsTo
    {
        return $this->belongsTo(FeedbackSession::class, 'session_id');
    }

    /**
     * Get the question this answer is for.
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(FeedbackQuestion::class, 'question_id');
    }

    /**
     * Get the user who provided this answer.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get numeric value (for scale answers).
     */
    public function getNumericValue(): ?int
    {
        if (is_numeric($this->answer_value)) {
            return (int) $this->answer_value;
        }
        return null;
    }

    /**
     * Get boolean value (for yes/no answers).
     */
    public function getBooleanValue(): ?bool
    {
        return match (strtolower($this->answer_value)) {
            'yes', 'oui', 'نعم', '1', 'true' => true,
            'no', 'non', 'لا', '0', 'false' => false,
            default => null,
        };
    }
}
