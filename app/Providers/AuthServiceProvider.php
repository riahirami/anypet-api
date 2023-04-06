<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        ResetPassword::createUrlUsing(function (User $user, string $token) {
            return 'http://localhost:3000/api/reset-password?token='.$token.'?email='.$user->getEmailForPasswordReset();
        });

        VerifyEmail::createUrlUsing(function ($notifiable) {
            $frontendUrl = 'http://localhost:3000/api/email/verify/';


            $verificationUrl = route('verification.verify', [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]);

            $expiration = Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60))->timestamp;

            $signature = hash_hmac('sha256', $verificationUrl . '&' . $expiration, config('app.key'));

            return $frontendUrl . $notifiable->getKey() . '/' . sha1($notifiable->getEmailForVerification()) . '?expires=' . $expiration . '&signature=' . $signature;

        });
    }
}
