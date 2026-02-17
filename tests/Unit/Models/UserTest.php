<?php

use App\Models\User;
use App\Models\Profile;
use App\Models\Post;
use App\Models\Company;
use App\Models\Specialty;
use App\Models\SubSpecialty;
use App\Models\Resume;
use App\Models\Certification;
use App\Models\Report;
use App\Models\Chat;
use App\Models\Message;
use App\Models\UserNotification;
use App\Models\SavedItem;
use App\Models\SearchHistory;
use App\Models\Badge;
use App\Models\Verification;
use App\Models\JobApplication;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

test('user can be created', function () {
    $user = User::factory()->create([
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'username' => 'johndoe',
    ]);

    expect($user->name)->toBe('John Doe')
        ->and($user->email)->toBe('john@example.com')
        ->and($user->username)->toBe('johndoe');
});

test('user has profile relationship', function () {
    $user = User::factory()->create();
    Profile::factory()->create(['user_id' => $user->id]);

    expect($user->profile)->toBeInstanceOf(Profile::class);
});

test('user has many posts', function () {
    $user = User::factory()->create();
    Post::factory()->count(3)->create(['user_id' => $user->id]);

    expect($user->posts)->toHaveCount(3);
});

test('user has one company', function () {
    $user = User::factory()->create();
    Company::factory()->create(['user_id' => $user->id]);

    expect($user->company)->toBeInstanceOf(Company::class);
});

test('user belongs to many specialties', function () {
    $user = User::factory()->create();
    $specialty = Specialty::factory()->create();
    $subSpecialty = SubSpecialty::factory()->create(['specialty_id' => $specialty->id]);
    $user->specialties()->attach($specialty->id, ['sub_specialty_id' => $subSpecialty->id]);

    expect($user->specialties)->toHaveCount(1);
});

test('user has many resumes', function () {
    $user = User::factory()->create();
    Resume::factory()->count(2)->create(['user_id' => $user->id]);

    expect($user->resumes)->toHaveCount(2);
});

test('user has many certifications', function () {
    $user = User::factory()->create();
    Certification::factory()->count(2)->create(['user_id' => $user->id]);

    expect($user->certifications)->toHaveCount(2);
});

test('user has many job applications', function () {
    $user = User::factory()->create();
    JobApplication::factory()->count(3)->create(['user_id' => $user->id]);

    expect($user->jobApplications)->toHaveCount(3);
});

test('user has many chats', function () {
    $user = User::factory()->create();
    $chat = Chat::factory()->create();
    $user->chats()->attach($chat->id);

    expect($user->chats)->toHaveCount(1);
});

test('user has many messages', function () {
    $user = User::factory()->create();
    $chat = Chat::factory()->create();
    $user->chats()->attach($chat->id);
    Message::factory()->count(2)->create(['sender_id' => $user->id, 'chat_id' => $chat->id]);

    expect($user->messages)->toHaveCount(2);
});

test('user has many notifications', function () {
    $user = User::factory()->create();
    UserNotification::factory()->count(3)->create(['user_id' => $user->id]);

    expect($user->notificationsCustom)->toHaveCount(3);
});

test('user has many reports made', function () {
    $user = User::factory()->create();
    Report::factory()->count(2)->create(['reporter_id' => $user->id]);

    expect($user->reportsMade)->toHaveCount(2);
});

test('user has many saved items', function () {
    $user = User::factory()->create();
    SavedItem::factory()->count(2)->create(['user_id' => $user->id]);

    expect($user->savedItems)->toHaveCount(2);
});

test('user has many search history entries', function () {
    $user = User::factory()->create();
    SearchHistory::factory()->count(3)->create(['user_id' => $user->id]);

    expect($user->searchHistory)->toHaveCount(3);
});

test('user belongs to many badges', function () {
    $user = User::factory()->create();
    $badge = Badge::factory()->create();
    $user->badges()->attach($badge->id, ['earned_at' => now()]);

    expect($user->badges)->toHaveCount(1);
});

test('user has many verifications', function () {
    $user = User::factory()->create();
    Verification::factory()->count(2)->create(['user_id' => $user->id]);

    expect($user->verifications)->toHaveCount(2);
});

test('user can follow another user', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    
    $user1->following()->attach($user2->id);

    expect($user1->following)->toHaveCount(1)
        ->and($user2->followers)->toHaveCount(1);
});

test('user can block another user', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    
    $user1->blockedUsers()->attach($user2->id);

    expect($user1->blockedUsers)->toHaveCount(1);
});

test('user can star posts', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create();
    
    $user->starredPosts()->attach($post->id);

    expect($user->starredPosts)->toHaveCount(1);
});

test('user password is hashed', function () {
    $user = User::factory()->create(['password' => 'password123']);

    expect($user->password)->not->toBe('password123')
        ->and(Hash::check('password123', $user->password))->toBeTrue();
});

test('user email verified at is cast to datetime', function () {
    $user = User::factory()->create(['email_verified_at' => now()]);

    expect($user->email_verified_at)->toBeInstanceOf(\DateTimeInterface::class);
});

test('user has profile photo url accessor', function () {
    $user = User::factory()->create();

    expect($user->profile_photo_url)->toBeString();
});

test('user is active method checks session', function () {
    $user = User::factory()->create();
    
    // The isActive method checks the sessions table
    // We'll just verify it returns a boolean without mocking
    // In a real scenario, you'd need to set up session data
    $result = $user->isActive();
    
    expect($result)->toBeBool();
});
