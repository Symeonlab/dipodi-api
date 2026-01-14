<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserReminderSetting extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'breakfast_enabled', 'breakfast_time',
        'lunch_enabled', 'lunch_time',
        'dinner_enabled', 'dinner_time',
        'workout_enabled', 'workout_time',
    ];
    protected $casts = [
        'breakfast_enabled' => 'boolean',
        'lunch_enabled' => 'boolean',
        'dinner_enabled' => 'boolean',
        'workout_enabled' => 'boolean',
    ];
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
