<?php

namespace App\Providers;

use App\Models\Message;
use App\Policies\CommentPolicy;
use App\Policies\MessagePolicy;
use App\Policies\ProfilePolicy;
use App\Policies\ToastPolicy;
use App\Policies\UserPolicy;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Gate;
use Laravelista\Comments\Comment;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        Toast::class => ToastPolicy::class,
        User::class => UserPolicy::class,
        Profile::class => ProfilePolicy::class,
        Comment::class => CommentPolicy::class,
        Message::class => MessagePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        VerifyEmail::toMailUsing(function ($notifiable, $url){
            return (new MailMessage)->subject("Xác minh email cho STUNetwork")->line("Để hoàn tất việc đăng ký tài khoản STUNetwork, xin hãy xác minh email của bạn.")->action("Xác minh email", $url);
        });
    }
}
