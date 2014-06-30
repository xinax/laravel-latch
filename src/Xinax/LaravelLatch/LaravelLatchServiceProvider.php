<?php namespace Xinax\LaravelLatch;

use Illuminate\Support\ServiceProvider;

class LaravelLatchServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 * @var bool
	 */
	protected $defer = false;

	/**
	* Bootstrap the application events.
	* @return void
	*/
	public function boot(){
		$this->package('xinax/laravel-latch');
	}

	/**
	 * Register the service provider.
	 * @return void
	 */
	public function register(){
		
		// Main class register
		$this->app['laravel-latch'] = $this->app->share(function($app){
			return new LaravelLatch;
		});

		// Auto alias
		$this->app->booting(function(){
			$loader = \Illuminate\Foundation\AliasLoader::getInstance();
			$loader->alias('LaravelLatch',
				'Xinax\LaravelLatch\Facades\LaravelLatch');
		});

	}

	/**
	 * Get the services provided by the provider.
	 * @return array
	 */
	public function provides(){
		return array('laravel-latch');
	}

}
