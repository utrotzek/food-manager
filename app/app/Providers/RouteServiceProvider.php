<?php

namespace App\Providers;

use App\Models\MealConfig;
use App\Repositories\DayRepository;
use App\Repositories\MealConfigRepository;
use App\Repositories\RecipeRepository;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    // protected $namespace = 'App\\Http\\Controllers';

    protected RecipeRepository $recipeReposotiry;
    protected DayRepository $dayRepository;
    protected MealConfigRepository $mealConfigRepository;

    public function __construct($app)
    {
        parent::__construct($app);
        $this->recipeReposotiry = $app->make(RecipeRepository::class);
        $this->dayRepository = $app->make(DayRepository::class);
        $this->mealConfigRepository = $app->make(MealConfigRepository::class);
    }

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }

    public function register()
    {
        parent::register();

        $this->app['router']->bind('recipe', function ($value, $route) {
            return $this->recipeReposotiry->findByIdOrSlug($value);
        });

        $this->app['router']->bind('day', function ($value, $route) {
            return $this->dayRepository->findByIdOrDate($value);
        });

        $this->app['router']->bind('meal_config', function ($value, $route) {
            return $this->mealConfigRepository->findByIdOrSlug($value);
        });
    }
}
