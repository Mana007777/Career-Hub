<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
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

    public function likedPosts()
    {
        return $this->belongsToMany(
            Post::class,
            'post_likes'
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
}
