<?php

namespace App\Providers;

use App\Policies\Policy;
use App\Policies\Task\TaskPolicy;
use App\Policies\User\UserPolicy;
use Illuminate\Support\Facades\Gate;
use App\Policies\System\SystemPolicy;
use App\Policies\Project\ProjectPolicy;
use App\Policies\TimeLog\TimeLogPolicy;
use App\Policies\System\SystemChangelogPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
    * The policy mappings for the application.
    *
    * @var array
    */
    protected $policies = [

    ];

    protected array $application_policies = [
        ProjectPolicy::class,
        SystemChangelogPolicy::class,
        SystemPolicy::class,
        TaskPolicy::class,
        TimeLogPolicy::class,
        UserPolicy::class,
        Policy::class
    ];

    /**
    * Register any authentication / authorization services.
    *
    * @return void
    */
    public function boot()
    {
        $this->loadPolicies();
        $this->registerPolicies();
    }

    /**
    * @return void
    */
    public function loadPolicies(): void
    {
        foreach ($this->application_policies as $application_policy) {
            foreach (get_class_methods($application_policy) as $application_policy_method) {
                if (mb_strpos($application_policy_method, '__') === false) {
                    $policy = explode('\\', $application_policy);
                    Gate::define(
                        $policy[count($policy) - 1] . "@{$application_policy_method}",
                        "{$application_policy}@{$application_policy_method}"
                    );
                }
            }
        }
    }
}