<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Joselfonseca\LighthouseGraphQLPassport\HasSocialLogin;
use Joselfonseca\LighthouseGraphQLPassport\Models\SocialProvider;
use Laravel\Passport\HasApiTokens;
use Laravel\Socialite\Facades\Socialite;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, HasRoles, HasSocialLogin;

    const TYPE_DOCS = [
        'CC',
        'NIT',
        'RUT',
        'PASSPORT',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'avatar',
        'email',
        'password',
        'telephone',
        'type_doc',
        'number_doc',
        'debit',
        'debit_threshold'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function socialProviders(): HasMany
    {
        return $this->hasMany(SocialProvider::class);
    }

    public function getAvatarAttribute($avatar): string
    {
        $name = Str::replace(' ', '+', $this->name);
        return $avatar ? $avatar : "https://ui-avatars.com/api/?name=${name}&bold=true";
    }

    /**
     * @param Request $request
     *
     * @return mixed
     */
    public static function byOAuthToken(Request $request)
    {
        $userData = Socialite::driver($request->get('provider'))->userFromToken($request->get('token'));

        try {
            $user = static::whereHas('socialProviders', function ($query) use ($request, $userData) {
                $query->where('provider', Str::lower($request->get('provider')))->where('provider_id', $userData->getId());
            })->firstOrFail();
        } catch (ModelNotFoundException $e) {
            $user = static::where('email', $userData->getEmail())->first();
            if (!$user) {
                $user = static::create([
                    'name' => $userData->getName(),
                    'email' => $userData->getEmail(),
                    'avatar' => $userData->getAvatar(),
                    'uuid' => Str::uuid(),
                    'password' => Hash::make(Str::random(16)),
                    'email_verified_at' => now(),
                ]);
            }
            SocialProvider::create([
                'user_id' => $user->id,
                'provider' => $request->get('provider'),
                'provider_id' => $userData->getId(),
            ]);
        }
        Auth::setUser($user);

        return $user;
    }
}
