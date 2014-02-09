<?php namespace Modbase\AssetManager;

use Illuminate\Support\ServiceProvider;

class AssetManagerServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('modbase/asset-manager');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->singleton('modbase.asset', 'Modbase\AssetManager\Manager');

		$this->app->booting(function()
		{
		  $loader = \Illuminate\Foundation\AliasLoader::getInstance();
		  $loader->alias('Asset', 'Modbase\AssetManager\Facade\Asset');
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('modbase.asset');
	}

}