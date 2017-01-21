<?php namespace Hpolthof\Translation;

use Illuminate\Database\Query\Builder;
use Illuminate\Translation\FileLoader;

class ServiceProvider extends \Illuminate\Translation\TranslationServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	protected $commands = [
		'Hpolthof\Translation\Console\Commands\DumpCommand',
		'Hpolthof\Translation\Console\Commands\FetchCommand',
	];

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->mergeConfigFrom(__DIR__.'/../config/translation-db.php', 'translation-db');

		$this->registerDatabase();
		$this->registerLoader();

		$this->commands($this->commands);

		$this->app->singleton('translator', function($app)
		{
			$loader = $app['translation.loader'];
			$database = $app['translation.database'];

			// When registering the translator component, we'll need to set the default
			// locale as well as the fallback locale. So, we'll grab the application
			// configuration so we can easily get both of these values from there.
			$locale = $app['config']['app.locale'];

			$trans = new Translator($database, $loader, $locale, $app);

			$trans->setFallback($app['config']['app.fallback_locale']);

			return $trans;
		});


	}

	public function boot()
	{
		$this->publishes([
			__DIR__.'/../database/migrations/' => database_path('/migrations')
		], 'migrations');

		$this->publishes([
			__DIR__.'/../config/translation-db.php' => config_path('translation-db.php'),
		]);

		$this->loadViewsFrom(__DIR__.'/../views', 'translation');
		$this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'translation');

		if($this->app['config']->get('translation-db.webinterface')) {
			$routeConfig = [
				'namespace' => 'Hpolthof\Translation\Controllers',
				'prefix' => $this->app['config']->get('translation-db.route_prefix'),
			];
			$this->app['router']->group($routeConfig, function($router) {
				$router->get('/', [
					'uses' => 'TranslationsController@getIndex',
					'as' => 'translations.index',
				]);
				$router->get('/groups', [
					'uses' => 'TranslationsController@getGroups',
					'as' => 'translations.groups',
				]);
				$router->get('/locales', [
					'uses' => 'TranslationsController@getLocales',
					'as' => 'translations.locales',
				]);
				$router->post('/items', [
					'uses' => 'TranslationsController@postItems',
					'as' => 'translations.items',
				]);
				$router->post('/store', [
					'uses' => 'TranslationsController@postStore',
					'as' => 'translations.store',
				]);
				$router->post('/translate', [
					'uses' => 'TranslationsController@postTranslate',
					'as' => 'translations.translate',
				]);
				$router->post('/delete', [
					'uses' => 'TranslationsController@postDelete',
					'as' => 'translations.delete',
				]);
			});
		}

		$this->app['translation.database']->addNamespace(null, null);
	}

	/**
	 * Register the translation line loader.
	 *
	 * @return void
	 */
	protected function registerLoader()
	{
		$this->app->singleton('translation.loader', function($app)
		{
			return new FileLoader($app['files'], $app['path.lang']);
		});
	}

	protected function registerDatabase()
	{
		$this->app->singleton('translation.database', function($app)
		{
			return new DatabaseLoader($app);
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('translator', 'translation.loader', 'translation.database');
	}

	/**
	 * Alternative pluck to stay backwards compatible with Laravel 5.1 LTS.
	 *
	 * @param Builder $query
	 * @param $column
	 * @param null $key
	 * @return array|mixed
	 */
	public static function pluckOrLists(Builder $query, $column, $key = null)
	{
		if(\Illuminate\Foundation\Application::VERSION < '5.2') {
			$result = $query->lists($column, $key);
		} else {
			$result = $query->pluck($column, $key);
		}

		return $result;
	}
}
