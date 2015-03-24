<?php namespace moay\VirusTotalApi;

use moay\VirusTotalApi\VirusTotalApi;
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

class VirusTotalApiServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
        $this->alias();
	}

	public function boot()
	{
		$this->publishes([
		    __DIR__.'/config/config.php' => config_path('virus-total-api.php'),
		]);
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return [];
	}


	public function alias()
	{
		AliasLoader::getInstance()->alias(
            'VirusTotal',
            'moay\VirusTotalApi\VirusTotalApi'
        );
	}

}
