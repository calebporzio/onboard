<?php

namespace Calebporzio\Onboard;

use Illuminate\Support\Facades\App;

trait HasOnboarding
{
	public function onboarding()
	{
		return App::make(OnboardingManager::class, [$this]);
	}
}