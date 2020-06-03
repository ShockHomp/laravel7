<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();
        //设置token过期时间
        Passport::tokensExpireIn(now()->addDays(env('TOKENS_EXPIRE_IN', 1)));
        //设置刷新token过期时间
        Passport::refreshTokensExpireIn(now()->addDays(env('REFRESH_TOKENS_EXPIRE_IN', 30)));
    }
}
