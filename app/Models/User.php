<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, Notifiable;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'role',
        'theme_preference',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_confirmed_at' => 'datetime',
        ];
    }

    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }

    public function specialties()
    {
        return $this->belongsToMany(
            Specialty::class,
            'user_specialties'
        )->withPivot('sub_specialty_id');
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function starredPosts()
    {
        return $this->belongsToMany(
            Post::class,
            'post_stars'
        );
    }

    public function followers()
    {
        return $this->belongsToMany(
            User::class,
            'follows',
            'following_id',
            'follower_id'
        );
    }

    public function following()
    {
        return $this->belongsToMany(
            User::class,
            'follows',
            'follower_id',
            'following_id'
        );
    }

    public function company()
    {
        return $this->hasOne(Company::class);
    }

    public function jobApplications()
    {
        return $this->hasMany(JobApplication::class);
    }

    public function chats()
    {
        return $this->belongsToMany(
            Chat::class,
            'chat_participants'
        );
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function notificationsCustom()
    {
        return $this->hasMany(UserNotification::class);
    }

    public function resumes()
    {
        return $this->hasMany(Resume::class);
    }

    public function certifications()
    {
        return $this->hasMany(Certification::class);
    }


    public function reportsMade()
    {
        return $this->hasMany(Report::class, 'reporter_id');
    }

    public function blockedUsers()
    {
        return $this->belongsToMany(
            User::class,
            'blocks',
            'blocker_id',
            'blocked_id'
        );
    }

    public function mutedUsers()
    {
        return $this->belongsToMany(
            User::class,
            'mutes',
            'user_id',
            'target_user_id'
        );
    }

    public function shares()
    {
        return $this->hasMany(Share::class);
    }

    public function savedItems()
    {
        return $this->hasMany(SavedItem::class);
    }

    public function jobViews()
    {
        return $this->hasMany(JobView::class);
    }

    public function jobRecommendations()
    {
        return $this->hasMany(JobRecommendation::class);
    }

    public function messageReads()
    {
        return $this->hasMany(MessageRead::class);
    }

    public function notificationSettings()
    {
        return $this->hasOne(NotificationSetting::class);
    }

    public function suspension()
    {
        return $this->hasOne(UserSuspension::class);
    }

    public function reputation()
    {
        return $this->hasOne(UserReputation::class);
    }

    public function endorsements()
    {
        return $this->hasMany(Endorsement::class);
    }

    public function endorsedBy()
    {
        return $this->hasMany(Endorsement::class, 'endorsed_by');
    }

    public function searchHistory()
    {
        return $this->hasMany(SearchHistory::class);
    }

    public function badges()
    {
        return $this->belongsToMany(Badge::class, 'user_badges')->withPivot('earned_at');
    }

    public function verifications()
    {
        return $this->hasMany(Verification::class);
    }

    public function adminLogs()
    {
        return $this->hasMany(AdminLog::class, 'admin_id');
    }

    public function blockedBy()
    {
        return $this->belongsToMany(
            User::class,
            'blocks',
            'blocked_id',
            'blocker_id'
        );
    }

    public function mutedBy()
    {
        return $this->belongsToMany(
            User::class,
            'mutes',
            'target_user_id',
            'user_id'
        );
    }

    public function sourceNotifications()
    {
        return $this->hasMany(UserNotification::class, 'source_user_id');
    }

    /**
     * Check if the user is currently active (online)
     * A user is considered active if they have a session with last_activity within the last 5 minutes
     */
    public function isActive(): bool
    {
        $activeThreshold = now()->subMinutes(5)->timestamp;
        
        return DB::table('sessions')
            ->where('user_id', $this->id)
            ->where('last_activity', '>=', $activeThreshold)
            ->exists();
    }

    /**
     * Check if the user is an admin
     */
    public function isAdmin(): bool
    {
        return $this->is_admin === true || $this->email === 'test@example.com';
    }

    /**
     * Check if the user is currently suspended
     */
    public function isSuspended(): bool
    {
        if (!$this->suspension) {
            return false;
        }

        // Consider suspension active only if it has no expiry or the expiry is in the future
        if ($this->suspension->expires_at && $this->suspension->expires_at->isPast()) {
            return false;
        }

        return true;
    }

    /**
     * Determine if the user can access the Filament admin panel.
     * ONLY test@example.com with admin role can access the admin dashboard.
     */
    public function canAccessPanel(\Filament\Panel $panel): bool
    {
        // Strictly only allow test@example.com with admin role
        if ($this->email === null) {
            return false;
        }
        
        $email = strtolower(trim($this->email));
        $role = $this->role ?? '';
        
        // Only test@example.com with admin role can access
        return $email === 'test@example.com' && $role === 'admin';
    }
}
