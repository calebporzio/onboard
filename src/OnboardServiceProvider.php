<?php

namespace Calebporzio\Onboard;

use Illuminate\Support\ServiceProvider;

class OnboardServiceProvider extends ServiceProvider
{
	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->singleton(OnboardingSteps::class);
	}
}
